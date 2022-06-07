<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
$this->title = $meta['title'];

$this->params['breadcrumbs'] = $meta['breadcrumbs'];

$this->registerCssFile('/css/one.css', ['depends' => ['frontend\assets\AppAsset']]);
//Подключаем слайдер
$this->registerCssFile('/assest_all/slider/css/lightslider.css');
$this->registerCssFile('/assest_all/slider/css/lightgallery.css');
$this->registerJsFile('/assest_all/slider/js/lightslider.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/slider/js/script.js',['depends' => [\yii\web\JqueryAsset::className()]]);	
$this->registerJsFile('/assest_all/slider/js/lightgallery.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/slider/js/lightgallery-all.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/cart.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/readmore.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/order.js',['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = $blog->title.' в Магазине "'.$shop->name.'"';
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $blog->title. ', магазин, '.$shop->name]);
$this->registerJsFile('/js/article.js',['depends' => [\yii\web\JqueryAsset::className()]]);
//передаем в шаблон
if(isset($shop->img)) {$this->params['logo'] = $shop->img;}else{$this->params['logo'] = '';}
$this->params['title'] = $shop->name;
$this->params['phone'] = $shop->field->phone;
$this->params['shop'] = $shop;
$this->params['arr'] =  array('Пн.','Вт.','Ср.','Чт.','Пт.','Сб.','Вс.');
$this->params['vote'] = $vote;
$this->params['vot'] = $shop->reating;
if ($count_art > 0) {$this->params['menu_art'] = true;}else{$this->params['menu_art'] = false;}


$this->params['breadcrumbs'] = $meta['breadcrumbs'];


foreach ($fields as $res) {
  if ($res['type'] == 'y') {if ($res['hide']) {if(!Yii::$app->user->isGuest) {$youtube = $res['value'];}else{$youtube = 'hide';}}else{$youtube = $res['value'];}}
  if ($res['type_string'] == 't') {if ($res['hide']) {if(!Yii::$app->user->isGuest) {$phone = $res['value'];}else{$phone = 'авторизируйтесь';}}else{$phone = $res['value'];}}
} 
sort($price); 
$count_car = Yii::$app->userFunctions3->coocCar($blog->id);


?>
<style>
 .lSSlideWrapper {
    height: 250px;
}
</style>
<div class="col-md-12 one-body">
 <? if ($blog->status_id == 2 && $blog->date_del < date('Y-m-d H:i:s')) {?><div class="alert alert-danger">Товар более не актуален и не доступен для покупки.</div><br><? }?>

  <div class="col-md-7">
  <div class="h1-price">
 <div class="all-bo-notepad"   data-toggle="tooltip" data-placement="top" title="Добавить в избранное" data-original-title="Добавить в избранное" >
 <a href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$blog->id?>"><?if (isset($notepad[$blog->id])) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><? } ?></a>
 </div>
   <h1><?=$meta['h1']?></h1>
   </div>
   <div class="row">
	<ul id="imageGallery" style="height: 250px;">
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
	</div>
  </div>

   <div class="col-md-5">
	   <div class="price-div">
		 <div data-toggle="tooltip" data-placement="top" title="Цена" class="one-price">
		<? if (isset($price[0]['value'])) {?>
		<? if (isset($price[0]['hide']) && $price[0]['hide'] > 0) {?>
	       <?=str_replace(' ','',$price[0]['value']);?> <i class="fa <?=$price[0]['rates']['text']?>" aria-hidden="true"></i>
		<?}else{?>	
		  <span  data-dickount="<?=$blog->discount?>"   data-price="<?=str_replace(' ','',$price[0]['value'])?>" class="count"><?=str_replace(' ','',$price[0]['value']);?></span> <i class="fa <?=$price[0]['rates']['text']?>" aria-hidden="true"></i><? foreach($fields as $res) { if ($res['type_string'] == 'q') {?> <span class="torg">торг</span><?}}?>
		<?}?>
		<?}else{?>
		     Безценно
		<?}?>
	    </div>
	 </div>
	 
