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

?>

<br>

<div class="row">
   <div class="col-md-12 cat_category">
   		  <?php if (!Yii::$app->request->get('text')) {?>
		      <h1 class="product-h1"><?=$metaCat['title']?></h1> 
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
 <?php } ?>		
	                  <!-- Цикл с ссылками -->
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


<br>

<div class="row">	
<?php if ($blogs) {?>
<?php foreach ($blogs as $one) {?>
	 
	
        <? $url = Url::to(['/boardone', 'id'=>$one->id]);?>
	 

		  <? if($one->auction > 0) {?>
	       <? $url = Url::to(['product/auction', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
        <?php } ?>
	 
	 
	  <div class=" col-md-3 main-board-one">
		<div class="main-bo-bod">
		<div class="main-bo-href body-href"  style="background-image: url(<? if ($one->imageBlogOne['image']) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlogOne['image'];?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo-main.png<? } ?>);">
		<a  data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить в избранное" href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$one->id?>"><?if (isset($notepad[$one->id])) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><? } ?></a>
	    <a class="img_a" href="<?=$url?>"></a>
	    </div>
    	<h3>
		<a class="cat-bo-href" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=Yii::$app->userFunctions->substr_user($one->title, 150)?>" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($one->title, 20)?></a></h3>
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
