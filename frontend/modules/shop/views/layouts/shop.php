<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
//для перетаскивания чата
$this->registerJsFile('/js/move.js',['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile('/js/shop_one.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/shop_personal.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/css/shopone.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/shop_personal.css', ['depends' => ['frontend\assets\AppAsset']]);
$url_notepad = Url::to(['/notepads']);
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
 

$shop = $this->params['shop'];
$arr = $this->params['arr'];
$vote = $this->params['vote'];
$vot = $this->params['vot'];
foreach(Yii::$app->caches->rates() as $res) {
	if($res['def'] == 1) {
		$rate_def = $res;
	}
}
$price_car = Yii::$app->userFunctions3->car_price($shop->id);
if(isset($price_car)) {
	$car_price = $price_car;
}else{
	$car_price = 0;
}

list($x1,$x2) = explode('.',strrev($_SERVER['HTTP_HOST']));
$xdomain = strrev($x1.'.'.$x2);

$patch_url = PROTOCOL.$xdomain;


 $urls = $patch_url.Url::to(['/user/messenger', 'route' => 'shop', 'id' => $shop['id'] ]);

$footer_online = explode("\n",Yii::$app->caches->setting()['online_footer']);
//Добавляем куку для чата - для гостей
 if (!Yii::$app->user->id && !Yii::$app->request->cookies['chat']) {
	 $time = time();
      Yii::$app->response->cookies->add(new \yii\web\Cookie([
          'name' => 'chat',
		  'domain' => $xdomain,
          'value' => $time
      ]));  
 }
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



if (!$this->blocks) {
//Ищем совпадение для блоков
$block_arr = Yii::$app->blockshop->block('', Yii::$app->controller->action->id, '', '', '', $shop->user_id);
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
	
$region = @Yii::$app->caches->region()[Yii::$app->request->cookies['region']->value];

$this->registerJsFile('/js/jquery.cookie.js',['depends' => [\yii\web\JqueryAsset::className()]]);





$region = '';
if($region = @Yii::$app->caches->region()[$_COOKIE['region']]) {
    $region = $region;
}
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" prefix="og: http://ogp.me/ns#">
<head>
    <meta property="og:image" content="/imagelogoPWA/viber.png" /> <!– для Viber –>
    <meta property="og:image:url" content="/imagelogoPWA/viber.png">
    <meta property="og:image:secure_url" content="/imagelogoPWA/viber.png">
    <meta property="og:image:width" content="1024">
    <meta property="og:image:height" content="1024">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title> 
       <?php $this->head()?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="top-menu">
    <div class="container">
	    <div class="col-md-12 mobile-width">
	       <div class="col-md-6">
		       <div class="row">  		     	
                   <a href="#" class="all_region region_act" data-user="<?=$shop->user_id?>" data-toggle="modal" data-target="#myModalreg" data-regionname="<?=Yii::$app->userFunctions->regions_top();?>" data-region="<?=Yii::$app->request->cookies['region']?>"><i class="fa fa-map-location-dot" aria-hidden="true"></i> <? if(!$region) {echo 'Выбрать регион';}else{echo $region['name'];}?></a></li>
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
	   <li><a class="login-head-class" href="<?=Url::to(['/user/history'])?>"><i class="fa fa-money-check-dollar-pen" aria-hidden="true"></i> Платежная история</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/user/alerts'])?>"><i class="fa fa-bell" aria-hidden="true"></i> Оповещения</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/mynotepad'])?>" target="_blank"><i class="fa fa-heart" aria-hidden="true"></i> Избранное</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/user/message'])?>"><i class="fa fa-comments" aria-hidden="true"></i> Сообщения</a></li>
	   <li><a class="login-head-class" href="<?=Url::to(['/user/product'])?>"><i class="fa fa-regular fa-cart-shopping-fast" aria-hidden="true"></i> Мои покупки</a></li>
	   <li><a class="login-head-class" style="color: #f34723" href="https://1tu.ru/user"><i class="fa fa-square-user" aria-hidden="true"></i> Личный кабинет на 1TU.ru</a></li>
	   <!----------------------------------------------------------->
	  

	    <? $exit = '<li class="exit"> <i class="fa fa-sign-out " aria-hidden="true" style="float: left;
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
				       <li><a href="https://1tu.ru/shop" target="_blank"><i class="fa fa-shop fa-lg" aria-hidden="true"></i><span class="hidden-xs"> Магазины</span></a></li>
				      <!-- <li><a href="https://1tu.ru/map" target="_blank"><i class="fa fa-globe fa-lg" aria-hidden="true"></i><span class="hidden-xs"> Объявления на карте</span></a></li> -->
				   </ul>
				   </ul>
				</div>  
		    </div>
		</div>
	</div>
</div>
<div class="wrap">

  <div class="header-personal">
    <div class="container">

	    <?if($this->params['logo']) {?>
	       <div class="logo-personal"><a href="/"><img src="<?=Yii::getAlias('@shop_logo').'/'.$this->params['logo']?>"/></a></div>
		<?}else{?>   
		   <div class="logo-personal"></div>
		<?}?>  

		  <div class="serch-sorm">
		  <div class="col-md-9">
		     <form action="<? if(Yii::$app->controller->action->id == 'article') {echo Url::to(['/article']);}else{echo Url::to(['/product']);}?>">
			      <input placeholder="<?if(Yii::$app->controller->action->id == 'article') {?>Введите название статьи<?}else{?> Поиск по названию товара<?}?>" class="top-input-text" type="text" name="text"  value="<?=Yii::$app->request->get('text')?>">
				  <button class="top-submit-text" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Поиск  </button>
		     </form>
		  </div>
		  <?php Pjax::begin(['id' => 'pjaxCar']); ?>
		    <div class="col-md-3 cart_go">
			<a  data-pjax="0" href="<?=Url::to(['/cart'])?>" class="cart"><i class="fa fa-basket-shopping" aria-hidden="true"></i>  
			(<span class="car-top"><?=number_format($car_price, 0, '.', ' ')?> <i class="fa <?=$rate_def['text']?>" aria-hidden="true"></i></span>)</a>
		       <!-- <span><i class="fa fa-phone" aria-hidden="true"></i> <?=$this->params['phone']?></span>-->
		    </div>
		  <?php Pjax::end(); ?>	
		  </div>
        
	</div>

	

		<div class="menutop-personal menutop-noname">
		  <div class="container">
		  <div class="row">
			<div class="col-md-12">
			<div class="row">
		  	<nav class="navbar navbar-default" role="navigation">
             <div class="phone-xs hidden-md visible-xs"><i class="fa fa-phone" aria-hidden="true"></i><a style="color: #FFF;" href="tel:<?=str_replace(' ', '',$this->params['phone'])?>"><?=$this->params['phone']?></a></div>
                 <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                   </button>
                 </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				 
                     <ul class="nav navbar-nav navbar-left">
                          <li><a href="<?=Url::to(['/product'])?>"><i class="fa fa-shop" aria-hidden="true"></i> Список товара</a></li>
                          <? if( $this->params['shop']['field']['price']) {?> <li> <a target="_blank" href="/uploads/images/shop/file/<?= $this->params['shop']['field']['price']?>"><i class="fa fa-pen-to-square" aria-hidden="true"></i> Прайс-Лист</a></li><?}?>
                          <li><a href="<?=Url::to(['/mynotepad'])?>"><i class="fa fa-heart" aria-hidden="true"></i> Избранное</a></li>
                          <li><a href="<?=Url::to(['/delivery'])?>"><i class="fa fa-truck" aria-hidden="true"></i> Доставка</a></li>
						  <li><a href="<?=Url::to(['/payment'])?>"><i class="fa fa-money-check-dollar-pen" aria-hidden="true"></i> Оплата</a></li>
						  
						   <?if($this->params['menu_art'] == true) {?>
						      <li><a href="<?=Url::to(['/article'])?>"><i class="fa fa-address-book-o" aria-hidden="true"></i> Статьи</a></li>
						   <?}?> 
						
							  <li><a href="<?=Url::to(['/contact'])?>"><i class="fa fa-map-location-dot" aria-hidden="true"></i> Контакты</a></li>
                    
					 
					</ul>
               </div><!-- /.navbar-collapse -->
     
           </nav>
		  </div>
         </div>
		  </div>
		 </div> 
		</div>
	   </div>
 
<!--<?php  NavBar::begin([]); NavBar::end();?>-->

    <div class="container body-container">
	

        <?= Alert::widget() ?>
		<?php if ($mobile === false) { ?>
		
		    <?=$this->render('/default/left_menu.php', compact('shop','arr', 'vote', 'vot', 'blocks_right'))?>
	
		<? } ?>
		<div class="col-md-9">
			<?= $this->render('block.php', ['block' => $blocks_top] ) ?>
          <div class="col-md-12">
	
		  <? if(Yii::$app->controller->action->id != 'index') {?>
		    <br>
		   <?= Breadcrumbs::widget([
              'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
		  <?}?>
          <?= $content ?>
		  
		 
		</div>
 <?= $this->render('block.php', ['block' => $blocks_footer] ) ?>
		</div>
		
    </div>
</div>
   
<footer class="footer">
    <div class="container">


	  
	  <div class="col-md-3">
	     <h4>Меню</h4>
	      <ul>
              <li>- <a href="<?=Url::to(['/product'])?>">Список товара</a></li>
			  <li>- <a href="<?=Url::to(['/delivery'])?>">Доставка</a></li>
			  <li>- <a href="<?=Url::to(['/payment'])?>">Оплата</a></li>
			  <li>- <a href="<?=Url::to(['/article'])?>">Статьи</a></li>
			  <li>- <a href="<?=Url::to(['/contact'])?>">Контакты</a></li>
			  <li>- <a href="<?=Url::to(['/mynotepad'])?>">Избранное</a></li>
	     </ul>	  
      </div>
	  
	  	<div class="col-md-3 sitemap-footer">
	     <h4>Экспорт</h4>
	      <ul>
              <li><i class="fa fa-sitemap" aria-hidden="true"></i> <a href="<?=Url::to(['/sitemap', 'act'=>'all'])?>">Карта сайта</a></li>
			  <li><i class="fa fa-rss" aria-hidden="true"></i> <a href="<?=Url::to(['/rss'])?>">RSS Объявления</a></li>
			  <li><i class="fa fa-rss" aria-hidden="true"></i> <a href="<?=Url::to(['/rss', 'act' => 'article'])?>">RSS Статей</a></li>
	     </ul>	  
      </div>
	  
	  	  <div class="col-md-3">
	     <h4>Связаться с Нами</h4>
	      <ul>
         
			     <li> <span>☎ <a href="tel:<?=str_replace(' ', '',$this->params['phone'])?>"><?=$this->params['phone']?></a></span></li>
	     </ul>	  
      </div>
	  <div class="col-md-3">
	    <div class="top-search-logo" style="background: #FFF; padding: 10px 20px; border: 1px solid #fff; border-radius: 5px 15px 5px 15px;"><a href="https://1tu.ru/"></a></div> 
      </div>		
    </div>
</footer>
<div class="copyright full-width">
  <div class="container">
      <div class="col-md-8">© <?=Yii::$app->caches->setting()['copyrait']?></div>
  </div>	
</div>
<?php $this->endBody() ?>

    <div class="chat disabled "id="draggable">
	   <div class="chat_h1 hidden-xs"><div class="click_chat" data-href="<?=$urls;?>"><i class="fa fa-chevron-up" aria-hidden="true"></i> Сообщения <i class="mess-online fa fa-regular fa-envelopes" aria-hidden="true"></i></div><div class="move_chat"><i class="fa fa-arrows-alt" aria-hidden="true"></i></div></div>
	  <div class="chat_h1 visible-xs"><div class="click_chat" data-href="<?=$urls;?>"><i class="fa fa-chevron-up" aria-hidden="true"></i>  Чат <i class="mess-online fa fa-regular fa-envelopes" aria-hidden="true"></i></div><div class="move_chat"><i class="fa fa-arrows-alt" aria-hidden="true"></i></div></div>
	  <div class="chat-body">
	     <iframe src=""></iframe>
	  </div>
	</div>
    <script>
       onlinego('<?=Url::to(['/online'])?>');
	   chat();
   </script>
   
   <!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(57601138, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/57601138" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
   
<!--Переводчик-->
<!--<div id="google_translate_element"></div>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'ru', includedLanguages: 'de,en,ru,zh-CN', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<div class="lang_elite">			
<div class="lang_icon"><div class="left_gooicon">Выбор языка</div><div class="right_gooicon"><i  class="fa fa-language"></i></div></div>
<ul style="float: left;">
<li><a class="a_del_log" href="<?=Url::to(['/translite'])?>" value="notranslate"><i class="fa fa-sign-out" aria-hidden="true"></i> Русский</a></li>
<li><a href="#googtrans(ru|zh-CN)/" target="_blank"><i class="fa fa-sign-out" aria-hidden="true"></i> Китайский</a></li>
<li><a href="#googtrans(ru|en)/" target="_blank"><i class="fa fa-sign-out" aria-hidden="true"></i> Английский</a></li>
<li><a href="#googtrans(ru|de)/" target="_blank"><i class="fa fa-sign-out" aria-hidden="true"></i> Немецкий</a></li>
</ul>
</div>-->

<!--Конец Переводчик-->









<!-- Modal -->
<div class="modal fade" id="myModaluser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Название модали</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary">Сохранить изменения</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModallogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title"></h4>
      </div>
      <div id="bodylogin" style="padding: 15px;">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModalreg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><? if ($region['name']) {?>Местоположение "<?=$region['name']?>"<? }else{?>Выберите регион поиска<? }?></h4>
      </div>
         <div class="modal-body">
       	 <div class="region-header region_ajax"></div>
         </div>
    </div>
  </div>
</div>




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
</body>
</html>
<?php $this->endPage() ?>
