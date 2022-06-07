<?php
/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
use vova07\imperavi\Widget;
$this->title = $meta['title'];
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $meta['keywords']]);
$this->params['breadcrumbs'][] = $meta['breadcrumbs'];
$this->params['h1'] = $meta['h1'];

$this->registerCssFile('/css/add.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/add.js',['depends' => [\yii\web\JqueryAsset::className()]]);
//Обновление Функция координаты в объявлении
$this->registerJsFile('//api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU&apikey='.(string)Yii::$app->caches->setting()['api_key_yandex'],['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/cross-control.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/location-tool.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/yamaps.js',['depends' => [\yii\web\JqueryAsset::className()]]);
foreach($rates as $res) {
	if ($res['value'] == 1) {
		$rates_cat_val = $res['text'];
		$rates_cat_name = $res['name'];
	}
}
?>

<?
$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<style>
.redactor-toolbar {
	z-index: 1;
}
</style>
 <div class="add-left-body">
 <!--Условие при успешном размещении-->
<?php Pjax::begin([ 'id' => 'pjaxContent']); ?>
<? if (!$save) {?>
<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true,], 'enableClientValidation' => false,]);?>
<?= $form->field($model, 'dir_name')->hiddenInput(['value'=> $dir_name])->label(false);?>




<!--Поля объявления-->
<div class="hr_add"><i class="fa fa-square-info" aria-hidden="true"></i> Основная информация</div>
<?= $form->field($model, 'title', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true])->label('Заголовок <span class="req_val">*</span>')?><br>

<?php if($time) { ?> <?= $form->field($model, 'date_del', ['template' => '{error}{label}{input}'])->dropDownList($time)?><?php } ?><br>


<div class="hr_add"><i class="fa fa-map-location-dot" aria-hidden="true"></i> Мето сделки</div>
<?= $form->field($model, 'region', ['template' => '{input}'])->textInput(['type' => 'hidden']) ?>

<!--Обновление координаты--->
<?= $form->field($model, 'coordlat', ['template' => '{input}'])->textInput(['id' => 'coord-lat','type' => 'hidden'])->label('') ?>
<?= $form->field($model, 'coordlon', ['template' => '{input}'])->textInput(['id' => 'coord-lon','type' => 'hidden'])->label('') ?>
<?= $form->field($model, 'address', ['template' => '{error}{label}{input}'])->textInput(['id' => 'suggest', 'placeholder' =>'Введите город или точный адрес', 'autocomplete' => 'off'])->label('Адрес <span class="req_val">*</span>')?>
<details>
	<summary>Посмотреть карту <i class="fa fa-map-location-dot" aria-hidden="true"></i></summary>
	<div id="YMapsIDadd"></div>
</details>

<div class="hr_add"><i class="fa fa-sitemap" aria-hidden="true"></i> Категория и дополнительная информация</div>
<?= $form->field($model, 'category', ['template' => '{error}{label}{input}'])->textInput(['class' => 'catchang','type' => 'hidden'])->label('Категория <span class="req_val">*</span>') ?><br>





<?php Pjax::begin([ 'id' => 'pjaxFields']); ?>

<?php if (isset($price_category['price']) && $price_category['price'] > 0) {?>
      <div class="alert alert-info sum-info" data-sum="<?=$price_category['sum']?>">Размещение в эту рубрику, платное. <br>Стоимость активации, <strong><span class="price_category"><?=$price_category['price']?></span> <i class="fa <?=$rates_cat_val?>" aria-hidden="true"></i></strong>, на указанный срок (<span class="days"><?=$price_category['tyme']?></span> дней)</div>
<?php } ?>

