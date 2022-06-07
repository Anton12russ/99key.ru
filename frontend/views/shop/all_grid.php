<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/shop_all.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/main.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->title = $metaCat['title'];
$this->registerMetaTag(['name' => 'description','content' => $metaCat['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $metaCat['keywords']]);
$this->params['breadcrumbs'] = $metaCat['breadcrumbs'];

//Ищем совпадение для блоков, если выбрана позиция (между объявлениями), то передаем в переменную и рендерим файл с обработкой блоков
$block_arr = @Yii::$app->block->block(Yii::$app->controller->id, Yii::$app->controller->action->id, $category, $cat_all, $reg_all);
$controller_id = Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;
 if($block_arr) {
	 $add_block = array();
		foreach($block_arr as $res) {
			if($res['position'] == 'add') {
			   $add_block[] = $res;
			 }else{
				$this->blocks[$res['position']][] = $res;
		      }
			 }
		}
?>
    <? 
	echo $this->render('category', [
	    'cat_menu' => $cat_menu,
		'h1' => $metaCat['h1'],
		'pages_cat'=> $pages_cat
    ]); 
	?>
	



<div class="row">	
<?php if ($shops) {?>
<?php $count = Yii::$app->caches->setting()['block_add'];?>
<?php foreach ($shops as $one) {?>
<?
if(isset($one->rayting) && $one->rayting > 0) {$rat = 'green';}else{$rat = '';}if(isset($one->rayting) && $one->rayting < 0) {$rat = 'red';}if(!isset($one->rayting)) {$rat = 'gray';}
if($one->active == 0) {$nostatus = 'nostatus'; $actsuss = '<span class="noactive">Не активирован</span>';}else{$nostatus = ''; $actsuss = '';}
if($one->status == 0) {$nostatus = 'nostatus'; $statususs = '<span class="nostatuss">На модерации</span>';}else{$nostatus = ''; $statususs ='';}
$url = Url::to(['shop/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'id'=>$one->id, 'name'=>Yii::$app->userFunctions->transliteration($one->name)]);
?>
   
               <!--Считаем записи и вставляем рекламу через указанное в настройках значение-->
			  <?php if (isset($add_block)) {?>
              <?php $count++; if ($count >= Yii::$app->caches->setting()['block_add']) {?>
		                <?= $this->render('/blog/block.php', ['block' => $add_block, 'meg' => true] ) ?>
	          <?php	$count = 0;}?>
	          <?php }?>	  
			  
    <!--<div class="col-md-12">
	  <div class="cat-board-body <?=$nostatus?>">
	    <div class="shop-all-img <? if (isset($this->blocks['right'])){?>col-md-3  col-sm-4<? }else{ ?>col-md-2  col-sm-3<? } ?> col-xs-4" style="background-size: 100%; background-image: url(<? if ($one->img) {?> <?= Yii::getAlias('@shop_logo').'/'.$one->img;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png<? } ?>);"></div>
	    <div class="all_body  <? if (isset($this->blocks['right'])){?>col-md-9 col-sm-8<? }else{ ?>col-md-10 col-sm-9<? } ?> col-xs-8">
	    <h3><?=$statususs?><?=$actsuss?><a class="cat-bo-href" href="<?=$url?>"> <?=Yii::$app->userFunctions->substr_user($one->name, 100)?></a></h3>
	

           <div class="all-bo-cat hidden-xs"><?= $one->regions['name']?> <br>
		   <?=Yii::$app->userFunctions->recursFrontCat($one->category)?>
		   </div>	
		   <div class="shop-text hidden-xs">
		      <?=Yii::$app->userFunctions->substr_user($one->text, 230)?>
		   </div>
           <div class="votes"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Рейтинг магазина">
		       <i class="fa fa-star-half-stroke <?=$rat?>" aria-hidden="true"></i> <i class="fa fa-star-half-stroke <?=$rat?>" aria-hidden="true"></i> <i class="fa fa-star-half-stroke <?=$rat?>" aria-hidden="true"></i>
		   </div>		   
	   </div>
	   

	
      </div>
	 </div>-->
	 
	 
	 
	 
	  <div class="<? if ($this->blocks['right']){?>col-md-3 col-sm-6<?}else{?>col-md-2<?}?> main-board-one">
		<div class="main-bo-bod">
		<div class="main-bo-href body-href"  style="background-size: 100%; background-image: url(<? if ($one->img) {?> <?= Yii::getAlias('@shop_logo').'/'.$one->img;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo-main.png<? } ?>);">
		<a  data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить в избранное" href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$one->id?>"><?if (isset($notepad[$one->id])) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><? } ?></a>
	    <a class="img_a" href="<?=$url?>"></a>
	    </div>
    	<h3>
		<a class="cat-bo-href" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=Yii::$app->userFunctions->substr_user($one->name, 150)?>" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($one->name, 20)?></a></h3>
              <div class="main-bo-price">
		
		
		
		
		
	
		 
           <div class="votes"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Рейтинг магазина">
		       <i class="fa fa-star-half-stroke <?=$rat?>" aria-hidden="true"></i> <i class="fa fa-star-half-stroke <?=$rat?>" aria-hidden="true"></i> <i class="fa fa-star-half-stroke <?=$rat?>" aria-hidden="true"></i>
		   </div>		  
		
		
		
		
		
		
		
		
			</div> 
			  <div class="main-bo-cat"><?=Yii::$app->userFunctions->recursFrontCat($one->category)?></div>
         </div>
	    </div>
<?php } ?>	 
<? }else{ ?>	
<br>
<div class="col-md-12"><div class="alert alert-warning">В этой категории нет Магазинов.</div></div>
<?php } ?>	


</div>

<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>
