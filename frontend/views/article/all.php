<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/article.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->title = $metaCat['title'];
$this->registerMetaTag(['name' => 'description','content' => $metaCat['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $metaCat['keywords']]);
$this->params['breadcrumbs'] = $metaCat['breadcrumbs'];
$this->registerJsFile('/js/article.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
    
	<? echo $this->render('category', [
	    'cat_menu' => $cat_menu,
		'h1' => $metaCat['h1'],
		'text' => $metaCat['text'],
    ]); ?>
<div class="row">	
<?php if ($article) {?>
<?php $count = Yii::$app->caches->setting()['block_add'];?>
<?php foreach ($article as $one) {?>
<? if($one->status == 0) {$nostatus = 'nostatus'; $statususs = '<span class="nostatuss">На модерации</span> ';}else{$nostatus = ''; $statususs ='';}?>
<? $url = Url::to(['article/one', 'category'=>$one->cats['url'], 'id'=>$one->id, 'name'=>Yii::$app->userFunctions->transliteration($one->title)]);?>
<?php 
	$text = $one['text'];
	$text = preg_replace('~\[spoiler=".*?\]~','[spoiler]',$text);
    $text = preg_replace('~\[spoiler].*?\[/spoiler]~',' [скрытый текст] ',$text);
    $text = Yii::$app->userFunctions->artText($text);
?>
<div class="col-md-12">
  <div class="art-body <?=$nostatus?>">
    <div class="title" style="max-width: 94%;"><h2><a href="<?=$url?>"><?=$one['title']?></a> <?=$statususs?></h2>
       <div class="like_divs">
	      <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Кол-во лайков" class="likes"><?if ($one['rayting'] > 0) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart" aria-hidden="true"></i><?}?></span> <span class="man"><?=$one['rayting']?></span>
	   </div>
	</div>
    <?if (isset($text['img'])) {?><div class="art-img"> <?if($text['iframe']){?><?=$text['iframe']?><?}else{?><img src="<?=$text['img']?>"><?}?></div><?}?>
	<div class="text-art">
	    <?=Yii::$app->userFunctions->substr_user($text['text'] , 1000)?>
	</div><br><a class="more-text" href="<?=$url?>"><i class="fa fa-plus-square" aria-hidden="true"></i> Читать статью полностью</a>
	<div class="date_add"><?=$one['date_add']?></div>
  </div> 
</div>
<?php } ?>	 
<? }else{ ?>	
<br>
<div class="col-md-12"><div class="alert alert-warning">В этой категории нет Статей.</div></div>
<?php } ?>	
</div>

<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>
