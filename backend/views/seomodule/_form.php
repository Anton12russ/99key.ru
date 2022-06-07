<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SeoModule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seo-module-form">
<div class="alert alert-warning">
<h4>Памятка:</h4><br>

Для того, чтобы указать павило сразу для всех регионов на страницах магазинов и объявлений, используйте данную переменую в url {region}<br>
<strong>ПРИМЕР:</strong>  http://site.ru/{region}Uslugi/Zaprosy-na-uslugi/IT-internet-telekom
В описании метатегом вы можете использовать такие переменные как {region} - данная переменная в тексте будет заменена на название выбранного пользователем региона.
<br>Так же в описании можете использовать склонение регионов, переменная {region_case}, эта переменная будет заменена на регион в родительном падеже, а именно - пример: "Москве"



</div>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'keywords')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'redirect')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'cod_redirect')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
