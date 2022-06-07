<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
/* @var $this yii\web\View */
/* @var $model common\models\StaticPage */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(Url::home(true).'js/category.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="static-page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

   <div class="row">	
      <div class="col-md-12"><span class="btn btn-info rigsstaticpage">Передать в ЧПУ</span>   <span class="btn btn-info transstaticpage">Передать в ЧПУ с транслитерацией</span></div>
   </div>
   <br>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'menu')->radioList(\common\models\StaticPage::STATUS_LIST) ?>
	
	
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
