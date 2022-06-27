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
//для перетаскивания чата
if (Yii::$app->user->id) {
    $this->registerJsFile('/js/move.js',['depends' => [\yii\web\JqueryAsset::className()]]);
} 
$url_notepad = Url::to(['ajax/notepad']);
$js = <<< JS
	const NOTEPAD_URL = '$url_notepad';
JS;
$this->registerJs($js, $position = yii\web\View::POS_HEAD, $key = null );

// Действия для заблокированныго пользователя
if ((Yii::$app->user->can('DANGER') && Yii::$app->controller->id == 'user') || 
(Yii::$app->user->can('DANGER') && Yii::$app->controller->id == 'blog' && Yii::$app->controller->action->id == 'add') ||
(Yii::$app->user->can('DANGER') && Yii::$app->controller->id == 'blog' && Yii::$app->controller->action->id == 'update') ||
(Yii::$app->user->can('DANGER') && Yii::$app->controller->id == 'shop' && Yii::$app->controller->action->id == 'add') ||
(Yii::$app->user->can('DANGER') && Yii::$app->controller->id == 'shop' && Yii::$app->controller->action->id == 'update')) { 
Yii::$app->response->redirect(['blog/index']);

 }

AppAsset::register($this);
$region = @Yii::$app->caches->region()[Yii::$app->request->cookies['region']->value];

if (!$this->blocks) {
//Ищем совпадение для блоков
$block_arr = Yii::$app->block->block(Yii::$app->controller->id, Yii::$app->controller->action->id, '', '', '');
$controller_id = Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;
 if($block_arr) {
		foreach($block_arr as $res) {
			$this->blocks[$res['position']][] = $res;
		}
	} 
}
if (!isset($this->blocks['top'])) {$blocks_top = '';}else{$blocks_top = $this->blocks['top'];}
if (!isset($this->blocks['right'])) {$blocks_right = '';}else{$blocks_right = $this->blocks['right'];}
if (!isset($this->blocks['footer'])) {$blocks_footer = '';}else{$blocks_footer = $this->blocks['footer'];}

if(Yii::$app->controller->id == "shop" && isset(Yii::$app->request->get()['id']) || Yii::$app->controller->id == "article" && isset(Yii::$app->request->get()['id']) || Yii::$app->controller->id == "blog" && isset(Yii::$app->request->get()['id']) && Yii::$app->controller->action->id != "users" ){
	$urls = Url::to(['user/messenger', 'route' => Yii::$app->controller->id, 'id' => Yii::$app->request->get()['id']]);
}else{
	$urls = Url::to(['/user/route']);
}
if (Yii::$app->controller->id != 'article') { if(Yii::$app->request->get('category')) { $search_cat = Yii::$app->request->get('category');  }else{ $search_cat = 0; } $form_searh = "blog/search"; $url_catsearch = ''; $arr_search_cat = Yii::$app->userFunctions->cat_top($search_cat);}
if (Yii::$app->controller->id == 'article') {  if(Yii::$app->request->get('category')) {   $search_cat = Yii::$app->request->get('category'); }else{   $search_cat = 0;  } $form_searh = "article/index";$url_catsearch = 'art'; $arr_search_cat = Yii::$app->userFunctions->art_top($search_cat);}
if (Yii::$app->controller->id == 'shop') {$form_searh = "shop/index";$url_catsearch = 'shop';}
$footer_online = explode("\n",Yii::$app->caches->setting()['online_footer']);
if (Yii::$app->controller->id == 'blog') {
   $this->registerJsFile('/js/search.js',['depends' => [\yii\web\JqueryAsset::className()]]);
}
$get = Yii::$app->request->get();
if(isset($get['radius']) && $get['radius'] > 0) {
	$radius = $get['radius'];
	$coord = $get['coord'];
}else{
	$radius = '';
	$coord = '';
}
$shop = Yii::$app->userFunctions->shopMenu(Yii::$app->user->id);
if(isset($shop)) {
	$dispute = Yii::$app->userFunctions->disputeshop();
}else{
	$dispute = '';
}


