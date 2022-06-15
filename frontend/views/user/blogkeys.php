<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
$this->title = 'Найти объявление';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = array('label'=> 'Мои объявления', 'url'=>Url::toRoute('blogs'));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="">
   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">
    
   <h1><i class="fa fa-key" aria-hidden="true"></i>  <?=$this->title?></h1>
   <?php Pjax::begin([ 'id' => 'pjaxContent']);  ?>
   <?php if ($save) {?><div class="alert alert-success">Объявления найдены и присвоены вашему аккаунту.</div><?}?>
     <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true], 'enableClientValidation' => false,]);?>
         <?= $form->field($model, 'key', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true])->label()?>
			
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success add-preloader']) ?>
 
	 <?php ActiveForm::end(); ?>	
   <?php Pjax::end(); ?>
   </div>
   </div>
</div>
</div>