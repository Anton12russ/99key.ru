<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
$this->registerCssFile('/css/main.css', ['depends' => ['frontend\assets\AppAsset']]);


$this->title = 'Доставка в магазине "'.$shop->name.'"';
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

$this->params['breadcrumbs'][] = ['label' => 'Доставка' ];


?>

<div class="one-bodys">
<div class="col-md-12"><h1><?=$this->title?></h1></div>

<div class="col-md-12"><?=$shop->field['delivery']?></br></div>

</div>
