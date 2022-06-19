<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/main.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/category.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title = $metaCat['title'];
$this->registerMetaTag(['name' => 'description','content' => $metaCat['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $metaCat['keywords']]);
$this->params['breadcrumbs'] = $metaCat['breadcrumbs'];
$this->params['h1'] = $metaCat['h1'];
//Ищем совпадение для блоков, если выбрана позиция (между объявлениями), то передаем в переменную и рендерим файл с обработкой блоков
$block_arr = @Yii::$app->block->block(Yii::$app->controller->id, Yii::$app->controller->action->id, $category, $cat_all, $reg_all);
$controller_id = Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;
 $this->registerJsFile('//api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU&apikey='.(string)Yii::$app->caches->setting()['api_key_yandex'],['depends' => [\yii\web\JqueryAsset::className()]]);

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
    <? if (Yii::$app->controller->action->id == 'category' || Yii::$app->controller->action->id == 'express') { 

	echo $this->render('category', [
	    'cat_menu' => $cat_menu,
		'fields' => $fields,
		'rates' => $rates,
		'category_text' => $category_text,
		'pages_cat' => $pages_cat,
		'category' => $category,
    ]); 
	}?>
	


<? if (Yii::$app->controller->action->id == 'search' && $search == '') {?>

<div class="row"><div class="col-md-12 no-search"><div class="alert alert-warning">Задан пустой поисковый запрос.</div></div></div>
<?}else{?>
<div class="row">	

<?php if ($blogs) {?>
<?php $count = Yii::$app->caches->setting()['block_add'];?>
 <?php foreach ($blogs as $one) {?>
	 
	    <!--Достаем условия для платных услуг-->
	    <? $turbo = false; $arr_services = Yii::$app->userFunctions->services($one->id); if (isset($arr_services['top']) && isset($arr_services['bright']) && isset($arr_services['block'])) {$turbo = true;} ?>
		 <!--Создаем ссылку на объявление-->
        <? $url = Url::to(['blog/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
	 
	 <div class="<? if ($this->blocks['right']){?>col-md-3 col-sm-6<?}else{?>col-md-2<?}?> main-board-one">
		<div class="main-bo-bod  <? if ($turbo || isset($arr_services['bright'])){?>marker<?}?>">
		<div class="main-bo-href body-href"  style="background-image: url(<? if ($one->imageBlogOne['image']) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlogOne['image'];?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo-main.png<? } ?>);">
		<a  data-toggle="tooltip" data-placement="top" title="Добавить в избранное" data-original-title="Добавить в избранное" href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$one->id?>"><?if (isset($notepad[$one->id])) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><? } ?></a>
	    <a class="img_a" href="<?=$url?>"></a>
		<? if($one->express) {?><div class="express_one">Экспресс</div><?}?>
	    </div>
    	<h3>
		<a data-toggle="tooltip" data-placement="top" title="<?=Yii::$app->userFunctions->substr_user($one->title, 150)?>" data-original-title="<?=Yii::$app->userFunctions->substr_user($one->title, 150)?>" class="cat-bo-href" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($one->title, 20)?></a></h3>
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
	<? if($one->auction != 0) {?>
		 <?$day = Yii::$app->userFunctions2->auctionday(strtotime($one->date_del)); ?>
				    <i  data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="
					 До окончания торгов осталось<br> (<?=$day?>)
					" style="font-size: 14px; color: #00b050; margin-left: 3px;" class="fa-solid fa-circle-info"></i>
		<i data-toggle="tooltip" data-placement="top" title="" data-original-title="Идёт аукцион" style="color: green;" class="fa fa-gavel" aria-hidden="true"></i> торги
	<?}?>
	<div class="services-main"> 
	<? if ($turbo) {?><i data-toggle="tooltip" data-placement="top" title="Турбо продажа" class="fa main fa-rocket-launch" aria-hidden="true" data-original-title="Турбо продажа"></i><?}else{?>
		<? if (isset($arr_services['top'])) {?><i data-toggle="tooltip" data-placement="top" title="Удерживается в топе" class="fa main fa-circle-arrow-up" aria-hidden="true" data-original-title="Удерживается в топе"></i><?}?>
		<? if (isset($arr_services['block'])) {?><i data-toggle="tooltip" data-placement="top" title="Правый блок" class="fa main fa-regular fa-fire" aria-hidden="true" data-original-title="Правый блок"></i><?}?>
		<? if (isset($arr_services['bright'])) {?><i data-toggle="tooltip" data-placement="top" title="Выделенное" class="fa main fa-star-half-stroke" aria-hidden="true" data-original-title="Выделенное"></i><?}?>
	<?}?> 
	</div>
			</div> 
			  <div class="main-bo-cat"><?=Yii::$app->userFunctions->recursFrontCat($one->category)?> | <?=$one->regions['name']?></div>
         </div>
	    </div>
		
     <?php } ?>	
	 
<? }else{ ?>	
<? if (Yii::$app->controller->action->id == 'notepad') {?>

<div class="col-md-12"><br><div class="alert alert-warning">Вы не добавляли объявления в "Избранное".</div></div>
<? }else{ ?>
<? if (Yii::$app->controller->action->id == 'search') {?>
<div class="col-md-12 no-search"><div class="alert alert-warning">По запросу "<?=$search?>" ничего не найдено</div></div>
<? }else{ ?>
<div class="col-md-12"><div class="alert alert-warning">В этой категории нет объявлений.</div></div>
<?php } ?>	
<?php } ?>	
<?php } ?>
</div>

<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>
<?php } ?>