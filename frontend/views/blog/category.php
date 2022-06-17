<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
$this->registerJsFile('/js/end.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$filtr = Yii::$app->request->cookies['filtr'];
?>

<div class="row">
   <div class="col-md-12 cat_category">

          <?php if ($cat_menu) { ?>

                 <ul class="ul_li">
	                  <!-- Цикл с ссылками -->
                     <?php foreach($cat_menu as $cat) {?>
                           <li>
						   <a href="<?= $cat['url']?>">
						      <span><?= $cat['name']?></span> 
							  <span class="counter_parent"><?=$cat['count']?></span>
						   </a> 
						   </li>
                     <?php } ?>
                 </ul>
	
		     <? if($pages_cat->totalCount > $pages_cat->pageSize){?>
			<!--Ajax отправка-->
		       <div class="col-md-12 end"></div>
			   <div class="end-click" data-href="<?=Url::to(['ajax/catandboard', 'category' => Yii::$app->request->get('category'), 'patch' => ltrim(Url::to(), '/')]);?>&page=2?>">Показать еще рубрики</div>
            <? } ?>
            <?php } ?>

      <?php if (!isset($model2)) {$model2 = '';}?>
      <? if(Yii::$app->controller->action->id == 'express') {?>
       <?= $this->render('cat_search_express', [
	    'fields' => $fields,
		'model2' => $model2,
		'fields' => $fields,
		'rates' => $rates,
		'category' => $category
        ]) ?>	
      <? }else{ ?>
       <?= $this->render('cat_search', [
	   'fields' => $fields,
		'model2' => $model2,
		'fields' => $fields,
		'rates' => $rates,
		'category' => $category
        ]) ?>	
      <? } ?>

 <?if ($category_text){?><div class="col-md-12 cat_text"><?= $category_text?></div><? }?>	
   <div class="mini-filter col-md-12">
   <div class="left-mini-filter">
<?= Html::beginForm([Url::to([$_SERVER['REQUEST_URI']])], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
    <?= Html::submitButton('<i class="fa fa-list" aria-hidden="true"></i>', ['class' => 'style-grid', 'name' => 'style', 'value' => 'list']) ?>
	<?= Html::submitButton('<i class="fa fa-th" aria-hidden="true"></i>', ['class' => 'style-grid', 'name' => 'style', 'value' => 'grid']) ?>
<?= Html::endForm() ?>
   </div>
   <div class="rilght-mini-filter">
   <a data-toggle="dropdown" href="#"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Сортировка: <span>
   <? if (Yii::$app->request->get('sort') == 'DESC') {echo 'Дорогие > дешевые';}if (Yii::$app->request->get('sort') == 'ASC') {echo 'Дешевые > дорогие';}if (Yii::$app->request->get('sort') == '') {echo 'Новые';}?>
   </span></a>
     <ul class="dropdown-menu sort-menu" role="menu" aria-labelledby="dLabel">
        <div class="treug"></div>
        <li><a href="<?=Url::to(['blog/sort']);?>">Самые новые </a></li>
        <li><a href="<?=Url::to(['blog/sort']);?>sort=ASC">От дешевых к дорогим</a></li>
	    <li><a href="<?=Url::to(['blog/sort']);?>sort=DESC">От дорогих к дешевым</a></li>
     </ul>
  </div>
  
  

   </div>
   
    <div class="tab-box tab-box-new col-md-12" id="filtrmain" style="background: #FFF;"> 
     <ul class="nav nav-tabs add-tabs" role="tablist">
        <li <?if(!isset($filtr) || isset($filtr) && $filtr == '') {?>class="active"<?}?>>
           <a href="" data-act="" rel="nofollow">Все</a>
        </li>
        <li <?if(isset($filtr) && $filtr == 'shop') {?>class="active"<?}?>>
          <a href="" data-act="shop" rel="nofollow"><i class="fa fa-shop" aria-hidden="true"></i> От компаний</a>
        </li>
        <li <?if(isset($filtr) && $filtr == 'chas') {?>class="active"<?}?>>
          <a href="" data-act="chas" rel="nofollow"><i class="fa fa-user" aria-hidden="true"></i> Частные</a>
	    </li>
     </ul>
   </div>
   <div class="col-md-12 margin-h1-cat"></div>
   
   </div>
   
   
</div>