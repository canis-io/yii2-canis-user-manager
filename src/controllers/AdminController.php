<?php
/**
 * @link http://canis.io/
 *
 * @copyright Copyright (c) 2015 Canis
 * @license http://canis.io/license/
 */

namespace canis\userManager\controllers;

use Yii;

class AdminController extends BaseController
{
    public function actionIndex()
    {
        $userManager = Yii::$app->getModule('userManager');

    }
}
