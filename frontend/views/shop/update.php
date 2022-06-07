<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;


$this->title = $meta['title'];
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $meta['keywords']]);
$this->params['breadcrumbs'][] = $meta['breadcrumbs'];

$this->registerCssFile('/css/add.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/shop.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/addshop.js',['depends' => [\yii\web\JqueryAsset::className()]]);

//Обновление Функция координаты в объявлении
$this->registerJsFile('//api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU&apikey='.(string)Yii::$app->caches->setting()['api_key_yandex'],['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/cross-control.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/location-tool.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/yamapshop.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$minus = Yii::$app->caches->setting()['price-shop'] - Yii::$app->user->identity->balance;


		
		if (Yii::$app->user->can('updateShop')) {
$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
}

$arr = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье');
?>

<div class="add-left-body">
 <h1><?=$meta['h1']?></h1>
 <? if (isset($_GET['save'])) {?> 
    <div class="alert alert-success">
	     Ваш магазин сохранен.
       <? if(Yii::$app->caches->setting()['moder-shop'] == '1') {?>
	     Он будет опубликован после проверки.
      <?}?>
    </div>
 <?}else{?>
 
 
 <!--Условие при успешном размещении-->

		
<?php Pjax::begin([ 'id' => 'pjaxContent']); ?>
<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true,], 'enableClientValidation' => false,]);?>
 

<? if ($print) {?>
	 <div class="alert alert-danger has-error">
 <? foreach($print[0] as $result) {?>
       <? foreach($result as $res) {?>
          <?=$res?><br>
       <?}?>
  <?}?>
  </div>
  <br>
<?}?>
  
  <?php Pjax::begin([ 'id' => 'pjaxLogo']); ?>
<div class="form-group field-shop-name">
<label class="control-label" for="shop-name">Логотип магазина<br><strong>(200x80)</strong> px<br><span class="mb-max">не более 1 мб</span></label>


 <div class="logo">
<? 

if (isset($_POST['dir_name'])) {$dir_name = $_POST['dir_name'];} 
echo  \kartik\file\FileInput::widget([
   'name' => 'Upload[logo]',
   'id' => 'input-logo',
        'pluginOptions' => [
		'dropZoneTitle' => 'Пененесите сюда логотип',
		'layoutTemplates' => [
        'footer' => '<div class="file-thumbnail-footer">{progress} {actions} </div>',
		'actions' => '<div class="file-actions"><div class="file-user-bottom">{delete}</div><div class="clearfix"></div></div>
		<script>progress_open();</script>
		'],					  
		'validateInitialCount'=>true,
		'overwriteInitial'=>false,
		'showUpload'=>false,
		'showClose'=>false,
		'FileTitle'=>false,
		'browseOnZoneClick'=>true,
        'showRemove'=>false,
		'showCancel'=>false,
		'minFileSize'=> 10,
		'maxFileSize'=> 1000,
        'maxFileCount' => 1,
		'showUploadedThumbs' => true, // сохранять ли отображение загруженных миниатюр файлов в окне предварительного просмотра (для загрузки ajax) до тех пор, пока не будет нажата кнопка удаления / очистки. Значения по умолчанию для true. Если установлено значение « falseСледующая партия файлов, выбранных для загрузки», эти миниатюры будут удалены из предварительного просмотра.
        'allowedFileExtensions' => [ 'jpg' , 'gif' , 'png', 'jpeg'],
		 'initialPreview'=> Yii::$app->userFunctions->filesshoplogo($dir_name),
		 'initialPreviewAsData'=>true,
		 'initialPreviewConfig' => Yii::$app->userFunctions->previewconfigshoplogo($dir_name),
		    'deleteUrl' => Url::toRoute(['/uploadshop/del-logo']),
            'uploadUrl' => \yii\helpers\Url::to(['/uploadshop/upload-logo']),
            'uploadExtraData' => [
            'Upload[dir]' => $dir_name,
            ],	
        ],
  ]);
?>
 </div>
</div> 
 <?php Pjax::end(); ?>
 
  
  <?= $form->field($model, 'dir_name')->hiddenInput(['value'=> $dir_name])->label(false);?>
  
  <?= $form->field($model, 'name', ['template' => '{error}{label}{input}'])->textInput()->label('Название магазина <span class="req_val">*</span>');?>
  <br>
  <?= $form->field($model, 'domen', ['template' => '{error}{label}{input}'])->textInput(['placeholder' => 'magazin', 'autocomplete' => 'off'])->label('Название на латинице <span class="req_val">*</span>');?> 
   <div class="alert-left"></div><div data-text="Личная ссылка на магазин будет" data-domen="<?=$_SERVER['HTTP_HOST']?>" class="alert-domen"></div>
   <br>
 
  
<div class="hr_add"><i class="fa fa-map-location-dot" aria-hidden="true"></i> Метоположение</div>
<?= $form->field($model, 'region', ['template' => '{input}'])->hiddenInput()->label('') ?>
<br>
    <?= $form->field($model, 'address', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true,'placeholder' =>'г. Москва, ул.Чехова, д. №1', 'class'=> 'form-control Address','id' => 'suggest'])->label('Адрес магазина') ?>
    <?= $form->field($model, 'coord', ['template' => '{error}{label}{input}'])->hiddenInput(['maxlength' => true,  'class'=> 'coordin'])->label('') ?>
   
    <div id="YMapsIDadd" style="width: 100%; height: 300px"></div>
