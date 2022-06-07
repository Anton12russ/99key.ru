<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title = 'Покупки';
$this->params['breadcrumbs'][] = $this->title;
		foreach(Yii::$app->caches->rates() as $rates) {
					if($rates['def'] == 1) {
						define('RAT',   $rates['text']);
					}
				}
?>
<div class="car-index">

    <?php Pjax::begin(); ?>


 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
          

          'id',
            ['attribute'=>'data_add', 
			  'filterInputOptions' => [
			  'autocomplete' => 'off',
                'class' => 'form-control  datepicker',
                'id' => false,

            ],
			],
            //'data_end',
            ['attribute'=>'status','filter'=>\common\models\Car::STATUS_LIST, 'format'=>'raw' ,'value'=> function($data) {
				$arrshop = ['Ожидает','Отправлен','Доставлен','Отменен', 'Завершен'];
				return $arrshop[$data->status];
			}],	
			
			['attribute'=>'id_product' ,'format'=>'raw' ,'filter'=>false,'value'=> function($data) {
				$prot = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
				$product_id = explode('&',$data->id_product);
				$products = explode(',',$product_id[0]);
				$result = '';
				foreach($products as $res) {
					$prod = explode('|',$res);
					if(isset($prod[1]) && isset($prod[2])) {
						
						if($data->note) {
							foreach($data->note as $results) {
							   if($results['id_product'] == $prod[0]) {
								   $note = ' <i style="color: red;" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$results['note'].'" class="fa fa-bell-o" aria-hidden="true"></i>';
							   }
							}
						}else{
							  $note = '';
						}
						
					    $result .= 'ID <a pjax="0" target="_blank" href="'.$prot.$data->shop['domen'].'.'.DOMAIN.'/boardone?id='.$prod[0].'">'.$prod[0].'</a> - '.$prod[1].' шт.'.$note.'<br>за шт - '.$prod[2].' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';
					if(isset($product_id[1])) {
						$result .= 'Доставка '.$product_id[1].' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';
					}
					}
				}
				return '<div style="min-width: 130px;">'.$result.'</div>';
			}],
            ['attribute'=>'pay' ,'format'=>'raw' ,'filter'=>\common\models\Car::PAY_LIST,'value'=>@Pay],
			['attribute'=>'price', 'format'=>'raw' , 'value'=>   function($model) {return $model->price.' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';}],
            ['attribute'=>'author', 'format'=>'raw' , 'value'=>   function($model) {
				$prot = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
				return $model->user['email'].'<br>Магазин <a pjax="0" target="_blank" href="'.$prot.$model->shop['domen'].'.'.DOMAIN.'">'.$model->shop['name'].'</a>';}],
			['attribute'=>'user', 'format'=>'raw' , 'value'=>   function($model) {
				return 
			
			'
			<div class="panel panel-default">
        <div class="panel-heading-car">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse-'.$model->id.'" class="collapsed">
               '.$model->bayer['name'].' <i class="fa fa-arrow-down" aria-hidden="true"></i>
              </a>
        </div>
        <div id="collapse-'.$model->id.'" class="panel-collapse collapse">
          <div class="panel-body">
		   <strong>ID пользователя:</strong>  '.$model->bayerid['id'].'<br>
           <strong>Имя:</strong>  '.$model->bayer['name'].'<br>
		   <strong>Фамилия:</strong>  '.$model->bayer['family'].' <br>  
		   <strong>E-mail:</strong>  '.$model->bayer['email'].'<br>
		   <strong>Телефон:</strong>  '.$model->bayer['phone'].'<br>
		   <strong>Страна:</strong>  '.$model->bayer['country'].'<br>
		   <strong>Регион:</strong>  '.$model->bayer['region'].'<br>
		   <strong>Город:</strong>  '.$model->bayer['city'].'<br>
		   <strong>Адрес:</strong>  '.$model->bayer['address'].'<br>
		   <strong>Индекс:</strong>  '.$model->bayer['postcode'].'<br>
		  </div>
        </div>
      </div>
			
			'
			//$model->bayer['name']
			
			;}],
			//'user.email'

        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
