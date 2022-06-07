<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\Region;
/* @var $this yii\web\View */
/* @var $searchModel common\models\RegionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Регионы';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile(Url::home(true).'js/region.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>



<div class="region-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<span class="hidden text-parent"></span>
<?php Pjax::begin([ 'id' => 'pjaxContent'
]); ?>


  <?php //  echo $this->render('_search', ['model' => $searchModel]); ?>

<!--\yii\helpers\ArrayHelper::map(common\models\Category::find()->all(),'id','name')-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            'url',
			['attribute'=>'parent', 
			'value'=> function ($model) {return Region::linenav($model->parent);},
			  'filterInputOptions' => [
                'class' => 'form-control regionchang', 
                'id' => null,
				'type'=>'hidden'
            ],
			],
			
            //'description:ntext',
           ['attribute'=>'sort', 'format'=>'raw', 'value'=>
	           function($data) {
				  return Html::input('text', 'sort', $data->sort,['class'=>'form-control sort_reg', 'style'=>'width: 70%', 'data-id'=> $data->id,  'type' => 'number'] );
			   }
			  
            ],
		
            ['class' => 'yii\grid\ActionColumn'],
          ],
			
    ]); 
?>
<script>
$('.regionchang').before('<div id="selectBox_region"></div>');
if ($('.regionchang').val()) {selectactreg($('.regionchang').val())}else{ getRegion(0)};
sort_reg();
</script>
    <?php Pjax::end(); ?>

</div>
