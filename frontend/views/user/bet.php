<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\DisputeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
		
		
		$this->registerJsFile('/js/user.js',['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = 'Мои Ставки';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = $this->title;



?>
<style>
.person-body {
position: absolute;
    z-index: 999;
}
</style>
<div class="row">
<div class="">
   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">
    <h1><i class="fa fa-bar-chart" aria-hidden="true"></i> <?=$this->title?></h1>


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
              ['attribute'=>'blog','format'=>'raw','value'=> 
                 function($data) {
					$url = Url::to(['blog/one', 'region'=>$data['blog']['regions']['url'], 'category'=>$data['blog']['categorys']['url'], 'url'=>$data['blog']['url'], 'id'=>$data['blog']['id']]);
                   return '<a href="'.$url.'" target="_blank" data-pjax=0>'.$data['blog']['title'].'</a>';
                 },
                 'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
             ],

            ['attribute'=>'date_add', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker2',
                'id' => false,
                 'autocomplete'=>'off',
            ],
			],
			
			
			     ['attribute'=>'price','format'=>'raw','value'=> 
                 function($data) {
                   return $data['price'].' <i class="fa '.$data['rates']['text'].'" aria-hidden="true"></i>';
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
   </div>
</div>
</div>