<? foreach($model_view as $res) {?>
    <? if ($res['type'] == 'v') {?>   <!--Text-->
        <!--Условие, если тип строки (Телефон или факс)-->
        <? if ($res['type_string'] == 't' || $res['type_string'] == 'x') {?>
              <?= $form->field($model2, 'f_'.$res['id'], ['template' => '{error}{label}{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => Yii::$app->caches->setting()['mask']])->textInput(['maxlength' => true])->label($res['name'].Yii::$app->userFunctions->req($res['req'])) ?><br>
        <? }elseif($res['type_string'] == 'u'){ ?>
		 <?= $form->field($model2, 'f_'.$res['id'], ['template' => '{error}{label}{input}'])->textInput(['placeholder' =>'https://'.$_SERVER['HTTP_HOST'] ,'maxlength' => true])->label(($res['name'].Yii::$app->userFunctions->req($res['req']))) ?><br>
		 <? }else{ ?>
              <?= $form->field($model2, 'f_'.$res['id'], ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true])->label(($res['name'].Yii::$app->userFunctions->req($res['req']))) ?><br>
        <? }?>
    <? }?> 
     
	

<? if ($res['type'] == 's') {?>   <!--Select-->
   <?= $form->field($model2, 'f_'.$res['id'], ['template' => '{error}{label}{input}'])->dropDownList(Yii::$app->userFunctions->arrayval($res['values']), ['prompt' => 'Выбрaть'] )->label($res['name'].Yii::$app->userFunctions->req($res['req'])) ?><br>
<? }?>



<? if ($res['type'] == 't') {?>   <!--Textarea-->
   <?= $form->field($model2, 'f_'.$res['id'], ['template' => '{error}{label}{input}'])->textArea(['rows' => '6','maxlength' => true])->label(($res['name'].Yii::$app->userFunctions->req($res['req']))) ?><br>
<? }?>



<? if ($res['type'] == 'r') {?>   <!--Radio-->
  <?= $form->field($model2, 'f_'.$res['id'], ['template' => '{error}{label}<div class="add_chex">{input}</div>'])->radioList(Yii::$app->userFunctions->arrayval($res['values']), ['prompt' => 'Выбрaть'] )->label($res['name'].Yii::$app->userFunctions->req($res['req'])) ?><br>
<? }?>


<?  if ($res['type'] == 'c') {?>   <!--Сheckbox-->
 <?= $form->field($model2, 'f_'.$res['id'], ['template' => '{error}{label}<div class="add_chex">{input}</div>'])->checkboxList(Yii::$app->userFunctions->arrayche($res['values']))->label($res['name'].Yii::$app->userFunctions->req($res['req'])) ?><br>
<? }?>

<? if ($res['type'] == 'y') {?>   <!--Ютуб-->
        <?= $form->field($model2, 'f_'.$res['id'], ['template' => '{error}{label}{input}'])->textInput(['placeholder' =>'https://www.youtube.com/watch?v=QFZm92Qno-I' ,'maxlength' => true])->label($res['name'].Yii::$app->userFunctions->req($res['req'])) ?><br>
<? }?>




<? if ($res['type'] == 'j') {?>   <!--Выбираем Координаты с адресом, чтобы поля были вместе-->
<? $this->registerJsFile('/js/maps.js',['depends' => [\yii\web\JqueryAsset::className()]]);?>



   <?= $form->field($model2, 'f_'.$res['id'].'_address')->textInput(['maxlength' => true,'placeholder' =>'г. Москва, ул.Чехова, д. №1', 'class'=> 'form-control Address', 'id' => 'address'])->label(('Адрес на карте'.Yii::$app->userFunctions->req($res['req']))) ?>
   <?= $form->field($model2, 'f_'.$res['id'], ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true, 'type'=> 'hidden', 'class'=> 'coordin'])->label('') ?>
    <div id="myMap" style="width: 100%; height: 300px"></div>
	<br>
<? }?>



<? if ($res['type'] == 'p') {?>  <!--Price-->
<? $price = $form->field($model2, 'f_'.$res['id'].'_rates', ['options' => ['tag' => false]], ['template' => '{input}'])->dropDownList(Yii::$app->userFunctions->arrayrates($rates),['class' => 'rates form-control'])->label(false) ?>
<?= $form->field($model2, 'f_'.$res['id'], ['template' => '{error}{label}{input}'.$price])->textInput(['maxlength' => true, 'class' => 'price form-control'])->label(($res['name'].Yii::$app->userFunctions->req($res['req']))) ?><br>






<?if(Yii::$app->userFunctions->board_shop() == true) {?>
<?
$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>


   <? $disc = $form->field($model, 'discount_text', ['options' => ['tag' => false]], ['template' => '{input}'])->textarea(['maxlength' => true,'type'=>'number', 'placeholder' => 'Описание скидки.       Пример: скидка до 1 мая ',  'class' => 'rates form-control' ,'style'=> 'margin-right: 1%; width: 38%; height: 78px;'])->label(false)?><br>
   <? $datedesc =  $form->field($model, 'discount_date', ['options' => ['tag' => false]], ['template' => '{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => '9999-99-99'])->textInput(['placeholder' => 'дата окончания', 'class' => 'form-control datepicker12' ,'style'=> 'width: 100%; margin-top: 10px;'])->label(false);?>
   <?= $form->field($model, 'discount', ['template' => '{error}{label}<div style="
    display: table;
    float: left;
    width: 30%;
	margin-right: 1%;
">{input}'.$datedesc.'</div>'.$disc])->textInput(['maxlength' => true,'type'=>'number', 'placeholder' => 'Цена',  'class' => 'price form-control','style'=> 'width: 100%; '])->label('Цена с учетом скидки')?><br>
<?}?>







<? }?>


<? }?>
<?php Pjax::end(); ?>

<?if(Yii::$app->userFunctions->board_shop() == true) {?>
<div class="hr_add"><i class="fa fa-shop" aria-hidden="true"></i> Информация для магазина</div>	
 <?= $form->field($model, 'count', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true,'type'=>'number' ,  'style' => 'max-width: 100px; float: left;'])->label('Количество товара')?><br>
<?}?>

<div class="hr_add"><i class="fa fa-filter" aria-hidden="true"></i> Описание</div>
 <?= $form->field($model, 'text')->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
		'formatting' => [
		'h1','h2','p','blockquote'
		],
        'plugins' => [
            'clips',
            'fullscreen',
			'video',
			'fontcolor',
			'fontfamily',
			'fontsize',
        ],
		'imageUpload' => \yii\helpers\Url::to(['/ajax/save-redactor-img','sub'=>'article']),
		'imageDelete' => \yii\helpers\Url::to(['/ajax/save-img-del']),
        'clips' => [
            ['Красный', '<span class="label-red">Здесь вставить текст</span>'],
            ['Зеленый', '<span class="label-green">Здесь вставить текст</span>'],
            ['Голубой', '<span class="label-blue">Здесь вставить текст</span>'],
        ],
    ],
])->label(false)?>
<div class="hr_add"><i class="fa fa-filter" aria-hidden="true"></i> Фото </div>
<div class="red_maxi">Максимум <?=Yii::$app->caches->setting()['max_photo_count']?> фото</div>
<div class="mb-max"><i class="fa fa-exclamation" aria-hidden="true"></i>  не более <?=Yii::$app->caches->setting()['max_photo_mb']/1000?> Мб</div>
<div class="row">
<div class="col-md-12">
<div class="dop-input">
<?php Pjax::begin([ 'id' => 'pjaxFile']); ?>
<? 
if (isset($_POST['dir_name'])) {$dir_name = $_POST['dir_name'];} 
echo  \kartik\file\FileInput::widget([
   'name' => 'Upload[file]',
   'id' => 'input-id',
        'options'=>[
            'multiple'=>true,
	      ],
        'pluginOptions' => [
			 'layoutTemplates' => [
                                    'footer' => '<div class="file-thumbnail-footer">
                                                      {progress} {actions}
                                                 </div>',
								 
					                'actions' => '
		                                       <div class="file-actions">
                                                <div class="file-user-bottom">
                                                  {delete} {zoom}
                                                </div>
                                               <div class="clearfix"></div>
                                               </div><script>progress_open();</script>'
                                      ],
									  
		'validateInitialCount'=>true,
		'overwriteInitial'=>false,
		'showUpload'=>false,
		'showClose'=>false,
		'FileTitle'=>false,
		'browseOnZoneClick'=>true,
        'showRemove'=>false,
		'showCancel'=>false,
		'minFileSize'=> 10,
		'maxFileSize'=>Yii::$app->caches->setting()['max_photo_mb'],
        'maxFileCount' => Yii::$app->caches->setting()['max_photo_count'],
		
		'showUploadedThumbs' => true, // сохранять ли отображение загруженных миниатюр файлов в окне предварительного просмотра (для загрузки ajax) до тех пор, пока не будет нажата кнопка удаления / очистки. Значения по умолчанию для true. Если установлено значение « falseСледующая партия файлов, выбранных для загрузки», эти миниатюры будут удалены из предварительного просмотра.
        'allowedFileExtensions' => [ 'jpg' , 'gif' , 'png'],
		 'initialPreview'=> Yii::$app->userFunctions->files($dir_name),
		 'initialPreviewAsData'=>true,
		'initialPreviewConfig' => Yii::$app->userFunctions->previewconfig($dir_name),

		    'deleteUrl' => Url::toRoute(['/upload/del']),
            'uploadUrl' => \yii\helpers\Url::to(['/upload/upload']),
            'uploadExtraData' => [
            'Upload[dir]' => $dir_name,
            ],	
			
		  'pluginEvents' => [
            'filesorted' => new \yii\web\JsExpression('function(event, params){
                  $.post("'.Url::toRoute(["/upload/sort","id"=>$model->id]).'",{sort: params});
            }')
        ],
        ],
  ]);
