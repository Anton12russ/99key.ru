<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?php if($blogi) {?>
<ul>
  <?php foreach($blogi as $blog) {?>
	  <li><a href="/<?=Yii::$app->userFunctions2->recursiveUrl($blog['category'], $region);?>?text=<?=$blog['title']?>
	  "><span class="span-text"><?=$blog['title']?>
	  </span><span class="span-cat">← 
	    <?=Yii::$app->userFunctions2->searchname($blog['category']);?>
	  </span></a></li> 
  
  <?php } ?>
</ul>
<?php }else{?>
По запросу ничего не найдено, попробуйте сменить регион.
<?php }	?>

<?php exit();?>