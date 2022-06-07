<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;


$this->title = 'Мои продажи';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = $this->title;


$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/js/user.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->blocks['right'] = '';
		foreach(Yii::$app->caches->rates() as $rates) {
					if($rates['def'] == 1) {
						define('RAT',   $rates['text']);
					}
				}
?>
<?//print_r($payment);?>
<div class="row">
<div class="">
   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">
    <h1><i class="fa-solid fa-chart-line-up" aria-hidden="true"></i> <?=$this->title?></h1>
<?php Pjax::begin(['id' => 'pjaxContent', 'enablePushState' => false]); ?>
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

			
				$arrshop = ['Ожидает','Отправлен','Доставлен','Отменен'];
				if($data->status == 4){
				  $return ='<div class="col-md-12" style="text-align: center">Завершен<br><br>';
				}else{
				  $return = '';
				}
		if($data->status == 3){
			return 'Отменено';
		}
			if($data->status != 4){
			  $return .= '<select class="car-shop-user form-control" data-id="'.$data->id.'">';
			      foreach($arrshop as $keys => $res) {
					  if($keys == $data->status) {$selected = 'selected="selected"';}else{$selected = '';}
				     $return .='<option '.$selected.' value="'.$keys.'">'.$res.'</option>'; 
			      }
			  $return .= '</select>'; 
			}
		
				return $return;
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
								   $note = ' <i style="color: red;" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$results['note'].'" class="fa fa-bell" aria-hidden="true"></i>';
							   }
							}
						}else{
							  $note = '';
						}
						
					    $result .= 'ID <a pjax="0" target="_blank" href="'.$prot.$data->shop['domen'].'.'.DOMAIN.'/boardone?id='.$prod[0].'">'.$prod[0].'</a> - ' . $prod[1].' шт.'.$note.'<br>за шт - '.$prod[2].' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';
					
					}
				}
				if(isset($product_id[1])) {
						$result .= 'Доставка '.$product_id[1].' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';
					}
				return /*$data->shop['domen']*/$result;
			}],
            ['attribute'=>'pay' ,'format'=>'raw' ,'filter'=>\common\models\Car::PAY_LIST,'value'=>@Pay],
            ['attribute'=>'price', 'format'=>'raw' , 'value'=>   function($model) {return $model->price.' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';}],
            ['attribute'=>'Покупатель', 'format'=>'raw' , 'value'=>   function($model) {
				
				
				$return = '';
				
				
				
				
				
				
			$return .=
			
			'
			<div class="panel panel-default">
        <div class="panel-heading-car">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse-'.$model->id.'" class="collapsed">
               '.$model->bayer['name'].' <i class="fa fa-arrow-down" aria-hidden="true"></i>
              </a>
        </div>
        <div id="collapse-'.$model->id.'" class="panel-collapse collapse">
          <div class="panel-body">
           <strong>Имя:</strong>  '.$model->bayer['name'].'<br>
		   <strong>Фамилия:</strong>  '.$model->bayer['family'].' <br>  
		   <strong>E-mail:</strong>  '.$model->bayer['email'].'<br>
		   <strong>Телефон:</strong>  '.$model->bayer['phone'].'<br>
		';   
		   if($model->bayer['country']) {
		   $return .= ' 
		   <strong>Страна:</strong>  '.$model->bayer['country'].'<br>
		   <strong>Регион:</strong>  '.$model->bayer['region'].'<br>
		   <strong>Город:</strong>  '.$model->bayer['city'].'<br>
		   <strong>Адрес:</strong>  '.$model->bayer['address'].'<br>
		   <strong>Индекс:</strong>  '.$model->bayer['postcode'].'<br>
		  '; 
		   }
		 $return .= '  
		  </div>
        </div>
      </div>
			
			';
			return $return;
			//$model->bayer['name']
			
			;}],
			
			
			  ['attribute'=>'dostavka', 'format'=>'raw', 'value' => function($model) {if($model->bayer['country']) {return 'Доставка';}else{return 'Самовывоз';}}],
			//'user.email'

        ],
    ]); ?>
 <?php Pjax::end(); ?>
   </div>
</div>
</div>
</div>