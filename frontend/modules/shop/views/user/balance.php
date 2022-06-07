<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


$this->title = 'Пополнить баланс';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = 'Пополнить баланс';



//передаем в шаблон
if(isset($shop->img)) {$this->params['logo'] = $shop->img;}else{$this->params['logo'] = '';}
$this->params['title'] = $shop->name;
$this->params['phone'] = $shop->field->phone;
$this->params['shop'] = $shop;
$this->params['arr'] =  array('Пн.','Вт.','Ср.','Чт.','Пт.','Сб.','Вс.');
$this->params['vote'] = $vote;
$this->params['vot'] = $shop->reating;

   $subdomen = explode('.', $_SERVER['HTTP_HOST']);
   
?>
<?//print_r($payment);?>
<div class="row">
<div class="">

   <div class="col-md-12">
   <div class="person-body">
    <h1><i class="fa-solid fa-money-from-bracket" aria-hidden="true"></i> <?=$this->title?></h1>
	   <div class="center">
	      <div class="form-group">
                <label class="control-label">Сумма пополнения</label>
                <input required type="number" id="sum" class="form-control sum" name="sum" value=""/>
          </div>
		  <div class="body-logo-pay"  data-url="<?=$subdomen[0]?>">
		      <?foreach($payment as $res) {?>
				  <div class="logo_pay">
		              <a class="sum-open" style="   
   background-image: url(<?=$res['logo']?>)" data-rout="https://1tu.ru<?=Url::to(['payment/'.$res['route'].'-form'])?>" href="#"></a>
				 </div>
		      <? } ?>
		 </div>
	   </div>
   </div>
</div>
</div>
</div>