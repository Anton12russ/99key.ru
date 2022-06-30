<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use kartik\select2\Select2;
use kartik\file\FileInput;
use yii\widgets\Pjax;
use common\models\Blog;
/* @var $this yii\web\View */
/* @var $model common\models\Blog */
/* @var $form yii\widgets\ActiveForm */
 $date_update = date('Y-m-d H:i:s');
 $this->registerJsFile(Url::home(true).'js/categoryAdd.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Url::home(true).'js/region.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
		
		
		//Обновление Функция координаты в объявлении
$this->registerJsFile('//api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU&apikey='.(string)Yii::$app->caches->setting()['api_key_yandex'],['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/cross-control.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/location-tool.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/coord/yamaps.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="blog-form">
<?php Pjax::begin([ 'id' => 'pjaxContent']); ?>
<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true,], 'enableClientValidation' => false,]);?>

<?php if(isset($time)) { ?> <?= $form->field($model, 'date_del')->dropDownList($time)?><?php } ?>
<div class="form-group">
<label class="control-label">Пользователь</label>
<a href="#" class="user_id btn btn-info" data-toggle="modal" data-target="#myModal">
<?php if ($model->user_id) {echo $model['author']['username'];}else{echo 'Выбрать пользователя';}?>
</a>
</div>
<!--'type' => 'hidden'-->

<?= $form->field($model, 'user_id')->textInput(['class' => 'form-control user_input','type' => 'hidden' ])->label('') ?>
<?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Заголовок') ?>


<?= $form->field($model, 'region', ['template' => '{input}'])->textInput(['type' => 'hidden']) ?>

<!--Обновление координаты--->
<?= $form->field($model, 'coordlat', ['template' => '{input}'])->textInput(['id' => 'coord-lat','type' => 'hidden'])->label('') ?>
<?= $form->field($model, 'coordlon', ['template' => '{input}'])->textInput(['id' => 'coord-lon','type' => 'hidden'])->label('') ?>
<?= $form->field($model, 'address', ['template' => '{error}{label}{input}'])->textInput(['id' => 'suggest'])->label('Адрес <span class="req_val">*</span>')?>
<div id="YMapsIDadd"></div>


<?= $form->field($model, 'category')->textInput(['class' => 'catchang','type' => 'hidden' ]) ?>
<?
echo  $form->field($model, 'dir_name')->textInput(['type' => 'hidden' , 'value'=> $dir_name])->label('');
?>

<?php Pjax::begin([ 'id' => 'pjaxFields']); ?>


<? foreach($model_view as $res) {?>
    <? if ($res['type'] == 'v') {?>   <!--Text-->
        <!--Условие, если тип строки (Телефон или факс)-->
        <? if ($res['type_string'] == 't' || $res['type_string'] == 'x') {?>
              <?= $form->field($model2, 'f_'.$res['id'])->widget(\yii\widgets\MaskedInput::className(), ['mask' => Yii::$app->caches->setting()['mask'],])->textInput(['maxlength' => true])->label($res['name'].Blog::req($res['req'])) ?>
        <? }?>
    <? }?> 
   

<? if ($res['type'] == 'p') {?>  <!--Price-->
<?= $form->field($model2, 'f_'.$res['id'])->textInput(['maxlength' => true])->label(($res['name'].Blog::req($res['req']))) ?>

<?= $form->field($model2, 'f_'.$res['id'].'_rates')->hiddenInput(['value' => '1'])->label(false) ?>


<? }?>


<? }?>

<?php Pjax::end(); ?>
<?php if (isset($_POST['Pjax_category'])) {exit(); };?>
<?= $form->field($model, 'text', ['template' => '{error}{label}{input}'])->textarea(['rows' => 6])->label(false)?>

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
		'MaxFileSize'=>Yii::$app->caches->setting()['max_photo_mb'],
        'maxFileCount' => Yii::$app->caches->setting()['max_photo_count'],
		
		'showUploadedThumbs' => true, // сохранять ли отображение загруженных миниатюр файлов в окне предварительного просмотра (для загрузки ajax) до тех пор, пока не будет нажата кнопка удаления / очистки. Значения по умолчанию для true. Если установлено значение « falseСледующая партия файлов, выбранных для загрузки», эти миниатюры будут удалены из предварительного просмотра.
        'allowedFileExtensions' => [ 'jpg' , 'gif' , 'png'],
		 'initialPreview'=> Blog::files($dir_name),
		 'initialPreviewAsData'=>true,
		'initialPreviewConfig' => Blog::previewconfig($dir_name),
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
</div>
</div>
</div>
<br>
<div class="row">
<div class="col-md-12">
<div class="dop-input">
             <?= $form->field($model, 'status_id')->radioList(\common\models\Blog::STATUS_LIST) ?>

             <?= $form->field($model, 'date_add')->textInput(['placeholder' => $date_update])->label('Дата создания') ?>

	         <?if ($model->id) {echo $form->field($model, 'date_update')->textInput(['placeholder' => $date_update,'min' => 0, 'max' => 10, 'value'=>''])->label('Дата Редактирования');}?>
	   </div>
	   
	   
	   <div class="dop-input">
             <?= $form->field($model, 'active')->radioList(\common\models\Blog::STATUS_ACTIVE) ?>
	   </div>
	 </div>
    </div>
	<br>
    <div class="form-group upcr">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success add-preloader']) ?>
    </div>
	

<?php ActiveForm::end(); ?>	

	  <?php Pjax::end(); ?>

</div>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Выбрать автора объявления</h4>
      </div>
	  <span class="hidden href_user" data-href="<?= Url::home(true)?>user"></span>
      <div class="modal-body" >
   <iframe src=""  id="user_cont"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary add_user" data-dismiss="modal">Выбрать</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--Блог анимированной загрузки, срабатывает при нажатии на кнопку отправки формы-->
<div class="preloader">
  <div class="loader loader-left"></div>
  <div class="loader loader-right"></div>
  <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
    <defs>
      <filter id="goo">
        <fegaussianblur in="SourceGraphic" stddeviation="15" result="blur"></fegaussianblur>
        <fecolormatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 26 -7" result="goo"></fecolormatrix>
        <feblend in="SourceGraphic" in2="goo"></feblend>
      </filter>
    </defs>
  </svg>
</div>

<script>
function params_sort() {
var arr = '<?php echo \yii\helpers\Url::to(['/upload/sort']);?>?dir_id=<?php echo $dir_name;?>';
return arr;
}
</script>