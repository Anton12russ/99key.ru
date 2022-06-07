<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$get = Yii::$app->request->get();
if(isset($get['radius']) && $get['radius'] > 0) {
	$radius = $get['radius'];
	$coord = $get['coord'];
}else{
	$radius = '';
	$coord = '';
}
?>
<span id="search_form"></span>
<div class="search_form col-md-12" data-cat="<?=$category?>">
<form id="formall" action="?" method="get">
<div class="col-md-4">
      <div class="form-group field-field-name required">
          <label class="control-label" for="field-name">Искомая фраза</label>
          <input type="text" class="form-control" name="text" value="<?=Yii::$app->request->get('text')?>"/>
       </div>
  </div>
<div class="col-md-12"></div>
<? foreach($fields as $res) {
	if ($res['type'] != 'p') {
	?>
<div class="col-md-4">
    <? if ($res['type'] == 'v' || $res['type'] == 'y' || $res['type'] == 't' || $res['type'] == 'j') {?>   <!--Text-->
	 <? if ($res['type_string'] != 'n') {?> 
	  <div class="form-group field-field-name required">
       <label class="control-label" for="field-name"><?=$res['name']?></label>
          <input type="text" class="form-control" name="f_<?=$res['id']?>" value="<?if (isset($get['f_'.$res['id']])) { echo $get['f_'.$res['id']];}?>"/>
       </div>
    <? }?> 
  <? }?> 
	

<? if ($res['type'] == 's' && $res['type_string'] != 'n' && count(explode("\n",$res['values'])) > 6) {?>   <!--Select-->
<div class="form-group field-field-cat">
   <label class="control-label" for="field-name"><?=$res['name']?></label>
   <div id="selectBox_cat">
      <select class="form-control sel_cat" name="f_<?=$res['id']?>">
         <option value="">Выбрать</option>
         <? foreach(explode("\n",$res['values']) as $key => $value) {?>
	     <option <? if((isset($get['f_'.$res['id']]) && $get['f_'.$res['id']] == $key) && ($get['f_'.$res['id']] != '')) {?>selected<? } ?> value="<?=$key?>"><?=$value?></option>
         <? }?>
     </select>
   </div>
</div>
<? }?>


<? if ($res['type'] == 'c' || $res['type'] == 'r') {?>   <!--Сheckbox-->
<div class="form-group field-field-cat ">
<label class="control-label" for="field-name"><?=$res['name']?></label>
<a data-toggle="dropdown" href="#" aria-expanded="true" class="sort-chex"><? $val = array(); foreach(explode("\n",$res['values']) as $key => $value) {?><? if((isset($get['f_'.$res['id'].'_chex_'.$key]) && $get['f_'.$res['id'].'_chex_'.$key] == $key) && ($get['f_'.$res['id'].'_chex_'.$key] != '')) {$val[] =  str_replace("\r", '' , $value); } ?><? }?><? if ($val) {?><?=implode(', ',$val);?><? }else{ ?>Выбрать<? }?></a>

     <ul class="dropdown-menu sort-menu" role="menu" aria-labelledby="dLabel">
        <div class="treug"></div>
		<div class="col-md-12 " id="f_<?=$res['id']?>">
            <? foreach(explode("\n",$res['values']) as $key => $value) {?>
                <label><input  <? if(isset($get['f_'.$res['id'].'_chex_'.$key]) && ($get['f_'.$res['id'].'_chex_'.$key] == $key) && ($get['f_'.$res['id'].'_chex_'.$key] != '')) {?>checked<? } ?> type="checkbox" name="<?='f_'.$res['id'].'_chex_'.$key?>" value="<?=$key?>"> <?=$value?></label><br>
            <? }?>
       </div>
     </ul>
</div>
<? }?>


<? if ($res['type'] == 's' && $res['type_string'] != 'n'  && count(explode("\n",$res['values'])) <= 6) {?>   <!--Сheckbox-->
<div class="form-group field-field-cat ">
<label class="control-label" for="field-name"><?=$res['name']?></label>
<a data-toggle="dropdown" href="#" aria-expanded="true" class="sort-chex"><? $val = array(); foreach(explode("\n",$res['values']) as $key => $value) {?><? if((isset($get['f_'.$res['id'].'_multi_'.$key]) && $get['f_'.$res['id'].'_multi_'.$key] == $key) && ($get['f_'.$res['id'].'_multi_'.$key] != '')) {$val[] =  str_replace("\r", '' , $value); } ?><? }?><? if ($val) {?><?=implode(', ',$val);?><? }else{ ?>Выбрать<? }?></a>

     <ul class="dropdown-menu sort-menu" role="menu" aria-labelledby="dLabel">
        <div class="treug"></div>
		<div class="col-md-12 " id="f_<?=$res['id']?>">
            <? foreach(explode("\n",$res['values']) as $key => $value) {?>
                <label><input  <? if((isset($get['f_'.$res['id'].'_multi_'.$key]) && $get['f_'.$res['id'].'_multi_'.$key] == $key) && ($get['f_'.$res['id'].'_multi_'.$key] != '')) {?>checked<? } ?> type="checkbox" name="<?='f_'.$res['id'].'_multi_'.$key?>" value="<?=$key?>"> <?=$value?></label><br>
            <? }?>
       </div>
     </ul>
</div>
<? }?>


<? if (($res['type'] == 's' && $res['type_string'] == 'n') || ($res['type'] == 'v' && $res['type_string'] == 'n')) {?>  
<div class="form-group field-field-cat">
   <label class="control-label" for="field-name"><?=$res['name']?></label>
    <div class="diap-sort row">
      <div class="diap_on col-md-6"><input class="form-control" name="diapazon_on_<?=$res['id']?>" value="<?if (isset($get['diapazon_on_'.$res['id']])) {echo $get['diapazon_on_'.$res['id']];}?>"  placeholder="От"></div>
      <div class="diap_of col-md-6"><input class="form-control" name="diapazon_end_<?=$res['id']?>" value="<? if (isset($get['diapazon_end_'.$res['id']])) {echo $get['diapazon_end_'.$res['id']];}?>"  placeholder="До"></div>
	 
   </div>
</div>

<? }?>


</div>
<? }?>

	<? } ?>