$support_new = Yii::$app->userFunctions->support_new();
//if (!Yii::$app->user->id) {return Yii::$app->response->redirect(['user']); exit();}

// определение мобильного устройства
function check_mobile_device() { 
    $mobile_agent_array = array('ipad', 'iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);    
    // var_dump($agent);exit;
    foreach ($mobile_agent_array as $value) {    
        if (strpos($agent, $value) !== false) return true;   
    }       
    return false; 
}
$mobile = check_mobile_device();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" prefix="og: http://ogp.me/ns#">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#f50404">
    <meta property="og:image" content="/imagelogoPWA/viber.png" />
    <meta property="og:image:url" content="/imagelogoPWA/viber.png">
    <meta property="og:image:secure_url" content="/imagelogoPWA/viber.png">
    <meta property="og:image:width" content="1024">
    <meta property="og:image:height" content="1024">
    <meta name="apple-mobile-web-app-title" content="1TU.RU">
    <meta name="application-name" content="1TU.RU">
    <meta name="msapplication-TileColor" content="#f50404">
    <meta name="theme-color" content="#ffffff">
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
	<title><?= Html::encode($this->title) ?></title> 
    <? } ?>
    <?php $this->head()?>
<!--Для PWA (Приложение)-->
   <!--<link rel='manifest' href='/manifest.json'>
	<script src="/pwabuilder-sw.js"></script>
	<meta name="theme-color" content="#ffffff"/>
	<link rel="apple-touch-icon" href="/imagelogoPWA/120x120.png"/>-->
	<!--<script>if("serviceWorker"in navigator){if(navigator.serviceWorker.controller){console.log("[PWA Builder] active service worker found, no need to register")}else{navigator.serviceWorker.register("/pwabuilder-sw.js",{scope:"./"}).then(function(reg){console.log("[PWA Builder] Service worker has been registered for scope: "+reg.scope)})}} let deferredPrompt=null;window.addEventListener('beforeinstallprompt',(e)=>{e.preventDefault();deferredPrompt=e}); async function install(){if(deferredPrompt){deferredPrompt.prompt();console.log(deferredPrompt);deferredPrompt.userChoice.then(function(choiceResult){if(choiceResult.outcome==='accepted'){console.log('Your PWA has been installed')}else{console.log('User chose to not install your PWA')}deferredPrompt=null})}}</script>-->
</head>
<body>
<?php $this->beginBody(); ?>
<div class="wrap">
<!--
<?php
    NavBar::begin();
    NavBar::end();
?>
-->
<div class="top-menu">
    <div class="container">
	    <div class="col-md-12 mobile-width">
	       <div class="col-md-6">
		       <div class="row">  		     	
                   <a href="#" class="all_region region_act" data-toggle="modal" data-target="#myModal" data-regionname="<?=Yii::$app->userFunctions->regions_top();?>" data-region="<?=Yii::$app->request->cookies['region']?>"><i class="fa fa-map-location-dot" aria-hidden="true"></i>  <?=Yii::$app->userFunctions->regions_top();?></a></li>
				</div>
		   </div>
		     <div class="col-md-6">
			    <div class="row">
		            <ul class="menu-top-ul">
				   
				   
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
    margin-top: 5px; "></i>'. Html::beginForm(['/user/logout'], 'post'). Html::submitButton( 'Выход',['class' => 'btn btn-link logout']). Html::endForm(). '</li>'; echo $exit;?>
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
				   
				   
				       <li><a href="<?=Url::to(['/shop/index'])?>"><i class="fa fa-shop fa-lg" aria-hidden="true"></i><span class="hidden-xs"> Магазины</span></a></li>
				       <li><a href="<?=Url::to(['/article/index'])?>"><i class="fa fa-pen-to-square fa-lg" aria-hidden="true"></i><span class="hidden-xs"> Статьи</span></a></li>
				       <!--<li><a href="<?=Url::to(['/map'])?>"><i class="fa fa-globe fa-lg" aria-hidden="true"></i><span class="hidden-xs"> Объявления на карте</span></a></li>-->
				   </ul>
				   </ul>
				</div>  
		    </div>
		</div>
	</div>
