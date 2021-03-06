<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/user_blogs.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->title = 'Ваши аукционы';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = 'Ваши аукционы';
$services = Yii::$app->userFunctions->servicesBlog();
foreach($services as $res) {
	$arrserv[$res['type']] = $res;
}
foreach(Yii::$app->caches->rates() as $res) {
     if ($res['value'] == 1) {
	    $rates_serv = $res;
	 }
}
$shop = Yii::$app->userFunctions->shopMenu(Yii::$app->user->id);
		$this->registerJsFile('/js/user.js',['depends' => [\yii\web\JqueryAsset::className()]]);
		
?>

<style>

.cat-board-body {
    /* height: 200px; */
    display: table;
    width: 100%;
}
.all_img {

    min-height: 150px;
}
</style>

<div class="row">

   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">
     
	     <h1><i class="fa fa-shopping-bag" aria-hidden="true"></i> Ваши аукционы</h1>
<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                Фильтр 
              </a>
            </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse">
      <div class="panel-body">  
       <?= $this->render('cat_search', [
	    'fields' => $fields,
	
		'fields' => $fields,
		'rates' => $rates,
		'category' => $category
        ]) ?>
     </div>
    </div>
  </div>


</div>
   
<?php Pjax::begin([ 'id' => 'pjaxContent']); ?>


<?php if (!isset($model2)) {$model2 = '';}?>
<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true,], 'enableClientValidation' => false,]);?>
   <div class="mini-filter col-md-12">
   <div class="rilght-mini-filter">
   <a data-toggle="dropdown" href="#"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i>Статус аукционов: <span>
   <? if (Yii::$app->request->get('sort') == 'moder') {echo 'На модерации';}
   if (Yii::$app->request->get('sort') == 'active') {echo 'Ожидают активации';}
   if (Yii::$app->request->get('sort') == 'del') {echo 'Торги окончены';}
   if (Yii::$app->request->get('sort') == '') {echo 'Все опубликованные торги';}?>
   </span></a>
     <ul class="dropdown-menu sort-menu" role="menu" aria-labelledby="dLabel">
        <div class="treug"></div>
        <li><a href="<?=Url::to(['user/auction', 'sort'=> '']);?>">Все опубликованные торги </a></li>
        <li><a href="<?=Url::to(['user/auction', 'sort'=> 'moder']);?>">На модерации</a></li>
	    <li><a href="<?=Url::to(['user/auction', 'sort'=> 'active']);?>">Ожидают активации</a></li>
		<li><a href="<?=Url::to(['user/auction', 'sort'=> 'del']);?>">Торги окончены</a></li>
     </ul>
  </div>
   </div>


