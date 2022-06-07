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
$this->registerCssFile('/passanger_js/css/user.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->title = 'Мои поездки';
$this->registerMetaTag(['name' => 'description','content' => 'Поиск попутчиков '.$region_name]);
$this->registerMetaTag(['name' => 'keywords','content' => 'Попутчики, Попутчики, поездка, автостоп']);
$this->params['breadcrumbs'][] = array('url'=>'/user','label'=> 'Личный кабинет');
$this->params['breadcrumbs'][] = array('label'=> 'Попутчики');
$this->registerJsFile('/passanger_js/js/all.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/passanger_js/js/script.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<style>
.person-body input {
	    min-width: 10px;
}
</style>


<?php Pjax::begin([ 'id' => 'pjaxContent', 'enablePushState' => false]); ?>
<div class="row">
<br>

<? if($models) { ?>
  <? foreach($models as $one) { ?>

  <? $url = Url::to(['/passanger/one', 'id' => $one->id]);?>
	  <div class="col-md-12">
	  
	  <div class="cat-board-body">
	  <a data-pjax=0 href="<?=$url?>">
	  	    <div class="all_img <? if (isset($this->blocks['right'])){?>col-md-3  col-sm-3   col-xs-2<? }else{ ?>col-md-2  col-sm-3<? } ?> col-xs-2" style="background-image: url(<? if (isset($one->img) && $one->img > 0) {?> <?= '/uploads/images/passanger/logo/'.$one->img;?> <? }else{ ?>
		<? if($one->appliances == 1) { ?>
		passanger_js/img/stop.png
		<? }else{ ?>
		passanger_js/img/auto.png
		<? } ?>
		<? } ?>);     background-size: 100%; min-height: 155px;"> </div>
	  	 </a>
	    <div class="alls_body  <? if ($this->blocks['right']){?>col-md-9 col-sm-9 col-xs-10<? }else{ ?>col-md-10 col-sm-9<? } ?> col-xs-10">
	
	   
	   <table class="table">
        <tbody>
          <tr>
            <td class="td-name"><span class="tds hidden-xs">Откуда:</span><span  data-toggle="tooltip" data-placement="top" title="" data-original-title="Откуда" class="minipunkt hidden-md visible-xs">A:</span></td>
            <td class="verti"><span class="info"><?=Yii::$app->userFunctions3->address($one->ot);?></span></td>
         
          </tr>
          <tr>
            <td class="td-name"><span class="tds hidden-xs">Куда:</span><span data-toggle="tooltip" data-placement="top" title="" data-original-title="Куда" class="minipunkt hidden-md visible-xs">B:</span></td>
            <td class="verti"><span class="info"><?=Yii::$app->userFunctions3->address($one->kuda);?></span></td>
           
          </tr>
          <tr>
            <td class="td-name"><span class="tds hidden-xs">Отправка:</span> <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Выезд" class="minipunkt hidden-md visible-xs"><i class="fa fa-clock" aria-hidden="true"></i></span></td>
            <td class="verti"><span class="info"><?=Yii::$app->userFunctions3->new_time(strtotime($one->time));?></span></td>
            
          </tr>
        </tbody>
      </table>
	 <div class="col-md-12 row">
	
	
	
	<?if ($one->pol == 0) {?>  
	 <div class="pol" data-toggle="tooltip" data-placement="top" title="" data-original-title="Мужчина">
	       <i class="fa fa-male" aria-hidden="true"></i>
	 </div>	
	 <? } ?>
	 <?if ($one->pol == 1) {?>  
	  <div class="pol-woman" data-toggle="tooltip" data-placement="top" title="" data-original-title="Женщина">
	       <i class="fa fa-female" aria-hidden="true"></i> 
	   </div>	 
	 <? } ?>
	
	 <?if ($one->appliances == 0) {?>  
	 <div class="appliances" data-toggle="tooltip" data-placement="top" title="" data-original-title="Водитель">
	      <i class="fa fa-car" aria-hidden="true"></i>
	 </div>	
	 <? } ?>
	 <?if ($one->appliances == 1) {?>  
	  <div class="appliances-man" data-toggle="tooltip" data-placement="top" title="" data-original-title="Пассажир">
	      <i class="fa fa-user" aria-hidden="true"></i>
	   </div>	 
	 <? } ?>
	 
		   <?if($one->price) {?>  
		   <div class="alls_price">
	            <?=$one->price?>  
				<i class="fa <?=$rat['text']?>" aria-hidden="true"></i> 
		   </div>
		    <? } ?>
		    <div class="redmy">
	             <a data-pjax=0 target="_blank" href="<?=$url?>">Подробнее..</a>
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