</div>
<div class="top-header">
    <div class="container">
	    <div class="col-md-12 top-search">
		   <div class="col-md-2 hidden-xs logo-1tu"><div class="top-search-logo"><a href="<?=Url::to(['/'])?>"></a></div></div>
		    <div class="col-md-7">
			    <div class="top-search-search">
				
<form id="formtop" action="<? if(!$search_cat) {echo Url::to([$form_searh]);}else{echo '?';}?>">	
				    <select class="category-go">
			            <? if(!$search_cat) {?>
                                <option value="<?=Url::to(['ajax/cat-search'.$url_catsearch, 'id' => 0])?>">Категории</option>
			            <?}?>
					<? if($arr_search_cat['one']) {?>
					    <option value="<?=Url::to(['ajax/cat-search'.$url_catsearch, 'id' => $arr_search_cat['one']->id, 'text' => Yii::$app->request->get('text')])?>"><<< <?=$arr_search_cat['one']->name?></option>
					 <?}elseif($search_cat) {?>
					    <option value="<?=Url::to(['ajax/cat-search'.$url_catsearch, 'id' => 0, 'text' => Yii::$app->request->get('text')])?>"><<< Назад</option>
					 <?}?>
					   <? foreach($arr_search_cat['par'] as $res) {?>
						    <option value="<?=Url::to(['ajax/cat-search'.$url_catsearch, 'id' => $res['id'], 'text' => Yii::$app->request->get('text')])?>" <? if ($res['id'] == $search_cat) {echo ' style="background: #ccc"; selected="selected"';};?>> <?=$res['name']?></option>
					   <?}?>
					</select>
			        <input placeholder="<? if (Yii::$app->controller->id != 'article' && Yii::$app->controller->id != 'shop') {?>Название объявления или населенного пункта<?}?><? if (Yii::$app->controller->id == 'article') {?>Название Статьи<?}?><? if (Yii::$app->controller->id == 'shop') {?>Название Магазина<?}?>" class="top-input-text" type="text" name="text" value="<?=Yii::$app->request->get('text')?>"/>
					<button class="top-submit-text" type="submit"><i class="fa fa-search" aria-hidden="true"></i><span class="hidden-xs"> Поиск</span>  </button>
				<? if (Yii::$app->controller->action->id == 'search'){ ?>
				  <input id="filtr-coord" class="form-control" type="hidden" name="coord" value="<?=$coord?>">
                  <input id="filtr-radius" class="form-control" type="hidden"  name="radius" value="<?=$radius?>">
				<?}?>
				
				</form>	
			    </div>
			</div>
