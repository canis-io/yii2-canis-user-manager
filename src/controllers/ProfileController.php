<?php
/**
 * @link http://canis.io/
 *
 * @copyright Copyright (c) 2015 Canis
 * @license http://canis.io/license/
 */

namespace canis\userManager\controllers;

use Yii;

class ProfileController extends BaseController
{
	public static function adminOnly()
    {
        return false;
    }

    public function actionIndex()
    {
        $userManager = Yii::$app->getModule('userManager');
        Yii::$app->response->view = 'index';
    }
}
