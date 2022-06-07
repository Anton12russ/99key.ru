<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BlogPayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-payment-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'type')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>
	
	<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
