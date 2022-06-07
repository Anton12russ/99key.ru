<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;


$this->title = 'Мои покупки';

$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = 'Мои покупки';
$this->blocks['right'] = '';
$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/js/user.js',['depends' => [\yii\web\JqueryAsset::className()]]);

		foreach(Yii::$app->caches->rates() as $rates) {
					if($rates['def'] == 1) {
						define('RAT',   $rates['text']);
					}
				}
?>
<?//print_r($payment);?>
<div class="row">
<div class="">
 
   <div class="person-body">
   <h1><i class="fa-solid fa-cart-shopping-fast" aria-hidden="true"></i> <?=$this->title?></h1>
<?php Pjax::begin(['id' => 'pjaxContent', 'enablePushState' => false]); ?>
  <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
          

           ['attribute'=>'id', 'format'=>'raw' ,'value'=> function($data) {
			   
			   if($data->dispute['status'] == 1) {
				   $alert =  'Спор открыт';
			   }elseif($data->dispute['status'] == 2){
				   $alert =  'Спор закрыт';
			   }else{   
				   $alert =  'Открыть спор';
			   }
			   if($data->pay == 1) {
			      $spor = '<br><a data-pjax="0" href="'.Url::toRoute(['dispute', 'car_id'=>$data->id]).'">'.$alert.'</a>';
			   }else{
				  $spor = ''; 
			   }
			   
			   if($data->status == 4){
				   $spor = ''; 
			   }
				return '№ '.$data->id;
			}],	
            ['attribute'=>'data_add', 
			  'filterInputOptions' => [
			  'autocomplete' => 'off',
                'class' => 'form-control  datepicker',
                'id' => false,

            ],
			],
            //'data_end',
            ['attribute'=>'status','filter'=>\common\models\Car::STATUS_LIST, 'format'=>'raw' ,'value'=> function($data) {
				
				$arrey = ['0'=>['На рассмотрении'],'1'=>['Отправлен'],'2'=>['Доставлен'],'3'=>['Отменен'],'4'=>['Завершен']];
				$return ='<div class="col-md-12" style="text-align: center">'.$arrey[$data->status][0].'<br><br>';
	if($data->status != 3){	if($data->status != 4 && !$data->dispute['status']){$return .= '<button data-id="'.$data->id.'" class="status-edit btn btn-success">Товар Получен</button></div>';}}
				return $return;
			}],	
			
			['attribute'=>'id_product' ,'format'=>'raw' ,'filter'=>false,'value'=> function($data) {
		
				$prot = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'https://';
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
							
							   }else{
								  $note = ''; 
							   }
							}
						}else{
							  $note = '';
						}

					    $result .= 'ID <a pjax="0" target="_blank" href="'.$prot.DOMAIN.'/boardone?id='.$prod[0].'">'.$prod[0].'</a> - '.$prod[1].' шт.'.$note.'<br>за шт - '.$prod[2].' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';
				
					}
				}
				
					if(isset($product_id[1])) {
						$result .= 'Доставка '.$product_id[1].' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';
					}
				return /*$data->shop['domen']*/$result;
			}],
            ['attribute'=>'pay' ,'format'=>'raw' ,'filter'=>\common\models\Car::PAY_LIST,'value'=>@Pay],
                      ['attribute'=>'price', 'format'=>'raw' , 'value'=>   function($model) {return $model->price.' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';}],
         
			 
			  ['attribute'=>'dostavka', 'format'=>'raw', 'value' => function($model) {if($model->bayer['country']) {return 'Доставка';}else{return 'Самовывоз';}}],

        ],
    ]); ?>
 <?php Pjax::end(); ?>
   </div>
</div>

</div>