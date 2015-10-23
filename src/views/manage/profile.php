<?php
use yii\helpers\Html;

canis\web\assetBundles\BootstrapSelectAsset::register($this);
$this->title = $title;
echo Html::beginTag('div', ['class' => 'panel panel-default']);
echo Html::beginTag('div', ['class' => 'panel-heading']);
echo Html::tag('h3', $this->title, ['class' => 'panel-title']);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'panel-body']);
echo "hi";
echo Html::endTag('div');
echo Html::endTag('div');