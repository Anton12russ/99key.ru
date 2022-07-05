<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?php if($blogi) {?>
	<div class="h2-cat">Укажите подходящую категорию</div>
<ul>
  <?php foreach($blogi as $blog) {?>
    <? $plat = Yii::$app->userFunctions2->platnaya($blog['category'])?>
	  <li class="ul-click-cat" data-day="<?=$blog['day']?>"data-user="<?=$blog['user_id']?>" data-user="<?=$blog['user_id']?>" data-plat="<?=$plat?>" data-id="<?=$blog['category']?>" style="float: left; width: 100%;">
       <i class="fa fa-long-arrow-right" aria-hidden="true"></i> <span <?if($plat) {?>style="opacity: 0.5;"<?}?> class="catspan"><?=Yii::$app->userFunctions2->searchname($blog['category']);?></span> <?if($plat) {?> <i class="fa fa-rub" aria-hidden="true" style="color: red;"></i><?}?>
	  </li> 
  <?php } ?>
</ul>
  <?if(isset($_GET['modal']) && $_GET['modal'] = 'true'){?>
       <div style="color: #FFF; width:100%;" class="btn btn-sm btn-success category-click" data-toggle="modal" data-target="#categoryMenu">Выбрат категорию из расширенного списка</div>
     <? }else{	?>
       <div class="catinfo">Если вы не смогли подобрать нужную категорию, продолжите заполнение формы без выбора категории</div>
  <? }	?>
<?php }	?>
<?php exit();?>