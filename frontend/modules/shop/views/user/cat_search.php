<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
$get = Yii::$app->request->get();

if(isset($get['radius']) && $get['radius'] > 0) {
	$radius = $get['radius'];
	$coord = $get['coord'];
}else{
	$radius = '';
	$coord = '';
}
$this->registerJsFile('/js/adduser.js',['depends' => [\yii\web\JqueryAsset::className()]]);

?>

<div class="row">
<?php Pjax::begin([ 'id' => 'pjaxSearch']); ?>
<div class="search_form col-md-12" data-cat="<?=$category?>">
<form id="formall" action="?" method="get">
<?if(isset($get['sort']) && $get['sort'] != '') {?>
	<input type="hidden" name="sort" value="<?=$get['sort']?>"/>
<?}?>
<div class="col-md-6">
 <div class="form-group field-field-name required">
   <label class="control-label" for="field-name">Категория</label>
   <input type="hidden" class="catchang" name="category" value="<?=Yii::$app->request->get('category')?>"/>
</div>
</div>

<div class="col-md-6">
      <div class="form-group field-field-name required">
          <label class="control-label" for="field-name">Искомая фраза или ID</label>
          <input type="text" class="form-control" name="text" value="<?=Yii::$app->request->get('text')?>"/>
       </div>
  </div>
<div class="col-md-12"></div>
<? foreach($fields as $res) {
	 if ($res['name'] != 'Характер объявления') {
	if ($res['type'] != 'p') {
	?>
<div class="col-md-4">
    <? if ($res['type'] == 'v' || $res['type'] == 'y' || $res['type'] == 't' || $res['type'] == 'j') {?>   <!--Text-->
	 <? if ($res['type_string'] != 'n') {?> 
	  <div class="form-group field-field-name required">
       <label class="control-label" for="field-name"><?=$res['name']?></label>
          <input type="text" style="min-width: auto;" class="form-control" name="f_<?=$res['id']?>" value="<?if (isset($get['f_'.$res['id']])) { echo $get['f_'.$res['id']];}?>"/>
       </div>
    <? }?> 
  <? }?> 
	

<? if ($res['type'] == 's' && $res['type_string'] != 'n' && count(explode("\n",$res['values'])) > 6) {?>   <!--Select-->
<div class="form-group field-field-cat">
   <label class="control-label" for="field-name"><?=$res['name']?></label>
 
      <select class="form-control" name="f_<?=$res['id']?>">
         <option value="">Выбрать</option>
         <? foreach(explode("\n",$res['values']) as $key => $value) {?>
	     <option <? if((isset($get['f_'.$res['id']]) && $get['f_'.$res['id']] == $key) && ($get['f_'.$res['id']] != '')) {?>selected<? } ?> value="<?=$key?>"><?=$value?></option>
         <? }?>
     </select>

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
      <div class="diap_on col-md-6"><input style="min-width: auto;" class="form-control" name="diapazon_on_<?=$res['id']?>" value="<?if (isset($get['diapazon_on_'.$res['id']])) {echo $get['diapazon_on_'.$res['id']];}?>"  placeholder="От"></div>
      <div class="diap_of col-md-6"  ><input style="min-width: auto;" class="form-control" name="diapazon_end_<?=$res['id']?>" value="<? if (isset($get['diapazon_end_'.$res['id']])) {echo $get['diapazon_end_'.$res['id']];}?>"  placeholder="До"></div>
	 
   </div>
</div>

<? }?>


</div>
<? }?>

	<? } ?>
	<? } ?>

	








<div class="col-md-12">
<button class="btn btn-success go-search">Искать <i class="fa fa-search" aria-hidden="true"></i></button>

 </div>
</form>
</div>


<?php Pjax::end(); ?>

</div>