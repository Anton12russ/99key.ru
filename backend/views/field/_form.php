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

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cat')->textInput(['class' => 'form-control catchang','type' => 'hidden' ]) ?>

    <?= $form->field($model, 'values')->textarea(['rows' => 6, 
	'placeholder' => 'Значение1
Значение 2
Значение 3
ИТД']) ?>

    <?= $form->field($model, 'max')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList([
        'v' => 'Строковое значение text',
        't' => 'Текстовая область textarea',
        's' => 'Выпадающий список select',
		'c' => 'Флажки для выбора checkbox',
		'r' => 'Переключатель radio',
		'p' => 'Цена',
		'y' => 'Ссылка на ролик youtube',
		'j' => 'Метка на Яндекс.Картах',
     ],
    [
        'prompt' => 'Выбрать'
    ]); ?>
    <?= $form->field($model, 'type_string')->dropDownList([
        'n' => 'Диапазон от и до',
        'l' => 'Латинские символы',
		't' => 'Телефонный номер',
		'x' => 'Факс',
		'u' => 'Адрес сайта',
		'q' => 'Торг (дополнение к цене)',
     ],
    [
        'prompt' => 'Выбрать'
    ]); ?>

    <?= $form->field($model, 'req')->radioList([
        '1' => 'Обязательное',
        '2' => 'Необязательное',
    ]) ?>

    <?= $form->field($model, 'hide')->radioList([
        '1' => 'Скрыть',
		'0' => 'Видно всем',
    ]) ?>

    <?= $form->field($model, 'block')->radioList([
        '1' => 'Показать',
        '0' => 'Скрыть',
    ]) ?>
    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
