<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?php if($blogi) {?>
<ul>
  <?php foreach($blogi as $blog) {?>
  <?if($blog['title']) {?>
	  <li><?=$blog['title']?></li> 
    <?php } ?>
  <?php } ?>
</ul>
<?php }else{?>
<?if($act == 'kuda') {?>
   До этого пункта нет попуток
<?php }else{	?>
   С этого пункта никто не отправляется
<?php }	?>
<?php }	?>

<?php exit();?>