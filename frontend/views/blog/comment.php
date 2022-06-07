<?php
/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;

$this->registerCssFile('/css/comment.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile(Url::home(true).'js/comment.js',['depends' => [\yii\web\JqueryAsset::className()]]);
if (isset($shop)) {
	$act = 'shop_comment';
}else{
	$act = 'blog_comment';
}
?>
<?php Pjax::begin([ 'id' => 'pjaxContent', 'enablePushState' => false]); ?>
<div class="col-md-12">
<div class="row">
  
<? if ($comment_save) {?>
   <br>
   <div class="alert alert-success">Комментарий сохранен. <? if (Yii::$app->caches->setting()['moder_blog_comment'] != 1) {?> Он будет опубликован после проверки <? } ?>
   
   </div>
<? } ?>
 
  
<? if (!$comment_save) {?> 

<!---------------FORM------------------------>
<div class="<?if (isset($shop)) {?>col-md-7<?}else{?>col-md-6<?}?> com">
<h3>Добавить комментарий</h3>

   <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true,]]);?> 
   <?= $form->field($comment_add, 'too')->hiddenInput(['maxlength' => true, 'class' => 'input-too'])->label(false) ?>
           <?if (Yii::$app->user->isGuest) {?>
		      
              <?= $form->field($comment_add, 'user_name')->textInput(['maxlength' => true]) ?>
              <?= $form->field($comment_add, 'user_email')->textInput(['maxlength' => true]) ?>
          <?}?>
          <?= $form->field($comment_add, 'text')->textarea(['rows' => 6, 'class' => 'form-control textarea-too', 'id' => 'textarea-too']) ?>
		  
		  
		  <? if (Yii::$app->caches->setting()['capcha'] == 1 && Yii::$app->controller->id != 'shop') { ?>
          <div class="capcha">
                 <?= $form->field($comment_add, 'reCaptcha',['template' => '{error}{input}'])->widget(\himiklab\yii2\recaptcha\ReCaptcha2::className(),['siteKey' => preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['recapcha2'])[0])])->label(false) ?>
         </div>
         <? } ?>

         <? if (Yii::$app->caches->setting()['capcha'] == 2) { ?>
                 <?= $form->field($comment_add, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha3::className(),['siteKey' => preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['recapcha3'])[0]),'action' => 'blog/add'])->label(false) ?>
         <? } ?>
		  
               <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
              </div>
  <?php ActiveForm::end(); ?>	
</div>
<? } ?>
  

  	<div class="col-md-12">
     <?if (!$comment) {?>

          <br>
	      <div class="alert alert-info">Комментариев пока нет, Вы можете стать первым.</div>
		  
     <?} else {?> 
 
	   <? foreach ($comment as $res) { ?> 
	   <div class="comment row">
	          <div class="comment-logo">
	               <img src="<?=Yii::getAlias('@images');?>/comment.png " />
	          </div>
	   
	          <div class="comment-body">
			  <div class="comm-head">
			      <span class="comm-name"><?= $res['user_name']?>: </span> 
				<span class="too" data-id="<?= $res["id"]?>" data-name="<?= $res["user_name"]?>">Ответить</span>   <span class="comm-date"><?= $res['date']?></span>  
			  </div>  
			      <div class="comm-text">
				  <div>
				     <?= $res['text']?>
				  </div>

				  <? if ($res["too"]) {$arr_com = Yii::$app->userFunctions->comment($act, $res["too"]); ?>
            
			
			  <div class="comment-body parent-com"> 
			   <div class="citata">Цитирую: </div>
			  <div class="comm-head">
			      <span class="comm-name"><?=$arr_com['user_name']?>: </span> 
				  <span class="too" data-id="<?=$arr_com["id"]?>" data-name="<?= $arr_com["user_name"]?>">Ответить</span>   <span class="comm-date"><?= $arr_com['date']?></span>  
			  </div>    
			     <div class="comm-text">
				     <?= $arr_com['text']?>
			     </div>
              </div> 
				   <? } ?>
				  
				  
				  
				  </div> 
			  </div>
		</div>	  
	   <? } ?> 
     <? }?> 
	 
	 </div>
  </div>
</div>





<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>

<?php Pjax::end(); ?>