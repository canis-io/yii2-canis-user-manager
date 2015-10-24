<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$userManager = Yii::$app->getModule('userManager');
canis\web\assetBundles\BootstrapSelectAsset::register($this);
$this->title = $title;
echo Html::beginTag('div', ['class' => 'panel panel-default']);
echo Html::beginTag('div', ['class' => 'panel-heading']);
echo Html::tag('h3', 'Password', ['class' => 'panel-title']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'panel-body']);
$form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]);
$model->password = '';
echo $form->field($model, 'password')->passwordInput();
echo $form->field($model, 'passwordConfirm')->passwordInput();
echo Html::submitButton('Update Password', ['class' => 'btn btn-primary']);
ActiveForm::end();
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
		$extra = '';
		$currentSession = false;
		if ($device->last_session === Yii::$app->session->id) {
			$currentSession = true;
			$extra = ' ' . Html::tag('small', 'Current Session');
		}
		echo Html::beginTag('div', ['class' => 'list-group-item']);
		if ($device->isActive) {
			echo Html::tag('div', Html::tag('span', '', ['class' => 'fa fa-dot-circle-o']), ['class' => 'pull-right label label-success', 'title' => 'Active Session']);
		} else {
			echo Html::tag('div', Html::tag('span', '', ['class' => 'fa fa-circle-o']), ['class' => 'pull-right label label-default', 'title' => 'Not an Active Session']);
		}
		echo Html::tag('h3', $device->descriptor . $extra, ['class' => 'list-group-item-heading', 'title' => $device->last_accessed_ip]);
		echo Html::beginTag('div', ['class' => 'row']);
		echo Html::beginTag('div', ['class' => 'col-md-3']);
		echo Html::tag('dt', 'Last Log-in');
		echo Html::tag('dd', date("F j, Y", strtotime($device->last_accessed)));
		echo Html::endTag('div');
		echo Html::beginTag('div', ['class' => 'col-md-3']);
		echo Html::tag('dt', 'First Log-in');
		echo Html::tag('dd', date("F j, Y", strtotime($device->created)));
		echo Html::endTag('div');

		if (!$currentSession) {
			echo Html::beginTag('div', ['class' => 'col-md-6']);
			echo Html::a('Log Off', ['revoke-device', 'id' => $device->id], ['class' => 'btn btn-default pull-right', 'data-handler' => 'background']);
			echo Html::endTag('div');
		}

		echo Html::endTag('div');
		//echo Html::tag('div', $device->userAgent->toString(), ['class' => 'list-group-item-text', 'title' => $device->user_agent]);
		echo Html::endTag('div');
	}
	if (empty($devices)) {
		echo Html::tag('div', 'No devices have active sessions', ['class' => 'alert alert-warning']);
	}

	echo Html::endTag('div');
	echo Html::endTag('div');
	echo Html::endTag('div');
}