<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\Field */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(Url::home(true).'js/category.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="fields-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'obsluga_plus')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'obsluga_minus')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'cena_plus')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'cena_minus')->textInput(['maxlength' => true]) ?>
	 <?= $form->field($model, 'kachestvo_plus')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'kachestvo_minus')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'shop_id')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
