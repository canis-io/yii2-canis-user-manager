<?php
use yii\helpers\Html;
$this->title = 'Disable Two-Factor Authentication';
echo Html::beginForm('', 'get', ['class' => 'ajax']);
echo Html::beginTag('div', ['class' => 'form']);
echo Html::hiddenInput('confirm', 1);
echo Html::tag('div', 'Are you sure you want to disable two-factor authentication on this account?', ['class' => 'alert alert-warning']);
echo Html::endTag('div');
echo Html::endForm();