<br>


<div class="hr_add"><i class="fa fa-sitemap" aria-hidden="true"></i> Категория</div>
<?= $form->field($model, 'category', ['template' => '{error}{label}{input}'])->textInput(['class' => 'catchang','type' => 'hidden'])->label('Категория <span class="req_val">*</span>') ?>

<div class="hr_add"><i class="fa fa-filter" aria-hidden="true"></i> Дополнительная информация</div>

<?= $form->field($model, 'phone', ['template' => '{error}{label}{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => Yii::$app->caches->setting()['mask']])->textInput()->label('Контактный телефон <span class="req_val">*</span>');?>
 <br>
<?= $form->field($model, 'site', ['template' => '{error}{label}{input}'])->textInput(['placeholder' => 'https://yandex.ru'])->label('Адрес сайта');?>
 <br>
<?= $form->field($model, 'text', ['template' => '{error}{label}{input}'])->textarea(['rows' => 3, 'class' => 'form-control shop_input'])->label('Краткое описание деятельности <span class="req_val">*</span>') ?>
<br>
<?= $form->field($model, 'payment', ['template' => '{error}{label}{input}'])->textarea(['rows' => 3, 'class' => 'form-control shop_input','placeholder'=>'Информация о доступных способах оплаты, будет отображена во вкладке "Оплата" в вашем магазине.'])->label('Прием платежей <span class="req_val">*</span>') ?>
<br>
<?= $form->field($model, 'delivery', ['template' => '{error}{label}{input}'])->textarea(['rows' => 3, 'class' => 'form-control shop_input'])->label('Информация о доставке <span class="req_val">*</span>') ?>
<br>
<?= $form->field($model, 'pay_delivery', ['template' => '{error}{label}{input}'])->textInput(['rows' => 3, 'class' => 'form-control shop_input','type' => 'number','placeholder'=>'Стоимость фиксированной доставки ('.$rates['name'].')'])->label('Стоимость доставки') ?>
<br>
<?= $form->field($model, 'private_payment', ['template' => '{error}{label}{input}'])->textarea(['rows' => 3, 'class' => 'form-control shop_input','placeholder'=>'Информация, согласно которой вы будете принимать платежи за товар в частном порядке. Будет отображена в качестве подсказки в корзине.'])->label('Договор приема платежей частного порядка <span class="req_val">*</span>') ?>
<br>
  <?= $form->field($model, 'сhoice_pay')
               ->radioList([
               '0' => 'Оплата через "Гарант-Сервис"',
               '1' => 'Прием платежа в частном порядке',
			   '2' => 'Оба варианта'
			   ])->label('Варианты платежей <span class="req_val">*</span>'); ?>	
<br>

<div class="form-group field-shop-delivery required">
<div class="help-block"></div><label class="control-label" for="shop-delivery">График работы <span class="req_val">*</span></label>

  <table class="table table-bordered graf-table" style="width: 70%">
     <thead>
        <tr>

          <th>День</th>
          <th>Начало</th>
          <th>Конец</th>
		  <th>Обед</th>
		  <th>Выходной</th>
        </tr>
      </thead>
      <tbody>
	 <?

	 foreach($arr as  $key => $res ) {?>
        <tr class="<?if (isset($model->grafik_arr[$key]['vih'])) {?>opacity<? }?>">
          <td><?=$res?>: </td>
          <td><?= $form->field($model, 'grafik_arr['.$key.'][ot]', ['template' => '{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => '99:99'])->textInput(['class' => 'form-control', 'placeholder' => '08:00'])->label(false) ?></td>
          <td><?= $form->field($model, 'grafik_arr['.$key.'][do]', ['template' => '{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => '99:99'])->textInput(['class' => 'form-control', 'placeholder' => '18:00'])->label(false) ?></td>
		  <td class="obed"><?= $form->field($model, 'grafik_arr['.$key.'][obed-on]', ['template' => '{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => '99:99'])->textInput(['class' => 'form-control', 'placeholder' => '00:00'])->label(false) ?><span> - </span><?= $form->field($model, 'grafik_arr['.$key.'][obed-do]', ['template' => '{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => '99:99'])->textInput(['class' => 'form-control', 'placeholder' => '00:00'])->label(false) ?></td>
          <td><?=$form->field($model, 'grafik_arr['.$key.'][vih]', ['template' => '{input}'])->checkbox(['label'=>false])->label('');?></td>
		</tr>
	 <? }?>	
      </tbody>
    </table>
</div>

<br>
<?= $form->field($model, 'stocks', ['template' => '{error}{label}{input}'])->textarea(['rows' => 3, 'class' => 'form-control shop_input']) ?>
<br>
<?php /* Pjax::begin([ 'id' => 'pjaxFile']); ?>
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
                                               </div>
											   <script>progress_open();</script>'
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
		 'initialPreview'=> Yii::$app->userFunctions->filesshop($dir_name),
		 'initialPreviewAsData'=>true,
		 'initialPreviewConfig' => Yii::$app->userFunctions->previewconfigshop($dir_name),

		    'deleteUrl' => Url::toRoute(['/uploadshop/del']),
            'uploadUrl' => \yii\helpers\Url::to(['/uploadshop/upload']),
            'uploadExtraData' => [
            'Upload[dir]' => $dir_name,
            ],	
			
		  'pluginEvents' => [
            'filesorted' => new \yii\web\JsExpression('function(event, params){
                  $.post("'.Url::toRoute(["/uploadshop/sort","id"=>$model->id]).'",{sort: params});
            }')
        ],
        ],
  ]);
?>
<?php Pjax::end(); */?>
<br>


<? if (Yii::$app->user->can('updateShop')) { ?>
<br>
<br>
<div class="moder_panel">
<h3>Панель администрации</h3>
<div class="user-moder">
<strong>Данные пользователя</strong><br>
Email: <?=$user->email?><br>
Имя: <?=$user->username?><br>
Баланс: <?=$user->balance?> (<?=$rates['name']?>)<br>
</div>
<br>
<?= $form->field($model, 'status', ['template' => '{error}{label}{input}'])->radioList([
        '0' => 'На модерации',
        '1' => 'Опубликован',
    ])->label('Статус <span class="req_val">*</span>') ?>
<br>
<?= $form->field($model, 'active', ['template' => '{error}{label}{input}'])->radioList([
        '0' => 'Ожидат оплаты',
        '1' => 'Активирован',
    ])->label('Активация <span class="req_val">*</span>') ?>
<br>

<?= $form->field($model, 'date_end', ['template' => '{error}{label}{input}'])->textInput(['class' => 'form-control  datepicker'])->label('Дата выключения <span class="req_val">*</span>');?>

<?= $form->field($model, 'balance_minus', ['template' => '{error}{label}{input}'])->textInput()->label('Списать средства с баланса, отобразить в выписке');?>
<br>
</div>
<? } ?>






<?php Pjax::begin([ 'id' => 'pjaxPrice']);  ?>

<div class="form-group field-shop-name">
<label class="control-label" for="shop-name">Добавить прайс-лист "xls, xlsx, doc, docx, pdf"<br><span class="mb-max">не более 100 мб</span></label>
 <div class="logo" style="float: left;">
<? 
if (isset($_POST['dir_name'])) {$dir_name = $_POST['dir_name'];}
echo  \kartik\file\FileInput::widget([
   'name' => 'Upload[file]',
   'id' => 'input-file',
        'pluginOptions' => [
		'dropZoneTitle' => 'Пененесите сюда файл',
		'layoutTemplates' => [
        'footer' => '<div class="file-thumbnail-footer">{progress} {actions} </div>',
		'actions' => '<div class="file-actions"><div class="file-user-bottom">{delete}</div><div class="clearfix"></div></div>'],					  
		'validateInitialCount'=>true,
		'overwriteInitial'=>false,
		'showUpload'=>false,
		'showClose'=>false,
		'FileTitle'=>false,
		'browseOnZoneClick'=>true,
        'showRemove'=>false,
		'showCancel'=>false,
		'minFileSize'=> 1,
		'maxFileSize'=> 100000,
        'maxFileCount' => 1,
		'showUploadedThumbs' => true, // сохранять ли отображение загруженных миниатюр файлов в окне предварительного просмотра (для загрузки ajax) до тех пор, пока не будет нажата кнопка удаления / очистки. Значения по умолчанию для true. Если установлено значение « falseСледующая партия файлов, выбранных для загрузки», эти миниатюры будут удалены из предварительного просмотра.
        'allowedFileExtensions' => ['xlsx', 'xls', 'doc', 'docx', 'pdf'],
		'preferIconicPreview' => true, // this will force thumbnails to display icons for following file extensions
		'previewFileIconSettings' => [
        'xlsx' => '<i class="fa fa-file-excel-o" aria-hidden="true" style="color: #53c10e; font-size: 120px;"></i>',  
        'xls' => '<i class="fa fa-file-excel-o" aria-hidden="true" style="color: #53c10e; font-size: 120px;"></i>', 
        'doc' => '<i class="fa fa-file-text-o" aria-hidden="true" style="color: #53c10e; font-size: 120px;"></i>',  
        'docx' => '<i class="fa fa-file-text-o" aria-hidden="true" style="color: #53c10e; font-size: 120px;"></i>',
        'pdf' => '<i class="fa fa-file-pdf-o" aria-hidden="true" style="color: #53c10e; font-size: 120px;"></i>',		
         ],

		
		'initialPreviewAsData'=>true,
		'initialPreview'=> Yii::$app->userFunctions2->filesshopprice($dir_name),
		'initialPreviewConfig' => Yii::$app->userFunctions2->previewconfigshopprice($dir_name),
		
		    'deleteUrl' => Url::toRoute(['/uploadshop/del-logo']),
            'uploadUrl' => \yii\helpers\Url::to(['/uploadshop/upload-file']),
            'uploadExtraData' => [
            'Upload[dir]' => $dir_name,
            ],	
        ],
  ]);
  
?>
 </div>
</div> 
<?php Pjax::end(); ?>












<? if (!Yii::$app->user->can('updateShop')) { ?>
<? if (Yii::$app->caches->setting()['capcha'] == 1) { ?>

<div class="capcha">
       <?= $form->field($model, 'reCaptcha',['template' => '{error}{input}'])->widget(\himiklab\yii2\recaptcha\ReCaptcha2::className(),['siteKey' => preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['recapcha2'])[0])])->label(false) ?>
</div>
<? } ?>

<? if (Yii::$app->caches->setting()['capcha'] == 2) { ?>
   <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha3::className(),['siteKey' => preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['recapcha3'])[0]),'action' => 'blog/add'])->label(false) ?>
<? } ?>
<? } ?>


<div class="form-group upcr">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success add-preloader']) ?>
</div>


<?php ActiveForm::end(); ?>	
<?php Pjax::end(); ?>


<?php } ?>
</div>

<script>

function params_sort() {
var arr = '<?php echo \yii\helpers\Url::to(['/uploadshop/sort']);?>?dir_id=<?php echo $dir_name;?>';
return arr;
}
</script>