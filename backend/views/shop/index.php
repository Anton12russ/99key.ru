<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\helpers\Url;
$this->title = 'Магазины';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="shop-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

           'id',
            'name',
            ['attribute'=>'author','format'=>'raw','value'=> 
                 function($data) {
                   return '<span class="author_email">'.$data['author']['email'].'</span>';
                 },
                 'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
             ],
			 
			
			 
			'text:ntext',
            //'category',
            //'region',
            ['attribute'=>'status','filter'=>\common\models\Shop::STATUS_LIST,'value'=>@Status],
			['attribute'=>'active','filter'=>\common\models\Shop::STATUS_ACTIVE,'value'=>@Active],
             ['attribute'=>'date_add', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker',
                'id' => false,

            ],
			],
			
			['attribute'=>'date_end', 
			  'filterInputOptions' => [
                'class' => 'form-control datepicker',
                'id' => false,
            ],
			],
            ['attribute'=>'','format'=>'raw','value'=> 
                 function($data) {
				    $url = str_replace('/admin','',Url::to(['shop/one', 'region'=>$data->regions['url'], 'category'=>$data->categorys['url'], 'id'=>$data->id, 'name'=>Yii::$app->userFunctions->transliteration($data->name)]));
					$update = str_replace('/admin','',Url::to(['shop/update', 'id'=>$data->id]));
					$del = Url::to(['shop/delete', 'id'=>$data->id]);
                    return '<a href="'.$url.'" target="_blank" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a><br>
					<a href="'.$update.'" target="_blank" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="/admin/shop/delete?id='.$data->id.'" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
					';
				 },
                'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
             ],



        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