?>

 <?php Pjax::end(); ?>
<br>
</div>
</div>
</div>





<? if (Yii::$app->user->can('updateBoard')) { ?>
<br>
<br>
<div class="moder_panel">
<h3>Панель администрации</h3>
<div class="user-moder">
<strong>Данные пользователя</strong><br>
Email: <?=$user->email?><br>
Имя: <?=$user->username?><br>
Баланс: <?=$user->balance?> (<?=$rates_cat_name?>)<br>
</div>
<br>
<?= $form->field($model, 'status_id', ['template' => '{error}{label}{input}'])->radioList([
        '0' => 'На модерации',
        '1' => 'Опубликован',
    ])->label('Статус <span class="req_val">*</span>') ?>
<br>
<?= $form->field($model, 'active', ['template' => '{error}{label}{input}'])->radioList([
        '0' => 'Ожидат оплаты',
        '1' => 'Активирован',
    ])->label('Активация <span class="req_val">*</span>') ?>
<br>

<?= $form->field($model, 'balance_minus', ['template' => '{error}{label}{input}'])->textInput()->label('Списать средства с баланса, отобразить в выписке');?>
</div>
<? } ?>



<div class="form-group upcr">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success add-preloader']) ?>
</div>


<?php ActiveForm::end(); ?>	



<? } ?>






<!--Сообщение при успешной подаче объявления.-->
<? if ($save) {?>
   <?=$this->render('add_save', [
	    'save' => $save,
		'price_category' => $price_category,
		'payment' => $payment,
		'rates_cat_val' => $rates_cat_val
    ]); 
	?>
<? } ?> 	



<?php Pjax::end(); ?>

</div>

<script>

function params_sort() {
var arr = '<?php echo \yii\helpers\Url::to(['/upload/sort']);?>?dir_id=<?php echo $dir_name;?>';
return arr;
}
</script>