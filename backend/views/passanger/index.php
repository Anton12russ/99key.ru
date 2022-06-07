<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PassangerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title = 'Попутчики';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="passanger-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
          

            'id',
			 ['attribute'=>'img','format'=>'raw', 'value'=>
	           function($model) {
				   if($model->img) {
				      $icon = "<img src='/uploads/images/passanger/logo/".$model->img."'/>";
				   }else{
					  $icon = "<img src='/uploads/images/no-photo.png'/>";   
				   }
				    return $icon;
			   }
			],
			
			['attribute'=>'appliances','filter'=>\common\models\Passanger::APPLIANCESS,'value'=>@Appliancess],
            ['attribute'=>'date_add', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker',
                'id' => false,

            ],
			],
			['attribute'=>'time', 
			  'filterInputOptions' => [
                'class' => 'form-control datepicker',
                'id' => false,
            ],
			],
            ['attribute'=>'author','format'=>'raw','value'=> 
                 function($data) {
                   return '<span class="author_email">'.$data['author']['email'].'</span>';
                 },
                 'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
             ],
       
            'ot',
            'kuda',
            //'time',
            //'price',
            //'appliances',
            //'mesta',
            //'pol',

                  ['attribute'=>'','format'=>'raw','value'=> 
                 function($data) {
				    $url = str_replace('/admin','',Url::to(['passanger/one', 'id' => $data->id]));
					$update = str_replace('/admin','',Url::to(['passanger/update', 'id'=>$data->id]));
					$del = Url::to(['shop/delete', 'id'=>$data->id]);
                    return '<a href="'.$url.'" target="_blank" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a><br>
					<a href="'.$update.'" target="_blank" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="/admin/passanger/delete?id='.$data->id.'" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
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
