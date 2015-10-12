<?php
namespace canis\userManager;

use Yii;

class Extension implements \yii\base\BootstrapInterface
{
    public function bootstrap($app)
    {
    	$app->setModule('userManager', ['class' => Module::className()]);
        $module = $app->getModule('userManager');
    }
}
