<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(Url::home(true).'js/category.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>


<div class="row">
<div class="col-md-12">
<div> <label class="control-label" for="category-name">Изображение</label></div>
<div class="preview"><img class="img_value" src="<? if ($model->image) {echo $model->image;}else{echo str_replace('admin/','',Url::home(true)).'uploads/images/no-photo.png';}?>"/></div>
<div class="form-group img_div">
<div>
<?= InputFile::widget([
    'language'   => 'ru',
    'controller' => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
    'filter'     => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
    'name'       => 'Category[image]',
    'value'      => $model->image,
	'buttonOptions' => ['class' => 'btn btn-success'],
	'buttonName' => 'Выбрать изображение',
]);
?>
</div>
</div>
</div>
</div>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<div class="row">	
<div class="col-md-12"><span class="btn btn-info rigs">Передать в ЧПУ</span>   <span class="btn btn-info trans">Передать в ЧПУ с транслитерацией</span></div>
</div>
<br>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent')->textInput(['class' => 'form-control catchang','type' => 'hidden' ]) ?>
	
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
