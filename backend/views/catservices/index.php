<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CatServicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerJsFile(Url::home(true).'js/categoryAdd.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Url::home(true).'js/region.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
		
$this->title = 'Платное размещение в категориях';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cat-services-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  


	?>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
         //   ['class' => 'yii\grid\SerialColumn'],

         //   'id',
          	['attribute'=>'cat', 
			   'value'=> function ($model) {return  Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->category(), $model->cat);},
			  'filterInputOptions' => [
                'class' => 'form-control catchang', 
                'id' => null,
				'type'=>'hidden'
            ],
			],
            ['attribute'=>'reg', 
		  'value'=> function ($model) {return  Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->region(), $model->reg);},
			  'filterInputOptions' => [
                'class' => 'form-control regionchang', 
                'id' => null,
				'type'=>'hidden'
            ],
			],
			
			['attribute'=>'price', 
		    'value'=> function ($model, $reate) {
				foreach (Yii::$app->caches->rates() as $res) {
			        if ($res['def'] == '1') {
				          $rates = $res['name'];
			        }
		         }
				
				return  $model->price.' ('.$rates.')';
				
			;},
			],



            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<script>
$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val()) {selectact($('.catchang').val())}else{ getCategory(0)};
$('.regionchang').before('<div id="selectBox_region"></div>');
if ($('.regionchang').val()) {selectactreg($('.regionchang').val())}else{ getRegion(0)};
</script>
    <?php Pjax::end(); ?>

</div>
