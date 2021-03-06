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

$this->registerJsFile('/js/searchcat.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/css/add.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/add.js',['depends' => [\yii\web\JqueryAsset::className()]]);
//Обновление Функция координаты в объявлении
$this->registerJsFile('//api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU&apikey='.(string)Yii::$app->caches->setting()['api_key_yandex'],['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/cross-control.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/location-tool.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/yamaps.js',['depends' => [\yii\web\JqueryAsset::className()]]);
if(isset($save) && $save) {
   $save['update'] = true;
}
$shopfield = Yii::$app->userFunctions3->blogShop(Yii::$app->user->id);
$model->text = strip_tags($model->text);
?>
<?
$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
        if($model->category) {
          $catin = '<div class="catok">Выбрана категория: <strong>'.Yii::$app->caches->category()[$model->category]['name'].'</strong></div>';
          }else{
          $catin = false;
          }


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
<? if($model->check()){?>
  <?= $form->field($model, 'key', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true])->label('Секретный Ключ <span class="req_val">*</span>')?>
  <br>
<? } ?>


<!--Поля объявления-->
<div class="hr_add"><i class="fa fa-square-info" aria-hidden="true"></i> Основная информация</div>
<?= $form->field($model, 'title', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true, 'class'=>'form-control'])->label('Заголовок <span class="req_val">*</span>')?>
<br>


<?if(isset($shopfield) && $shopfield) {
	$coord = explode(',',$shopfield->field->coord);
	$model->region = $shopfield->region;
	$model->coordlat = $coord[0];
	$model->coordlon = $coord[1];
	$model->address = $shopfield->field->address;
  } ?>  
  
<!--<div class="hr_add"><i class="fa fa-map-location-dot" aria-hidden="true"></i> Мето сделки</div>-->
<?= $form->field($model, 'region', ['template' => '{input}'])->textInput(['type' => 'hidden', 'class' => 'form-control region-hidden']) ?>

<!--Обновление координаты--->
<?= $form->field($model, 'coordlat', ['template' => '{input}'])->textInput(['id' => 'coord-lat','type' => 'hidden'])->label('') ?>
<?= $form->field($model, 'coordlon', ['template' => '{input}'])->textInput(['id' => 'coord-lon','type' => 'hidden'])->label('') ?>
<?= $form->field($model, 'address', ['template' => '{error}{label}{input}'])->textInput(['id' => 'suggest', 'placeholder' =>'Введите город или точный адрес', 'autocomplete' => 'off'])->label('Адрес <span class="req_val">*</span>')?>

<details>
	<summary>Посмотреть карту <i class="fa fa-map-location-dot" aria-hidden="true"></i></summary>
	<div id="YMapsIDadd"></div>
</details>


<!---------------------------Блок с выбором категорий--------------------------------------->
<div class="cat-st"></div>
<div class="form-group">
<label class="control-label">
  Категория
</label>
<input type="text" class="form-control blog-title" value="<?=Yii::$app->caches->category()[$model->category]['name']?>" maxlength="150" aria-required="true" <?if($catin){echo 'style="display: none;"';}?> >
<?=$catin?>
</div>


<br>
<?= $form->field($model, 'category', ['template' => '{input}'])->hiddenInput(['maxlength' => true, 'class'=>'form-control blog-category'])->label(false)?>

<?php Pjax::begin([ 'id' => 'pjaxFields']); ?>
   <?php if (isset($price_category['price']) && $price_category['price'] > 0) {?>
         <div class="alert alert-info sum-info" data-sum="<?=$price_category['sum']?>" style="margin-top: 0px;">Размещение в эту рубрику, платное. <br>Стоимость активации, <strong><span class="price_category"><?=$price_category['price']?></span> <i class="fa <?=$rates_cat_val?>" aria-hidden="true"></i></strong>, на указанный срок (<span class="days"><?=$price_category['tyme']?></span> дней)</div>
   <?php } ?>
<?php Pjax::end(); ?>
<!---------------------------Блок с выбором категорий--------------------------------------->


<?= $form->field($model, 'phone', ['template' => '{error}{label}{input}'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => Yii::$app->caches->setting()['mask']])->textInput()->label('Телефон <span class="req_val">*</span>');?>
 <br><?=$form->field($model, 'price', ['template' => '{error}{label}{input}'])->textInput(['maxlength' => true])->label('Цена')?><br>


<div class="hr_add"><i class="fa fa-filter" aria-hidden="true"></i> Описание</div>


<?= $form->field($model, 'text', ['template' => '{error}{label}{input}'])->textarea(['rows' => 6])->label(false)?>


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
                                               </div>'
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
        'allowedFileExtensions' => [ 'jpg' , 'gif' , 'png', 'jpeg'],
		 'initialPreview'=> Yii::$app->userFunctions2->files($dir_name),
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

<br>
<?$model->status_id_false = $model->status_id;?>
<?$model->active_false = $model->active;?>
<?= $form->field($model, 'status_id_false', ['template' => '{error}{label}{input}'])->radioList([
        '0' => 'На модерации',
        '1' => 'Опубликован',
		        '2' => 'Снят с публикации',
    ])->label('Статус <span class="req_val">*</span>') ?>
<br>
<?= $form->field($model, 'active_false', ['template' => '{error}{label}{input}'])->radioList([
        '0' => 'Ожидат оплаты',
        '1' => 'Активирован',
    ])->label('Активация <span class="req_val">*</span>') ?>
<br>
</div>
<? } ?>







<div class="form-group upcr">
    <?= Html::submitButton('Опубликовать', ['class' => 'btn btn-success add-preloader', 'style' => 'width: 100%;height: 50px;background:#f50c0c;']) ?>
</div>

<?php ActiveForm::end(); ?>	
<? } ?>

<!--Сообщение при успешной подаче объявления.-->
<? if ($save) {?>
   <?=$this->render('add_save_express', [
	  'save' => $save,
    'key' => $key,
		'registr_success' => $registr_success,
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