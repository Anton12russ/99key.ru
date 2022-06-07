<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);

$this->registerCssFile('/css/article.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/personal_article.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->title = 'Ваши статьи';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = 'Ваши статьи';
$services = Yii::$app->userFunctions->servicesBlog();
$this->registerJsFile('/js/article.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>


<div class="row">	

   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">
      <h1><i class="fa fa-book" aria-hidden="true"></i> <?=$this->title?></h1>
	  
   <div class="mini-filter col-md-12">
   <div class="rilght-mini-filter">
   <a data-toggle="dropdown" href="#"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Сортировка: <span>
   <? if (Yii::$app->request->get('sort') == 'DESC') {echo 'Сначала с высоким рейтингом';}if (Yii::$app->request->get('sort') == 'ASC') {echo 'Сначала с низким рейтингом';}if (Yii::$app->request->get('sort') == '') {echo 'Новые';}?>
   </span></a>
     <ul class="dropdown-menu sort-menu" role="menu" aria-labelledby="dLabel">
        <div class="treug"></div>
        <li><a href="<?=Url::to(['user/articles']);?>">Сначала новые </a></li>
        <li><a href="<?=Url::to(['user/articles', 'sort'=> 'ASC']);?>">Низкий рейтинг</a></li>
	    <li><a href="<?=Url::to(['user/articles', 'sort'=> 'DESC']);?>">Высокий рейтинг</a></li>
     </ul>
  </div>
   </div>	
</div>   
<?php if ($article) {?>
<br>
<div class="row">   
<?php $count = Yii::$app->caches->setting()['block_add'];?>
<?php foreach ($article as $one) {?>
<? $url = Url::to(['article/one', 'category'=>$one->cats['url'], 'id'=>$one->id, 'name'=>Yii::$app->userFunctions->transliteration($one->title)]);?>
<?php 

$text = Yii::$app->userFunctions->artText($one['text']);
?>

<div class="col-md-12">
  <div class="art-body">
    <div class="title"><h2><a target="_blank" href="<?=$url?>"><?=$one['title']?></a></h2>

       <div class="like_divs">
	      <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Кому понравилась статья" class="likes"><?if ($one['rayting'] > 0) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><?}?></span> <span class="man"><?=$one['rayting']?></span>
	   </div>
	</div>
	
	
		

	 <div class="status">
	  <?if($one['status'] == 1) {?>
	     <div class="alert alert-success">Статья опубликована</div>
	 <?}?>
	  <?if($one['status'] == 0) {?>
	     <div class="alert alert-warning">Статья на модерации</div>
	 <?}?>
	 <?if($one['status'] == 2) {?>
	     <div class="alert alert-danger">Статья удалена</div>
	 <?}?>
	</div>
	
    <?if (isset($text['img'])) {?><div class="art-img"> <?if($text['iframe']){?><?=$text['iframe']?><?}else{?><img src="<?=$text['img']?>"><?}?></div><?}?>
	<div class="text-art"><?=Yii::$app->userFunctions->substr_user($text['text'], 1000)?> </div><br><a target="_blank" href="<?=$url?>">Читать далее...</a>
	<div class="date_add"><?=$one['date_add']?></div>
	
	<div class="row">
	 <div class="edit">
	     <a target="_blank" class="update" href="<?=Url::to(['article/update', 'id' => $one->id])?>">Редактировать</a>
		 <?if($one->status == 2) {?>
		     <a target="_blank" class="del" href="<?=Url::to(['article/active', 'id' => $one->id])?>">Активировать</a>
		 <? }else{ ?>
	         <a target="_blank" class="del" href="<?=Url::to(['article/del', 'id' => $one->id])?>">Снять с публикации</a>
		 <? } ?>
	</div> 
	</div> 
  </div> 
</div>
<?php } ?>	 
<? }else{ ?>	
<br>
<div class="col-md-12"><div class="alert alert-warning">В этой категории нет Статей.</div>
<?php } ?>	
</div>
  <?= LinkPager::widget([
    'pagination' => $pages,
   ]); ?>
</div>
</div>