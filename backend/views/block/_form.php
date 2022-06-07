<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;


/* @var $this yii\web\View */
/* @var $model common\models\Block */
/* @var $form yii\widgets\ActiveForm */


$this->registerJsFile(Url::home(true).'js/category.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Url::home(true).'js/region.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="block-form">

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
        ],
		'imageUpload' => \yii\helpers\Url::to(['/site/save-redactor-img','sub'=>'blog']),
        'clips' => [
            ['Красный', '<span class="label-red">Здесь вставить текст</span>'],
            ['Зеленый', '<span class="label-green">Здесь вставить текст</span>'],
            ['Голубой', '<span class="label-blue">Здесь вставить текст</span>'],
        ],
    ],
])->label('Текст')?>


	    <?= $form->field($model, 'position')->dropDownList([
        'top' => 'Под шапкой',
        'footer' => 'Перед подвалом',
        'right' => 'Справа',
		'add' => 'Между объявлениями',
     ],
    [
        'prompt' => 'Выбрать'
    ]); ?>



    <?//= $form->field($model, 'date_add')->textInput() ?>

    <?= $form->field($model, 'date_del')->textInput(['class' => 'form-control datepicker']) ?>

    <?= $form->field($model, 'action')->dropDownList([
	'auction/' => 'Аукционы',
        'all' => 'Везде',
		'blog/index' => 'Главная страница',
		'blog/' => 'Объявление (На всех страницах)',
		'blog/category' => 'Объявление (Только в рубриках)',
		'blog/notepad' => 'Объявление (Избранные)',
		'blog/one' => 'Объявление (На странице объявления)',
		'blog/add' => 'Добавление объявления',
		'shop/' => 'Магазтины, на всех страницах',

		'user/' => 'Личный кабинет пользователя',
		'passanger/' => 'Попутчики',
		
     ],
    [
        'prompt' => 'Выбрать'
    ]); ?>

 
		<?= $form->field($model, 'registr')->radioList([
		'0' => 'Видно всем', 
		'1' => 'Только для зарегистрированных', 
	]); ?>
	<?= $form->field($model, 'header_ok')->radioList([
		'0' => 'Не показывать', 
		'1' => 'Показывать', 
	]); ?>
<?= $form->field($model, 'region')->textInput(['class' => 'regionchang','type' => 'hidden' ]) ?>
<?= $form->field($model, 'category')->textInput(['class' => 'catchang','type' => 'hidden' ]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>
	
	<?= $form->field($model, 'status')->radioList([
		'1' => 'Опубликовано', 
		'2' => 'Снято с публикации', 
	]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
