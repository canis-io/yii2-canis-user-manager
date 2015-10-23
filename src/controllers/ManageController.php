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
        $menu['tokens'] = [
            'url' => ['/'. $userManager->friendlyUrl .'/manage/token'],
            'label' => 'Personal Access Tokens'
        ];
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
        Yii::$app->response->view = 'profile';
    }

    public function actionSecurity()
    {
    	$userManager = Yii::$app->getModule('userManager');
        $this->params['title'] = 'Security';
        Yii::$app->response->view = 'security';
    }

    public function actionToken()
    {
        $userManager = Yii::$app->getModule('userManager');
        $this->params['title'] = 'Personal Access Tokens';
        Yii::$app->response->view = 'token';
    }
}
