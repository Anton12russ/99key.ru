<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
$mobile = check_mobile_devices();
$this->title = $meta['title'];
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $meta['keywords']]);

$this->params['breadcrumbs'] = $meta['breadcrumbs'];


$this->registerCssFile('/css/main.css', ['depends' => ['frontend\assets\AppAsset']]);

$arr = array('Пн.','Вт.','Ср.','Чт.','Пт.','Сб.','Вс.');

$vot = $shop->reating;
$this->registerCssFile('/css/shop_img.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/assest_all/slider/css/lightslider.css');
$this->registerCssFile('/assest_all/slider/css/lightgallery.css');
$this->registerJsFile('/assest_all/slider/js/lightslider.js',['depends' => [\yii\web\JqueryAsset::className()]]);


//передаем в шаблон
if(isset($shop->img)) {$this->params['logo'] = $shop->img;}else{$this->params['logo'] = '';}
$this->params['title'] = $shop->name;
$this->params['phone'] = $shop->field->phone;
$this->params['shop'] = $shop;
$this->params['arr'] = $arr;
$this->params['vote'] = $vote;
$this->params['vot'] = $vot;
if ($count_art > 0) {$this->params['menu_art'] = true;}else{$this->params['menu_art'] = false;}



// определение мобильного устройства
function check_mobile_devices() { 
    $mobile_agent_array = array('ipad', 'iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);    
    // var_dump($agent);exit;
    foreach ($mobile_agent_array as $value) {    
        if (strpos($agent, $value) !== false) return true;   
    }       
    return false; 
}
$regionpadeg = '';
if(isset($_COOKIE['region']) && $_COOKIE['region'] > 0) {
	$regionpadeg = ' в '.Yii::$app->caches->regionCase()[$_COOKIE['region']]['name'];
}
?>
<style>
.lSPager.lSGallery {
	display: none;
}
</style>


   <br>
    <?if (isset($shop->field->stocks)) {?><div style="background: #e51b23; color: #f6f6f6; border: #49080f;" class="alert alert-info"><strong>Внимание акция!!!</strong> <?=$shop->field->stocks?></div><?}?>
	  <?php if ($shop->imageShop) { ?>

	  	<ul id="imageGallery">
              <?php foreach ($shop->imageShop as $images) { ?>
	                   <li data-thumb="<?= '/uploads/images/shop/slider/'.$images['image']?>" data-src="<?= '/uploads/images/shop/slider/'.$images['image']?>"> 
							<?if($images['url']) {?>
							   <a target="_blank" href="<?=$images['url']?>"> <div style="background-image: url(<?= '/uploads/images/shop/slider/'.$images['image']?>);" class="false_window"></div></a>
                            <?}else{?>    
                               <div style="background-image: url(<?= '/uploads/images/shop/slider/'.$images['image']?>);" class="false_window"></div>
                            <?}?>							
						</li>						 
              <?php } ?>
		</ul>
		<br>
		</div>
       <?php } ?>	
     
	 
 <h3>Новинки <?=$regionpadeg?></h3>
	  <? 
	     echo $this->render('blog_index', [
	    'blogs' => $blog,
		'rates' => $rates,
		'price' => $price,
		'notepad' => $notepad,
		'user_id' => $shop->user_id,
		'mobile' => $mobile,
		
    ]); 
	?>
	<div class="product-and"><a href="<?=Url::to(['/product'])?>">Еще товар</a></div>

<div class="col-md-12">
<br>
  <div class="one-bodys">
      <div><h1>Магазин "<?=$shop->name?>"</h1>  <?=$shop->text?></div>
  </div>
</div>

<?php if ($mobile !== false) { ?>
<div class="hidden-sm visible-xs">
    <?=$this->render('/default/left_menu.php', compact('shop','arr', 'vote', 'vot'))?>
</div>
<?php } ?>


<?php 
$js = <<< JS
	$('#imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:true,
        thumbItem:5,
		   speed: 750, //ms'
           auto: true,
            pauseOnHover: true,
            loop: true,
            slideEndAnimation: true,
            pause: 5000,
        slideMargin:0,
        enableDrag: false,
        currentPagerPosition:'left',
        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#imageGallery .lslide'
            });
        }   
    });  
JS;
$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );
?>






