<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
?>
    <? if (Yii::$app->controller->action->id == 'category') { 
	echo $this->render('category', [
	    'cat_menu' => $cat_menu,
		'h1' => $metaCat['h1'],
		'fields' => $fields,
		'rates' => $rates,
		'category_text' => $category_text,
    ]); 
	}?>
	

<br>
<?php Pjax::begin([ 'id' => 'pjaxBlog', 'enablePushState' => false]); ?>

<div class="shop_search">
<?= Html::beginForm(['shop/blogsearch','user_id'=>$user_id], 'get', ['data-pjax' => '', 'class' => 'form-inline']); ?>
    <?= Html::input('text', 'text', Yii::$app->request->get('text'), ['class' => 'form-control', 'placeholder' =>  'Введите искомую фразу']) ?>
    <?= Html::submitButton('Искать', ['class' => 'btn btn-success', 'name' => 'hash-button']) ?>
<?= Html::endForm() ?>
<hr>
</div>
<div class="row">	
<?php if ($blogs) {?>
<?php $count = Yii::$app->caches->setting()['block_add'];?>
<?php foreach ($blogs as $one) {?>
<!--Достаем условия для платных услуг-->
<? $turbo = false;
 $arr_services = @Yii::$app->userFunctions->services($one->id); if (isset($arr_services['top']) && isset($arr_services['bright']) && isset($arr_services['block'])) {$turbo = true;}?>
<!--Создаем url объявления -->
<? $url = Url::to(['blog/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
   
               <!--Считаем записи и вставляем рекламу через указанное в настройках значение-->
			  <?php if (isset($add_block)) {?>
              <?php $count++; if ($count >= Yii::$app->caches->setting()['block_add']) {?>
		                <?= $this->render('block.php', ['block' => $add_block, 'meg' => true] ) ?>
	          <?php	$count = 0;}?>
	          <?php }?>
			  
			  
    <div class="col-md-12">
	  <div class="cat-board-body <? if ($turbo || isset($arr_services['bright'])){?>marker<?}?>">
	    <a data-pjax=0 class="cat-bo-href" href="<?=$url?>">
		<div class="all_img <? if (isset($this->blocks['right'])){?>col-md-3  col-sm-4<? }else{ ?>col-md-2  col-sm-3<? } ?> col-xs-4" style="background-image: url(<? if (isset($one->imageBlog[0]['image'])) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlog[0]->image;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png<? } ?>);">
		<? if($one->express) {?><div class="express_one">Экспресс</div><?}?>
	</div></a>
	    <div class="all_body  <? if ($this->blocks['right']){?>col-md-9 col-sm-8<? }else{ ?>col-md-10 col-sm-9<? } ?> col-xs-8">
	   <h3><a data-pjax=0 class="cat-bo-href" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=Yii::$app->userFunctions->substr_user($one->title, 150)?>" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($one->title, 100)?></a> 		  
		  	<? if($one->auction != 0) {?>
		<i style="color: green; font-size: 13px;" class="fa fa-gavel" aria-hidden="true"></i> торги
	<?}?></h3>
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
	<? if ($turbo) {?><i data-toggle="tooltip" data-placement="top" title="" class="fa main fa-rocket-launch" aria-hidden="true" data-original-title="Турбо продажа"></i><?}else{?>
		<? if (isset($arr_services['top'])) {?><i data-toggle="tooltip" data-placement="top" title="" class="fa main fa-circle-arrow-up" aria-hidden="true" data-original-title="Удерживается в топе"></i><?}?>
		<? if (isset($arr_services['block'])) {?><i data-toggle="tooltip" data-placement="top" title="" class="fa main fa-regular fa-fire" aria-hidden="true" data-original-title="Правый блок"></i><?}?>
		<? if (isset($arr_services['bright'])) {?><i data-toggle="tooltip" data-placement="top" title="" class="fa main fa-star-half-stroke" aria-hidden="true" data-original-title="Выделенное"></i><?}?>
	<?}?>
	</div>
	
      </div>
	 </div>
<?php } ?>	 
<? }else{ ?>
<br>
<div class="col-md-12"><div class="alert alert-warning">По этому запросу объявлений не найдено.</div></div>
<?php } ?>	

</div>


<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>
<?php Pjax::end(); ?>