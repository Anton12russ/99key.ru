<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Личные настройки';
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
   <?php if (isset($save)) {?><div class="alert alert-success">Данные изменены</div><?}?>
     <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true], 'enableClientValidation' => false,]);?>
         <?= $form->field($model, 'username', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true])->label('Ваше Имя')?>
			<?= $form->field($model, 'email', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true])->label('Новый email')?>
         <?= $form->field($model, 'phone', ['template' => '{error}{label}{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => Yii::$app->caches->setting()['mask']])->textInput(['maxlength' => true])->label('Телефон')?>
         <?= $form->field($model, 'old_password', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true, 'value'=>''])->label('Старый пароль')?>
			<?= $form->field($model, 'password', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true, 'value'=>''])->label('Новый пароль')?>
 
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success add-preloader']) ?>
 
	 <?php ActiveForm::end(); ?>	
   <?php Pjax::end(); ?>
   </div>
   </div>
</div>
</div>