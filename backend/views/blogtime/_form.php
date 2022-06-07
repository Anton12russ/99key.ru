<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BlogTime */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-time-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'days')->textInput() ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'def')->radioList([
	    '0' => 'В резерве',
        '1' => 'Задействован',
    ]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
