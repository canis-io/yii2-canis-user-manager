<?php
/**
 * @link http://canis.io/
 *
 * @copyright Copyright (c) 2015 Canis
 * @license http://canis.io/license/
 */

namespace canis\userManager\controllers;

use Yii;
use canis\web\unifiedMenu\Menu;
use canis\userManager\models\EnableTwoFactorForm;

class ManageController 
	extends BaseController
	implements \canis\web\unifiedMenu\MenuProviderInterface
{
	public function behaviors()
	{
		return array_merge(parent::behaviors(), [
			'UnifiedMenu' => [
				'class' => \canis\web\unifiedMenu\ControllerBehavior::className()
			]
		]);
	}

    public static function provideMenuItems(Menu $menu)
    {
        $userManager = Yii::$app->getModule('userManager');
        $menu = [];
        $menu['profile'] = [
            'url' => ['/'. $userManager->friendlyUrl .'/manage/profile'],
            'label' => 'Account Profile'
        ];
        $menu['settings'] = [
            'url' => ['/'. $userManager->friendlyUrl .'/manage/security'],
            'label' => 'Account Security'
        ];
        // $menu['tokens'] = [
        //     'url' => ['/'. $userManager->friendlyUrl .'/manage/token'],
        //     'label' => 'Personal Access Tokens'
        // ];
        return $menu;
    }

	public static function adminOnly()
    {
        return false;
    }

    public function actionIndex()
    {
        $this->redirect(['profile']);
    }

    public function actionProfile()
    {
    	$userManager = Yii::$app->getModule('userManager');
        $this->params['title'] = 'Account Profile';
        $this->params['model'] = $model = Yii::$app->user->identity;
        $model->scenario = 'updateSelfProfile';
        if (!empty($_POST)) {
            $model->load($_POST);
            if ($model->save()) {
                Yii::$app->response->refresh = true;
                Yii::$app->response->task = 'message';
                Yii::$app->response->success = 'Profile was saved';
            } else {
                Yii::$app->response->task = 'message';
                Yii::$app->response->error = 'Profile could not be saved';
            }
        }
        Yii::$app->response->view = 'profile';
    }

    public function actionSecurity()
    {
    	$userManager = Yii::$app->getModule('userManager');
        $this->params['title'] = 'Security';
        $this->params['model'] = $model = Yii::$app->user->identity;
        $model->scenario = 'passwordChange';
        if (!empty($_POST)) {
            $model->load($_POST);
            if ($model->save()) {
                Yii::$app->response->refresh = true;
                Yii::$app->response->task = 'message';
                Yii::$app->response->success = 'Password was updated';
            } else {
                Yii::$app->response->task = 'message';
                Yii::$app->response->error = 'Password could not be updated';
            }
        }
        Yii::$app->response->view = 'security';
    }


    public function actionRevokeDevice()
    {
        if (!isset(Yii::$app->classes['UserDevice'])) {
            throw new \yii\web\BadRequestHttpException("No User Device Model");
        }
        $userDeviceClass = Yii::$app->classes['UserDevice'];
        if (empty($_GET['id']) || !($device = $userDeviceClass::find()->where(['user_id' => Yii::$app->user->identity->id, 'id' => $_GET['id']])->one()) || empty($device)) {
            throw new \yii\web\NotFoundHttpException("Device not found!");
        }

        Yii::$app->response->refresh = true;
        if ($device->delete()) {
            Yii::$app->response->task = 'message';
            Yii::$app->response->success = 'Device was logged off!';
        } else {
            Yii::$app->response->task = 'message';
            Yii::$app->response->error = 'Device could not be logged off';
        }
    }

    public function actionEnableTwoFactor()
    {
    	$userManager = Yii::$app->getModule('userManager');
        $this->params['title'] = 'Enable Two Factor';
        if (!$userManager->getTwoFactorEnabled()) {
        	throw new \yii\web\BadRequestHttpException("Two factor authentication is not enabled in this instance!");
        }
		$tfa = $this->params['tfa'] = new \RobThree\Auth\TwoFactorAuth(Yii::$app->params['siteName']);
        if (!isset(Yii::$app->session['two-factor-secret'])) {
			Yii::$app->session['two-factor-secret'] = $tfa->createSecret();
		}
		$this->params['model'] = $model = new EnableTwoFactorForm;
		$model->secret = Yii::$app->session['two-factor-secret'];
		$model->tfa = $tfa;
		$model->identity = Yii::$app->user->identity;

		if (!empty($_POST)) {
			$model->load($_POST);
			if ($model->enable()) {
				Yii::$app->response->success = 'Two-factor authentication has been enabled';
                Yii::$app->response->refresh = true;
                return;
			}
		}
        Yii::$app->response->task = 'dialog';
        Yii::$app->response->labels['submit'] = 'Enable Two-Factor Authentication';
        Yii::$app->response->taskOptions = ['title' => $this->params['title'], 'width' => '800px'];
        Yii::$app->response->view = 'enable_two_factor';
    }

    public function actionDisableTwoFactor()
    {
    	$userManager = Yii::$app->getModule('userManager');
        $this->params['title'] = 'Disable Two Factor';
        if (!$userManager->getTwoFactorEnabled()) {
        	throw new \yii\web\BadRequestHttpException("Two factor authentication is not enabled in this instance!");
        }
        if (!empty($_GET['confirm'])) {
            if (Yii::$app->user->identity->disableTwoFactor()) {
                Yii::$app->response->refresh = true;
                Yii::$app->response->success = 'Two-Factor Authentication was disabled!';
            } else {
                Yii::$app->response->task = 'message';
                Yii::$app->response->error = 'An error occurred while disabling two-factor authentication';
            }
            return;
        }
        // isConfirmation

        Yii::$app->response->taskOptions = ['title' => 'Disable Two-Factor Authentication', 'isConfirmDeletion' => true];
        Yii::$app->response->labels['confirm_delete'] = 'Disable Two-Factor Authentication';
        Yii::$app->response->task = 'dialog';
        Yii::$app->response->view = 'disable_two_factor';

    }

    public function actionToken()
    {
        $userManager = Yii::$app->getModule('userManager');
        $this->params['title'] = 'Personal Access Tokens';
        Yii::$app->response->view = 'token';
    }
}
