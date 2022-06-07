<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/article.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/shop_all.css', ['depends' => ['frontend\assets\AppAsset']]);


?>
<?php Pjax::begin([ 'id' => 'pjaxArticle', 'enablePushState' => false]); ?>
<div class="row">	
<div class="col-md-12">
</br>
<div class="shop_search">
<?= Html::beginForm(['shop/article','id'=>$user_id], 'get', ['data-pjax' => '', 'class' => 'form-inline']); ?>
    <?= Html::input('text', 'text', Yii::$app->request->get('text'), ['class' => 'form-control', 'placeholder' =>  'Введите искомую фразу']) ?>
    <?= Html::submitButton('Искать', ['class' => 'btn btn-success', 'name' => 'hash-button']) ?>
<?= Html::endForm() ?>

<hr>
</div>
</div>

<?php if ($article) {?>

<?php foreach ($article as $one) {?>
<? $url = Url::to(['article/one', 'category'=>$one->cats['url'], 'id'=>$one->id, 'name'=>Yii::$app->userFunctions->transliteration($one->title)]);?>
<?php 

$text = Yii::$app->userFunctions->artText($one['text']);
?>
<div class="col-md-12">
  <div class="art-body">
    <div class="title"><h2><a data-pjax="0" target="_blank" href="<?=$url?>"><?=$one['title']?></a></h2>
       <div class="like_divs">
	      <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Кому понравилась статья" class="likes"><?if ($one['rayting'] > 0) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><?}?></span> <span class="man"><?=$one['rayting']?></span>
	   </div>
	</div>
    <?if (isset($text['img'])) {?><div class="art-img"> <?if($text['iframe']){?><?=$text['iframe']?><?}else{?><img src="<?=$text['img']?>"><?}?></div><?}?>
	<div class="text-art"><?=Yii::$app->userFunctions->substr_user($text['text'], 1000)?> </div><br><a data-pjax="0"  target="_blank" href="<?=$url?>">Читать далее...</a>
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


<?php Pjax::end(); ?>