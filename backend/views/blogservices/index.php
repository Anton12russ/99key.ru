<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
use common\models\BlogSearch;
$this->title = 'Платные услуги для объявлений';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');

$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="blog-services-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            //'id',
			['attribute'=>'blog','format'=>'raw','value'=> function($data) {return '<a target="_blank" data-pjax="0" href="/admin/blog?BlogSearch%5Bid%5D='.$data->blog_id.'">'.$data['blog']['title'].'</a>';}],
			['attribute'=>'type','filter'=>\common\models\BlogServices::TYPE_LIST, 'value'=>@Type],
            ['attribute'=>'date_end', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker',
                'id' => false,

            ],
			],
               ['attribute'=>'date_add', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker',
                'id' => false,

            ],
			],

              ['class' => 'yii\grid\ActionColumn',
			  'template'=>'{delete}',
		    	],
        ],
    ]); ?>
	
	
<script>
calendar();
</script>
    <?php Pjax::end(); ?>

</div>
