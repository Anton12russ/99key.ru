<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Личные настройки';
$this->params['breadcrumbs'][] = $this->title;



//передаем в шаблон
if(isset($shop->img)) {$this->params['logo'] = $shop->img;}else{$this->params['logo'] = '';}
$this->params['title'] = $shop->name;
$this->params['phone'] = $shop->field->phone;
$this->params['shop'] = $shop;
$this->params['arr'] =  array('Пн.','Вт.','Ср.','Чт.','Пт.','Сб.','Вс.');
$this->params['vote'] = $vote;
$this->params['vot'] = $shop->reating;

?>
<div class="row">
<div class="">

   <div class="person-body">
   <h1><i class="fa fa-key" aria-hidden="true"></i>  <?=$this->title?></h1>
   <?php Pjax::begin([ 'id' => 'pjaxContent']);  ?>
   <?php if (isset($save)) {?><div class="alert alert-success">Данные изменены</div><?}?>
     <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true], 'enableClientValidation' => false,]);?>
            <?= $form->field($model, 'username', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true])->label('Ваше Имя')?>
			<?= $form->field($model, 'email', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true])->label('Новый email')?>
			<?= $form->field($model, 'old_password', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true, 'value'=>''])->label('Старый пароль')?>
			<?= $form->field($model, 'password', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true, 'value'=>''])->label('Новый пароль')?>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success add-preloader']) ?>
 
	 <?php ActiveForm::end(); ?>	
   <?php Pjax::end(); ?>
   </div>

</div>
</div>