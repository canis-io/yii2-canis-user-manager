<?php
use yii\helpers\Html;

echo Html::beginForm('', 'post', ['class' => 'ajax']);
echo Html::beginTag('div', ['class' => 'form-group']);
echo Html::tag('label', 'Scan the following image with your favorite two-factor authentication app:');
echo Html::beginTag('p');
echo Html::tag('img', false, ['src' => $tfa->getQRCodeImageAsDataUri(Yii::$app->params['siteName'] .':'. Yii::$app->user->identity->email, Yii::$app->session['two-factor-secret'])]);
echo Html::endTag('p');
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'form-group']);

$fieldExtra = '';
if (!empty($model->errors['verificationCode'])) {
	$fieldExtra = 'has-feedback has-error';
}
echo Html::beginTag('div', ['class' => 'form-group ' . $fieldExtra]);
echo Html::activeLabel($model, 'verificationCode');
echo Html::activeTextInput($model, 'verificationCode', ['class' => 'form-control']);
echo Html::error($model, 'verificationCode', ['class' => 'help-inline text-danger']);
echo Html::endTag('div');

echo Html::endForm();