<?if (!Yii::$app->user->id) {?>
	<a class="add-board" href="<?=Url::to(['blog/expressadd'])?>">+ Подать Объявление</a>
<?}else{?>	
<? if (Yii::$app->controller->id != 'article' && Yii::$app->controller->id != 'shop' && Yii::$app->controller->module->id != 'passanger') {?>
<?php if ($mobile === false) { ?>
<div class="col-md-3 dropdown droptuggol">
   <a class="add-board" data-toggle="dropdown" data-target="#" href="#">+ Подать Объявление</a>
  <ul class="dropdown-menu add-board-drop" role="menu" aria-labelledby="dLabel">
  <li><a href="<?=Url::to(['blog/addauction'])?>">Добавить аукцион</a></li>
		  <li><a href="<?=Url::to(['blog/expressadd'])?>">Добавить экспресс объявление</a></li>
		  <li><a href="<?=Url::to(['blog/add'])?>">Добавить расширенное объявление</a></li>
		  <?$shop = Yii::$app->userFunctions->shopMenu(Yii::$app->user->id);?>
		  <?if(!isset($shop)) {?>
		      <li><a href="<?=Url::to(['shop/add'])?>">Создать магазин</a></li>
		  <?}?>
		  <li><a href="<?=Url::to(['passanger/add'])?>"> Искать попутку / попутчика</a></li>
  </ul>
</div>
<? }else{?>
<!--Для стационарного -->
<a class="add-board"  tabindex="-1" role="dialog" data-toggle="modal" data-target=".bs-example-modal-lg" href="#">+ Подать Объявление</a>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <ul class="menu-new">
		  <li><a href="<?=Url::to(['blog/addauction'])?>">Аукцион</a></li>
		  <li><a href="<?=Url::to(['blog/expressadd'])?>">Экспресс объявление</a></li>
		  <li><a href="<?=Url::to(['blog/add'])?>">Расширенное объявление</a></li>
		  <?$shop = Yii::$app->userFunctions->shopMenu(Yii::$app->user->id);?>
		  <?if(!isset($shop)) {?>
		      <li><a href="<?=Url::to(['shop/add'])?>">Создать магазин</a></li>
		  <?}?>
		  <li><a href="<?=Url::to(['passanger/add'])?>"> Искать попутку / попутчика</a></li>
	  </ul>
    </div>
  </div>
</div>
<?} ?>
<?}?>
<?} ?>
			<?if (Yii::$app->controller->id == 'article') {?>
			    <div class="col-md-3"><a class="add-board" href="<?=Url::to(['article/add'])?>">+ Добавить Статью</a></div>
			<?}?>
			<?if (Yii::$app->controller->id == 'shop') {?>
			    <div class="col-md-3"><a class="add-board add-shop" href="<?=Url::to(['shop/add'])?>">+ Добавить Магазин</a></div>
			<?}?>

			
			<? if (Yii::$app->controller->module->id == 'passanger') { ?>
			    <div class="col-md-3"><a class="add-board add-shop" href="<?=Url::to(['/passanger/add'])?>">+ Добавить поездку</a></div>
			<?}?>
		</div>  
	</div>
</div>

    <nav class="navbar navbar-default div_head" role="navigation"> 
      <div class="container">
	   <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-9">
            <span class="sr-only">Меню</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-9">	
         <ul class="nav navbar-nav">
             <li><a href="<?=Url::to(['/article/add'])?>"><i class="fa fa-pen-to-square" aria-hidden="true"></i> Добавить статью</a></li>
             <li><a href="<?=Url::to(['/shop/add'])?>"><i class="fa fa-square-plus" aria-hidden="true"></i> Добавить магазин</a></li>
             <li><a href="<?=Url::to(['/shop/index'])?>"><i class="fa fa-shop" aria-hidden="true"></i> Магазины</a></li>
             <li><a href="https://1tu.ru/Stroitelnye-kalkulyatory.htm"><i class="fa fa-calculator" aria-hidden="true"></i> Строительные калькуляторы</a></li>
			 <li><a href="<?=Url::to(['/blog/notepad'])?>"><i class="fa fa-heart" aria-hidden="true"></i> Избранное</a></li>
			 <!--<li class="maps-li"><a href="<?=Url::to(['/map/'])?>"><i class="fa fa-globe" aria-hidden="true"></i> Поиск на карте</a></li>-->
			 <? if (!Yii::$app->user->id) {?>
			 <li><a href="<?=Url::to(['/user/signup'])?>"><i class="fa fa-solid fa-user" aria-hidden="true"></i> Регистрация</a></li>
             <? }?>
		 </ul>
		</div>
      </div>

