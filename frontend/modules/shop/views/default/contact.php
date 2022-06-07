<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
$this->registerCssFile('/css/main.css', ['depends' => ['frontend\assets\AppAsset']]);
use yii\widgets\ActiveForm;

$this->title = 'Контакты';
$this->registerMetaTag(['name' => 'description','content' => $this->title]);
$this->registerMetaTag(['name' => 'keywords','content' => $this->title. ', магазин, '.$shop->name]);
//передаем в шаблон
if(isset($shop->img)) {$this->params['logo'] = $shop->img;}else{$this->params['logo'] = '';}
$this->params['title'] = $shop->name;
$this->params['phone'] = $shop->field->phone;
$this->params['shop'] = $shop;
$this->params['arr'] =  array('Пн.','Вт.','Ср.','Чт.','Пт.','Сб.','Вс.');
$arr = $this->params['arr'];
$this->params['vote'] = $vote;
$this->params['vot'] = $shop->reating;
if ($count_art > 0) {$this->params['menu_art'] = true;}else{$this->params['menu_art'] = false;}
$this->params['breadcrumbs'] = array();

$this->params['breadcrumbs'][] = ['label' => 'Контакты' ];


?>

<div class="one-bodys">
<div class="col-md-12"><h1><?=$this->title?></h1></div>





<div class="col-md-12">


   <? if (isset($shop->field->coord)) {?> 
<div class="col-md-7">
<div class="hr_add graf-info" style="margin-top: 20px;"><i class="fa fa-map-location-dot" aria-hidden="true"></i> На карте</div>

      <div class="tab-pane" id="maps">
	      <div class="maps-one"> 
	   	       <div class="iframe-div">
		          <iframe src="/maps?coord=<?=$shop->field->coord?>&address=<?=$shop->field->address?>" style="width:100%; height: 400px; max-height: 100%;" frameborder="0"></iframe>
		      </div>
         </div>
	  </div>
	     </div>
   <? } ?>

   
   
   
   <div class="grafik col-md-5">
<div class="hr_add graf-info" style="margin-top: 20px;"><i class="fa-regular fa-circle-info" aria-hidden="true"></i> Информация</div>
<div class="col-md-12" style="text-align: left; margin-bottom: 20px;">
<strong>Контактный номер:</strong> <a href="tel:<?=str_replace(' ', '',$shop->field['phone'])?>"><?=$shop->field['phone']?></a><br>
<strong>Адрес:</strong> <?=$shop->field['address']?>

</div>
<div class="hr_add graf-info" style="margin-top: 20px;"><i class="fa fa-clock" aria-hidden="true"></i> График работы</div>
 <table class="table table-bordered">
      <tbody>
<? foreach ($shop->grafik as $key => $graf) { ?>
        <tr <? if (isset($graf['vih'])) {?>class="opacity"<? } ?>>
          <td><?=$arr[$key]?></td>
          <td style="white-space: nowrap;" <? if (isset($graf['vih'])) {?>colspan="2"<? } ?>>
		  <? if (isset($graf['vih'])) {?>Выходной<?}else{?>
		       <?=$graf['time']?>
		  <? } ?>
		  </td>
		  <? if (!isset($graf['vih'])) {?>
              <td><?if (isset($graf['obed']) && $graf['obed']) {?><div>Обед:</div><?=$graf['obed']?><?}else{?><div>Без обеда</div><? } ?></td>
		  <? } ?>
        </tr>
<? } ?>	
      </tbody>
 </table>
</div>


</div>

   <div class="col-md-12">
   <h3>Форма обратной связи</h3>

      <br>
  <?php Pjax::begin([ 'id' => 'pjaxBlog', 'enablePushState' => false]); ?>
  
  	<?  if (isset($form_ok)) {?>
	   <div class="alert alert-success">Ваше сообщение отправлено, ожидайте пожалуйста, мы свяжемся с вами в ближайшее время.</div>
	<?}else{?>
	
<?php $form = ActiveForm::begin([
    'options' => [
        'data-pjax' => true,
    ],
]); ?>

	
<div class="contact_search">

<?= $form->field($model, 'name', ['template' => '{error}{label}{input}'])->textInput(['class' => 'form-control'])->label('Ваше имя *') ?>
<?= $form->field($model, 'email', ['template' => '{error}{label}{input}'])->textInput(['class' => 'form-control'])->label('Ваш e-mail *') ?>
<?= $form->field($model, 'text', ['template' => '{error}{label}{input}'])->textarea(['class' => 'form-control'])->label('Ваше сообщение *') ?>

<? if (Yii::$app->caches->setting()['capcha'] == 1) { ?>
<div class="capcha-body">
  <div class="capcha">
       <?= $form->field($model, 'reCaptcha',['template' => '{error}{input}'])->widget(\himiklab\yii2\recaptcha\ReCaptcha2::className(),['siteKey' => preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['recapcha2'])[0])])->label(false) ?>
  </div>
 </div> 
<? } ?>

<? if (Yii::$app->caches->setting()['capcha'] == 2) { ?>
   <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha3::className(),['siteKey' => preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['recapcha3'])[0]),'action' => 'blog/add'])->label(false) ?>
<? } ?>
<div class="form-group upcr">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success add-preloader']) ?>
</div>

</div>

<?php ActiveForm::end(); ?>
<?}?>
<?php Pjax::end(); ?>
   <br>
</div>
</div>





