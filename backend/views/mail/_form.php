<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
/* @var $this yii\web\View */
/* @var $model common\models\Mail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

       <?= $form->field($model, 'text')->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
		'formatting' => [
		'h1','h2','p','blockquote'
		],
        'plugins' => [
            'clips',
            'fullscreen',
			'video',
			'fontcolor',
			'fontfamily',
			'fontsize',
        ],
		'imageUpload' => \yii\helpers\Url::to(['/site/save-redactor-img','sub'=>'article']),
		'imageDelete' => \yii\helpers\Url::to(['/site/save-img-del']),
        'clips' => [
            ['Красный', '<span class="label-red">Здесь вставить текст</span>'],
            ['Зеленый', '<span class="label-green">Здесь вставить текст</span>'],
            ['Голубой', '<span class="label-blue">Здесь вставить текст</span>'],
        ],
    ],
])->label('Текст')?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
