<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/article.css', ['depends' => ['frontend\assets\AppAsset']]);



$this->title = 'Список статей магазина "'.$shop->name.'"';
$this->registerMetaTag(['name' => 'description','content' => 'Статьи и свежие публикации магазина "'.$shop->name.'"']);
$this->registerMetaTag(['name' => 'keywords','content' => 'статьи,'.$shop->name]);
$this->registerJsFile('/js/article.js',['depends' => [\yii\web\JqueryAsset::className()]]);
//передаем в шаблон
if(isset($shop->img)) {$this->params['logo'] = $shop->img;}else{$this->params['logo'] = '';}
$this->params['title'] = $shop->name;
$this->params['phone'] = $shop->field->phone;
$this->params['shop'] = $shop;
$this->params['arr'] =  array('Пн.','Вт.','Ср.','Чт.','Пт.','Сб.','Вс.');
$this->params['vote'] = $vote;
$this->params['vot'] = $shop->reating;
if ($count_art > 0) {$this->params['menu_art'] = true;}else{$this->params['menu_art'] = false;}
$this->params['breadcrumbs'] = array();

$this->params['breadcrumbs'][] = ['label' => 'Статьи' ];

?>

<div class="row">
<br>	
<?php if ($article) {?>
<?php $count = Yii::$app->caches->setting()['block_add'];?>
<?php foreach ($article as $one) {?>
<? $url = Url::to(['/articleone', 'id'=>$one->id]);?>
<?php 
	$text = $one['text'];
	$text = preg_replace('~\[spoiler=".*?\]~','[spoiler]',$text);
    $text = preg_replace('~\[spoiler].*?\[/spoiler]~',' [скрытый текст] ',$text);
    $text = Yii::$app->userFunctions->artText($text);
?>
<div class="col-md-12">
  <div class="art-body">
    <div class="title"><h2><a target="_blank" href="<?=$url?>"><?=$one['title']?></a></h2>
       <div class="like_divs">
	      <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Кому понравилась статья" class="likes"><?if ($one['rayting'] > 0) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><?}?></span> <span class="man"><?=$one['rayting']?></span>
	   </div>
	</div>
    <?if (isset($text['img'])) {?><div class="art-img"> <?if($text['iframe']){?><?=$text['iframe']?><?}else{?><img src="<?=$text['img']?>"><?}?></div><?}?>
	<div class="text-art">
 <?=Yii::$app->userFunctions->substr_user($text['text'] , 1000)?>
	</div><br><a class="more-text" href="<?=$url?>"><i class="fa fa-plus-square" aria-hidden="true"></i> Читать статью полностью...</a>
	<div class="date_add"><?=$one['date_add']?></div>
  </div> 
</div>
<?php } ?>	 
<? }else{ ?>	
<br>
<div class="col-md-12"><div class="alert alert-warning">По этому запросу статей не найдено.</div></div>
<?php } ?>	
</div>

<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>
