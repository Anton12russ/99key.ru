<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


$this->title = 'Продлить магазин';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = 'Пополнить баланс';
?>

<div class="row">
<div class="">
   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">
    <h1> <?=$this->title?></h1>
	   <div class="center">
	   <? if (date('Y-m-d H:i:s', strtotime(' - 10 day')) > $model->date_end) { ?>
         <? if(Yii::$app->user->identity->balance >=  Yii::$app->caches->setting()['price-shop']) { ?>
		 <?php Pjax::begin([ 'id' => 'pjaxBlog', 'enablePushState' => false]); ?>
		 
		 
		 <div class="alert alert-success">Магазин будет активирован на <?=Yii::$app->caches->setting()['end-shop']?> дней,  до <?=$data_end?>.<br><br> С вашего счета будет списано <?= Yii::$app->caches->setting()['price-shop']?> <i class="fa <?=$rates['text']?>" aria-hidden="true"></i></div>
		    <?= Html::beginForm(['user/extend-shop-act','shop_id'=>$model->id, 'date_end' => $model->date_end], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
              <?= Html::submitButton('Продлить', ['class' => 'btn btn-success', 'name' => 'hash-button']) ?>
            <?= Html::endForm() ?>
		 <?php Pjax::end(); ?>
		 <? }else{ ?>
		 <div class="alert alert-warning">У Вас нехватает средств, пополните баланс и повторите активацию. Стоимость активации <?= Yii::$app->caches->setting()['price-shop']?>  <i class="fa <?=$rates['text']?>" aria-hidden="true"></i></div>
		 <? } ?>
		 <? }else{  ?>
		 <div class="alert alert-warning">Магазин уже активирован.</div>
		 <? } ?>
	   </div>
   </div>
</div>
</div>
</div>