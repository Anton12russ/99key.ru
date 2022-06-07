<?php
/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
$get = Yii::$app->request->get();

if(!empty($get['ot'])) {
	$region_name = '';
	$region_name .= $get['ot'];
	
	if(isset($get['kuda']) && $get['kuda'] != '') {
		$region_name .= ' - '.$get['kuda'];
	}
}

$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/passanger_js/css/all.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->title = 'Поиск попутчиков '.$region_name;
$this->params['h1'] = 'Поиск попутчиков '.$region_name;
$this->registerMetaTag(['name' => 'description','content' => 'Поиск попутчиков '.$region_name]);
$this->registerMetaTag(['name' => 'keywords','content' => 'Попутчики, Попутчики, поездка, автостоп']);
$this->params['breadcrumbs'][] = array('label'=> 'Попутчики '.$region_name);
$this->registerJsFile('/passanger_js/js/all.js',['depends' => [\yii\web\JqueryAsset::className()]]);
/*$this->registerCssFile('/assest_all/calendar/jquery-ui.css');*/
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/passanger_js/js/script.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<?php Pjax::begin([ 'id' => 'pjaxContent']); ?>
<div class="row">
<div class="col-md-12 cat_category">



<div class="search_form col-md-12">
<form id="formall" action="?" method="get" data-pjax='true'>
<div class="col-md-4">
  <div class="form-group field-field-name required">
       <label class="control-label" for="field-name">Откуда</label>
       <input id="ot" type="text" class="form-control" name="ot" value="<?if (isset($get['ot'])) { echo $get['ot'];}?>"/>
   </div>
</div>  

<div class="col-md-4">
  <div class="form-group field-field-name required">
       <label class="control-label" for="field-name">Куда</label>
       <input id="kuda" type="text" class="form-control" name="kuda" value="<?if (isset($get['kuda'])) { echo $get['kuda'];}?>"/>
   </div>
</div>  

<div class="col-md-4 photo-search">
	<div class="form-group">
	<label class="control-label">
	    <br>
        <input <? if(isset($get['photo'])) {?>checked<? } ?> type="checkbox" name="photo" value="ON">
		Только с фото</label>
   </div>	
</div>	

<div class="col-md-12"></div>



<div class="col-md-2 col-sm-4">
  <div class="form-group field-field-name required">
       <label class="control-label" for="field-name">Количество мест</label>
       <input  type="number" class="form-control" name="mesta" value="<?if (isset($get['mesta'])) { echo $get['mesta'];}?>"/>
   </div>
</div> 
 
 
 
 
 

 
 
 
 <div class="col-md-3">
<div class="form-group field-field-cat">
<label class="control-label" for="field-name">Пол</label>
<a data-toggle="dropdown" href="#" aria-expanded="false" class="sort-chex">
<?if (isset($get['pol_0']) && $get['pol_0'] == 0) {?>Мужчины, <?}?>
<?if (isset($get['pol_1']) && $get['pol_1'] == 1) {?>Женщины, <?}?>
<?if ((!isset($get['pol_0']) && !isset($get['pol_1']) || (isset($get['pol_0']) && $get['pol_0'] == '') ||  (isset($get['pol_1']) && $get['pol_1'] == ''))) {?>Мужчины и Женщины<?}?>
</a>
     <ul class="dropdown-menu sort-menu" role="menu" aria-labelledby="dLabel">
        <div class="treug"></div>
		<div class="col-md-12" id="pol">
                 <label><input type="checkbox" name="pol_0" value="0" <?if (isset($get['pol_0']) && $get['pol_0'] == 0) {?>checked<?}?> > Мужчины</label><br>
                 <label><input type="checkbox" name="pol_1" value="1" <?if (isset($get['pol_1']) && $get['pol_1'] == 1) {?>checked<?}?>> Женщины</label><br>
       </div>
     </ul>
</div>
</div>
 
