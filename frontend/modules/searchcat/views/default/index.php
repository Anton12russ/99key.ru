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
    <i class="fa fa-long-arrow-right" aria-hidden="true"></i> <span class="catspan"><?=Yii::$app->userFunctions2->searchname($blog['category']);?></span>
	  </li> 
  <?php } ?>
</ul>
<div class="catinfo">Если вы не смогли подобрать нужную категорию, продолжите заполнение формы без выбора категории</div>
<?php }	?>
<?php exit();?>