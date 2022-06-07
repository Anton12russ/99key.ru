<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->registerJsFile('/js/personal.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/css/personal.css', ['depends' => ['frontend\assets\AppAsset']]);
		 foreach(Yii::$app->caches->rates() as $res) {
        	if ($res['value'] == 1) {
	        	$rates = $res;
	        }
         }
		 
$shop = Yii::$app->userFunctions->shopMenu(Yii::$app->user->id);
if(isset($shop)) {
	$dispute = Yii::$app->userFunctions->disputeshop();
}else{
	$dispute = '';
}

$support_new = Yii::$app->userFunctions->support_new();
if (!Yii::$app->user->id) {return Yii::$app->response->redirect(['user']); exit();}
?>


<div id="menu" class="col-md-12 alert balance_menu  
<?if (Yii::$app->user->identity->balance > 50) {?>alert-success<?}?>
<?if (Yii::$app->user->identity->balance < 50 && Yii::$app->user->identity->balance > 0) {?>alert-warning<?}?>
<?if (Yii::$app->user->identity->balance == 0) {?>alert-danger<?}?>
">Баланс: <span><?=Yii::$app->user->identity->balance?></span> <i class="fa <?=$rates['text']?>" aria-hidden="true"></i></div>

<?if(Yii::$app->user->identity->balance_temp) {?>
   <div class="col-md-12 alert balance_menu alert-info">Удержано: <span><?=Yii::$app->user->identity->balance_temp?></span> <i class="fa <?=$rates['text']?>" aria-hidden="true"></i></div>
<? } ?>   

	<ul class="menu_user">
	
	  <h5 style="border-bottom: 1px solid #ccc;margin-bottom: 2px; font-weight: 600;"><i class="fa fa-gavel" aria-hidden="true"></i> Аукцион</h5>
	  <li><a href="<?=Url::to(['/user/auction'])?>">Мои аукционы</a></li>
	  <li><a href="<?=Url::to(['/user/bet'])?>">Мои ставки</a></li>
	  <li><a href="<?=Url::to(['/user/reserv'])?>">Зарезервировано мной</a></li>
	  <li><a href="<?=Url::to(['/user/successlot'])?>">Выигрышные лоты</a></li>
	  
	  <h5 style="border-bottom: 1px solid #ccc; margin-bottom: 2px; font-weight: 600;"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Товар</h5>
	   <?if ($shop) {?>
	     <li><a href="<?=Url::to(['/user/blogs'])?>">Мой Товар</a></li>
	   <? }else{ ?>
	     <li><a href="<?=Url::to(['/user/blogs'])?>">Объявления</a></li>
	   <? } ?>
	   <li><a href="<?=Url::to(['/blog/notepad'])?>" target="_blank">Избранное</a></li>
	   
	   <li><a href="<?=Url::to(['/user/product'])?>">Мои покупки</a></li>
	   
	       <h5 style="border-bottom: 1px solid #ccc; margin-bottom: 2px; font-weight: 600;"><i class="fa fa-cart-plus" aria-hidden="true"></i> Магазин</h5>
	   <?if ($shop) {?>
	       <li><a target="_blank" href="<?=Url::to(['/shop/update', 'id'=> $shop['shop']])?>">Редактировать магазин</a></li>
		   <li><a href="<?=Url::to(['/user/block'])?>">Рекламные блоки</a></li>
		   <li><a href="<?=Url::to(['/user/slider', 'shop_id'=> $shop['shop']])?>">Слайдер магазина</a></li>
		   <li><a href="<?=Url::to(['/user/car'])?>">Мои продажи</a></li>
		   <li><a href="<?=Url::to(['/user/disputeshop'])?>">Споры 
		   <? if($dispute) {?><span style="color: red">(<?=$dispute?>)</span><?}?>
		   </a></li>
		   <?if ($shop['pay']) {?>
	           <li><a href="<?=Url::to(['/extend_shop'])?>">Продлить магазин</a></li>
		   <? }?>
	   <? }else{ ?>
	       <li><a target="_blank"  href="<?=Url::to(['/shop/add'])?>">Регистрация магазина</a></li>
	   <? } ?>
	  	    <h5 style="border-bottom: 1px solid #ccc; margin-bottom: 2px; font-weight: 600;"><i class="fa fa-car" aria-hidden="true"></i> Попутчики</h5>
	   <li><a href="<?=Url::to(['/user/passanger'])?>">Мои поездки</a></li>
	   <!----------------------------------------------------------->
	   <h5 style="border-bottom: 1px solid #ccc; margin-bottom: 2px; font-weight: 600;"><i class="fa fa-comments" aria-hidden="true"></i> Переписка</h5>
	   <li><a href="<?=Url::to(['/user/message'])?>">Сообщения</a></li>
	   <li><a href="<?=Url::to(['/user/support'])?>">Поддержка </a><? if($support_new) {?><span style="color: red">(<?=$support_new?>)</span><?}?></li>
	   <li><a href="<?=Url::to(['/user/articles'])?>">Мои статьи</a></li>
	   
	  <h5 style="border-bottom: 1px solid #ccc;margin-bottom: 2px; font-weight: 600;"><i class="fa fa-wrench" aria-hidden="true"></i> Настройки</h5>

       <li><a href="<?=Url::to(['/user/index'])?>">Профиль</a></li>
	   <li><a href="<?=Url::to(['/user/balance'])?>">Пополнить баланс</a></li>
	   <li><a href="<?=Url::to(['/user/history'])?>">Платежная история</a></li>
	   <li><a href="<?=Url::to(['/user/alerts'])?>">Оповещения</a></li>
	   

	
	   <? $exit = '<li class="exit"> <i class="fa fa-times" aria-hidden="true"></i>'. Html::beginForm(['/user/logout'], 'post'). Html::submitButton( 'Выход',['class' => 'btn btn-link logout']). Html::endForm(). '</li>'; echo $exit;?>
    </ul>