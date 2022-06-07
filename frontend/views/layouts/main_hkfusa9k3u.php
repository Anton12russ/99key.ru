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
$region = @Yii::$app->caches->region()[Yii::$app->request->cookies['region']];
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

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
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
   <link rel='manifest' href='/manifest.json'>
	<script src="/pwabuilder-sw.js"></script>
	<meta name="theme-color" content="#ffffff"/>
	<link rel="apple-touch-icon" href="/imagelogoPWA/120x120.png"/>
	<script>if("serviceWorker"in navigator){if(navigator.serviceWorker.controller){console.log("[PWA Builder] active service worker found, no need to register")}else{navigator.serviceWorker.register("pwabuilder-sw.js",{scope:"./"}).then(function(reg){console.log("[PWA Builder] Service worker has been registered for scope: "+reg.scope)})}} let deferredPrompt=null;window.addEventListener('beforeinstallprompt',(e)=>{e.preventDefault();deferredPrompt=e}); async function install(){if(deferredPrompt){deferredPrompt.prompt();console.log(deferredPrompt);deferredPrompt.userChoice.then(function(choiceResult){if(choiceResult.outcome==='accepted'){console.log('Your PWA has been installed')}else{console.log('User chose to not install your PWA')}deferredPrompt=null})}}</script>
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
   		          <a href="#" class="all_region region_act" data-toggle="modal" data-target="#myModal"  data-region="<?=Yii::$app->request->cookies['region']?> "><i class="fa fa-map-location-dot" aria-hidden="true"></i>  <?=Yii::$app->userFunctions->regions_top();?></a></li>
		       </div>
		   </div>
		     <div class="col-md-6">
			    <div class="row">
		            <ul class="menu-top-ul">
				       <li><a href="<?=Url::to(['user/index'])?>"><i class="fa fa-square-user" aria-hidden="true"></i><span class="hidden-xs"> <?if (Yii::$app->user->isGuest) {?>Вход / Регистрация<?}else{?>Личный кабинет<?}?></span></a></li>
				       <li><a href="<?=Url::to(['shop/index'])?>"><i class="fa fa-shop" aria-hidden="true"></i><span class="hidden-xs"> Магазины</span></a></li>
				       <li><a href="<?=Url::to(['article/index'])?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span class="hidden-xs"> Статьи</span></a></li>
				   </ul>
				</div>  
		    </div>
		</div>
	</div>
</div>

