<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerJsFile(Url::home(true).'js/article.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
/* @var $this yii\web\View */
/* @var $model common\models\ArticleCat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-cat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'id' => 'category-name']) ?>
<div class="row">	
<div class="col-md-12"><span class="btn btn-info rigs">Передать в ЧПУ</span>   <span class="btn btn-info trans">Передать в ЧПУ с транслитерацией</span></div>
</div>
<br>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'id' => 'category-url']) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'parent')->textInput(['class' => 'form-control catchang','type' => 'hidden' ]) ?>
	
    <?= $form->field($model, 'sort')->textInput() ?>
	
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
