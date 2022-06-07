<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Rates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rates-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numcode')->textInput() ?>

    <?= $form->field($model, 'charcode')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'text')->textInput()->label('Класс иконки валюты (https://fontawesome.ru/all-icons/)') ?>
	 
	<?= $form->field($model, 'value')->textInput() ?>

    <?= $form->field($model, 'def')->radioList([
        '1' => 'Да',
        '0' => 'Нет',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
