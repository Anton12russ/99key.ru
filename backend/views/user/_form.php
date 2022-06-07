<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    
    <?= $form->field($model, 'status')->radioList(\common\models\User::STATUS_LIST) ?>
	
	<?= $form->field($model, 'balance')->textInput() ?>
	
	<?= $form->field($model, 'email')->textInput() ?>
	
	<?//= $form->field($model, 'password')->textInput() ?>
	
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
