<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
$this->title = $meta['title'];
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $meta['keywords']]);
$this->params['breadcrumbs'] = $meta['breadcrumbs'];
$this->registerCssFile('/css/article_one.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/article.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/assest_all/slider/css/lightgallery.css');
$this->registerJsFile('/js/spoiler.js',['depends' => [\yii\web\JqueryAsset::className()]]);





$this->title = $art->title.' в Магазине "'.$shop->name.'"';
$this->registerMetaTag(['name' => 'description','content' => $this->title]);
$this->registerMetaTag(['name' => 'keywords','content' => $art->title. ', магазин, '.$shop->name]);
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
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => Url::to(['/article'])];
$this->params['breadcrumbs'][] = ['label' => $art->title ];
?>
<style type="text/css">
   .splCont {display:none; padding:5px 15px;  border:1px solid #ccc;}
</style>
<div class="one-body">
<h1><?=$art->title?></h1>
  

  <div class="row">
  <div class="col-md-12">
      <div style="bottom: 2px;" class="like_div"><div class="like"  data-href="<?=Url::to(['/like', 'id' => $art->id])?>" data-like="1" data-id="<?=$art->id?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Понравилась статья"><i id="like" <? if($like) {?>class="fa fa-heart"<?}else{?>class="fa fa-heart-crack"<?}?> aria-hidden="true"></i></div> <span class="manlike"><?=$art->rayting?></span></div>

  <div class="pubdate">- Опубликовано: <?=$art->date_add?></div>
  <?if($art->date_update){?>
    <div class="pubdate">- Обновлено: <?=$art->date_update?></div>

	  <?}?>
  <br>
   <div class="text-art"  id="aniimated-thumbnials">
        <?php $text_html = $art->text;?>
		<?php $text_html = str_replace('[spoiler="','<div><a class="splLink btn btn-success" href="">',$text_html);?>
		<?php $text_html = str_replace('"]','</a><div class="splCont" style="margin-top: 10px;">',$text_html);?>
		<?php $text_html = str_replace('[/spoiler]','</div></div>',$text_html);?>
        <?=$text_html?>
   </div>
   <hr>
   <script src="https://yastatic.net/share2/share.js"></script>
<div class="ya-share2" data-curtain data-shape="round" data-limit="5" data-services="vkontakte,odnoklassniki,telegram,viber,moimir,skype,messenger"></div>
 </div>
</div>
</div> 
<?
	echo $this->render('comment_art', [
		'comment' => $comment,
		'comment_add' => $comment_add,
		'comment_save' => $comment_save,
		'pages' => $pages,
    ]);
?> 


