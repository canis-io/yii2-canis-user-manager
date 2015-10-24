<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

canis\web\assetBundles\BootstrapSelectAsset::register($this);
$this->title = $title;
echo Html::beginTag('div', ['class' => 'panel panel-default']);
echo Html::beginTag('div', ['class' => 'panel-heading']);
echo Html::tag('h3', $this->title, ['class' => 'panel-title']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'panel-body']);
$form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]);
$model->password = '';
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-md-6']);
echo $form->field($model, 'first_name');
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'col-md-6']);
echo $form->field($model, 'last_name');
echo Html::endTag('div');
echo Html::endTag('div');
echo $form->field($model, 'email');

echo Html::submitButton('Update Profile', ['class' => 'btn btn-primary']);
ActiveForm::end();
echo Html::endTag('div');
echo Html::endTag('div');