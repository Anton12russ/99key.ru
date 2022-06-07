<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use vova07\imperavi\Widget;
$this->title = 'Вопрос № '.$subject->id.' ('.$subject->subject.')';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = array('label'=> 'Поддержка', 'url'=>Url::toRoute('user/support'));
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
<div class="">
   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">
    <h1><i class="fa fa-life-ring" aria-hidden="true"></i> <?=$this->title?></h1>


<?php Pjax::begin([ 'id' => 'pjaxSupportadd']); ?>

<? foreach($support as $res) { ?>
<div class="col-md-12 row">
    <?if($res['admin']) {?>
	    <span class="time_disput">Ответ от администрации сайта (<?=$res['date_add']?>)</span>

	<? }else{ ?>
	     <span class="time_disput">Ваше сообщение (<?=$res['date_add']?>)</span>
	<? } ?>
</div>	
	<div class="mess_disput col-md-12"><?=$res['text']?></div>
<? } ?>



<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true,], 'enableClientValidation' => false,]);?>


<?= $form->field($model, 'text')->widget(Widget::className(), [
   
    'settings' => [
	
        'lang' => 'ru',
        'minHeight' => 200,
		'formatting' => [
		'h1','h2','p','blockquote'
		],
        'plugins' => [

            'fullscreen',
			'video',

        ],
		'imageUpload' => \yii\helpers\Url::to(['/ajax/save-redactor-img','sub'=>'article']),
		'imageDelete' => \yii\helpers\Url::to(['/ajax/save-img-del']),
       
    ],  
])->label('Вопрос')?>

    
<div class="col-md-12 form-group upcr">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success add-preloader']) ?>
</div>


<?php ActiveForm::end(); ?>	
<?= LinkPager::widget([
 'pagination' => $pages,
]); ?> 
<?php Pjax::end(); ?>

   </div>
</div>
</div>
</div>