<div class="top-header">
    <div class="container">
	    <div class="col-md-12 top-search">
		   <div class="col-md-2 hidden-xs"><div class="top-search-logo"><a href="<?=Url::to(['/'])?>"></a></div></div>
		    <div class="col-md-7">
			    <div class="top-search-search">
				
				<form action="<? if(!$search_cat) {echo Url::to([$form_searh]);}else{echo '?';}?>">	
				    <select class="category-go">
			            <? if(!$search_cat) {?>
                                <option value="<?=Url::to(['ajax/cat-search'.$url_catsearch, 'id' => 0])?>">Выбрать категорию</option>
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
			        <input placeholder="<? if (Yii::$app->controller->id != 'article' && Yii::$app->controller->id != 'shop') {?>Название Объявления<?}?><? if (Yii::$app->controller->id == 'article') {?>Название Статьи<?}?><? if (Yii::$app->controller->id == 'shop') {?>Название Магазина<?}?>" class="top-input-text" type="text" name="text" value="<?=Yii::$app->request->get('text')?>"/>
					<button class="top-submit-text" type="submit"><i class="fa fa-search" aria-hidden="true"></i><span class="hidden-xs"> Поиск</span>  </button>
				</form>	
			    </div>
			</div>
			<? if (Yii::$app->controller->id != 'article' && Yii::$app->controller->id != 'shop') {?>
			    <div class="col-md-3"><a class="add-board" href="<?=Url::to(['blog/add'])?>">+ Подать Объявление</a></div>
			<?}?>
			<?if (Yii::$app->controller->id == 'article') {?>
			    <div class="col-md-3"><a class="add-board" href="<?=Url::to(['article/add'])?>">+ Добавить Статью</a></div>
			<?}?>
			<?if (Yii::$app->controller->id == 'shop') {?>
			    <div class="col-md-3"><a class="add-board add-shop" href="<?=Url::to(['shop/add'])?>">+ Добавить Магазин</a></div>
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
             <li><a href="<?=Url::to(['article/add'])?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Добавить статью</a></li>
             <li><a href="<?=Url::to(['shop/add'])?>"><i class="fa fa-shop" aria-hidden="true"></i> Добавить магазин</a></li>
			 <li><a href="<?=Url::to(['blog/notepad'])?>"><i class="fa fa-star" aria-hidden="true"></i> Избранное</a></li>
			 <? if (!Yii::$app->user->id) {?>
			 <li><a href="<?=Url::to(['user/signup'])?>"><i class="fa fa-solid fa-user" aria-hidden="true"></i> Регистрация</a></li>
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
		     <div class="col-md-12 di-h1" style="background: #FFF;">
			 <?if(isset($this->params['note'])) {?>
			       <div class="all-bo-notepad"   data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить в избранное" ><a href="javascript:void(0);" class="notepad-act-plus" data-id="<?=$this->params['note']['id']?>"><?if (isset($this->params['note']['pad'][$this->params['note']['id']])) {?><i class="fa fa-star" aria-hidden="true"></i><?}else{?><i class="fa fa-star-o" aria-hidden="true"></i><? } ?></a></div>
			 <?}?>
			    <h1><?=$this->params['h1']?></h1></div>
		 <?}?>
		 
		 
		 
        <?= $content ?>
		
		</div>
		<!--Правый модуль-->
		<? if ($blocks_right) {?>
		  <div class="col-md-3 main-right">
	          <?= $this->render('block.php', ['block' => $blocks_right] ) ?>
		  </div>
		<?}?>
		<?= $this->render('block.php', ['block' => $blocks_footer] ) ?>
    </div>
</div>
       
<footer class="footer">
    <div class="container">
	  <div class="col-md-4">
	     <h4>Связаться с Нами</h4>
	      <ul>
             <?php foreach($footer_online as $res) {?>
		         <li> <span><?=$res?></span></li>
	         <? } ?>
	     </ul>	  
      </div>
	  <div class="col-md-4">
	     <h4>Информация</h4>
	      <ul>
		  	<li>- <a href="<?=Url::to(['user/contact'])?>">Связаться с нами</a></li>
             <?php foreach(Yii::$app->caches->staticpage() as $res) {?>
		         <li>- <a href="<?=Url::to(['staticpage/index', 'url' => $res['url']])?>"><?=$res['name']?> </a></li>
	         <? } ?>
	     </ul>	  
      </div>
	  
	  <div class="col-md-4">
	     <h4>Меню</h4>
	      <ul>
		  	  <li>- <a href="<?=Url::to(['blog/notepad'])?>">Избранное</a></li>
              <li>- <a href="<?=Url::to(['blog/add'])?>">Добавить объявление</a></li>
			  <li>- <a href="<?=Url::to(['article/add'])?>">Статьи</a></li>
			  <li>- <a href="<?=Url::to(['shop/index'])?>">Магазины</a></li>
			  <li>- <a href="<?=Url::to(['shop/add'])?>">Добавить Магазин</a></li>
			  <li>- <a href="<?=Url::to(['article/add'])?>">Добавить Статью</a></li>
			 <!-- <li>- <a href="#" onclick="install()">Установить приложение</a></li>-->
	     </ul>	  
      </div>
	  
	  <!--	<div class="col-md-3 sitemap-footer">
	     <h4>Экспорт</h4>
	      <ul>
              <li><i class="fa fa-sitemap" aria-hidden="true"></i> <a href="<?=Url::to(['sitemap/index'])?>">Карта сайта</a></li>
			  <li><i class="fa fa-rss" aria-hidden="true"></i> <a href="<?=Url::to(['rss/board'])?>">RSS Объявления</a></li>
			  <li><i class="fa fa-rss" aria-hidden="true"></i> <a href="<?=Url::to(['rss/article'])?>">RSS Блог (статей)</a></li>
			  <li><i class="fa fa-rss" aria-hidden="true"></i> <a href="<?=Url::to(['rss/shop'])?>">RSS Магазины</a></li>
	     </ul>	  
      </div>-->
	  
	  
	  
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


<?php if (!isset(Yii::$app->request->cookies['coocs']) && Yii::$app->caches->setting()['targeting'] == 0) { ?>
<?php Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => 'coocs', 'value' => 'true',]));?>
<script id="yandexone" type="text/javascript"></script>
<div id="myModalBox" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content cont-mod-user">
    </div>
  </div>
</div>
<script>$(window).on('load', function () {if ($(window).width() > '400'){	regminimodal();}});</script>
<?php } ?>
<?php if (Yii::$app->user->id) {?>
    <div class="chat disabled "id="draggable">
	  <div class="chat_h1"><div class="click_chat" data-href="<?=$urls;?>"><i class="fa fa-chevron-up" aria-hidden="true"></i> Сообщения <i class="mess-online fa fa-regular fa-envelopes" aria-hidden="true"></i></div><div class="move_chat"><i class="fa fa-arrows-alt" aria-hidden="true"></i></div></div>
	  <div class="chat-body">
	     <iframe src=""></iframe>
	  </div>
	</div>
    <script>
       online('<?=Url::to(['/ajax/online'])?>');
	   chat();
   </script>
<?php } ?>
<!--Переводчик-->
<div id="google_translate_element"></div>
<script id="googlang" type="text/javascript" ></script>
<div class="lang_mobile">			
<div class="lang_icon"><div class="left_gooicon">Выбор языка</div><div class="right_gooicon"><i  class="fa fa-language"></i></div></div>
<ul style="float: left;">
<li><a class="a_del_log" href="<?=Url::to(['/ajax/translite'])?>" value="notranslate"><i class="fa fa-sign-out" aria-hidden="true"></i> Русский</a></li>
<li><a href="#googtrans(ru|en)/" target="_blank"><i class="fa fa-sign-out" aria-hidden="true"></i> Английский</a></li>
<li><a href="#googtrans(ru|de)/" target="_blank"><i class="fa fa-sign-out" aria-hidden="true"></i> Немецкий</a></li>
<li><a href="#googtrans(ru|zh-CN)/" target="_blank"><i class="fa fa-sign-out" aria-hidden="true"></i> Китайский</a></li>
</ul>
</div>

<!--Конец Переводчик-->
</body>
</html>
<?php $this->endPage() ?>
<?php if (Yii::$app->user->id) {
 if (Yii::$app->request->cookies['auth_key'] != Yii::$app->user->identity->auth_key || !Yii::$app->request->cookies['auth_key']) {
	Yii::$app->user->logout();
	return Yii::$app->response->redirect(['user']);
} }?>
<?php
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";
?>