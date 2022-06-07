<?php
/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/user.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title = 'Слайдер';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js',['depends' => [\yii\web\JqueryAsset::className()]]);

?>

	


<div class="row">

   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">


<h1><i class="fa fa-file-image-o" aria-hidden="true"></i> <?=$this->title?></h1>

<?php yii\widgets\Pjax::begin(['id' => 'sliders']) ?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','data-pjax' => true, 'id' => 'w1']]) ?>

 <? if($sliders) { ?>
 <table class="table">
  <tbody id="sortable">
   <? foreach($sliders as $res) { ?>
    <tr class="ui-state-default"  data-id="<?=$res['id']?>">
      <td><img  class="shows" style="max-width: 150px;" src="/uploads/images/shop/slider/<?=$res['image']?>"></td>
      <td>
   <div class="form-group">
      <input data-id="<?=$res['id']?>" class="form-control urlupdate" value="<?=$res['url']?>"/>
   </div>
	  </td>
	  <td><div data-id="<?=$res['id']?>" class="delet-slider" ><i class="fa fa-times" aria-hidden="true"></i></div></td>
    </tr>
   <? } ?>
</tbody>
</table>
 <? }else{ ?>
    <div class="col-md-12"><div class="alert alert-warning">Добавьте изображения в слайдер.</div></div>
<? } ?>




<div class="col-md-12">
<table class="table" style="background: #f3f3f3;
    border-radius: 4px;
    margin-top: 20px;">
  <tbody  class="sortable">
   <tr style="border: 0;">
      <td style="border: 0;" width="25%"><?= $form->field($model, 'imageFile', ['template' => '{error}{label}{input}'])->fileInput()->label('<img id="img-preview" src="/uploads/images/uploud.png" />') ?></td>
      <td style="border: 0; vertical-align: middle;"><?= $form->field($model, 'url')->textInput(['maxlength' => true, 'placeholder'=> 'https://yandex.ru']) ?></td>
      
	</tr>
   </tbody>
</table>
 </div>


 <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success gouploads']) ?>

<?php ActiveForm::end() ?>
<?php Pjax::end() ?>
</div>
</div>
</div>