</nav>
    <div class="container body-container">
     <?= $this->render('block.php', ['block' => $blocks_top] ) ?>
	 <div id="body_conteiner" class="<? if ($blocks_right) {?>col-md-9<?}else{?>col-md-12<?}?>">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
	
	
	
	
        <?= Alert::widget() ?>
			 <?if(isset($this->params['h1'])){?>
		     <div class="col-md-12 di-h1 " <?php if(Yii::$app->controller->id == 'article') {echo 'style="background: #FFF;"';}?>>
			 <?if(isset($this->params['note'])) {?>
			       <div class="all-bo-notepad"   data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить в избранное" ><a href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$this->params['note']['id']?>"><?if (isset($this->params['note']['pad'][$this->params['note']['id']])) {?><i class="fa fa-heart" aria-hidden="true"></i><?}else{?><i class="fa fa-heart-crack" aria-hidden="true"></i><? } ?></a></div>
			 <?}?>
			    <h1><?=$this->params['h1']?></h1>
				
               <? if (Yii::$app->controller->action->id == 'search'){ ?>
			   <?if($radius) {?>
                      <div class="form-group field-field-name required">
                             <span class="radius-none">Радиус поиска <span class="km"><?=$radius?></span> км<span> <i class="fa fa-times close-radius" aria-hidden="true"></i>
                     </div>
               <? } ?>
			   <? if (Yii::$app->controller->action->id == 'search' && Yii::$app->request->get('text') != '') {?>
                 <button type="button" class="btn btn-info" id="loadcoord" style="margin-bottom: 10px;"  data-toggle="modal" data-target="#myModalmap">
                         <i class="fa fa-map-location-dot" aria-hidden="true"></i> Искать в радиусе 
                 </button>
               <? } ?>
			   <? } ?>			
				</div>
				
				
		 <?}?>
		 
		 
		 <!--Функция, которая ищет на странице тег для таймера она же (КОНТЕНТ)-->
		 <?if (Yii::$app->controller->action->id == 'update') {?>
		      <?=$content?>
		 <? }else{ ?>
            <?=Yii::$app->userFunctions3->timer($content)?>
		 <? } ?>
		</div>
		<!--Правый модуль-->
		<? if ($blocks_right) {?>
		  <div class="col-md-3 main-right">
		       <!--<a href="http://octa.1tu.ru" style="text-decoration: none;">
		          <div class="block-timer">
		              <div class="timer-text">Успей на скидки!</div>
		          <div>Загрузка таймера</div>
		          </div>
		          </a>-->
	          <?= $this->render('block.php', ['block' => $blocks_right] ) ?>
		  </div>
		<?}?>
		<?= $this->render('block.php', ['block' => $blocks_footer] ) ?>
    </div>
</div>
       
