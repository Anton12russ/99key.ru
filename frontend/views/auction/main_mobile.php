<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
$this->registerCssFile('/css/main.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->title = $meta['title'];
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $meta['keywords']]);


//Ищем совпадение для блоков
$block_arr = Yii::$app->block->block(Yii::$app->controller->id, Yii::$app->controller->action->id, '', '', '');
$controller_id = Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;
 if($block_arr) {
		foreach($block_arr as $res) {
			$this->blocks[$res['position']][] = $res;
		}
	} 
?>

<?// $this->beginBlock('top'); ?>
<?//=Yii::$app->block->block(Yii::$app->controller->id, Yii::$app->controller->action->id)?>
<?// $this->endBlock();?>


    <?= $this->render('cat_main', [
	    'cat_main' => $cat_main,
		'block_right' => $this->blocks['right'],
    ]) ?>


<h1 style="font-size: 20px;"><?=$meta['h1'] ?></h1>
    <div class="row">	
<?php if ($blogs) {?>
     <?php foreach ($blogs as $one) {?>
	 
	    <!--Достаем условия для платных услуг-->
	    <? $turbo = false; $arr_services = Yii::$app->userFunctions->services($one->id); if (isset($arr_services['top']) && isset($arr_services['bright']) && isset($arr_services['block'])) {$turbo = true;} ?>
		 <!--Создаем ссылку на объявление-->
        <? $url = Url::to(['blog/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
    <div class="col-md-12">
	  <div class="cat-board-body <? if ($turbo || isset($arr_services['bright'])){?>marker<?}?>">
	    <div class="all_img <? if (isset($this->blocks['right'])){?>col-md-3  col-sm-4<? }else{ ?>col-md-2  col-sm-3<? } ?> col-xs-4" style="background-image: url(<? if (isset($one->imageBlog[0]['image'])) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlog[0]->image;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png<? } ?>);"> <a class="img_a" href="<?=$url?>"></a></div>
	    <div class="all_body  <? if ($this->blocks['right']){?>col-md-9 col-sm-8<? }else{ ?>col-md-10 col-sm-9<? } ?> col-xs-8">
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
		   	   
		   <div class="all-bo-notepad"   data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить в избранное" >
		     <a href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$one->id?>"><?if (isset($notepad[$one->id])) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><? } ?></a>
	      </div>
	   </div>
	   
	<div class="services-all hidden-xs">
	<? if ($turbo) {?><i data-toggle="tooltip" data-placement="top" title="Турбо продажа" class="fa main fa-rocket-launch" aria-hidden="true"></i><?}else{?>
		<? if (isset($arr_services['top'])) {?><i data-toggle="tooltip" data-placement="top" title="Удерживается в топе" class="fa main fa-circle-arrow-up" aria-hidden="true"></i><?}?>
		<? if (isset($arr_services['block'])) {?><i data-toggle="tooltip" data-placement="top" title="Правый блок" class="fa main fa-regular fa-fire" aria-hidden="true"></i><?}?>
		<? if (isset($arr_services['bright'])) {?><i data-toggle="tooltip" data-placement="top" title="Выделенное" class="fa main fa-star-half-stroke" aria-hidden="true"></i><?}?>
	<?}?>
	</div>
	
      </div>
	 </div>
     <?php } ?>
	 
<?php }else{ ?>	 
<div class="col-md-12"><div class="alert alert-warning">В этом регионе нет объявлений, продолжите поиск в соседнем регионе.</div></div>
<?php } ?>
</div>


