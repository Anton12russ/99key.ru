<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
$this->registerCssFile('/css/main.css', ['depends' => ['frontend\assets\AppAsset']]);


$this->title = 'Оплата в магазине "'.$shop->name.'"';
$this->registerMetaTag(['name' => 'description','content' => $this->title]);
$this->registerMetaTag(['name' => 'keywords','content' => $this->title. ', магазин, '.$shop->name]);
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
$this->params['breadcrumbs'][] = ['label' => 'Оплата'];
?>

<div class="one-bodys">
   <div class="col-md-12"><h1><?=$this->title?></h1></div>
   <div class="col-md-12"><?=$shop->field['payment']?></div>
  

   <!--<div class="col-md-12">
     <hr>
   <strong>Внимание!!!</strong><br>
   Вам доступна возможность, оплачивать Ваши заказы через Гарант-Сервис сайта  "<?=Yii::$app->caches->setting()['site_name']?>". 
   <br>Какие приемущества Вы получаете при оплате через гарант-сервис?<br>
   Данный Сайт "<?=Yii::$app->caches->setting()['site_name']?>" удерживает Ваши средства на своем личном счете до тех пор, пока Вы не подтвердили получение товара. 
   <br>
   Если Вы забыли сообщить о получении посылки с заказом, то средства за покупку будут переведены на счет продавца (Сайт <?=DOMAIN?>) только через месяц со дня оформления заказа. Таким образом, за этот период, Вы можете оспорить сделку.
   <br> 
   Услуга включает в себя удержание и сохранение Ваших средств только за купленный Вами товар. Средства за пересылку товара, в случае, если оплата пересылки происходила по частному договору с продавцом, в различные регионы и города, не удерживаются и гарантии не подлежит.
   <br> 
   <br> 
   Если Вы воспользовались услугой "Гарант-Сервис", то в личном кабинете, на сайте "<?=Yii::$app->caches->setting()['site_name']?>" Вы можете отслеживать статус Вашего заказа и при необходимости открыть спор по возврату денежных средств.
   </div>-->
</div>
