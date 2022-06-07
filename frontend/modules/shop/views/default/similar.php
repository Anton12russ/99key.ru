<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
$this->registerCssFile('/css/main.css', ['depends' => ['frontend\assets\AppAsset']]);
?>


<?php if ($blogssimilar) {?>
<div class="row">
<br>
<div class="col-md-12"><h3>Похожие Товары</h3></div>
     <?php foreach ($blogssimilar as $one) {?>

	    <!--Достаем условия для платных услуг-->

  <? $url = Url::to(['/boardone', 'id'=>$one->id]);?>
	    <div class="<? if ($this->blocks['right']){?>col-lg-4<?}else{?>col-lg-3<?}?> main-board-one">
		<div class="main-bo-bod">
		<div class="main-bo-href body-href"  style="background-image: url(<? if ($one->imageBlogOne['image']) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlogOne['image'];?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo-main.png<? } ?>);">
		<a  data-toggle="tooltip" data-placement="top" title="Добавить в избранное" data-original-title="Добавить в избранное" href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$one->id?>"><?if (isset($notepad[$one->id])) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><? } ?></a>
	    <a class="img_a" target="_blank" href="<?=$url?>"></a>
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
			


			</div> 
			  <div class="main-bo-cat"><?=Yii::$app->userFunctions->recursFrontCat($one->category)?> | <?=$one->regions['name']?></div>
         </div>
	    </div>
     <?php } ?>
	</div>
<?php } ?>


