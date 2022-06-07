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
?>
<?//print_r($payment);?>
<div class="row">
<div class="">
   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">
    <h1><i class="fa fa-solid fa-money-from-bracket" aria-hidden="true"></i> <?=$this->title?></h1>
	   <div class="center">
	      <div class="form-group">
                <label class="control-label">Сумма пополнения</label>
                <input required type="number" id="sum" class="form-control sum" name="sum" value=""/>
          </div>
		  <div class="body-logo-pay">
		      <?foreach($payment as $res) {?>
				  <div class="logo_pay">
		              <a class="sum-open" style="background-image: url(<?=$res['logo']?>)" data-rout="<?=Url::to(['payment/'.$res['route'].'-form'])?>" href="#"></a>
				 </div>
		      <? } ?>
		 </div>
	   </div>
   </div>
</div>
</div>
</div>