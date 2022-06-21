<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
use frontend\widget\auction\auction;
$this->title = $meta['title'];
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $meta['keywords']]);
$this->params['breadcrumbs'] = $meta['breadcrumbs'];

$this->registerCssFile('/css/one.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/one.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/readmore.js',['depends' => [\yii\web\JqueryAsset::className()]]);

//Подключаем слайдер
$this->registerCssFile('/assest_all/slider/css/lightslider.css');
$this->registerCssFile('/assest_all/slider/css/lightgallery.css');
$this->registerJsFile('/assest_all/slider/js/lightslider.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/slider/js/script.js',['depends' => [\yii\web\JqueryAsset::className()]]);	
$this->registerJsFile('/assest_all/slider/js/lightgallery.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/slider/js/lightgallery-all.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->params['h1'] = $meta['h1'];
$this->params['note']['id'] = $blog->id;
$this->params['note']['pad'] = $notepad;
foreach ($fields as $res) {
  if ($res['type'] == 'y') {if ($res['hide'] == 1) {if(!Yii::$app->user->isGuest) {$youtube = $res['value'];}else{$youtube = 'hide';}}else{$youtube = $res['value'];}}
  if ($res['type_string'] == 't') {if ($res['hide'] == 1) {if(!Yii::$app->user->isGuest) {$phone = $res['value'];}else{$phone = 'авторизируйтесь';}}else{$phone = $res['value'];}}
} 
sort($price); 
?>
<div class="col-md-12 one-body">
 <? if ($blog->active != 1) {?><br><br><br><div class="alert alert-warning">Объявление еще не активировано. Активируйте его в личном кабинете.</div><br><? } ?>
 <? if ($blog->status_id == 0) {?><br><br><br><br><div class="alert alert-danger">Объявление не опубликовано, возобновить показы можно в личном кабинете.</div><br><? }?>
  <div class="col-md-7 padd-h1">
   <div class="row">
	<ul id="imageGallery">
		       <?php if (isset($youtube)) {?> 
			           <?php  if ($youtube == 'hide') {?> 
					     <li data-thumb="/assest_all/slider/img/youtube-play.png" data-src="/uploads/images/youtube-none.png"> 
						     <div  style="background-image: url(/uploads/images/youtube-none.png);" class="false_window"></div>
                         </li>
					   <? }else{?>
					     <li data-thumb="/assest_all/slider/img/youtube-play.png" data-src=" <?=$youtube?>"> 
						   <iframe src="<?=str_replace('watch?v=', 'embed/',$youtube)?>"class="false_window"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						 </li>
					   <? }?>
		       <? }?>

	
	  <?php if ($blog->imageBlog) { ?>
              <?php foreach ($blog->imageBlog as $images) { ?>
						 <li data-thumb="<?= '/uploads/images/board/mini/'.$images['image']?>" data-src="<?= '/uploads/images/board/maxi/'.$images['image']?>"> 
						     <div style="background-image: url(<?= '/uploads/images/board/maxi/'.$images['image']?>);" class="false_window"></div>
                         </li> 
              <?php } ?>
       <?php } ?>	
     </ul>
	 
<?if($blog->auction == 1 || $blog->auction == 2) {?>	 
 <div style="display: table; width: 100%; text-align: center;"> 
 <h3>Окончание торгов через</h3>
<?$second = strtotime($blog->date_del)?>
<iframe allowtransparency class="iframe" id="esfrsd"  style="margin-bottom: 10px;  overflow: hidden; height: 0px; width: 100%; border: 0px;" src="/ajax/timerauction?tyme=<?=$second?>"></iframe>
 </div>
  <?php } ?>
	</div>
  </div>

   <div class="col-md-5">

   	<!--Аукцион-->
	<?if($blog->auction == 1 || $blog->auction == 2) {?>
	<div class="col-md-12 hidden-xs" style="margin-top: 55px;"></div>
	  <? echo Auction::widget(['blog_id' => $blog->id, 'blog' => $blog,  'price' => $price[0]['value'], 'auction_act' => $blog->auction]);?>
	<? }else{ ?>
	
	
	
	   <div class="price-div">
	   <div data-toggle="tooltip" data-placement="top" title="Цена" class="one-price"> 
		<? if ($price[0]['value']) {?>
		<? if (isset($price[0]['hide']) && $price[0]['hide'] > 0) {?>
	       <?if (!Yii::$app->user->isGuest) { echo str_replace(' ','',$price[0]['value']);?> <i class="fa <?=$price[0]['rates']['text']?>" aria-hidden="true"></i><? foreach($fields as $res) { if ($res['type_string'] == 'q') {?><span class="torg">торг</span><?}}?><?}else{?>После авторизации<?}?>
		<?}else{?>	
		  <span data-dickount="<?=$blog->discount?>" data-price="<?=$price[0]['value']?>" class="count"><?=str_replace(' ','',$price[0]['value'])?></span> <i class="fa <?=$price[0]['rates']['text']?>" aria-hidden="true"></i><? foreach($fields as $res) { if ($res['type_string'] == 'q') {?> <span class="torg">торг</span><?}}?>
		<?}?>
		<?}else{?>
		    Цена не указана
		<?}?>
	    </div>
	 </div>
   
<?php if ($blog->discount && $blog->discount_date >= date('Y-m-d H:i:s') || $blog->discount && !$blog->discount_date)  { ?>
<div class="col-md-12" style="text-align: right;">
<div style="float: right; margin-bottom: 20px;">
  <div data-toggle="tooltip" data-placement="bottom" title="Активировать скидку" class="checkbox-other onoffswitch">
    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0">
    <label class="onoffswitch-label" for="myonoffswitch"></label>
  </div>
</div>
<span style="line-height: 18px;
    margin-right: 70px;
    font-size: 17px;
	margin-top: 5px;
    font-weight: 600;
    display: block;">
	<?php if ($blog->discount_text) { ?>
	  <?=$blog->discount_text?>
	<?}else{?>
	   Активировать скидку
	<?}?>
	</span>
</div>
<?}?>
	
	<? }?>
	
	<?if ($blog->user_id == Yii::$app->user->id || Yii::$app->user->can('updateBoard')) {?>
	 <div class="col-md-12 edit">
	   <a target="_blank" class="update" href="<?=Url::to(['blog/update', 'id' => $blog->id])?>">Редактировать</a>
	   <a target="_blank" class="del" href="<?=Url::to(['blog/del', 'id' => $blog->id])?>">Снять с публикации</a>
	</div> 
	<? } ?>
	   
	
	
	
		
	<!--
	<div class="header-one">
    <div class="panel panel-default">
      
      <div class="panel-heading">Контакты продавца</div>


      <table class="table">

        <tbody>
          <tr>
            <td><strong>Имя:</strong></td>
            <td><?=$blog->author['username']?></td>

          </tr>
          <tr>
            <td><strong>Телефон:</strong></td>
            <td><?=$phone?></td>
     
          </tr>
          <tr>
            <td><strong>Email</strong></td>
            <td><?=$blog->author['email']?></td>
          </tr>
		  
		  <tr>
            <td><strong>Адрес сделки</strong></td>
            <td><?=$blog->coord->text?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
	
	-->
	
	
	
    <!---->
	  <div class="header-one">
	 <? if ($blog->shop && $blog->shop['status'] == 1) {?>
	 <div class="ava-name-body col-md-4">
	 <? $url = Url::to(['shop/one', 'region'=>$blog->shop->regions['url'], 'category'=>$blog->shop->categorys['url'], 'id'=>$blog->shop->id, 'name'=>Yii::$app->userFunctions->transliteration($blog->shop->name)]);?>
	 <a class="go-user" href="<?=$url?>"> <div class="header-one-div ava-name" data-toggle="tooltip" data-html="true" data-placement="top" title="<?=$blog->shop->name?>"><span><i class="fa fa-shop" aria-hidden="true"></i> Магазин</span></div></a></div>
	 <? }else{?>
	 
	<? if($blog->user_id == '1' && $blog->express == '1') {}else{?>
	  <div class="ava-name-body col-md-4"><a target="_blank" class="go-user" href="<?=Url::to(['blog/users', 'id'=>$blog->user_id])?>"> <div class="header-one-div ava-name" data-toggle="tooltip" data-html="true" data-placement="top" title="Автор 
	  <?if(iconv_strlen($blog->author['username']) >= 8) {?>
	  <br><strong class='author_one'><?=$blog->author['username']?>
	  <?}?>
	  </strong>">
	  <span>
	  <i class="fa fa-solid fa-user" aria-hidden="true"></i> 
	  <? if(iconv_strlen($blog->author['username']) >= 8) {echo 'Автор';}else{echo $blog->author['username'];}?>
	  </span></div></a>
	</div>
	 <?}?>
	 <?}?>
		   <div class="header-one-div one-phone <? if($blog->user_id == '1' && $blog->express == '1') {?>col-md-12<?}else{?>col-md-8<?}?>  " data-phone="<?php if (isset($phone)){?><?=$phone?><? }else{  ?>Не указан<? }  ?>" data-toggle="tooltip" data-placement="top" title="Показать Телефон"><span><i class="fa-regular fa-phone" aria-hidden="true"></i> Телефон</span></div>
	
	
		    
		 <!--  <div class="header-one-div one-mass" data-toggle="tooltip" data-placement="top" title="Написать автору"><span><i class="fa fa-regular fa-envelopes" aria-hidden="true"></i> Написать</span></div>-->

	</div>
	
	
	

	
	
	<div class="dop-one">
	
	<table class="table table-bordered table-one">
      <tbody>
<? if (isset($blog->coord->text)) { ?>
	    <tr>
		<td>Место сделки</td>
        <td><?=$blog->coord->text?></td>
	  </tr>
<? } ?>
  	    <? if ($coord) { 
		foreach($coord as $key => $res) {?>
	         <tr>
                <td><?=$res['name']?></td>
                <td> <?php if ($res['hide']) {if(!Yii::$app->user->isGuest) {?><?if ($res['address']) {echo  $res['address'].'<br>';}?> <?=$res['value']?><?}else{echo 'После авторизации';} }else{ ?><?if ($res['address']) {echo  $res['address'].'<br>';}?> <?=$res['value']?><? }?></td>
             </tr>
         <? } }?> 
	  	<? if ($price) { 
		foreach($price as $key => $res) {?>
		   <? if ($key) {?>
	         <tr>
                <td><?=$res['name']?></td>
                <td> <?php if ($res['hide']) {if(!Yii::$app->user->isGuest) {?><?=$res['value']?>  (<?=$res['rates']['name']?>)<?}else{echo 'После авторизации';} }else{ ?><?=$res['value']?>  (<?=$res['rates']['name']?>) <? }?></td>
             </tr>
		   <? } ?>  
         <? } }?> 
	
	<?php 
	$you_key = 0;
	$phone_key = 0;
	foreach ($fields as $res) {?>
	   <!--телефон-->
	   <?php 
	   if ($res['type_string'] == 't' ) { $phone_key++; if ($phone_key >1) {?>
	      <tr>
             <td><?=$res['name']?></td>
             <td> <?php if ($res['hide']) {if(!Yii::$app->user->isGuest) {echo $res['value'];}else{echo 'После авторизации';} }else{ ?><?=$res['value']?> <? }?></td>
          </tr>
	  <? } }
	   if ($res['type'] == 'y' ) { $you_key++; if ($you_key >1) {?>
	      <tr>
              <td><?=$res['name']?></td>
              <td> <?php if ($res['hide']) {if(!Yii::$app->user->isGuest) {echo $res['value'];}else{echo 'После авторизации';} }else{ ?><?=$res['value']?> <? }?></td>
          </tr>
	  <? } }
	  if ($res['type_string'] != 't' && $res['type'] != 'y') {
	  ?>
	  	
	      <tr>
             <td><?=$res['name']?></td>
             <td> <?php if ($res['hide']) {if(!Yii::$app->user->isGuest) {echo $res['value'];}else{echo 'После авторизации';} }else{ ?><?=$res['value']?> <? }?></td>
          </tr>
	 <?} }?>
	
	</tbody>
    </table>
	</div>
	

  </div>
  <? if ($blog->shop && $blog->shop['status'] == 1) {?>
    <div class="header-one">
	 <div class="ava-name-body col-md-4 buttonmagaz">
	 <? $url = Url::to(['shop/one', 'region'=>$blog->shop->regions['url'], 'category'=>$blog->shop->categorys['url'], 'id'=>$blog->shop->id, 'name'=>Yii::$app->userFunctions->transliteration($blog->shop->name)]);?>
	 <a class="go-user" href="<?=$url?>"> <div class="header-one-div ava-name ava-name1" data-toggle="tooltip" data-html="true" data-placement="top" title="<?=$blog->shop->name?>"><span><i class="fa fa-shop" aria-hidden="true"></i> Перейти на страницу магазина</span></div></a></div>
        </div>
	 <? }else{?>
	  <div></div>
	 <?}?>
  <div class="col-md-12 block-one">

     <div class="row">
        <h3>Описание: Объявление ID <?=$blog->id?></h3>  
	   	 <div class="text-one">
		 <span class="date_blog">Просмотров: <?=$blog->views?></span><br>
		 <span class="date_blog">Добавлено: <?=$blog->date_add?></span><br><br>
		 
		 

	
			<article>
				<?=htmlspecialchars_decode (Yii::$app->formatter->asNtext($blog->text))?>
			</article>



   <div class="col-md-12" style="padding-top: 20px; padding-bottom: 20px;">
     <div class="row">
       <script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
       <script src="https://yastatic.net/share2/share.js"></script>
       <div class="ya-share2" <? if(isset($blog->imageBlog[0]['image']) && $blog->imageBlog[0]['image']) {?><?='data-image="https://1tu.ru/uploads/images/board/mini/'.$blog->imageBlog[0]['image'].'"'?> <? }?> data-curtain data-limit="5" data-services="vkontakte,odnoklassniki,skype,telegram,moimir,messenger"></div>
     </div>
   </div>
  
		 
		 
	  <? if($blog->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>
	      <div class="online-one">Онлайн</div>
	  <?}else{?>
	      <div class="offline-one">Был(а) в сети <?=$blog->author->online?></div>
	  <? } ?>
	  <? if($blog->user_id == '1' && $blog->express == '1') {?>
		<a href="/update?id=<?=$blog->id?>" class="btn btn-success">Редактировать объявление</a>
		<?}else{?>
		 <? if (!Yii::$app->user->isGuest) {?>
		    <span class="mail-send">Написать автору <i class="fa fa-regular fa-envelopes" aria-hidden="true"></i></span>
		 <? }else{?>
		    <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Авторизируйтесь, чтобы написать автору."  class="mail-send-err">Написать автору <i class="fa fa-regular fa-envelopes" aria-hidden="true"></i></span>
		 <? }?>
		 <?}?>
		 </div>
     </div>
	 
	<?if(isset($blog->coord->coordlat)) {?>
	<div class="row maps-one"> 
	<details>
	<summary class="noselect" >Посмотреть на карте <i class="fa fa-map-location-dot" aria-hidden="true"></i></summary>
	<br>
	
	   	 <div class="iframe-div">
		    <iframe src="/ajax/maps?coord=<?=$blog->coord->coordlat?>,<?=$blog->coord->coordlon?>&address=<?=$blog->coord->text?>" style="width:100%; height: 100%;" frameborder="0"></iframe>
		 </div>
     </div>
	<? } ?>
	 
	<?if (isset($coord[0]['hide']) && $coord[0]['hide'] == 1) {if(Yii::$app->user->isGuest) {$coord_hide = true;}}?>
	 
	<? if (isset($coord[0]['value']) && !isset($coord_hide)) {?>
	  <div class="row maps-one"> 
	   	 <div class="iframe-div">
		    <iframe src="/ajax/maps?coord=<?=$coord[0]['value']?>&address=<?=$coord[0]['address']?>" style="width:100%; height: 100%;" frameborder="0"></iframe>
		 </div>
     </div>
	<? } ?>
 </details>
  </div>
</div>
<?echo $this->render('similar', [
		'blogssimilar' => $blogssimilar,
		'pages' => $pagessimilar,
		'rates' => $ratessimilar,
		'price' => $pricesimilar,
		'notepad' => $notepadsimilar
    ]);
?>
<?echo $this->render('comment', [
		'comment' => $comment,
		'comment_add' => $comment_add,
		'comment_save' => $comment_save,
		'pages' => $pages,
    ]);
?> 




