<?php
use yii\helpers\Html;
$userManager = Yii::$app->getModule('userManager');
canis\web\assetBundles\BootstrapSelectAsset::register($this);
$this->title = $title;
echo Html::beginTag('div', ['class' => 'panel panel-default']);
echo Html::beginTag('div', ['class' => 'panel-heading']);
echo Html::tag('h3', 'Password', ['class' => 'panel-title']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'panel-body']);

echo Html::endTag('div');
echo Html::endTag('div');

if ($userManager->getTwoFactorEnabled()) {
	echo Html::beginTag('div', ['class' => 'panel panel-default']);
	echo Html::beginTag('div', ['class' => 'panel-heading']);
	echo Html::tag('h3', 'Two-Factor Authentication', ['class' => 'panel-title']);
	echo Html::endTag('div');
	echo Html::beginTag('div', ['class' => 'panel-body']);
	if (Yii::$app->user->identity->isTwoFactorEnabled()) {
		echo Html::tag('div', 'Two-factor authentication is enabled', ['class' => 'alert alert-success']);
		echo Html::a('Disable Two-Factor Authentication', ['disable-two-factor'], ['class' => 'btn btn-warning', 'data-handler' => 'background']);
	} else {
		echo Html::tag('div', 'Two-factor authentication is disabled', ['class' => 'alert alert-warning']);
		echo Html::a('Enable Two-Factor Authentication', ['enable-two-factor'], ['class' => 'btn btn-primary', 'data-handler' => 'background']);
	}
	echo Html::endTag('div');
	echo Html::endTag('div');
}

if (($userDeviceClass = Yii::$app->classes['UserDevice'])) {
	echo Html::beginTag('div', ['class' => 'panel panel-default']);
	echo Html::beginTag('div', ['class' => 'panel-heading']);
	echo Html::tag('h3', 'Sessions', ['class' => 'panel-title']);
	echo Html::endTag('div');
	echo Html::beginTag('div', ['class' => 'panel-body']);
	echo Html::beginTag('div', ['class' => 'list-group']);
	$devices = $userDeviceClass::find()->where(['user_id' => Yii::$app->user->identity->id])->orderBy(['last_accessed' => SORT_DESC])->all();
	foreach ($devices as $device) {
		echo Html::beginTag('div', ['class' => 'list-group-item']);
		
		echo Html::endTag('div');
	}
	if (empty($devices)) {
		echo Html::tag('div', 'No devices have active sessions', ['class' => 'alert alert-warning']);
	}

	echo Html::endTag('div');
	echo Html::endTag('div');
	echo Html::endTag('div');
}