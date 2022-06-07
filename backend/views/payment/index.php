<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Платежная история';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="payment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--<p>
        <?= Html::a('Create Payment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'price',
			'system',
            'currency',
             ['attribute'=>'user_id','format'=>'raw', 'value'=>
	           function($data) {
	               if(isset($data->user->id)) {
				         return '(ID '.$data->user->id. ')  ' . '<a target="_blank" data-pjax="0" href="/admin/user?UserSearch%5Bid%5D='.$data->user_id.'">подробно...</a>';
	               }
	                   
	           }
			],
            ['attribute'=>'blog_id','format'=>'raw', 'value'=>
	           function($data) {
				   if ($data->blog_id) {
				      // return Html::a(\yii\helpers\StringHelper::truncate('(ID '.$data->blog->id. ')  ' . $data->blog->url,15,'...'),'/'.Yii::$app->caches->region()[$data->blog->region]['url'].'/'.Yii::$app->caches->category()[$data->blog->category]['url'].'/'.$data->blog->url.'_'.$data->blog->id.'.html',['target'=>'_blank', 'data-pjax'=>"0"]);
				      if (isset($data->blog->id)) { 
					      return '(ID '.$data->blog->id. ')  ' . '<a target="_blank" data-pjax="0" href="/admin/blog?BlogSearch%5Bid%5D='.$data->blog_id.'">подробно...</a>'; 
					  }
				   }
			   }
			],
            'services',
			['attribute'=>'status','filter'=>\common\models\Payment::STATUS_LIST ,'format'=>'raw', 'value'=>@statusid],
             ['attribute'=>'time', 
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

    <?php Pjax::end(); ?>

</div>
