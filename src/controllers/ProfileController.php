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

class ProfileController 
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
            'url' => ['/'. $userManager->friendlyUrl .'/profile'],
            'label' => 'Account'
        ];
        $menu['keys'] = [
            'url' => ['/'. $userManager->friendlyUrl .'/profile/keys'],
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
        $userManager = Yii::$app->getModule('userManager');
        $this->params['title'] = 'Account Profile';
        Yii::$app->response->view = 'index';
    }
}
