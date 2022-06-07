<?php
/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;

if(Yii::$app->controller->action->id == 'add') {
$this->title = 'Добавить поездку';
$this->params['h1'] = 'Добавить поездку';
$this->registerMetaTag(['name' => 'description','content' => 'Добавить поездку']);
$this->registerMetaTag(['name' => 'keywords','content' => 'Добавить поездку, Попутчики, поездка, автостоп']);
$this->params['breadcrumbs'][] = array('label'=> 'Попутчики', 'url'=>Url::toRoute('/passanger'));
$this->params['breadcrumbs'][] = array('label'=> 'Добавить поездку');
}else{
$this->title = 'Изменить информацию о поездке';
$this->params['h1'] = 'Редактор информации';
$this->registerMetaTag(['name' => 'description','content' => 'Изменить информацию о поездке']);
$this->registerMetaTag(['name' => 'keywords','content' => 'Добавить поездку, Попутчики, поездка, автостоп']);
$this->params['breadcrumbs'][] = array('label'=> 'Попутчики', 'url'=>Url::toRoute('/passanger'));
$this->params['breadcrumbs'][] = array('label'=> 'Изменить информацию о поездке');
}


$this->registerCssFile('/css/add.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/passanger_js/css/style.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/passanger_js/css/add.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/add.js',['depends' => [\yii\web\JqueryAsset::className()]]);
//Обновление Функция координаты в объявлении
$this->registerJsFile('//api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU&apikey='.(string)Yii::$app->caches->setting()['api_key_yandex'],['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/cross-control.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/location-tool.js',['depends' => [\yii\web\JqueryAsset::className()]]);


$this->registerJsFile('/passanger_js/js/yamaps.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/passanger_js/js/script.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
if(isset($model->time)) {
   $dateval = date("H:i", strtotime($model->time));
}else{
	$dateval = '';
}
?>



 <div class="add-left-body">

 <? if(!Yii::$app->user->id) {?>
 
 <script src="//ulogin.ru/js/ulogin.js"></script>

   <div class="col-md-12">
   <div class="alert alert-warning">Войдите в личный кабинет чтобы добавить поездку</div>
  </div>
  
   <br>
 <div class="col-md-12 login-forms">
     <h3>Войти через соцсеть</h3>
     <div id="uLogin" data-ulogin="display=panel;theme=classic;fields=first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=<?=$patch_url.Url::to(['/user/social'])?>;mobilebuttons=0;"></div>
  <br>
 <br>
<a class="btn btn-success" target="_blank" href="<?=Url::to(['/user/index'])?>"><span>Вход / Регистрация через сайт</span></a>
 </div>
 <? }else{?>
 
 
 <br>
 <?php Pjax::begin([ 'id' => 'pjaxContent']); ?>

 <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true], 'enableClientValidation' => false,]);?>

 <div class="button-body">
   <?php Pjax::begin([ 'id' => 'pjaxLogo']); ?>
<div class="form-group field-shop-name">
<label class="control-label" for="shop-name">Свое Фото<br><span class="mb-max">не более 10 мб</span></label>
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
		'actions' => '<div class="file-actions"><div class="file-user-bottom">{delete}</div><div class="clearfix"></div></div>'],					  
		'validateInitialCount'=>true,
		'overwriteInitial'=>false,
		'showUpload'=>false,
		'showClose'=>false,
		'FileTitle'=>false,
		'browseOnZoneClick'=>true,
        'showRemove'=>false,
		'showCancel'=>false,
		'minFileSize'=> 10,
		'maxFileSize'=> 10000,
        'maxFileCount' => 1,
		'showUploadedThumbs' => true, // сохранять ли отображение загруженных миниатюр файлов в окне предварительного просмотра (для загрузки ajax) до тех пор, пока не будет нажата кнопка удаления / очистки. Значения по умолчанию для true. Если установлено значение « falseСледующая партия файлов, выбранных для загрузки», эти миниатюры будут удалены из предварительного просмотра.
        'allowedFileExtensions' => [ 'jpg' , 'gif' , 'png', 'jpeg'],
		 'initialPreview'=> Yii::$app->userFunctions2->filespassangerlogo($dir_name),
		 'initialPreviewAsData'=>true,
		 'initialPreviewConfig' => Yii::$app->userFunctions2->previewconfigpassangerlogo($dir_name),
		    'deleteUrl' => Url::toRoute(['/passanger/del-logo']),
            'uploadUrl' => \yii\helpers\Url::to(['/passanger/upload-logo']),
            'uploadExtraData' => [
            'Upload[dir]' => $dir_name,
            ],	
        ],
  ]);
 
