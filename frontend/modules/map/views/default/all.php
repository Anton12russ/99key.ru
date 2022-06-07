<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);

?>
     <div class="one-all"></div>
<?php Pjax::begin([ 'id' => 'pjaxContent', 'enablePushState' => false]); ?>
    <div class="col-md-12 padd-all">	
<?php if ($blogs) {?>
     <?php foreach ($blogs as $one) {?>
	    <div class="col-md-12 main-board-one" data-id="<?=$one->id?>">

	  <div data-toggle="tooltip" data-placement="top" title="<?=Yii::$app->userFunctions->substr_user($one->title, 150)?>" data-original-title="<?=Yii::$app->userFunctions->substr_user($one->title, 150)?>" class="cat-board-body">
	    <div class="all_img col-md-4  col-sm-4 col-xs-4" style="background-image: url(<? if (isset($one->imageBlog[0]['image'])) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlog[0]->image;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png<? } ?>);"></div>
	    <div class="all_body col-md-8 col-sm-8 col-xs-8">
	   <h3><span class="cat-bo-href"><?=Yii::$app->userFunctions->substr_user($one->title, 50)?></a></h3>
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
		  
	   
	       <div class="all-bo-time hidden-xs">
		   <?=Yii::$app->formatter->asDatetime($one->date_add, "php:d.m.Y");?>
		   </div>
	   </div>

      </div>
	 </div>
     <?php } ?>
	

<?= LinkPager::widget([
 'pagination' => $pages,
]); ?> 
<?php }else{ ?>	 
<div class="col-md-12"><div class="alert alert-warning">Нет отображаемых обектов</div></div>
<?php } ?>


<?php Pjax::end(); ?>
</div>





