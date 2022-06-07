<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
$this->title = $meta['title'];
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $meta['keywords']]);
$this->params['h1'] = $meta['h1'];
$this->params['breadcrumbs'] = $meta['breadcrumbs'];
$this->registerCssFile('/css/article_one.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/article.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/assest_all/slider/css/lightgallery.css');
$this->registerJsFile('/js/spoiler.js',['depends' => [\yii\web\JqueryAsset::className()]]);

?>

<style type="text/css">
   .splCont {display:none; padding:5px 15px;  border:1px solid #ccc;}
</style>

<div class="col-md-12 one-body">
  <div class="like_div"><div class="like"  data-href="<?=Url::to(['article/like', 'id' => $art->id])?>" data-like="1" data-id="<?=$art->id?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Мне нравиться"><i id="like" <? if($like) {?>class="fa fa-heart"<?}else{?>class="fa fa-heart-crack"<?}?> aria-hidden="true"></i></div> <span class="manlike"><?=$art->rayting?></span></div>
  	<?if ($art->user_id == Yii::$app->user->id || Yii::$app->user->can('updateArticle')) {?>
	 <div class="edit">
	     <a target="_blank" class="update" href="<?=Url::to(['article/update', 'id' => $art->id])?>">Редактировать</a>
		 <?if($art->status == 2) {?>
		     <a target="_blank" class="active_one" href="<?=Url::to(['article/active', 'id' => $art->id])?>">Активировать</a>
		 <? }else{ ?>
	         <a target="_blank" class="del" href="<?=Url::to(['article/del', 'id' => $art->id])?>">Снять с публикации</a>
		 <? } ?>
	</div> 
	
    <? } ?>
	 <? if ($art->status != 1) {?><div class="alert alert-danger">Статья не опубликована.</div><br><br><? }?>
  <div class="row">
  <div class="col-md-12">
  <div class="author">- Автор статьи: <?=$art->author['username']?></div>
  <div class="pubdate">- Опубликовано: <?=$art->date_add?></div>
  <?if($art->date_update){?>
    <div class="pubdate">- Обновлена: <?=$art->date_update?></div>
	<div class="pubdate">- Отредактировал: <?=$art->userupdate['username']?></div>
	<div class="pubdate">- Профиль пользователя: <a target="_blank" class="go-user" href="<?=Url::to(['blog/users', 'id'=>$art->user_id])?>"><?=$art->author['username']?></a></div>
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
	echo $this->render('comment', [
		'comment' => $comment,
		'comment_add' => $comment_add,
		'comment_save' => $comment_save,
		'pages' => $pages,
    ]);
?> 


