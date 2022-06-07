<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DisputeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dispute-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'date_update') ?>

    <?= $form->field($model, 'id_car') ?>

    <?= $form->field($model, 'id_user') ?>

    <?php // echo $form->field($model, 'id_bayer') ?>

    <?php // echo $form->field($model, 'cashback') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'flag_shop') ?>

    <?php // echo $form->field($model, 'flag_admin') ?>

    <?php // echo $form->field($model, 'flag_bayer') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
