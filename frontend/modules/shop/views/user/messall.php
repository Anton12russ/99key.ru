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
$this->registerJsFile('/js/messenger.js',['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<div class="messenger aut-mess">
    <div class="mess-header">    
		<?php $new = Yii::$app->userFunctions->messagerNew($rout->id);?>
		
	    <div class="route_all"  data-href="<?=$rout->id?>">
	<?if($new) {?><div  data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Есть новые сообщения" class="new-rout"><i class="fa fa-regular fa-envelope" aria-hidden="true"></i></div><? } ?>
	<?if($rout->route == 'blog') {?>
	<?$too = $rout->blogs->user_id;?>
	<? $url = Url::to(['blog/one', 'region'=>$rout->blogs->regions['url'], 'category'=>$rout->blogs->categorys['url'], 'url'=>$rout->blogs->url, 'id'=>$rout->blogs->id]);?>
         <!--Объявление-->
         <div class="images-rout" style="<?if($rout->blogs->imageBlog) {?>background-image: url(<?= Yii::getAlias('@blog_image_mini').'/'.$rout->blogs->imageBlog[0]->image;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			      <div class="rout-rout board-rout">Объявление</div>
			       <i class="fa fa-solid fa-user" aria-hidden="true"></i> <?if($rout->authorToo->id == Yii::$app->user->id) {?><?=$rout->authorFrom->username?><?}else{?><?=$rout->authorToo->username?><?}?>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($rout->blogs->title, 55)?></a></div>
		 </div>
		 
		 

	<?}elseif($rout->route == 'shop') {?>
	     <? $too = $rout->shops->user_id;?>
         <? $url = Url::to(['shop/one', 'region'=>$rout->shops->regions['url'], 'category'=>$rout->shops->categorys['url'], 'id'=>$rout->shops->id, 'name'=>Yii::$app->userFunctions->transliteration($rout->shops->name)]);?>
         <!--Магазины-->
         <div class="images-rout" style="<?if($rout->shops->img) {?>background-image: url(<?= Yii::getAlias('@shop_logo').'/'.$rout->shops->img;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			      <div class="rout-rout shop-rout">Магазин</div>
			      <i class="fa fa-solid fa-user" aria-hidden="true"></i> <?if($rout->authorToo->id == Yii::$app->user->id) {?><?=$rout->authorFrom->username?><?}else{?><?=$rout->authorToo->username?><?}?>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($rout->shops->name, 55)?> <i class="fa fa-external-link" aria-hidden="true"></i></a></div>
		 </div>
		 
		 
	<?}elseif($rout->route == 'article') {?>
	<? $too = $rout->articles->user_id;?>
	<? $img = Yii::$app->userFunctions->artImage($rout->articles->text);?>
	<? $url = Url::to(['article/one', 'category'=>$rout->articles->cats['url'], 'id'=>$rout->articles->id, 'name'=>Yii::$app->userFunctions->transliteration($rout->articles->title)]);?>
         <!--Статьи-->
         <div class="images-rout" style="<?if($img) {?>background-image: url(<?=$img;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			      <div class="rout-rout art-rout">Статья</div>
			      <i class="fa fa-solid fa-user" aria-hidden="true"></i> <?if($rout->authorToo->id == Yii::$app->user->id) {?><?=$rout->authorFrom->username?><?}else{?><?=$rout->authorToo->username?><?}?>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($rout->articles->title, 55)?></a></div>
		 </div>
	<?}?>	
      </div>		 
    </div>
	
	<!-------------------Получение переписки-------------------->

	<?php Pjax::begin([ 'id' => 'pjaxContent']); ?>
	<div class="col-md-12 mess_all none">
	<div class="mess-als-div" id="top">

	 <? if($mess) {?>
	  <div class="col-md-12 pagin">
	   <?= LinkPager::widget([
           'pagination' => $pages,
       ]); ?>
      </div>
	    <? foreach (array_reverse($mess) as $res) {?>
		  <?if($res['u_from'] != Yii::$app->user->id) {?>
		      <div class="mess-div too-div"><div class="too-mess"><?=$res['text']?></div><div class="date-mess"><?=$res['date']?></div></div>
		  <?}else{?>
		      <div class="mess-div from-div"><div class="from-mess"><?=$res['text']?></div><div class="date-mess"><?=$res['date']?></div></div>
		 <?}?>
			  
	    <?}?>
	  <?}else{?>
	  <br>
	     <div class="alert alert-warning">Пока нет переписки с этим пользователем</div>
	  <?}?>
	</div>
	</div>
	<?php Pjax::end(); ?>
	
	
</div>
<div class="eshe"></div>