<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;

$this->title = $meta['title'];
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $meta['keywords']]);
$this->params['breadcrumbs'] = $meta['breadcrumbs'];
$this->registerJsFile('/js/shop_one.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/css/blog_users.css', ['depends' => ['frontend\assets\AppAsset']]);
?>


<div class="col-md-12 one-body">
   <h1><?=$this->title?></h1>
   <div class="data-site">
       На сайте с <?=date('d.m.Y h:i:s',$model->created_at)?>
   </div>
   <br>


<ul class="nav nav-tabs">
  <li class="active"><a href="#board" data-toggle="tab">Объявления</a></li>
  <li><a href="#article" data-toggle="tab"  class="article" data-href="<?=Url::to(['shop/article', 'id' => Yii::$app->request->get('id')])?>" data-toggle="tab">Статьи</a></li>
  <li><a href="#passanger" data-toggle="tab"  class="passanger" data-href="<?=Url::to(['passanger/users', 'id' => Yii::$app->request->get('id')])?>" data-toggle="tab">Попутчики</a></li>
</ul>


<div class="tab-content">
  <div class="tab-pane active" id="board">
<?
	echo $this->render('/shop/blog_all', [
		'rates' => $rates,
		'notepad' => $notepad,
		'price' => $price,
		'pages' => $pages,
		'blogs' => $blog,
		'user_id' => $model->id,
		//'fields' => $fields,

    ]);
?> 
   </div>

  <div class="tab-pane" id="article"></div>
  <div class="tab-pane" id="passanger"></div>
</div>
</div>