<?php if ($blog->discount && $blog->discount_date >= date('Y-m-d H:i:s') || $blog->discount && !$blog->discount_date) { ?>
<div class="col-md-12" style="text-align: right;">
<div style="float: right; margin-bottom: 20px;">
  <div data-toggle="tooltip" data-placement="bottom" title="Активировать скидку" class="checkbox-other onoffswitch">
    <input  type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0">
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


	<div class="dop-one">
	
	<table class="table table-bordered table-one">
      <tbody>


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
	
	
	
	
	


	
	
<? if($blog->count !== NULL && $blog->count == 0) {?>
<? }else{?>
	<? if ($blog->status_id == 2 && $blog->date_del < date('Y-m-d H:i:s')) {?><? }else{?>
	<!--Валюта по умолчанию-->
    <?//$rate_def['value'];?>
	<br>
	<textarea placeholder="Примечание к заказу (Размер, цвет итд)" name="note" class="form-control note_add" rows="3"><?=Yii::$app->userFunctions->Carnote($blog->id)?></textarea>
    <?if($price[0]['rates']['def'] != 1) { 
	    $price_car = str_replace(' ','', $price[0]['value'])*$price[0]['rates']['value']; 
	}else{
		$price_car = $price[0]['value'];
		}?>
   	<div class="buttons-carts">
	   
	  <div class="button-korz">
	  <input class="form-control" data-count="<?=Yii::$app->userFunctions3->coocCar($blog->id)?>" type="number" value="<? if(Yii::$app->userFunctions3->coocCar($blog->id)) {?><?=Yii::$app->userFunctions3->coocCar($blog->id)?><? }else{?>1<? } ?>"/>
	  <button class="car-add btn btn-success" data-act="plus" data-price="<?=$price_car?>" data-current="<?=$price[0]['rates']['value']?>" data-id="<?=$blog->id?>">
	  <?if(Yii::$app->userFunctions3->coocCar($blog->id)) {?>
	    <i id="fa-car" class="fa fa-refresh" aria-hidden="true"></i>
	  <? }else{?>
	    <i id="fa-car"  class="fa fa-shop" aria-hidden="true"></i> 
	  <? }?>
	  <span <? if($count_car == 0) {?>style="display: none"<?}?>  class="count-product">
		  </span>
	  </button> 
	  </div>

	 <!-- <button class="car-add btn btn-success" data-act="plus" data-price="<?=$price_car?>" data-id="<?=$blog->id?>"><i class="fa fa-shop" aria-hidden="true"></i> Купить <span <? if($count_car == 0) {?>style="display: none"<?}?>  class="count-product">(<span class="car_count"><?=Yii::$app->userFunctions3->coocCar($blog->id)?></span> шт.)</span></button> -->
	  <button data-price="<?=$price_car?>" <? if($count_car == 0) {?>style="display: none"<?}?> class="car-minus car-minus btn btn-danger" data-act = "minus" data-id="<?=$blog->id?>"> Убрать из корзины</button>
	</div>
    <?if (isset($blog->count) && $blog->count >= 0) {$blogcount = $blog->count;}elseif($blog->count == NULL){$blogcount = 100000;}?><div data-counter="<?=$blogcount?>" class="sklad" <? if(!isset($blog->count)) {?>style="opacity: 0;"<?}?>>Остаток на складе (<span class="count-sklad"><?=$blogcount-(int)Yii::$app->userFunctions3->coocCar($blog->id)?></span> шт.)</div>
	<div class="hidden cart-alert alert alert-info"><a href="<?=Url::to(['/cart'])?>">Оформить покупку?</a></div>
	<div style="margin: 10px 0 0 0;" class="hidden cart-alert alert alert-info"><a href="<?=Url::to(['/product'])?>">Продолжить покупки</a></div>
	<? }?>
	<? }?>
	
	
	
	<br>
<div <? if($blog->count !== NULL && $blog->count == 0) {?>style="display: block"<? }?> class="alert alert-none alert-warning alertpre-order">
     Товара нет на складе, но вы можете сделать предзаказ, после поступления товара, вы будете оповещены о поступлении.<br><br>
     <button class="btn btn-success order_ok" data-board="<?=$blog->id?>" data-toggle="modal" data-target="#myModal">Предзаказ</button> 
</div>
	
	</div>
  </div>
  
  
  <div class="col-md-12 block-one">
     <div class="row">
        <h3>Описание: ID <?=$blog->id?></h3>  
	   	 <div class="text-one">
		    <article style="margin-bottom: 10px;">
				<?=htmlspecialchars_decode (Yii::$app->formatter->asNtext($blog->text))?>
			</article>
		 </div>
     </div>
     <div class="row" style="padding-top: 10px; padding-bottom: 20px;">
       <script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
       <script src="https://yastatic.net/share2/share.js"></script>
       <div class="ya-share2" <? if(isset($blog->imageBlog[0]['image']) && $blog->imageBlog[0]['image']) {?><?='data-image="https://1tu.ru/uploads/images/board/mini/'.$blog->imageBlog[0]['image'].'"'?> <? }?> data-curtain data-limit="5" data-services="vkontakte,odnoklassniki,skype,telegram,viber,moimir,messenger"></div>
     </div>
	 <?if(isset($blog->coord->coordlat)) {?>
	<div class="row"> 
	<details>
	<summary class="noselect" >Место сделки <i class="fa fa-map-location-dot" aria-hidden="true"></i></summary>
	<br>
	
	   	 <div class="iframe-div">
		    <iframe src="https://1tu.ru/ajax/maps?coord=<?=$blog->coord->coordlat?>,<?=$blog->coord->coordlon?>&address=<?=$blog->coord->text?>" style="width:100%; height: 100%;" frameborder="0"></iframe>
		 </div>
		 </details>
     </div>
	<? } ?>

  </div>
  
  
  
  
  
  
</div>
<div class="col-md-12 row">
<?echo $this->render('similar', [
		'blogssimilar' => $blogssimilar,
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
</div>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Предзаказ</h4>
      </div>
      <div class="modal-body">
        <div class="form_order"></div>
      </div>
    </div>
  </div>
</div>