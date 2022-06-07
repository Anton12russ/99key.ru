<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Settings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Пример: max-photo']) ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true, 'placeholder' => 'Пример: Максимальное кол-во фото']) ?>

  
    <?= $form->field($model, 'type')->dropDownList([
        'v' => 'text',
        't' => 'textarea',
        's' => 'select',
		'r' => 'radio',
     ],
    [
        'prompt' => 'Выбрать'
    ]); ?>
	
	 <?= $form->field($model, 'val_text')->textarea(['rows' => 6, 
	    'placeholder' => 
        'Значение1
Значение 2
Значение 3
ИТД']) ?>
    
	 <?= $form->field($model, 'placeholder')->textarea(['rows' => 6,	    
	 'placeholder' => 
        'Ключ 1
Ключ 2
Ключ 3
ИТД']) ?>
    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
