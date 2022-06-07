<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\CatServices */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile(Url::home(true).'js/category.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);

?>

<div class="cat-services-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cat')->hiddenInput(['maxlength' => true, 'class' =>'catchang']) ?>


 	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