<footer class="footer">
    <div class="container">
	  <div class="col-md-3">
	     <h4>Связаться с Нами</h4>
	      <ul>
             <?php foreach($footer_online as $res) {?>
		         <li> <span><?=$res?></span></li>
	         <? } ?>
	     </ul>	  
      </div>
	  <div class="col-md-3">
	     <h4>Информация</h4>
	      <ul>
	        <li>- <a href="https://play.google.com/store/apps/details?id=com.prog.onetu" target="_blank">Установить приложение <i data-toggle="tooltip" data-placement="top" title="" class="fa fa-brands fa-google-play" aria-hidden="true" data-original-title="Установка с Google Play"></i></a></li>
	        <li>- <a href="<?=Url::to(['user/contact'])?>">Связаться с нами</a></li>
		  	<li>- <a href="<?=Url::to(['/My-v-SocSetyah.htm'])?>">Мы в Соц.Сетях</a></li>
		  	<li>- <a href="<?=Url::to(['/Polzovatelskoe-soglashenie.htm'])?>">Пользовательское соглашение</a></li>
		  	<li>- <a href="<?=Url::to(['/Politika-konfidencialnosti.htm'])?>">Политика конфиденциальности</a></li>
             <?php foreach(Yii::$app->caches->staticpage() as $res) {?>
		         <li>- <a href="<?=Url::to(['/staticpage/index', 'url' => $res['url']])?>"><?=$res['name']?> </a></li>
	         <? } ?>
	     </ul>	  
      </div>
	  
	  <div class="col-md-3">
	     <h4>Меню</h4>
	      <ul>
	          <!--<li>- <a href="<?=Url::to(['/map/'])?>">Поиск объявлений на карте</a></li>-->
		  	  <li>- <a href="<?=Url::to(['/blog/notepad'])?>">Избранное</a></li>
			  <li>- <a href="<?=Url::to(['/article/'])?>">Статьи</a></li>
			  <li>- <a href="<?=Url::to(['/shop/index'])?>">Магазины</a></li>
			  <li>- <a href="<?=Url::to(['/passanger'])?>">Попутчики</a></li>
              <li>- <a href="<?=Url::to(['/blog/add'])?>">Добавить Объявление</a></li>
			  <li>- <a href="<?=Url::to(['/shop/add'])?>">Добавить Магазин</a></li>
			  <li>- <a href="<?=Url::to(['/article/add'])?>">Добавить Статью</a></li>
			 <!-- <li>- <a href="#" onclick="install()">Установить приложение</a></li>-->
	     </ul>	  
      </div>
    
	  	<div class="col-md-3 sitemap-footer">
	     <h4>Экспорт</h4>
	      <ul>
              <li><i class="fa fa-sitemap" aria-hidden="true"></i> <a href="<?=Url::to(['sitemap/index'])?>">Карта сайта</a></li>
			  <li><i class="fa fa-rss" aria-hidden="true"></i> <a href="<?=Url::to(['rss/board'])?>">RSS Объявления</a></li>
			  <li><i class="fa fa-rss" aria-hidden="true"></i> <a href="<?=Url::to(['rss/article'])?>">RSS Блог (статей)</a></li>
			  <li><i class="fa fa-rss" aria-hidden="true"></i> <a href="<?=Url::to(['rss/shop'])?>">RSS Магазины</a></li>
			  <li><i class="fa fa-rss" aria-hidden="true"></i> <a href="<?=Url::to(['rss/akcii'])?>">RSS Акции, Распродажи, Предложения и Статьи</a></li>
	     </ul>	  
      </div>
	  
    
	  
    </div>
</footer>
<div class="copyright full-width">
  <div class="container">
      <div class="col-md-8">© <?=Yii::$app->caches->setting()['copyrait']?></div>
	   <div class="col-md-4 metrika"><?=Yii::$app->caches->setting()['yandex-metrika']?></div>
  </div>	
</div>
<?php $this->endBody() ?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
 <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><? if ($region) {?>Местоположение "<?=$region['name']?>"<? }else{?>Выберите регион поиска<? }?></h4>
      </div>
         <div class="modal-body">
       	 <div class="region-header region_ajax"></div>
         </div>
    </div>
  </div>
</div>


<?php if (!isset(Yii::$app->request->cookies['coocs'])) { ?>
<?php Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => 'coocs', 'value' => 'true',]));?>
<script id="yandexone" type="text/javascript"></script>
<div id="myModalBox" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content cont-mod-user">
    </div>
  </div>
