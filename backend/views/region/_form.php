<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model common\models\Region */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(Url::home(true).'js/region.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="region-form">

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'regionCase')->textInput() ?>

<div class="row">	
<div class="col-md-12"><span class="btn btn-info rigs">Передать в ЧПУ</span>   <span class="btn btn-info trans">Передать в ЧПУ с транслитерацией</span></div>
</div>
<br>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent')->textInput(['class' => 'form-control regionchang','type' => 'hidden' ]) ?>
	 
    <?= $form->field($model, 'sort')->textInput() ?>

	<?= $form->field($model, 'coordlat')->textInput(['maxlength' => true, 'placeholder' => 'Заполняется автоматически']) ?>
	
	<?= $form->field($model, 'coordlon')->textInput(['maxlength' => true, 'placeholder' => 'Заполняется автоматически']) ?>
	
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
