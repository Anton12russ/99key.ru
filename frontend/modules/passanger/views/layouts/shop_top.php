<?php
use yii\helpers\Url;
$this->registerCssFile('/css/shop_top.css', ['depends' => ['frontend\assets\AppAsset']]);
?>


<? if ($shops) {?>
<div class="col-md-12 shop-top">
<h3><i class="fa fa-shop" aria-hidden="true"></i> Популярные магазины</h3>
  <? foreach($shops as $one) {?>
<?$shop_url = PROTOCOL.$one->domen.'.'.DOMAIN;?>
  <div class="col-md-12">
  <?if(isset($one['rayting']) && $one['rayting'] > 0) {$rat = 'green';}else{$rat = '';}if(isset($one['rayting']) && $one['rayting'] < 0) {$rat = 'red';}if(!isset($one['rayting'])) {$rat = 'gray';}?>
     <h5 class="h5-shop-top"><?=$one['name']?>
	    <div class="votes"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Рейтинг магазина">
		       <i class="fa fa-star-half-stroke <?=$rat?>" aria-hidden="true"></i> <i class="fa fa-star-half-stroke <?=$rat?>" aria-hidden="true"></i> <i class="fa fa-star-half-stroke <?=$rat?>" aria-hidden="true"></i>
		   </div>	
	 </h5>
     <div class="shop-top-img" style="background-image: url(<? if ($one['img']) {?> <?= Yii::getAlias('@shop_logo').'/'.$one['img'];?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png<? } ?>);"></div>
     <a target="_blank" class="href-shop-top" href="<?=$shop_url?>"></a>
  </div>
	<div class="col-md-12">
	    <hr>
    </div>
  <? } ?>
</div>  
<?php } ?>