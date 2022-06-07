<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
$this->title = 'Активация объявления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
	<br>
    <h1><?= $this->title. ' "'. $token->blog->title.'"' ?></h1>
	   <div class="alert alert-success">Объявление успешно активировано на <?=$tyme?> дней 
	   <?if(Yii::$app->caches->setting()['moder'] == 0) {?>
		 <br> Оно будет опубликовано после проверки.  
	   <? }?>
	   </div>
    </div>

