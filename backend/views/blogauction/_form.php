<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BlogAuction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-auction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'price_add')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_moment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_add')->textInput() ?>

    <?= $form->field($model, 'date_end')->textInput() ?>

    <?= $form->field($model, 'blog_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
