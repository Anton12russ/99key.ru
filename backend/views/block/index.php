<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\BlockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use sjaakp\sortable\SortableGridView;
$this->title = 'Блоки';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile(Url::home(true).'js/category.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Url::home(true).'js/region.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="block-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить блок', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?= SortableGridView::widget( [
    'dataProvider' => $dataProvider,
    'orderUrl' => ['order'],

        'filterModel' => $searchModel,
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'name',
            //'text:ntext',
            ['attribute'=>'position','filter'=>\common\models\Block::POSITION_LIST, 'value'=>@Position],
            ['attribute'=>'status','filter'=>\common\models\Block::STATUS_LIST,'value'=>@Status],
           // 'date_add',
           // 'date_del',
		   ['attribute'=>'action','filter'=>\common\models\Block::ACTION_LIST, 'value'=>@Action],
           // 'registr',
           ['attribute'=>'category', 
			   'value'=> function ($model) {return  Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->category(), $model->category);},
			  'filterInputOptions' => [
                'class' => 'form-control catchang', 
                'id' => null,
				'type'=>'hidden'
            ],
			],
            ['attribute'=>'region', 
		  'value'=> function ($model) {return  Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->region(), $model->region);},
			  'filterInputOptions' => [
                'class' => 'form-control regionchang', 
                'id' => null,
				'type'=>'hidden'
            ],
			],
            'sort',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<script>

$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val()) {selectact($('.catchang').val())}else{ getCategory(0)};
$('.regionchang').before('<div id="selectBox_region"></div>');
if ($('.regionchang').val()) {selectactreg($('.regionchang').val())}else{ getRegion(0)};
$('.author_email').click(function(){pjaxBlog();});
alssAct();
</script>
    <?php Pjax::end(); ?>

</div>
