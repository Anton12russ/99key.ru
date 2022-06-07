<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\Category;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile(Url::home(true).'js/category.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);

?>



<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
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
     //       ['class' => 'yii\grid\SerialColumn'],
            'SmallImage:image',
            //'id',
            'name',
            'url',
			['attribute'=>'parent', 
			'value'=> function ($model) {return Category::linenav($model->parent);},
			  'filterInputOptions' => [
                'class' => 'form-control catchang', 
                'id' => null,
				'type'=>'hidden'
            ],
			],
			['attribute'=>'filter', 
			'format'=>'raw',
			'value'=> function ($data) {
			    return '<a data-pjax=0 target="_blank" href="'.Url::home(true).'field?FieldSearch%5Bcat%5D='.$data->id.'"><i class="fa fa-filter" aria-hidden="true"></i><i></a>';
		    },
			
			],
            //'description:ntext',
           ['attribute'=>'sort', 'format'=>'raw', 'value'=>
	           function($data) {
				  return Html::input('text', 'sort', $data->sort,['class'=>'form-control sort_cat', 'style'=>'width: 70%', 'data-id'=> $data->id,  'type' => 'number'] );
			   }
			  
            ],
		
            ['class' => 'yii\grid\ActionColumn'],
          ],
			
    ]); 
?>
<script>
$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val()) {selectact($('.catchang').val())}else{ getCategory(0)};
sort_cat();
</script>
    <?php Pjax::end(); ?>

</div>
