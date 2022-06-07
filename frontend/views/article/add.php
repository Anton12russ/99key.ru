<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use vova07\imperavi\Widget;
$this->title = $meta['title'];
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $meta['keywords']]);
$this->params['breadcrumbs'] = $meta['breadcrumbs'];
$this->params['h1'] = $meta['h1'];

$this->registerCssFile('/css/article_add.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/js/article_add.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
		if (Yii::$app->user->can('updateArticle')) {
           $this->registerCssFile('/assest_all/calendar/jquery-ui.css');
           $this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
}
?>

<div class="article-form add-left-body">


<? if(isset($save)) {?>

<? if(Yii::$app->caches->setting()['article_moder'] == 1) {?>
   <div class="alert alert-success">Ваша статья добавлена.</div>
<? }else{ ?>  
   <div class="alert alert-success">Ваша статья добавлена, она будет опубликована после модерации</div>
<? } ?>  
<? }else{ ?>
    <?php $form = ActiveForm::begin(); ?>
	
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cat')->textInput(['class' => 'form-control catchang','type' => 'hidden' ]) ?>
		
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
		'imageUpload' => \yii\helpers\Url::to(['/ajax/save-redactor-img','sub'=>'article']),
		'imageDelete' => \yii\helpers\Url::to(['/ajax/save-img-del']),
        'clips' => [
            ['Красный', '<span class="label-red">Здесь вставить текст</span>'],
            ['Зеленый', '<span class="label-green">Здесь вставить текст</span>'],
            ['Голубой', '<span class="label-blue">Здесь вставить текст</span>'],
        ],
    ],
])->label('Текст')?>
	
<?if(!Yii::$app->user->can('updateOwnPost', ['article' => '']) && !Yii::$app->user->can('updateArticle')) {?>
<? if (Yii::$app->caches->setting()['capcha'] == 1) { ?>

<div class="capcha">
       <?= $form->field($model, 'reCaptcha',['template' => '{error}{input}'])->widget(\himiklab\yii2\recaptcha\ReCaptcha2::className(),['siteKey' => preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['recapcha2'])[0])])->label(false) ?>
</div>
<? } ?>

<? if (Yii::$app->caches->setting()['capcha'] == 2) { ?>
   <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha3::className(),['siteKey' => preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['recapcha3'])[0]),'action' => 'blog/add'])->label(false) ?>
<? } ?>
<? } ?>	

<? if (Yii::$app->controller->route == 'article/update' && Yii::$app->user->can('updateArticle')) { ?>
<br>
<br>
<div class="moder_panel">
<h3>Панель администрации</h3>

<br>
<?= $form->field($model, 'status', ['template' => '{error}{label}{input}'])->radioList([
        '0' => 'На модерации',
        '1' => 'Опубликован',
    ])->label('Статус <span class="req_val">*</span>') ?>
<br>
   <?= $form->field($model, 'date_end', ['template' => '{error}{label}{input}'])->textInput(['class' => 'form-control  datepicker'])->label('Дата выключения <span class="req_val">*</span>');?>
<br>
</div>
<? } ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
<? } ?>	

</div>
<div class="hidden url_all" data-href="<?=Url::to(['ajax/catallart'])?>"></div>