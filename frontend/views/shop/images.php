<?php
use yii\helpers\Url;
//Подключаем слайдер
$this->registerCssFile('/css/shop_img.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/slider_svodka.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/assest_all/slider/css/lightslider.css');
$this->registerCssFile('/assest_all/slider/css/lightgallery.css');
$this->registerJsFile('/assest_all/slider/js/lightslider.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/slider/js/script.js',['depends' => [\yii\web\JqueryAsset::className()]]);	
$this->registerJsFile('/assest_all/slider/js/lightgallery.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/slider/js/lightgallery-all.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<br>
  <div class="col-md-12">
   <div class="row">
	<ul id="imageGallery" style="min-height: 300px; ">
	  <?php if ($images) { ?>
              <?php foreach ($images as $images) { ?>
						 <li data-thumb="<?= '/uploads/images/shop/slider/'.$images['image']?>" data-src="<?= '/uploads/images/shop/slider/'.$images['image']?>"> 
							<?if($images['url']) {?>
							   <a target="_blank" href="<?=$images['url']?>"> <div style="background-image: url(<?= '/uploads/images/shop/slider/'.$images['image']?>);" class="false_window"></div></a>
                            <?}else{?>    
                               <div style="background-image: url(<?= '/uploads/images/shop/slider/'.$images['image']?>);" class="false_window"></div>
                            <?}?>							
						</li> 
              <?php } ?>
       <?php } ?>	
     </ul>
	</div>
	</div>