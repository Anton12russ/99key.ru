<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\Field;
/* @var $this yii\web\View */
/* @var $searchModel common\models\FieldSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поля фильтра';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile(Url::home(true).'js/category.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="fields-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить поле', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            ['attribute'=>'cat', 
			'value'=> function ($model) {return @Field::linenav(Yii::$app->caches->category(),$model->cat);},
			  'filterInputOptions' => [
                'class' => 'form-control catchang', 
                'id' => null,
				'type'=>'hidden'
            ],
			],
            'name',
            //'values:ntext',
            //'max',
            //'type',
            //'type_string',
            //'req',
            //'hide',
            //'block',
            'sort',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<script>
$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val()) {selectact($('.catchang').val())}else{ getCategory(0)};
</script>

    <?php Pjax::end(); ?>

</div>