<div class="col-md-3">
<div class="form-group field-field-cat">
<label class="control-label" for="field-name">Кого ищем?</label>
<a data-toggle="dropdown" href="#" aria-expanded="false" class="sort-chex">
<?if (isset($get['appliances_0']) && $get['appliances_0'] == 0) {?>Водитель, <?}?>
<?if (isset($get['appliances_1']) && $get['appliances_1'] == 1) {?>Пассажир, <?}?>
<?if ((!isset($get['appliances_0']) && !isset($get['appliances_1']) || (isset($get['appliances_0']) && $get['appliances_0'] == '') ||  (isset($get['appliances_1']) && $get['appliances_1'] == ''))) {?>Водители и пассажиры<?}?>
</a>
     <ul class="dropdown-menu sort-menu" role="menu" aria-labelledby="dLabel">
        <div class="treug"></div>
		<div class="col-md-12" id="appliances">
                 <label><input type="checkbox" name="appliances_0" value="0" <?if (isset($get['appliances_0']) && $get['appliances_0'] == 0) {?>checked<?}?> > Водитель</label><br>
                 <label><input type="checkbox" name="appliances_1" value="1" <?if (isset($get['appliances_1']) && $get['appliances_1'] == 1) {?>checked<?}?>> Пассажир</label><br>
       </div>
     </ul>
</div>
</div>
 
 <div class="col-md-3">
  <div class="form-group field-field-name required">
       <label class="control-label "for="field-name">Дата выезда</label>
       <input type="text" class="form-control calendarmain1" placeholder="год-месяц-день"  autocomplete="off" name="date" value="<?if (isset($get['date'])) { echo $get['date'];}?>"/>
   </div>
</div> 
 
 
<div class="col-md-12"></div>

<div class="col-md-12">
<button id="btn" class="btn btn-success go-search">Искать <span></span> <i class="fa fa-search" aria-hidden="true"></i></button>
 </div>
</form>
 </div>
   <div class="mini-filter col-md-12">

   <div class="rilght-mini-filter">
   <a data-toggle="dropdown" href="#"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Сортировка: <span>
   <? if (Yii::$app->request->get('sort') == 'DESC') {echo 'Дорогие > дешевые';}if (Yii::$app->request->get('sort') == 'ASC') {echo 'Дешевые > дорогие';}?>
   <? if (Yii::$app->request->get('sort_tyme') == 'DESC') {echo 'Время отправки > дальше';}if (Yii::$app->request->get('sort_tyme') == 'ASC') {echo 'Время отправки > ближе';}if (Yii::$app->request->get('sort') == '' && Yii::$app->request->get('sort_tyme') == '') {echo 'Новые';}?>
   </span></a>
     <ul class="dropdown-menu sort-menu" role="menu" aria-labelledby="dLabel">
        <div class="treug"></div>
        <li><a href="<?=Yii::$app->userFunctions2->urlshop($_SERVER['REQUEST_URI'])?>">Самые новые </a></li>
		<li><a href="<?=Yii::$app->userFunctions2->urlshop($_SERVER['REQUEST_URI'])?>sort_tyme=ASC">Время отрпавки > ближе</a></li>
		<li><a href="<?=Yii::$app->userFunctions2->urlshop($_SERVER['REQUEST_URI'])?>sort_tyme=DESC">Время отправки > дальше</a></li>
        <li><a href="<?=Yii::$app->userFunctions2->urlshop($_SERVER['REQUEST_URI'])?>sort=ASC">От дешевых к дорогим</a></li>
	    <li><a href="<?=Yii::$app->userFunctions2->urlshop($_SERVER['REQUEST_URI'])?>sort=DESC">От дорогих к дешевым</a></li>

     </ul>
  </div>
   </div>
   <div class="col-md-12 margin-h1-cat"></div>
   
   </div>
   