?>
 </div>
</div> 
 <?php Pjax::end(); ?>

   <?= $form->field($model, 'name', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true])->label('Ваше имя') ?><br>

   <?= $form->field($model, 'dir_name')->hiddenInput(['value'=> $dir_name])->label(false);?>
   <?= $form->field($model, 'appliances', ['template' => '{error}{label}<div class="add_chex">{input}</div> '])->radioList(['0'=>'Водитель', '1'=>'Пассажир'])->label('Кто вы <span class="req_val">*</span>') ?>

</div>
<br>
<div class="button-body">
  <?= $form->field($model, 'ot', ['template' => '{error}{label}{input} '])->textInput(['placeholder' => 'Выберите место на карте', 'maxlength' => true, 'readonly'=> 'true'])->label('Откуда едем <span class="req_val">*</span>')?><br>
  <button type="button" class="btn btn-warning btn-ot">Выбрать</button>
</div>
<div class="button-body">
  <?= $form->field($model, 'kuda', ['template' => '{error}{label}{input}'])->textInput(['placeholder' => 'Выберите место на карте','maxlength' => true, 'readonly'=> 'true'])->label('Куда едем <span class="req_val">*</span>')?><br>
  <button type="button" class="btn btn-warning btn-kuda">Выбрать</button>
</div>
<?= $form->field($model, 'coord_ot', ['template' => '{input}'])->hiddenInput() ?>
<?= $form->field($model, 'coord_kuda', ['template' => '{input}'])->hiddenInput() ?>


<div class="button-body">
  <?= $form->field($model, 'phone', ['template' => '{error}{label}{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => Yii::$app->caches->setting()['mask']])->textInput(['maxlength' => true])->label('Телефон <span class="req_val">*</span>') ?><br>

   <?= $form->field($model, 'pol', ['template' => '{error}{label}<div class="add_chex">{input}</div> '])->radioList(['0'=>'Мужчина', '1'=>'Женщина'])->label('Пол <span class="req_val">*</span>') ?>
<br>
   <?= $form->field($model, 'mesta', ['template' => '{error}{label}{input}'])->textInput(['class' => 'price form-control','type'=>'number'])->label('Количество мест <span class="req_val">*</span>') ?><br>
</div>
<div class="button-body close-div">
   <?= $form->field($model, 'price', ['template' => '{error}{label}{input} <i style="font-size: 16px; margin-top: 10px;" class="fa '.$rat['text'].'" aria-hidden="true"></i>'])->textInput(['maxlength' => true, 'class' => 'price form-control','type'=>'number'])->label('Цена') ?><br>

   <?= $form->field($model, 'marka', ['template' => '{error}{label}{input}'])->textInput()->label('Марка авто') ?><br>
</div>

<div class="button-body">
   <?= $form->field($model, 'time', ['template' => '{error}{label}{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => '9999-99-99'])->textInput(['class' => 'form-control  calendarmain1', 'placeholder' => 'год, месяц, день'])->label('Дата выезда <span class="req_val">*</span>');?>
<br>
   <?= $form->field($model, 'clock', ['template' => '{error}{label}{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => '99:99'])->textInput(['value' => $dateval,'placeholder' => '12:00', 'style'=>'max-width: 100px; float: left;'])->label('Время выезда <span class="req_val">*</span>');?>
</div>
<?//echo date("d.m.Y", $model->time); ?>

<div class="hr_add"><i class="fa fa-filter" aria-hidden="true"></i> Примечание.</div> 
<?= $form->field($model, 'text', ['template' => '{error}{label}{input}'])->textarea(['rows' => '5' ])->label(false) ?><br>


<br>
<div class="form-group upcr">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success add-preloader']) ?>
</div>





<?php ActiveForm::end(); ?>	
<?php Pjax::end(); ?>

 <? } ?>

</div>














<!-- Modal -->
<div class="modal fade" id="coord" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">

	  
        <div id="map" style="display: table;width: 100%;"></div>
      </div>

    </div>
  </div>
</div>