<?if (count($fields) > 2) {?>
  <div class="col-md-12"></div>
<? } ?>
<? foreach($fields as $res) {
	 if ($res['type'] == 'p') {?>
<div class="col-md-4">
<div class="form-group field-field-cat">
   <label class="control-label" for="field-name"><?=$res['name']?></label>
 <div class="price-sort">

     <div class="price_on"><input class="form-control" name="price_on_<?=$res['id']?>" value="<? if (isset($get['price_on_'.$res['id']])) {echo $get['price_on_'.$res['id']];}?>"  placeholder="От"></div>
     <div class="price_of"><input class="form-control" name="price_end_<?=$res['id']?>" value="<? if (isset($get['price_end_'.$res['id']])) {echo $get['price_end_'.$res['id']];} ?>"  placeholder="До"></div>
	 <div class="price_of">

	   <select class="form-control rates-search"  name="price_rt_<?=$res['id']?>">
	   <option value="">Валюта</option>
	  <? foreach($rates as $key => $rat) {?>
	      <option <? if(isset($get['price_rt_'.$res['id']]) && $get['price_rt_'.$res['id']] == $key) { ?>selected<?}?> value="<?=$key?>"><?=$rat['name']?></option>
	  <? } ?>
	     </select>
	 </div>
 </div>
</div>
</div>
<? }?>
	<? } ?>
	

<div class="col-md-4 photo-search">
	<div class="form-group">
	<label class="control-label">
	    <br>
        <input <? if(isset($get['photo'])) {?>checked<? } ?> type="checkbox" name="photo" value="ON">
		Только с фото</label>
   </div>	
</div>	




<div class="col-md-12"></div>

<input id="filtr-coord" class="form-control" type="hidden" name="coord" value="<?=$coord?>">
<input id="filtr-radius" class="form-control" type="hidden" name="radius" value="<?=$radius?>">



<?if($radius) {?>
<div class="col-md-12">
  <div class="form-group field-field-name required">
     <span class="radius-none">Радиус поиска <span class="km"><?=$radius?></span> км<span> <i class="fa fa-times close-radius" aria-hidden="true"></i>
  </div>
</div>
<? } ?>



<div class="col-md-12">
<button class="btn btn-success go-search">Искать <i class="fa fa-search" aria-hidden="true"></i></button>
<button id="loadcoord" data-auction="true" type="button" class="btn btn-info"  data-toggle="modal" data-target="#myModalmap">
    <i class="fa fa-map-location-dot" aria-hidden="true"></i> Искать в радиусе 
</button>
 </div>
</form>
</div>