<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?php if($blogi) {?>
	<div class="h2-cat">Укажите подходящую категорию</div>
<ul>
  <?php foreach($blogi as $blog) {?>
	  <li class="ul-click-cat" data-id="<?=$blog['category']?>" style="float: left; width: 100%;">
            <?=Yii::$app->userFunctions2->searchname($blog['category']);?>
	  </li> 
  <?php } ?>
</ul>
<?php }	?>
<?php exit();?>