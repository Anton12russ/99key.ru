<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
// Действия для заблокированныго пользователя
if ((Yii::$app->user->can('DANGER') && Yii::$app->controller->id == 'user') || 
(Yii::$app->user->can('DANGER') && Yii::$app->controller->id == 'blog' && Yii::$app->controller->action->id == 'add') ||
(Yii::$app->user->can('DANGER') && Yii::$app->controller->id == 'blog' && Yii::$app->controller->action->id == 'update') ||
(Yii::$app->user->can('DANGER') && Yii::$app->controller->id == 'shop' && Yii::$app->controller->action->id == 'add') ||
(Yii::$app->user->can('DANGER') && Yii::$app->controller->id == 'shop' && Yii::$app->controller->action->id == 'update')) { 
Yii::$app->response->redirect(['blog/index']);

 }
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/one.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/map_js/css/style.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/map_js/js/script.js',['depends' => [\yii\web\JqueryAsset::className()]]);
AppAsset::register($this);
$region = @Yii::$app->caches->region()[Yii::$app->request->cookies['region']];



if (Yii::$app->controller->id != 'article') { if(Yii::$app->request->get('category')) { $search_cat = Yii::$app->request->get('category');  }else{ $search_cat = 0; } $form_searh = "blog/search"; $url_catsearch = ''; $arr_search_cat = Yii::$app->userFunctions->cat_top($search_cat);}

$footer_online = explode("\n",Yii::$app->caches->setting()['online_footer']);
$notepad = Yii::$app->userFunctions->notepadArr();
$note  = count($notepad);
//Подключаем слайдер
$this->registerCssFile('/assest_all/slider/css/lightslider.css');
$this->registerCssFile('/assest_all/slider/css/lightgallery.css');
$this->registerJsFile('/assest_all/slider/js/lightslider.js',['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('/assest_all/slider/js/lightgallery.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/slider/js/lightgallery-all.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$shop = Yii::$app->userFunctions->shopMenu(Yii::$app->user->id);
if(isset($shop)) {
	$dispute = Yii::$app->userFunctions->disputeshop();
}else{
	$dispute = '';
}


$support_new = Yii::$app->userFunctions->support_new();
//if (!Yii::$app->user->id) {return Yii::$app->response->redirect(['user']); exit();}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
	<? //Правило для сео модуля
	if($meta = Yii::$app->functionSeo->meta(Url::to())) {?>
    <title><?=$meta['title']?></title>
    <? $this->metaTags[0] = '<meta name="description" content="'.$meta['description'].'">';?>
	<? $this->metaTags[1] = '<meta name="keywords" content="'.$meta['keywords'].'">';?>
	<? if($meta['h1']) {$this->params['h1'] = $meta['h1'];}?>
	<? }else{ ?>
	<title>Поиск на карте от 1TU.RU</title> 
    <? } ?>
    <?php $this->head()?>
</head>
<body>
<?php $this->beginBody(); ?>


<!--
<?php
    NavBar::begin();
    NavBar::end();
?>
-->
<!--<div style="background: #FFF;"><div style="height: 30px; background: url(/img/girlianda_uguide_ru_1.gif) repeat-x 100%;"></div></div>-->
<div class="container-top-body">
<div class="top-menu">
    <div class="container">
	    <div class="col-md-12 mobile-width">
	       
		      <div class="col-md-4 col-sm-4  hidden-xs">
			    <div class="row">
		            <ul class="menu-top-ul ">
					   <li><a class="add_map" href="<?=Url::to(['/blog/add'])?>"><span class="hidden-xs"> Добавить объявление</span></a></li>

				   </ul>
				</div>  
		    </div>
		   <div class="col-md-8 col-sm-8">
		       <div class="row">
			    <ul class="menu-top-ul ul-right">






  <? if (Yii::$app->user->id) {?>

    	<li class="dropdown menu-head-drop"> 
			<a id="dLabel" role="button" data-toggle="dropdown" data-target="#">
        <i class="fa fa-square-user fa-lg" aria-hidden="true"></i>  <span class="hidden-xs"> Личный кабинет</span>
  </a>


  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel"  style="right: 0;left: auto;">


	  <li><a class="login-head-class" href="<?=Url::to(['/user/index'])?>"><i class="fa fa-user" aria-hidden="true"></i> Профиль</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/user/balance'])?>"><i class="fa-solid fa-money-from-bracket" aria-hidden="true"></i> Пополнить баланс</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/user/passanger'])?>"><i class="fa fa-car" aria-hidden="true"></i> Мои поездки</a></li>
	
	   <?if ($shop) {?>
	     <li><a class="login-head-class" href="<?=Url::to(['/user/blogs'])?>"><i class="fa fa-shop" aria-hidden="true"></i> Мой товар</a></li>
	   <? }else{ ?>
	     <li><a class="login-head-class" href="<?=Url::to(['/user/blogs'])?>"><i class="fa fa-bullhorn" aria-hidden="true"></i> Объявления</a></li>
	   <? } ?>
	   <li><a class="login-head-class" href="<?=Url::to(['/user/auction'])?>" ><i class="fa fa-gavel" aria-hidden="true"></i> Мои аукционы</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/user/bet'])?>" ><i class="fa fa-bar-chart" aria-hidden="true"></i> Мои ставки</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/blog/notepad'])?>" target="_blank"><i class="fa fa-heart" aria-hidden="true"></i> Избранное</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/user/articles'])?>"><i class="fa fa-newspaper" aria-hidden="true"></i> Мои статьи</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/user/product'])?>"><i class="fa fa-regular fa-cart-shopping-fast" aria-hidden="true"></i> Мои покупки</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/user/support'])?>"><i class="fa fa-life-ring" aria-hidden="true"></i> Поддержка </a><? if($support_new) {?><span style="color: red">(<?=$support_new?>)</span><?}?></li>
	   <!----------------------------------------------------------->
	  
	   
	   <?if ($shop) {?>
	       <li><a class="login-head-class" target="_blank" href="<?=Url::to(['/shop/update', 'id'=> $shop['shop']])?>"><i class="fa fa-cog" aria-hidden="true"></i> Редактировать магазин</a></li>
		   <li><a class="login-head-class" href="<?=Url::to(['/user/car'])?>"><i class="fa fa-solid fa-chart-line-up" aria-hidden="true"></i> Мои продажи</a></li>
		   <li><a class="login-head-class" href="<?=Url::to(['/user/disputeshop'])?>"><i class="fa fa-balance-scale" aria-hidden="true"></i> Споры 
		   <? if($dispute) {?><span style="color: red">(<?=$dispute?>)</span><?}?>
		   </a></li>
		   <?if ($shop['pay']) {?>
	           <li><a class="login-head-class" href="<?=Url::to(['/extend_shop'])?>"><i class="fa fa-cart-plus" aria-hidden="true"></i> Продлить магазин</a></li>
		   <? }?>
	   <? }else{ ?>
	       <li><a class="login-head-class" target="_blank"  href="<?=Url::to(['/shop/add'])?>"><i class="fa fa-cart-plus" aria-hidden="true"></i> Регистрация магазина</a></li>
	   <? } ?>
	   <!----------------------------------------------------------->
	  
	   
	   <? $exit = '<li class="exits"> <i class="fa fa-sign-out" aria-hidden="true" style="float: left;
    margin-top: 5px;"></i>'. Html::beginForm(['/user/logout'], 'post'). Html::submitButton( 'Выход',['class' => 'btn btn-link logout']). Html::endForm(). '</li>'; echo $exit;?>
	 </ul>
  </li>
  <?}else{?>
  
  	<li class="dropdown menu-head-drop">
		<a id="dLabel" role="button" data-toggle="dropdown" data-target="#">
        <i class="fa fa-square-user fa-lg" aria-hidden="true"></i> <span class="hidden-xs"> Вход / Регистрация</span>
  </a>


  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel"   style="right: 0; left: auto;">
     <li><a data-toggle="modal" data-target="#myModallogin" class="loginpop login-head-class" href="#"><i class="fa fa-sign-in" aria-hidden="true"></i> Вход</a></li>
	 <li><a  data-toggle="modal" data-target="#myModallogin" class="signuppop login-head-class" href="#"><i class="fa fa-user-plus" aria-hidden="true"></i> Регистрация</a></li>
	 <li><a  class="loginpop login-head-class" href="/user/request-password-reset"><i class="fa fa-unlock" aria-hidden="true"></i> Сбросить пароль</a></li>
  </ul>
  </li>

  <?}?>	








				       <li><a href="#" class="notepad"><i class="fa fa-heart" aria-hidden="true"></i><span> Избранное (<span class="noteint"><?=$note?></span>)</span></a></li>
		          </ul>
			   </div>
		   </div>
		  
		</div>
	</div>