<?php if ($blogs) {?>

<?php if(isset($get['sort']) && $get['sort'] != 'active' || !isset($get['sort'])) {?>
      <div class="col-md-12 act">
        <span>выбрать все</span>  <button type="submit" class="btn btn-success add-preloader">
  <?php $get = Yii::$app->request->get();
	    	if (!isset($get['sort']) || $get['sort'] == '') {?>
	        	Снять с публикации
         <? }elseif (isset($get['sort']) || $get['sort'] == 'del'){ ?>
		        Опубликовать
		 <? } ?>
	    	</button>
<button type="submit" class="btn btn-danger add-preloader" name="del" onclick="reload_page(); " style="background: #ff0000;"  value="null">Удалить безвозвратно</button>

         </div>
<? } ?>	  
	  
<?php if (isset($act) && $act == true) {?>
    <div class="col-md-12 ">
	<br>
       <div class="alert-act alert alert-success">Объявления перемещены</div>
   </div>
 <? } ?>

	 
<?php $count = Yii::$app->caches->setting()['block_add'];?>
<?php foreach ($blogs as $one) {?>
<!--Достаем условия для платных услуг-->
<? $turbo = false;
 $arr_services = @Yii::$app->userFunctions->services($one->id); if (isset($arr_services['top']) && isset($arr_services['bright']) && isset($arr_services['block'])) {$turbo = true;}?>
<!--Создаем url объявления -->
<? $url = Url::to(['blog/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>

    <div class="col-md-12 active-ads">
	  <div class="cat-board-body" style="box-shadow: 0px 0px 0px rgb(0 0 0 / 0%);">
	  		<?php if ($one->status_id == 1 || $one->status_id == 2) {?>
			   <div class="chex col-md-12">
			   <div class="col-md-1 col-xs-3 chex-input"><label class="control-label"><input type="checkbox" name="check[]" value="<?=$one->id?>"></label></div>
			   <div  class="col-md-4 col-xs-9 update"><a data-pjax="0" target="_blank" href="<?=Url::to(['blog/update', 'id' => $one->id])?>">Редактировать</a></div>
	           <div  class="col-md-4  col-xs-12 update" style="background: #54b1c2;"><a data-pjax=0 target="_blank" href="/ajax/copy?id=<?=$one->id?>">Копировать</a></div>
			   </div>
			   
			<? } ?>
	  
	    <div class="all_img <? if (isset($this->blocks['right'])){?>col-md-3  col-sm-4<? }else{ ?>col-md-3  col-sm-3<? } ?> col-xs-4" style="background-image: url(<? if (isset($one->imageBlog[0]['image'])) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlog[0]->image;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png<? } ?>);">
	
		</div>
	    <div class="all_body  <? if ($this->blocks['right']){?>col-md-9 col-sm-8<? }else{ ?>col-md-9 col-sm-9<? } ?> col-xs-8">

		<h3>  <a class="cat-bo-href" data-pjax=0 target="_blank" href="<?=$url?>"> <? if($one->auction != 0) {?>
		    <i style="color: green; font-size: 18px;" class="fa fa-gavel" aria-hidden="true"></i>
	       <?}?> <?=Yii::$app->userFunctions->substr_user($one->title, 100)?></a></h3>
	        <div class="all_price">
	               <? $price_arr = array();
				   foreach($one->blogField as $res) {
					if ($res['field'] == $price) { 
				      $price_val = $res['value'];
					  $rates_val = $res['dop'];
				    }
				   }
	                if (isset($price_val)) { 
					$priceajax = $price_val/$rates[$rates_val]['value'];
					echo number_format($price_val/$rates[$rates_val]['value'], 0, '.', ' ');
					}?>  
					<!--<div class="col-md-3 row"><input style="min-width: auto; width: 100%;" class="ajax-price" data-id="<?=$one->id?>" value="<?=$priceajax?>"/></div>-->
					<? if (isset($price_val)) { ?>  
					 <i class="fa <?=$rates[$rates_val]['text']?>" aria-hidden="true"></i>  
                    <? }?> 					<span style="margin-left: 10px; font-size: 11px;">id: <?=$one->id?><span>
		    <i  data-toggle="tooltip" data-placement="top" title="" data-original-title="Начало торгов: <?=$one->date_add?><br> Окончание торгов:  <?=$one->date_del?>" data-html = "true" style="font-size: 20px; color: #00b050; margin-left: 10px;" class="fa-solid fa-circle-info"></i>
	
	<? if($one->auctions->bets) {?>
	<br>
	<button type="button" class="btn btn-warning btn-history-auction btn-sm" data-toggle="modal" data-target="#auctionModalstavka" data-id="<?=$one->id?>">
        Ставки
    </button>
	<? } ?>   
		  </div>   	   
<? if(isset($shop)) {?> 	   
           <div class="colvo" <? if($one->count == '' && $one->count != '0') {echo 'style="background:  #B5B8B1;"';}?>  <? if($one->count < 5 && $one->count != '0' ) {echo 'style="background:  #f9752a;"';}?>  <? if($one->count >=5) {echo 'style="background: #5cb85c;"';}?> <? if($one->count == '0') {echo 'style="background: #ff0000;"';}?>>остаток <span class="span-colvo con-<?=$one->id?>"> (<?=$one->count?><? if($one->count == '' && $one->count != '0') {echo ' не указан ';}?>) </span><span class="input-colvo"><input style="min-width: 80px; max-width: 50px;" type="number" data-max="25" data-price="7" data-id="<?=$one->id?>" value="<?=$one->count?>" class="form-control count-input" name="Blog[count]" placeholder="99"></span>	 шт.
           </div>
<? } ?> 	  	  
		 
		  

	  <?if($one['auction'] == 2){?>
	  <div class="col-md-12 row" style="margin-top: 15px; margin-bottom: 5px;">
	     <span class="reserv-span">Зарезервировано</span>
	  </div>
	  <div class="col-md-12 row" style="margin-top: 10px; margin-bottom: 10px;">

	    <button type="button" class="btn btn-danger reserv-del" style="padding: 5px; font-size: 12px;" data-id="<?=$one->id?>">Снять с резервации</button>
	  </div>
	 <?}?>
	 
	 	 
           <div class="all-bo-cat hidden-xs"><?= $one->regions['name']?> <br>
		   <?=Yii::$app->userFunctions->recursFrontCat($one->category)?>
		   
		   </div>		  
	   
	   
	
		   	   
		   <div class="all-bo-notepad" data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить в избранное" >
		     <a href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$one->id?>"><?if (isset($notepad[$one->id])) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><? } ?></a>
	      </div>
	   </div>
	   
	<div class="services-all hidden-xs">
	<? if ($turbo) {?><i data-toggle="tooltip" data-placement="top" title="" class="fa main fa-rocket-launch" aria-hidden="true" data-original-title="Турбо продажа"></i><?}else{?>
		<? if (isset($arr_services['top'])) {?><i data-toggle="tooltip" data-placement="top" title="" class="fa main fa-circle-arrow-up" aria-hidden="true" data-original-title="Удерживается в топе"></i><?}?>
		<? if (isset($arr_services['bright'])) {?><i data-toggle="tooltip" data-placement="top" title="" class="fa main fa-star-half-stroke" aria-hidden="true" data-original-title="Выделенное"></i><?}?>
		<? if (isset($arr_services['block'])) {?><i data-toggle="tooltip" data-placement="top" title="" class="fa main fa-regular fa-fire" aria-hidden="true" data-original-title="Правый блок"></i><?}?>
	<?}?>
	</div>
	
    </div>
	 <? $day_serv =  round((strtotime ($one->date_del)-strtotime (date('Y-m-d H:i:s')))/(60*60*24)); ?>
	 <? if (Yii::$app->request->get('sort') == '') {?> 
	 <? if ($day_serv > 0 && !$turbo) {?>
	 
	

	    <!--Предложение платных услуг-->
	    <details>
	        <summary>Платные услуги для продвижения <i class="fa-regular fa-rocket-launch fa-spin" style="--fa-animation-duration: 15s; --fa-animation-timing: ease-in-out;""></i></summary>
	    <div class="col-md-12 services">
		<div class="row">
		
		   <? if (!isset($arr_services['top'])) {?>
		   <div class="col-md-4" boolean="true" data-toggle="tooltip" data-placement="top" title=" <?=$arrserv['top']['text']?>"> 

		     <div class="top">
                Удерживать в топе <i  class="fa main fa-circle-arrow-up" aria-hidden="true"></i><br>
				 <? $price_serv = $arrserv['top']['price'];?> 
		         Стоимость: <?=$price_serv?> <i class="fa <?=$rates_serv['text']?>" aria-hidden="true"></i>  в день<br>
				 
				  Итого дней
				 <div class="form-group" style=" margin-top: 10px;">
                    <input style="min-width: 100%;"  type="number" data-max="<?=$day_serv?>" data-price="<?=$price_serv?>" value="<?=$day_serv?>" id="blog-count" class="form-control day" name="Blog[count]" placeholder="99" style="max-width: 100px; float: left;">
                    <label class="control-label" for="blog-count">Итого <span class="itog"><?$price_day = $price_serv*$day_serv; echo $price_day;?></span> руб.</label>
				 </div>
				 <a data-pjax=0 data-hrefs="<?=Url::to(['payment/personal', 'blog_id' => $one->id, 'services' => 'top'])?>" href="<?=Url::to(['payment/personal', 'blog_id' => $one->id, 'services' => 'top'])?>&day=<?=$day_serv?>">Оплатить</a>
			 </div>
			</div> 
		   <?}?>
		   <? if (!isset($arr_services['bright'])) {?>
		    <div class="col-md-4" boolean="true" data-toggle="tooltip" data-placement="top" title=" <?=$arrserv['bright']['text']?>">
		       <div class="bright">
			   Выделить цветом <i class="fa main fa-star-half-stroke" aria-hidden="true"></i><br>
                <? $price_serv = $arrserv['bright']['price'];?> 
		         Стоимость: <?=$price_serv?> <i class="fa <?=$rates_serv['text']?>" aria-hidden="true"></i>  в день<br>
				 
				  Итого дней
				 <div class="form-group" style=" margin-top: 10px;">
                    <input style="min-width: 100%;"  type="number" data-max="<?=$day_serv?>" data-price="<?=$price_serv?>" value="<?=$day_serv?>" id="blog-count" class="form-control day" name="Blog[count]" placeholder="99" style="max-width: 100px; float: left;">
                    <label class="control-label" for="blog-count">Итого <span class="itog"><?$price_day = $price_serv*$day_serv; echo $price_day;?></span> руб.</label>
				 </div>
				  <a data-pjax=0 data-hrefs="<?=Url::to(['payment/personal', 'blog_id' => $one->id, 'services' => 'bright'])?>" href="<?=Url::to(['payment/personal', 'blog_id' => $one->id, 'services' => 'bright'])?>&day=<?=$day_serv?>">Оплатить</a>
			  </div>	
            </div>	
          <?}?>			
		  <? if (!isset($arr_services['block'])) {?>
		       <div class="col-md-4" boolean="true" data-toggle="tooltip" data-placement="top" title=" <?=$arrserv['block']['text']?>">
		        <div class="block">
		           Вывод в правый блок <i class="fa main fa-regular fa-fire" aria-hidden="true"></i><br>
		        <? $price_serv = $arrserv['block']['price'];?> 
		         Стоимость: <?=$price_serv?> <i class="fa <?=$rates_serv['text']?>" aria-hidden="true"></i>  в день<br>
				 Итого дней
				 <div class="form-group" style=" margin-top: 10px;">
                    <input style="min-width: 100%;"  type="number" data-max="<?=$day_serv?>" data-price="<?=$price_serv?>" value="<?=$day_serv?>" id="blog-count" class="form-control day" name="Blog[count]" placeholder="99" style="max-width: 100px; float: left;">
                    <label class="control-label" for="blog-count">Итого <span class="itog"><?$price_day = $price_serv*$day_serv; echo $price_day;?></span> руб.</label>
				 </div>
				<a data-pjax=0 data-hrefs="<?=Url::to(['payment/personal', 'blog_id' => $one->id, 'services' => 'block'])?>" href="<?=Url::to(['payment/personal', 'blog_id' => $one->id, 'services' => 'block'])?>&day=<?=$day_serv?>">Оплатить</a>
			   </div>
		     </div>
		  <?}?>
	    </div>
	 </div>
	 </details>
	 
	  <? } ?>
	
	
<?php } ?>	










<!--Если активация объявлений-->
<? if (Yii::$app->request->get('sort') == 'active') {?> 
<?  $price_serv = $activate[$one->id]*$day_serv ;?> 

    <div class="col-md-12 services activ">
          <div class="col-md-4" boolean="true" data-toggle="tooltip" data-placement="top" title="Объявление в указанной рубрике публикуется на платной соснове.">
		        <div class="bright">
		          <a style="border:0;" class="blank" data-href="<?=Url::to(['payment/personal', 'active' => $one->id])?>" href="#"> Активировать <br>
		            Стоимость: <?=$price_serv?> <i class="fa <?=$rates_serv['text']?>" aria-hidden="true"></i> на (<?=$day_serv ?>) дней</a>
			   </div>
		     </div>
    </div>

<? } ?> 

















</div>  
<?php } ?>
<? }else{ ?>

<div class="col-md-12">
<br>

<? if (Yii::$app->request->get('sort') == 'moder') {echo '<div class="alert alert-warning">У Вас нет объявлений на модерации.</div>';}
   if (Yii::$app->request->get('sort') == 'active') {echo '<div class="alert alert-warning">У Вас нет объявлений, которые ожидают активации.</div>';}
   if (Yii::$app->request->get('sort') == 'del') {echo '<div class="alert alert-warning">У Вас нет снятых с публикации объявлений.</div>';}
   if (Yii::$app->request->get('sort') == '') {echo '<div class="alert alert-warning">У Вас нет опубликованных объявлений.</div>';}?>
</div>
<?php } ?>	

<?php ActiveForm::end(); ?>	


<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>
<?php Pjax::end(); ?>
   </div>
</div>

</div>




	   <!-- Modal -->
<div class="modal fade" id="auctionModalstavka" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body modal-body2 row">	

	  </div>
	      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>

      </div>
	</div>
  </div>
</div>