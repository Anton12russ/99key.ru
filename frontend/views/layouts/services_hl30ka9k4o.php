<?php
use yii\helpers\Url;
?>

<? if (!isset($horisont)) {$horisont = '';}?>
<? if (!$horisont) {?>
<? if ($blog['blog']) {?>
<? $this->registerCssFile('/css/services.css', ['depends' => ['frontend\assets\AppAsset']]);?>
<? if (Yii::$app->caches->setting()['slider_block'] == 1) {?>
<div class="serv-h3"><img class="fastsale" src="..//img/fire.png" alt="ups" data-toggle="tooltip" data-placement="top" title="" aria-hidden="true" data-original-title="Срочная продажа"> Специальное предложение!</div>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
<div class="carousel-inner">
<?}?>

     <?php foreach ($blog['blog'] as $key => $one) {?>
	 <!--Создаем ссылку на объявление-->
     <? $url = Url::to(['blog/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
	 <div class="serv-block item <? if ($key == 0) {?> active<?}?>">
		<div class="serv-bo-href"   style="background-image: url(<? if ($one->imageBlogOne['image']) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlogOne->image;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo.png<? } ?>);   <? if (!$one->imageBlogOne['image']) {?> background-size: 80%;<? } ?> ">
	    <a class="img_a" href="<?=$url?>"></a>
	    </div>
    	<h3>
		<a class="cat-bo-href" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($one->title, 20)?></a></h3>
              <div class="serv-bo-price">
			     <div class="cena-fon">
		            <? 
				   $price_arr = array();
				   foreach($one->blogField as $res) {
					   if ($res['field'] == $blog['price']) {
					  $price_val = $res['value'];
					  $rates_val = $res['dop'];
						 
						}
					}
	              echo round($price_val / $blog['rates'][$rates_val]['value'])?>   <i class="fa <?=$blog['rates'][$rates_val]['text']?>" aria-hidden="true"></i>  
				 </div>
			</div> 
			  <div class="serv-bo-cat"><?=Yii::$app->userFunctions->recursFrontCat($one->category)?> | <?=$one->regions['name']?></div>
         </div>
	    
     <?php } ?>
<? if (Yii::$app->caches->setting()['slider_block'] == 1) {?>	 
	 </div>
<? if (count($blog['blog']) >1) {?>	
<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
<span class="glyphicon glyphicon-chevron-left"></span>
</a>
<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
<span class="glyphicon glyphicon-chevron-right"></span>
</a>
<?php } ?>
</div>
<?php } ?>
<?php } ?>




<?php }else{ ?>
<?php if ($blog) {?>
<? $this->registerCssFile('/css/services-horisont.css', ['depends' => ['frontend\assets\AppAsset']]);?>
     <?php foreach ($blog['blog'] as $one) {?>
	 
	    <!--Создаем ссылку на объявление-->
        <? $url = Url::to(['blog/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
	 
	 <div class="<? if ($this->blocks['right']){?>col-lg-2<?}else{?>col-lg-2<?}?> horisont-board-one">
		<div class="horisont-bo-bod">
		<div class="horisont-bo-href"  style="background-image: url(<? if ($one->imageBlogOne->image) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlogOne->image;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo-main.png<? } ?>);">
	    <a class="img_a" href="<?=$url?>"></a>
	    </div>
    	<h3>
		<a class="cat-bo-href" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($one->title, 20)?></a></h3>
              <div class="horisont-bo-price">
		                 <? 
				   $price_arr = array(); foreach($one->blogField as $res) {if ($res['field'] == $blog['price']) { $price_arr[$res['field']] = $res['value'];}}
	               echo round($price_arr[$blog['price']] / $blog['rates'][$price_arr[$blog['price'].'_rates']]['value'])?>   <i class="fa <?=$blog['rates'][$price_arr[$blog['price'].'_rates']]['text']?>" aria-hidden="true"></i>  
			 </div> 
			  <div class="horisont-bo-cat"><?=Yii::$app->userFunctions->recursFrontCat($one->category)?> | <?=$one->regions['name']?></div>
         </div>
	    </div>
     <?php } ?>
	 
<?php }else{ ?>	 
<div class="col-md-12"><div class="alert alert-warning">В этом регионе нет объявлений, продолжите поиск в соседнем регионе.</div></div>
<?php } ?>

<?php } ?>
<?php
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";
?>