</div>

<div class="container-top">
 <div class="container">
    <nav class="navbar navbar-default div_head" role="navigation"> 
      <div class="container">
	   <div class="row width">
	   <div class="navbar-header">
	   <div class="top-search-logo hidden-md visible-xs"><a href="<?=Url::to(['/'])?>"></a></div>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-9">
            <span class="sr-only">Меню</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-9">	
		   <div class="col-md-2 hidden-xs"><div class="row"><div class="top-search-logo"><a href="<?=Url::to(['/'])?>"></a></div></div></div>
        <div class="col-md-10 col-xs-12 search-form-div"> 
        <div class="note-ang"></div>		
		<form action="?" method="get" id="ajax_form">			
		<ul class="nav navbar-nav filtrjs">	
			<li>
			  <div class="drop"> 
			  <a class="dropdown-toggle region-click" data-toggle="dropdown"  data-region="" aria-haspopup="true" aria-expanded="false" href="#">Регион</a>
               <input id="region" type="hidden" name="region">
			   <div class="dropdown-menu region-header  region_ajax noclose"></div>
			   </div>
			</li>
			
			<li>
			<div class="drop"> 
			   <a class="dropdown-toggle category-click" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Категория</a>
               <input id="category" type="hidden" name="category">
			   <div class="dropdown-menu cat_ajax noclose"></div>
			</div>
			</li>
			
			<li class="filtr-li">
			<div class="drop"> 
			   <a class="dropdown-toggle filtr" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Фильтр</a>
               <div class="dropdown-menu noclose response-filtr"></div>
			</div>
			</li>
			
			<li class="search-li">
			     <button id="btn" class="btn btn-success ">Показать <span></span> <i class="fa fa-search" aria-hidden="true"></i></button>
			</li>
		 </ul>
		 </form>
		</div>
		</div>
      </div>
	  </div>
  </nav>
 </div>
</div>
</div>     
	 <div id="body_conteiner">
	
        <?= Alert::widget() ?>

        <?= $content ?>
		
		</div>

<?php $this->endBody() ?>




<div class="modal fade" id="myModallogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">Авторизация</h4>
      </div>
      <div id="bodylogin" style="padding: 15px;">
      </div>
    </div>
  </div>
</div>
</body>
</html>
<?php $this->endPage() ?>
<?php if (Yii::$app->user->id) {
 if (Yii::$app->request->cookies['auth_key'] != Yii::$app->user->identity->auth_key || !Yii::$app->request->cookies['auth_key']) {
	Yii::$app->user->logout();
	return Yii::$app->response->redirect(['user']);
} }?>