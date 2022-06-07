<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
$this->registerJsFile('/js/end.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>

         <?php if ($cat_menu) {?>
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

			<? $coun = $pages_cat->totalCount-$counter; 
			if($coun >= 15){?>
			<!--Ajax отправка-->
		     <div class="col-md-12 end"></div>
			 <div class="end-click" href="#" data-href="<?=Url::to(['ajax/catandboard', 'patch' => $patch, 'category' => Yii::$app->request->get('category'), 'page' => (int)Yii::$app->request->get('page')+1]);?>"><span>Показать еще рубрики</span></div>
            <? }else{ ?>
			<br>
			 <?php } ?>
            <?php } ?>

