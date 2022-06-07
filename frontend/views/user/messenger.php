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
//Удалем куку чата 
Yii::$app->response->cookies->remove('chat_push');


 if (!Yii::$app->user->id && !Yii::$app->request->cookies['chat']) {
	   $id_user = Yii::$app->request->cookies['chat']->value;
 }elseif(Yii::$app->request->cookies['chat']){
	  $id_user = Yii::$app->request->cookies['chat']->value;
 }else{
	 $id_user = Yii::$app->user->id;
 }
 if (Yii::$app->user->id) {
	  $id_user = Yii::$app->user->id;
 }
?>
<style>
html, body {
	height: 100%;
}
</style>
<script>
console.log('OK');
</script>
<div class="messenger aut-mess">
    <div class="mess-header">   
		<?php $new = Yii::$app->userFunctions->messagerNew($rout->id);?>
		<?php Yii::$app->userFunctions->messagerNoflag($rout->id);?>
       <?if($route_name) {	?>
	    <div class="route_all"  data-href="<?=$rout->id?>">
	<?if($new) {?><div  data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Есть новые сообщения" class="new-rout"><i class="fa fa-regular fa-envelope" aria-hidden="true"></i></div><? } ?>
	<?if($route_name == 'blog') {?>
	<?$too = $rout->user_id;?>
	<? $url = Url::to(['blog/one', 'region'=>$rout->regions['url'], 'category'=>$rout->categorys['url'], 'url'=>$rout->url, 'id'=>$rout->id]);?>
         <!--Объявление-->
         <div class="images-rout" style="<?if($rout->imageBlog) {?>background-image: url(<?= Yii::getAlias('@blog_image_mini').'/'.$rout->imageBlog[0]->image;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			    <div class="rout-rout board-rout">Объявление</div>
			    <div style="white-space: nowrap;"><i class="fa fa-solid fa-user  <? if($rout->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?>"></i> 
				<?=$rout->author->username?></div><div class="ine-um"> <? if($rout->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>На связи<? }else{?>был(а) в сети <?=$rout->author->online?><? }?></div>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($rout->title, 55)?></a></div>
		 </div>
		 
		 

	<?}elseif($route_name == 'shop') {?>
	     <? $too = $rout->user_id;?>
         <? $url = Url::to(['shop/one', 'region'=>$rout->regions['url'], 'category'=>$rout->categorys['url'], 'id'=>$rout->id, 'name'=>Yii::$app->userFunctions->transliteration($rout->name)]);?>
         <!--Магазины-->
         <div class="images-rout" style="background-size: 100%; <?if($rout->img) {?>background-image: url(<?= Yii::getAlias('@shop_logo').'/'.$rout->img;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			      <div class="rout-rout shop-rout">Магазин</div>
			    <div style="white-space: nowrap;"><i class="fa fa-solid fa-user  <? if($rout->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?>"></i> 
				<?=$rout->author->username?></div><div class="ine-um"> <? if($rout->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>На связи<? }else{?>был(а) в сети <?=$rout->author->online?><? }?></div>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($rout->name, 55)?> <i class="fa fa-external-link" aria-hidden="true"></i></a></div>
		 </div>
		 
		 
		 
<?}elseif($route_name == 'passanger') {?>

	<? $too = $rout->user_id;?>
	<? $img = $rout->img;?>
	<? $url = Url::to(['passanger/one', 'id'=>$rout->id]);?>
         <!--Поездки-->
         <div class="images-rout" style="<?if($img) {?>background-image: url(/uploads/images/passanger/logo/<?=$img;?>);<?}else{?>background-image: url(/passanger_js/img/stop.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			    <div class="rout-rout art-rout">Поездка</div>
			    <i class="fa fa-solid fa-user  <? if($rout->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?>"></i> 
				<?=$rout->author->username?><div class="ine-um"> <? if($rout->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>На связи<? }else{?>был(а) в сети <?=$rout->author->online?><? }?></div>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions3->address($rout->ot).' до '.Yii::$app->userFunctions3->address($rout->kuda)?></a></div>
		 </div>		 
		 
	<?}elseif($route_name == 'article') {?>
	<? $too = $rout->user_id;?>
	<? $img = Yii::$app->userFunctions->artImage($rout->text);?>
	<? $url = Url::to(['article/one', 'category'=>$rout->cats['url'], 'id'=>$rout->id, 'name'=>Yii::$app->userFunctions->transliteration($rout->title)]);?>
         <!--Статьи-->
         <div class="images-rout" style="<?if($img) {?>background-image: url(<?=$img;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			    <div class="rout-rout art-rout">Статья</div>
			    <div style="white-space: nowrap;"><i class="fa fa-solid fa-user  <? if($rout->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?>"></i> 
				<?=$rout->author->username?></div><div class="ine-um"> <? if($rout->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>На связи<? }else{?>был(а) в сети <?=$rout->author->online?><? }?></div>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($rout->title, 55)?></a></div>
		 </div>
	<?}?>	
      </div>
	  
	  
	  
	  
		<?}else{?>

	<?if($rout->user_too == $id_user) {
		$too = $rout->user_from;
	}else{
		$too = $rout->user_too;
	}?>
	<div class="route_all"  data-href="<?=$rout->id?>">
	<?if($new) {?><div  data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Есть новые сообщения" class="new-rout"><i class="fa fa-regular fa-envelope" aria-hidden="true"></i></div><? } ?>
	<?if($rout->route == 'blog') {?>
    <!--Объявление-->
	<?if(isset($rout->blogs->id)) {?>
	<? $url = Url::to(['blog/one', 'region'=>$rout->blogs->regions['url'], 'category'=>$rout->blogs->categorys['url'], 'url'=>$rout->blogs->url, 'id'=>$rout->blogs->id]);?>
    <?if($rout->authorToo->id == $id_user) {?><? $author = $rout->authorFrom?><?}else{?><? $author = $rout->authorToo?><?}?>     
		
         <div class="images-rout" style="<?if($rout->blogs->imageBlog) {?>background-image: url(<?= Yii::getAlias('@blog_image_mini').'/'.$rout->blogs->imageBlog[0]->image;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			      <div class="rout-rout board-rout">Объявление</div>
			       <div style="white-space: nowrap;"> <i class="fa fa-solid fa-user  <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?> "></i> 
				   <?=$author->username?></div><div class="ine-um"> <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>  На связи<? }else{?>был(а) в сети <?=$author->online?><? }?></div>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($rout->blogs->title, 55)?></a></div>
		 </div>
	<? }else{ ?>
		 Объявление удалено
    <? } ?>
	<?}elseif($rout->route == 'shop') {?>
        <?if(isset($rout->shops->id)) {?>
         <? $url = Url::to(['shop/one', 'region'=>$rout->shops->regions['url'], 'category'=>$rout->shops->categorys['url'], 'id'=>$rout->shops->id, 'name'=>Yii::$app->userFunctions->transliteration($rout->shops->name)]);?>
         <?if($rout->authorToo->id == $id_user) {?><? $author = $rout->authorFrom?><?}else{?><? $author = $rout->authorToo?><?}?>
		 <!--Магазины-->
         <div class="images-rout" style="background-size: 100%; <?if($rout->shops->img) {?>background-image: url(<?= Yii::getAlias('@shop_logo').'/'.$rout->shops->img;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			      <div class="rout-rout shop-rout">Магазин</div>
				<? if(isset($author->username)) {?> 
			       <div style="white-space: nowrap;"><i class="fa fa-solid fa-user  <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?> "></i> 
				    <?=$author->username?></div><div class="ine-um"> <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>  На связи<? }else{?>был(а) в сети <?=$author->online?><? }?></div>
			    <? }else{ ?>
				    Гость
				<? } ?>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($rout->shops->name, 55)?> <i class="fa fa-external-link" aria-hidden="true"></i></a></div>
		 </div>
	<? }else{ ?>
		Магазин удален
    <? } ?> 



	<?}elseif($rout->route == 'passanger') { ?>
	<?if(isset($rout->passangers->id)) {?>
	    <?if($rout->authorToo->id == $id_user) {?><? $author = $rout->authorFrom?><?}else{?><? $author = $rout->authorToo?><?}?>
		<? $img = $rout->passangers->img;?>
	    <? $url = Url::to(['passanger/one', 'id'=>$rout->passangers->id]);?>
	
         <!--Поездки-->
         <div class="images-rout" style="<?if($img) {?>background-image: url(/uploads/images/passanger/logo/<?=$img;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
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
	<?if(isset($rout->articles->id)) {?>
	<?if($rout->authorToo->id == $id_user) {?><? $author = $rout->authorFrom?><?}else{?><? $author = $rout->authorToo?><?}?>
	
	<? $img = Yii::$app->userFunctions->artImage($rout->articles->text);?>
	<? $url = Url::to(['article/one', 'category'=>$rout->articles->cats['url'], 'id'=>$rout->articles->id, 'name'=>Yii::$app->userFunctions->transliteration($rout->articles->title)]);?>
         <!--Статьи-->
         <div class="images-rout" style="<?if($img) {?>background-image: url(<?=$img;?>);<?}else{?>background-image: url(<?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png);<? } ?>"></div>
         <div class="body-rout">
		      <div class="author-rout">
			      <div class="rout-rout art-rout">Статья</div>
			        <div style="white-space: nowrap;"><i class="fa fa-solid fa-user  <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>online <? }else{?> offline<? }?> "></i> 
				    <?=$author->username?></div><div class="ine-um"> <? if($author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>  На связи<? }else{?>был(а) в сети <?=$author->online?><? }?></div>
			  </div>
		     <div class="name-board"><a target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($rout->articles->title, 55)?></a></div>
		 </div>
		 	<? }else{ ?>
		 Статья удалена
    <? } ?>
	<?}?>	
      </div>
<?}?>	  
    </div>
	
	<!-------------------Получение переписки-------------------->
	<div class="ajax hidden"></div>
	<? if(Yii::$app->user->id) {?> 
	   <a class="close-act" href="<?=Url::to(['user/route']);?>">← Все сообщения</a>
	<? } ?> 
	<?php Pjax::begin([ 'id' => 'pjaxContent']); ?>
	<?php Pjax::begin([ 'id' => 'pjaxMess']); ?>
	<div class="col-md-12 mess_all close-mess">
	<div class="mess-als-div" id="top">
    <?if($count > 20) {?>
	   <div class="loads" data-href="<?=Url::to(['user/mess-all', 'id'=>$rout->id]);?>">Показать всю переписку</div>
	<? } ?>
	 <? if($mess) {?>
	    <? foreach (array_reverse($mess) as $res) {?>
		  <?if($res['u_from'] != $id_user) {?>
		      <div class="mess-div too-div"><div class="too-mess"><?=$res['text']?></div><div class="date-mess"><?=$res['date']?></div></div>
		  <?}else{?>
		      <div class="mess-div from-div"><div class="from-mess"><?=$res['text']?></div><div class="date-mess"><?=$res['date']?></div></div>
		 <?}?>
			  
	    <?}?>
	  <?}else{?>
	  <br>
	     <div class="alert alert-warning">Пока нет переписки с этим пользователем</div>
		 <?$route_add = $route_name;?>
	  <?}?>
	</div>
	</div>
	<?php Pjax::end(); ?>
	<div class="mess-form">
   <?if($too != $id_user) { ?>
   <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
	
    <?= $form->field($model, 'u_from')->hiddenInput(['value' => $id_user])->label(false) ?>

    <?= $form->field($model, 'u_too')->hiddenInput(['value' => $too])->label(false) ?>
	<? if(isset($route_add)) {?>
		<?= $form->field($model, 'route_add')->hiddenInput(['value' => $route_add])->label(false) ?>
		<?= $form->field($model, 'message')->hiddenInput(['value' => $rout->id])->label(false) ?>
	<?}?>
	<?= $form->field($model, 'route_id')->hiddenInput(['value' => $rout->id])->label(false) ?>
	<?= Html::submitButton('<i class="fa fa-paper-plane" aria-hidden="true"></i>', ['class' => 'btn btn-success']) ?>
    <?= $form->field($model, 'text', ['template' => '{input}'])->textInput(['value'=>'', 'autocomplete' => 'off'])->label(false) ?>

     


    <?php ActiveForm::end(); ?>
<? }else{ ?>
<div class="alert alert-warning">Вы не можете писать самому себе</div>
<? } ?>		
	</div>
	<?php Pjax::end(); ?>

</div>
<div class="eshe"></div>