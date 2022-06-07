<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\helpers\Url;

$this->registerCssFile('/css/route_mess.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/routemess.js',['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<script>
console.log('OK');
</script>
<div class="messenger">
<?php Pjax::begin([ 'id' => 'pjaxContentroute', 'enablePushState' => false]); ?>
<div class="col-md-12">
<? if($model) {?>
	<? foreach ($model as $rout) {?>
	<? if(isset($rout->authorToo) && isset($rout->authorFrom) || $rout->user_from > 100000000)  {?>
	<?php $new = Yii::$app->userFunctions->messagerNew($rout->id);?>
	<div class="route_all"  data-href="<?=$rout->id?>">
	<a class="a-absolute" href="<?=Url::to(['user/messenger', 'id' => $rout->id]);?>" data-pjax ="0"></a>
	<?if($new) {?><div  data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Есть новые сообщения" class="new-rout"><i class="fa fa-regular fa-envelope" aria-hidden="true"></i></div><? } ?>
	<?if($rout->route == 'blog') {?>
	<?if(isset($rout->authorToo->id) && $rout->authorToo->id == Yii::$app->user->id) {?><? $author = $rout->authorFrom?><?}else{?><? $author = $rout->authorToo?><?}?>
         <!--Объявление-->
		 <?if(isset($rout->blogs->title)) {?>
         <div class="images-rout" style="<?if($rout->blogs->imageBlog) {?>background-image: url(<?= Yii::getAlias('@blog_image_mini').'/'.$rout->blogs->imageBlog[0]->image;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			      <div class="rout-rout board-rout">Объявление</div>
                   <i class="fa fa-solid fa-user  <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?>"></i> 
				   <?=$author->username?><span class="ine-u"> <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>На связи<? }else{?>Не в сети</span><? }?>
			  </div>
		     <div class="name-board"><?=Yii::$app->userFunctions->substr_user($rout->blogs->title, 55)?></div>
		 </div>
		 <? }else{ ?>
		    Объявление удалено
		 <? } ?>
	<?}elseif($rout->route == 'shop') {?>
    	<?if($rout->authorToo->id == Yii::$app->user->id) {?><? $author = $rout->authorFrom?><?}else{?><? $author = $rout->authorToo?><?}?>
         <!--Магазины-->
		<?if(isset($rout->shops->name)) {?>  
         <div class="images-rout" style="<?if($rout->shops->img) {?>background-image: url(<?= Yii::getAlias('@shop_logo').'/'.$rout->shops->img;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		        <div class="author-rout">
			      <div class="rout-rout shop-rout">Магазин</div>
                  
				   <? if(isset($author->online)) {?> 
				        <i class="fa fa-solid fa-user  <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?> "></i> 
				        <?=$author->username?><span class="ine-u"> <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>На связи<? }else{?>Не в сети</span><? }?>
				    <? }else{ ?>
				       Гость
				    <? } ?>
			  </div>
		     <div class="name-board"><?=Yii::$app->userFunctions->substr_user($rout->shops->name, 55)?></div>
		 </div>
		<? }else{ ?>
		   Магазин удален
		<? } ?>
		
		
		
	<?}elseif($rout->route == 'passanger') {?>

	<?if($rout->authorToo->id == Yii::$app->user->id) {?><? $author = $rout->authorFrom?><?}else{?><? $author = $rout->authorToo?><?}?>
    <?if(isset($rout->passangers->id)) {?>
	<? $img = $rout->passangers->img;?> 
	 <? $url = Url::to(['passanger/one', 'id'=>$rout->passangers->id]);?>
		 <!--Статьи-->
        <div class="images-rout" style="<?if($img) {?>background-image: url(/uploads/images/passanger/logo/<?=$img;?>);<?}else{?>background-image: url(/passanger_js/img/stop.png);<? } ?>"></div>
            <div class="body-rout">
		      <div class="author-rout">
			    <div class="rout-rout art-rout">Поездка</div>
			     <i class="fa fa-solid fa-user  <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?> "></i> 
			     <?=$author->username?><div class="ine-um"> <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>  На связи<? }else{?>был(а) в сети <?=$author->online?><? }?></div>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions3->address($rout->passangers->ot).' до '.Yii::$app->userFunctions3->address($rout->passangers->kuda)?></a></div>
		 </div>
		 	 <? }else{ ?>
		   Поездка удалена
		 <? } ?>		
	<?}elseif($rout->route == 'article') {?>
	<?if($rout->authorToo->id == Yii::$app->user->id) {?><? $author = $rout->authorFrom?><?}else{?><? $author = $rout->authorToo?><?}?>
    <?if(isset($rout->articles->title)) {?>   
	<? $img = Yii::$app->userFunctions->artImage($rout->articles->text);?>	 
		 <!--Статьи-->
         <div class="images-rout" style="<?if($img) {?>background-image: url(<?=$img;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		         <div class="author-rout">
			      <div class="rout-rout art-rout">Статья</div>
                                     <i class="fa fa-solid fa-user  <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?> "></i> 
				   <?=$author->username?><span class="ine-u"> <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>На связи<? }else{?>Не в сети</span><? }?>
			  </div>
		     <div class="name-board"><?=Yii::$app->userFunctions->substr_user($rout->articles->title, 55)?></div>
		 </div>
		 	 <? }else{ ?>
		   Статья удалена
		 <? } ?>
	<?}?>	
 </div>	
<? }else{ ?>  
<div class="route_all"  data-href="<?=$rout->id?>">
        Пользователь удален
</div>	
 <? } ?> 
<? } ?> 


<? }else{ ?>
<br>
<div class="alert alert-info">Начать диалог можно с любой страницы опубликованной автором.</div> 
	<? } ?>
</div>	
 <div class="col-md-12">
	<?= LinkPager::widget([
      'pagination' => $pages,
    ]); ?>
</div>
<?php Pjax::end(); ?>
</div>