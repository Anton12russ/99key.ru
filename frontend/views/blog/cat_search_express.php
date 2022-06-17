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
	 if ($res['type'] == 'p') {?>
<div class="col-md-4">
<div class="form-group field-field-cat">
   <label class="control-label" for="field-name"><?=$res['name']?></label>
 <div class="price-sort">

     <div class="price_on"><input class="form-control" name="price_on_<?=$res['id']?>" value="<? if (isset($get['price_on_'.$res['id']])) {echo $get['price_on_'.$res['id']];}?>"  placeholder="От"></div>
     <div class="price_of"><input class="form-control" name="price_end_<?=$res['id']?>" value="<? if (isset($get['price_end_'.$res['id']])) {echo $get['price_end_'.$res['id']];} ?>"  placeholder="До"></div>
	 <div class="price_of">


	 </div>
 </div>
</div>
</div>
<? }?>
	<? } ?>

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
<button id="loadcoord" type="button" class="btn btn-info"  data-toggle="modal" data-target="#myModalmap">
    <i class="fa fa-map-location-dot" aria-hidden="true"></i> Искать в радиусе 
</button>
 </div>
</form>
</div>