<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use mihaildev\elfinder\InputFile;
/* @var $this yii\web\View */
/* @var $model common\models\PaymentSystem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-system-form">

    <?php $form = ActiveForm::begin(); ?>
	
<div class="row">
<div class="col-md-12">
<div> <label class="control-label" for="category-name">Изображение</label></div>
<div class="preview"><img class="img_value" src="<? if ($model->logo) {echo $model->logo;}else{echo str_replace('admin/','',Url::home(true)).'uploads/images/no-photo.png';}?>"/></div>
<div class="form-group img_div">
<div>
<?= InputFile::widget([
    'language'   => 'ru',
    'controller' => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
    'filter'     => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
    'name'       => 'PaymentSystem[logo]',
    'value'      => $model->logo,
	'buttonOptions' => ['class' => 'btn btn-success'],
	'buttonName' => 'Выбрать изображение',
]);
?>
</div>
</div>
</div>
</div>
    <? if (Yii::$app->controller->action->id == 'create') {?>
            <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
    <? } ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rates')->textInput() ?>

    <? if (Yii::$app->controller->action->id == 'create') {?>
        <?= $form->field($model, 'settings_input')->textarea(['rows' => 6]) ?>
	<? }else{ 
	    $settings = explode("\n", $model->settings);
	     foreach(explode("\n", $model->settings_input) as $key => $res) {
			 if(!isset($settings[$key])) {$settings[$key] = '';}
			echo $form->field($model, 'settings[]')->textInput(['value' => $settings[$key]])->label($res); 
		 }
	 } ?>
	
	<?= $form->field($model, 'status')->radioList([
           '0' => 'Выключен',
           '1' => 'Включен',
    ]) ?>
	<br>
	<h2>Инструкция</h2>
     <div class="col-md-12"><?=$model->comment?></div>
	<!--
		<?= $form->field($model, 'comment')->widget(Widget::className(), [
     'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
		'formatting' => [
		'h1','h2','p','blockquote'
		],
        'plugins' => [
            'clips',
            'fullscreen',
        ],
		'imageUpload' => \yii\helpers\Url::to(['/site/save-redactor-img','sub'=>'blog']),
        'clips' => [
            ['Красный', '<span class="label-red">Здесь вставить текст</span>'],
            ['Зеленый', '<span class="label-green">Здесь вставить текст</span>'],
            ['Голубой', '<span class="label-blue">Здесь вставить текст</span>'],
        ],
    ],
])->label('Инструкция')?>
	-->
	
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
     
    <?php ActiveForm::end(); ?>

</div>
