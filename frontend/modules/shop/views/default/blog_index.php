<?php
/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
if($mobile) {
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);	
}
?>

<div class="row">	
<?php if ($blogs) {?>
  <?php foreach ($blogs as $one) {?>
	
       <? $url = Url::to(['/boardone', 'id'=>$one->id]);?>
	    <? if($one->auction > 0) {?>
	       <? $url = Url::to(['product/auction', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
        <?php } ?>
	  <? if($mobile) {?>
	      <div class="col-md-12">
	  <div class="cat-board-body">
	    <div class="all_img <? if (isset($this->blocks['right'])){?>col-md-3  col-sm-4<? }else{ ?>col-md-3  col-sm-3<? } ?> col-xs-4" style="background-image: url(<? if (isset($one->imageBlog[0]['image'])) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlog[0]->image;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png<? } ?>);"> <a class="img_a" href="<?=$url?>"></a></div>
	    <div class="all_body  <? if ($this->blocks['right']){?>col-md-9 col-sm-8<? }else{ ?>col-md-9 col-sm-9<? } ?> col-xs-8">
	   <h3><a class="cat-bo-href" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=Yii::$app->userFunctions->substr_user($one->title, 150)?>" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($one->title, 50)?></a></h3>
	        <div class="all_price">
	               <? $price_arr = array();
				   foreach($one->blogField as $res) {
					if ($res['field'] == $price) { 
				      $price_val = $res['value'];
					  $rates_val = $res['dop'];
				    }
				   }
	                if (isset($price_val)) { 
					echo number_format($price_val/$rates[$rates_val]['value'], 0, '.', ' ');
					}?>  
					
					<? if (isset($price_val)) { ?>  
					<i class="fa <?=$rates[$rates_val]['text']?>" aria-hidden="true"></i> 
                    <? }?> 					
		   </div>	

           <div class="all-bo-cat hidden-xs"><?= $one->regions['name']?> <br>
		   <?=Yii::$app->userFunctions->recursFrontCat($one->category)?>
		   </div>		  
	   
	       <div class="all-bo-time hidden-xs">
		   <?=Yii::$app->formatter->asDatetime($one->date_add, "php:d.m.Y");?>
		   </div>
		   	   
		   <div class="all-bo-notepad"   data-toggle="tooltip" data-placement="top" title="Добавить в избранное" data-original-title="Добавить в избранное" >
		     <a href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$one->id?>"><?if (isset($notepad[$one->id])) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><? } ?></a>
	      </div>
	   </div>

      </div>
	 </div>
	  <? }else{ ?>
	 <div class=" col-md-3 main-board-one">
		<div class="main-bo-bod">
		<div class="main-bo-href body-href"  style="background-image: url(<? if ($one->imageBlogOne['image']) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlogOne['image'];?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo-main.png<? } ?>);">
		<a  data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить в избранное" href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$one->id?>"><?if (isset($notepad[$one->id])) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><? } ?></a>
	    <a class="img_a" href="<?=$url?>"></a>
	    </div>
    	<h3>
		<a class="cat-bo-href"  data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=Yii::$app->userFunctions->substr_user($one->title, 150)?>" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($one->title, 20)?></a></h3>
              <div class="main-bo-price">
		            <? 
				   $price_arr = array();
				   foreach($one->blogField as $res) {
					if ($res['field'] == $price) { 
				      $price_val = $res['value'];
					  $rates_val = $res['dop'];
				    }
				   }
	                if ($price_val) { 
					echo $price_val/$rates[$rates_val]['value'];
					}?>  
					<i class="fa <?=$rates[$rates_val]['text']?>" aria-hidden="true"></i>  
			
 <? if($one->auction > 0) {?>
  <?$day = Yii::$app->userFunctions2->auctionday(strtotime($one->date_del)); ?>
				    <i  data-toggle="tooltip" data-placement="top" title="" data-original-title="
					 До окончания торгов осталось (<?=$day?>)
					" style="font-size: 16px; color: #00b050; margin-left: 10px;" class="fa-solid fa-circle-info"></i>
					<i data-toggle="tooltip" data-placement="top" title="" data-original-title="Идёт аукцион" style="color: green;" class="fa fa-gavel" aria-hidden="true"></i> торги

 <?php } ?>

			</div> 
			  <div class="main-bo-cat"><?=Yii::$app->userFunctions->recursFrontCat($one->category)?> | <?=$one->regions['name']?></div>
         </div>
	    </div>
		<? } ?>
     <?php } ?>
<? }else{ ?>
<br>
<div class="col-md-12"><div class="alert alert-warning">По этому запросу объявлений не найдено.</div></div>
<?php } ?>	
</div>