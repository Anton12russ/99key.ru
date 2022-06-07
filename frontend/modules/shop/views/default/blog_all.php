<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->registerCssFile('/css/main.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
//передаем в шаблон
if(isset($shop->img)) {$this->params['logo'] = $shop->img;}else{$this->params['logo'] = '';}
$this->params['title'] = $shop->name;
$this->params['phone'] = $shop->field->phone;
$this->params['shop'] = $shop;
$this->params['arr'] =  array('Пн.','Вт.','Ср.','Чт.','Пт.','Сб.','Вс.');
$this->params['vote'] = $vote;
$this->params['vot'] = $shop->reating;
if ($count_art > 0) {$this->params['menu_art'] = true;}else{$this->params['menu_art'] = false;}
$this->params['breadcrumbs'] = array();
if(Yii::$app->controller->action->id == 'mynotepad') {
  $this->params['breadcrumbs'][] = ['label' => 'Избранное' ];
}


if(Yii::$app->controller->action->id == 'product' && Yii::$app->request->get('text')) {
	 $this->params['breadcrumbs'][] = ['label' => 'Поиск товара по значению "'.Yii::$app->request->get('text').'"'];
	 $this->title = 'Поиск товара';
}elseif(Yii::$app->controller->action->id == 'product') {
  $this->title = $metaCat['title'];
  $this->registerMetaTag(['name' => 'description','content' => $metaCat['description']]);
  $this->registerMetaTag(['name' => 'keywords','content' => $metaCat['keywords']]);
  $this->params['breadcrumbs'] = $metaCat['breadcrumbs'];
    
}

if(Yii::$app->controller->action->id == 'mynotepad') {
   $this->title = 'Избранное';

}



$block_arr = @Yii::$app->blockshop->block(Yii::$app->controller->id, Yii::$app->controller->action->id, $category, $cat_all, '', $shop->user_id);

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

$regionpadeg = '';
if(isset($_COOKIE['region']) && $_COOKIE['region'] > 0) {
	$regionpadeg = ' в '.Yii::$app->caches->regionCase()[$_COOKIE['region']]['name'];
}

?>



<br>


<div class="row">
   <div class="col-md-12 cat_category">
   		  <?php if (!Yii::$app->request->get('text')) {?>
		      <h1 class="product-h1"><?=$metaCat['title']?><?=$regionpadeg?></h1> 
		  <?php } ?> 
          <?php if (isset($cat_menu)  && $cat_menu) { ?>
                 <ul class="ul_li">
<?php if(count($this->params['breadcrumbs']) == 1) { ?>			 
<?php if(Yii::$app->userFunctions2->auctionshop($shop->user_id)) {	?>	 
	 <li>	 
		 <a href="/product/auction">
			<span>Аукцион</span> 
			<span class="counter_parent"><?=Yii::$app->userFunctions2->auctionshop($shop->user_id)?></span>
		</a> 
	 </li>	
 <?php } ?>	
 <?php } ?>		                  <!-- Цикл с ссылками -->
                     <?php foreach($cat_menu as $cat) {?>
                        <? if($cat['count'] > 0) {?>
							 <li>
						         <a href="<?= $cat['url']?>">
						           <span><?= $cat['name']?></span> 
							       <span class="counter_parent"><?=$cat['count']?></span>
						        </a> 
						   </li>
						 <?php } ?>     
                     <?php } ?>
                 </ul>
            <?php } ?>
			
	<?php if (Yii::$app->controller->action->id != 'mynotepad') { ?>
	<?php if (!isset($model2)) {$model2 = '';}?>
       <?= $this->render('cat_search', [
	    'fields' => $fields,
		'model2' => $model2,
		'fields' => $fields,
		'rates' => $rates,
        ]) ?>
	 <?php } ?>
<div class="mini-filter col-md-12">
  <div class="left-mini-filter">
<?= Html::beginForm([Url::to([$_SERVER['REQUEST_URI']])], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
  
	<?= Html::submitButton('<i class="fa fa-th" aria-hidden="true"></i>', ['class' => 'style-grid', 'name' => 'style', 'value' => 'grid']) ?>
	<?= Html::submitButton('<i class="fa fa-list" aria-hidden="true"></i>', ['class' => 'style-grid', 'name' => 'style', 'value' => 'list']) ?>
<?= Html::endForm() ?>
   </div>
   
   <?php if (Yii::$app->controller->action->id != 'mynotepad') { ?>
  <div class="rilght-mini-filter">
   <a data-toggle="dropdown" href="#"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Сортировка: <span>
   <? if (Yii::$app->request->get('sort') == 'DESC') {echo 'Дорогие > дешевые';}if (Yii::$app->request->get('sort') == 'ASC') {echo 'Дешевые > дорогие';}if (Yii::$app->request->get('sort') == '') {echo 'Новые';}?>
   </span></a>
     <ul class="dropdown-menu sort-menu" role="menu" aria-labelledby="dLabel">
        <div class="treug"></div>
        <li><a href="<?=Yii::$app->userFunctions2->urlshop($_SERVER['REQUEST_URI'])?>">Самые новые </a></li>
        <li><a href="<?=Yii::$app->userFunctions2->urlshop($_SERVER['REQUEST_URI'])?>sort=ASC">От дешевых к дорогим</a></li>
	    <li><a href="<?=Yii::$app->userFunctions2->urlshop($_SERVER['REQUEST_URI'])?>sort=DESC">От дорогих к дешевым</a></li>
     </ul>
  </div>
    <?php } ?> 
   </div>

   
   
   

   </div> 

</div>





	


<div class="row">	
<?php if ($blogs) {?>
<?php $count = Yii::$app->caches->setting()['block_add'];?>
<?php foreach ($blogs as $one) {?>
	 
	 
	          <!--Считаем записи и вставляем рекламу через указанное в настройках значение-->
			  <?php if (isset($add_block)) {?>
              <?php $count++; if ($count >= Yii::$app->caches->setting()['block_add']) {?>
			 
		                <?= $this->render('/layouts/block.php', ['block' => $add_block, 'meg' => true] ) ?>
	          <?php	$count = 0;}?>
	          <?php }?>
	
        <? $url = Url::to(['/boardone', 'id'=>$one->id]);?>
		  <? if($one->auction > 0) {?>
	       <? $url = Url::to(['product/auction', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
        <?php } ?>
	     <div class="col-md-12">
	  <div class="cat-board-body">
	    <div class="all_img <? if (isset($this->blocks['right'])){?>col-md-3  col-sm-4<? }else{ ?>col-md-3  col-sm-3<? } ?> col-xs-4" style="background-image: url(<? if (isset($one->imageBlog[0]['image'])) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlog[0]->image;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png<? } ?>);"> <a class="img_a" href="<?=$url?>"></a></div>
	    <div class="all_body <? if (isset($this->blocks['right'])){?>col-md-9 col-sm-8<? }else{ ?>col-md-9 col-sm-9<? } ?> col-xs-8">
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
					
					
<? if($one->auction > 0) {?>
  <?$day = Yii::$app->userFunctions2->auctionday(strtotime($one->date_del)); ?>
				    <i  data-toggle="tooltip" data-placement="top" title="" data-original-title="
					 До окончания торгов осталось (<?=$day?>)
					" style="font-size: 16px; color: #00b050; margin-left: 10px;" class="fa-solid fa-circle-info"></i>
					<i data-toggle="tooltip" data-placement="top" title="" data-original-title="Идёт аукцион" style="color: green;" class="fa fa-gavel" aria-hidden="true"></i> торги

 <?php } ?>
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
	
     <?php } ?>
<? }else{ ?>
<br>
<? if(Yii::$app->controller->action->id != 'mynotepad') {?>
       <div class="col-md-12"><div class="alert alert-warning">По этому запросу товара не найдено.</div></div>
   <? }else{ ?>
      <div class="col-md-12"><div class="alert alert-warning">Вы не добавляли товар в избранное.</div></div> 
<?php } ?>	
<?php } ?>	

</div>


<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>
