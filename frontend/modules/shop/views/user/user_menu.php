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

	
	
      <li><a href="<?=Url::to(['/user/index'])?>">Профиль</a></li>
	   <li><a href="<?=Url::to(['/user/balance'])?>">Пополнить баланс</a></li>
	   <li><a href="<?=Url::to(['/user/history'])?>">Платежная история</a></li>
	   <li><a href="<?=Url::to(['/user/alerts'])?>">Оповещения</a></li>
	   <li><a href="<?=Url::to(['/mynotepad'])?>" target="_blank">Избранное</a></li>
	   <li><a href="<?=Url::to(['/user/message'])?>">Сообщения</a></li>
	   <li><a href="<?=Url::to(['/user/product'])?>">Мои покупки</a></li>
	   <li><a style="color: #f34723" href="https://1tu.ru/user">Личный кабинет на 1TU.ru</a></li>
	   <!----------------------------------------------------------->
	  

	
	   <? $exit = '<li class="exit"> <i class="fa fa-times" aria-hidden="true"></i>'. Html::beginForm(['/user/logout'], 'post'). Html::submitButton( 'Выход',['class' => 'btn btn-link logout']). Html::endForm(). '</li>'; echo $exit;?>
     
	</ul>