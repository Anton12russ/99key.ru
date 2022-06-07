<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
$this->registerCssFile('/coord/radius.css', ['depends' => ['frontend\assets\AppAsset']]);

$this->registerJsFile('/coord/radius.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/cross-control.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/location-tool.js',['depends' => [\yii\web\JqueryAsset::className()]]);


if(!$coord = @Yii::$app->caches->coord()[Yii::$app->request->cookies['region']->value]) {
	$coord = '55.753994, 37.622093';
}else{
	$coord = $coord['coordlat'].','.$coord['coordlon'];
}
if(isset(Yii::$app->request->get()['auction'])) {
	echo '<span class="auction" style="display: none;"></span>';
}
?>




<div class="form-group">

<input data-coord="<?=$coord?>"  data-radius="1" type="text" id="suggest" type="text" class="form-control" placeholder="Улица или населенный пункт">
</div>

<div id="YMapsIDadd"></div>


<h5>Радиус в километрах</h3>
<div class="form_radio_group radius">
	<div class="form_radio_group-item">
		<input data-radius="1" id="radio-1" type="radio" name="radio" checked>
		<label for="radio-1">1</label>
	</div>
	<div class="form_radio_group-item">
		<input data-radius="5" id="radio-2" type="radio" name="radio">
		<label for="radio-2">5</label>
	</div>
	<div class="form_radio_group-item">
		<input data-radius="10" id="radio-3" type="radio" name="radio">
		<label for="radio-3">10</label>
	</div>
	<div class="form_radio_group-item">
		<input data-radius="25" id="radio-4" type="radio" name="radio">
		<label for="radio-4">25</label>
	</div>
	
	<div class="form_radio_group-item">
		<input data-radius="50" id="radio-5" type="radio" name="radio">
		<label for="radio-5">50</label>
	</div>
	
	<div class="form_radio_group-item">
		<input data-radius="100" id="radio-6" type="radio" name="radio">
		<label for="radio-6">100</label>
	</div>
	
	<div class="form_radio_group-item">
		<input data-radius="200" id="radio-7" type="radio" name="radio">
		<label for="radio-7">200</label>
	</div>
	
	<div class="form_radio_group-item">
		<input data-radius="300" id="radio-8" type="radio" name="radio">
		<label for="radio-8">300</label>
	</div>
	
	<div class="form_radio_group-item">
		<input data-radius="400" id="radio-9" type="radio" name="radio">
		<label for="radio-9">400</label>
	</div>
</div>

<button class="btn btn-info btn-coord">Показать<span class="coord-count"></span></button>