<? if($models) { ?>
  <? foreach($models as $one) { ?>

  <? $url = Url::to(['/passanger/one', 'id' => $one->id]);?>
	  <div class="col-md-12">
	  <a data-pjax=0 class="img_a" href="<?=$url?>" style="position: absolute; display: block; width: 100%; height: 100%;     z-index: 1;"></a>
	  <div class="cat-board-body">
	    <div class="all_img <? if (isset($this->blocks['right'])){?>col-md-3  col-sm-3   col-xs-2<? }else{ ?>col-md-2  col-sm-3<? } ?> col-xs-2" style="background-image: url(<? if (isset($one->img) && $one->img > 0) {?> <?= '/uploads/images/passanger/logo/'.$one->img;?> <? }else{ ?>
		<? if($one->appliances == 1) { ?>
		passanger_js/img/stop.png
		<? }else{ ?>
		passanger_js/img/auto.png
		<? } ?>
		<? } ?>);     background-size: 100%; min-height: 155px;"> </div>
	    <div class="all_body  <? if ($this->blocks['right']){?>col-md-9 col-sm-9 col-xs-10<? }else{ ?>col-md-10 col-sm-9<? } ?> col-xs-10">
	   <? if(isset($one->author->online) && $one->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>
	      <div class="online-all" data-toggle="tooltip" data-placement="top" title="Онлайн"></div>
	  <?}elseif(isset($one->author->online)){?>
	      <div class="offline-all" data-toggle="tooltip" data-placement="top" title="Был(а) в сети <?=Yii::$app->userFunctions2->new_time(strtotime($one->author->online));?>"></div>
	  <? } ?>
	   
	   <table class="table">
        <tbody>
          <tr>
            <td class="td-name"><span class="tds hidden-xs">Откуда:</span><span  data-toggle="tooltip" data-placement="top" title="Откуда" class="minipunkt hidden-md visible-xs">A:</span></td>
            <td class="verti"><span class="info"><?=Yii::$app->userFunctions2->address($one->ot);?></span></td>
         
          </tr>
          <tr>
            <td class="td-name"><span class="tds hidden-xs">Куда:</span><span data-toggle="tooltip" data-placement="top" title="Куда" class="minipunkt hidden-md visible-xs">B:</span></td>
            <td class="verti"><span class="info"><?=Yii::$app->userFunctions2->address($one->kuda);?></span></td>
           
          </tr>
          <tr>
            <td class="td-name"><span class="tds hidden-xs">Отправка:</span> <span data-toggle="tooltip" data-placement="top" title="Выезд" class="minipunkt hidden-md visible-xs"><i class="fa fa-clock" aria-hidden="true"></i></span></td>
            <td class="verti"><span class="info"><?=Yii::$app->userFunctions2->new_time(strtotime($one->time));?></span></td>
            
          </tr>
        </tbody>
      </table>
	 <div class="col-md-12 row">
	
	
	
	<?if ($one->pol == 0) {?>  
	 <div class="pol" data-toggle="tooltip" data-placement="top" title="Мужчина">
	       <i class="fa fa-male" aria-hidden="true"></i>
	 </div>	
	 <? } ?>
	 <?if ($one->pol == 1) {?>  
	  <div class="pol-woman" data-toggle="tooltip" data-placement="top" title="Женщина">
	       <i class="fa fa-female" aria-hidden="true"></i> 
	   </div>	 
	 <? } ?>
	
	 <?if ($one->appliances == 0) {?>  
	 <div class="appliances" data-toggle="tooltip" data-placement="top" title="Водитель">
	      <i class="fa fa-car" aria-hidden="true"></i>
	 </div>	
	 <? } ?>
	 <?if ($one->appliances == 1) {?>  
	  <div class="appliances-man" data-toggle="tooltip" data-placement="top" title="Пассажир">
	      <i class="fa fa-user" aria-hidden="true"></i>
	   </div>	 
	 <? } ?>
	 
		   <?if($one->price) {?>  
		   <div class="all_price">
	            <?=$one->price?>  
				<i class="fa <?=$rat['text']?>" aria-hidden="true"></i> 
		   </div>
		    <? } ?>
		    <div class="redmy">
	             <a data-pjax=0 href="<?=$url?>">Подробнее..</a>
		   </div>
	   </div>
	 </div>  

	
      </div>
	 </div>
	  
	  
	  
	  
	  
	  
	  
	  
	  
  <? } ?>
<? }else{ ?>
     <div class="col-md-12">
       <div class="alert alert-warning">Попутчиков нет по данному запросу</div>
	 </div>
<? } ?>

<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>
 </div>



    
 <div id="url" data-url="<?=strtok($_SERVER['REQUEST_URI'], '?')?>?count=true" class="hiden"></div>
 <?php Pjax::end(); ?>