</div>
<!--<script>$(window).on('load', function () {if ($(window).width() > '400'){	regminimodal();}});</script>-->
<script>$(window).on('load', function () {	regminimodal();});</script>
<?php } ?>
<?php if (Yii::$app->user->id && Yii::$app->controller->module->id != 'passanger') {?>
    <div class="chat disabled "id="draggable">
	  <div class="chat_h1 hidden-xs"><div class="click_chat" data-href="<?=$urls;?>"><i class="fa fa-chevron-up" aria-hidden="true"></i> Сообщения <i class="mess-online fa fa-regular fa-envelopes" aria-hidden="true"></i></div><div class="move_chat"><i class="fa fa-arrows-alt" aria-hidden="true"></i></div></div>
	  <div class="chat_h1 visible-xs"><div class="click_chat" data-href="<?=$urls;?>"><i class="fa fa-chevron-up" aria-hidden="true"></i>  Чат <i class="mess-online fa fa-regular fa-envelopes" aria-hidden="true"></i></div><div class="move_chat"><i class="fa fa-arrows-alt" aria-hidden="true"></i></div></div>
	  <div class="chat-body">
	     <iframe src=""></iframe>
	  </div>
	</div>
    <script>
       online('<?=Url::to(['/ajax/online'])?>');
	   chat();
   </script>
   

  <script src="/js/jquery.cookie.js"></script>
<?php } ?>
<!--Переводчик-->
<!--<div id="google_translate_element"></div>
<script id="googlang" type="text/javascript" ></script>
<div class="lang_mobile">			
<div class="lang_icon"><div class="left_gooicon">Выбор языка</div><div class="right_gooicon"><i  class="fa fa-language"></i></div></div>
<ul style="float: left;">
<li><a class="a_del_log" href="<?=Url::to(['/ajax/translite'])?>" value="notranslate"><i class="fa fa-sign-out" aria-hidden="true"></i> Русский</a></li>
<li><a href="#googtrans(ru|en)/" target="_blank"><i class="fa fa-sign-out" aria-hidden="true"></i> Английский</a></li>
<li><a href="#googtrans(ru|de)/" target="_blank"><i class="fa fa-sign-out" aria-hidden="true"></i> Немецкий</a></li>
<li><a href="#googtrans(ru|zh-CN)/" target="_blank"><i class="fa fa-sign-out" aria-hidden="true"></i> Китайский</a></li>
</ul>
</div>-->

<!--Конец Переводчик-->
<? if (Yii::$app->controller->action->id == 'search' || Yii::$app->controller->id == 'auction' || Yii::$app->controller->action->id == 'category' || Yii::$app->controller->action->id == 'express'){ ?>
<!-- Modal -->
<div class="modal fade" id="myModalmap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div id="radiusmap" class="modal-body">
      </div>
    </div>
  </div>
</div>
<? } ?>

<?php if (Yii::$app->user->id) {?>
<script>
function sendNotification(title, link, options) {
// Проверим, поддерживает ли браузер HTML5 Notifications
if (!("Notification" in window)) {
alert('Ваш браузер не поддерживает HTML Notifications, его необходимо обновить.');
}

// Проверим, есть ли права на отправку уведомлений
else if (Notification.permission === "granted") {
// Если права есть, отправим уведомление
var notification = new Notification(title, options);

function clickFunc() {
   window.open(link, '_blank');
}

notification.onclick = clickFunc;
}

// Если прав нет, пытаемся их получить
else if (Notification.permission !== 'denied') {
Notification.requestPermission(function (permission) {
// Если права успешно получены, отправляем уведомление
if (permission === "granted") {
var notification = new Notification(title, options);

} else {
alert('Вы запретили показывать уведомления'); // Юзер отклонил наш запрос на показ уведомлений
}
});
} else {
// Пользователь ранее отклонил наш запрос на показ уведомлений
// В этом месте мы можем, но не будем его беспокоить. Уважайте решения своих пользователей.
}
}
</script>







<?php if(Yii::$app->controller->action->id == 'alerts') {?>

<script>

// Проверим, поддерживает ли браузер HTML5 Notifications
if (!("Notification" in window)) {
alert('Ваш браузер не поддерживает HTML Notifications, его необходимо обновить.');
}

// Проверим, есть ли права на отправку уведомлений
else if (Notification.permission === "granted") {
// Если права есть, отправим уведомление
var notification = new Notification(title, options);


}

// Если прав нет, пытаемся их получить
else if (Notification.permission !== 'denied') {
Notification.requestPermission(function (permission) {
// Если права успешно получены, отправляем уведомление
if (permission === "granted") {

} else {
alert('Вы запретили показывать уведомления'); // Юзер отклонил наш запрос на показ уведомлений
}
});
} else {
	$(document).ready(function() {
         alert('Вы отключили уведомления браузера, включить их Вы можете в настройках браузера.');
	});
}

</script>

<?php } ?>



<?php } ?>










<div class="modal fade" id="myModallogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
	//Yii::$app->user->logout();
	//return Yii::$app->response->redirect(['user']);
} }?>