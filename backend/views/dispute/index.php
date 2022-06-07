<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\DisputeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title = 'Споры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dispute-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

    
			['attribute'=>'id_car','format'=>'raw', 'value'=>
	           function($data) {
				   	if($data->flag_admin == 1) {
						$flag = '<span style="color: green">(Ожидает ответа)</span>';
					}else{
						$flag = '';
					}
				    return '№ '.$data->id_car.' <br>'.Html::a('Ответить',['dispute','car_id' => $data->id_car]).'<br>'.$flag

					
					;
			   }
			],
            ['attribute'=>'date', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker',
                'id' => false,
                 'autocomplete'=>'off',
            ],
			],
             ['attribute'=>'date_update', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker',
                'id' => false,
                 'autocomplete'=>'off',
            ],
			],
           

			  ['attribute'=>'selleradmin','format'=>'raw','value'=> 
                 function($data) {
                   return '<span class="selleradmin_email">'.$data['selleradmin']['email'].'</span>';
                 },
                 'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
             ],
			  ['attribute'=>'bayeradmin','format'=>'raw','value'=> 
                 function($data) {
                   return '<span class="bayeradmin_email">'.$data['bayeradmin']['email'].'</span>';
                 },
                 'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
             ],

            //'cashback',
            ['attribute'=>'status','filter'=>\common\models\Dispute::STATUS_LIST,'value'=>@Status],
            //'flag_shop',
            //'flag_admin',
            //'flag_bayer',

            ['class' => 'yii\grid\ActionColumn',
			'template'=>'{delete} {view}',
			],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
