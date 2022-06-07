<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
?>


<div class="row">
   <div class="col-md-12 cat_category">
          <div class="col-md-12 cat-h1"><h1><?=$h1?></h1></div>
       <?php  if ($cat_menu) { ?>

                 <ul class="ul_li">
	                  <!-- Цикл со ссылками -->
                     <?php  foreach($cat_menu as $cat) {?>

                           <li>
						   <a href="<?= $cat['url']?>">
						      <span><?= $cat['name']?></span> 
							  <span class="counter_parent"><?=$cat['counter']?></span>
						   </a> 
						   </li>
                     <?php } ?>
                 </ul>
            <?php $br = false;}else{$br = '<br>';}?>
	
	
   <?if (isset($text)){?><div class="col-md-12 cat_text"><?=$br?><?= $text?></div><? }?>
   <div class="mini-filter col-md-12">
   <div class="rilght-mini-filter">
   <a data-toggle="dropdown" href="#"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Сортировка: <span>
   <? if (Yii::$app->request->get('sort') == 'DESC') {echo 'Сначала с высоким рейтингом';}if (Yii::$app->request->get('sort') == 'ASC') {echo 'Сначала с низким рейтингом';}if (Yii::$app->request->get('sort') == '') {echo 'Новые';}?>
   </span></a>
     <ul class="dropdown-menu sort-menu" role="menu" aria-labelledby="dLabel">
        <div class="treug"></div>
        <li><a href="<?=Url::to(['blog/sort']);?>">Сначала новые </a></li>
        <li><a href="<?=Url::to(['blog/sort']);?>sort=ASC">Низкий рейтинг</a></li>
	    <li><a href="<?=Url::to(['blog/sort']);?>sort=DESC">Высокий рейтинг</a></li>
     </ul>
  </div>
   </div>
   <div class="col-md-12 margin-h1-cat"></div>

   
   </div>
   
   
</div>