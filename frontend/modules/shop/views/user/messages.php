<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


$this->title = 'Моя переписка';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = 'Сообщения';
?>
<?//print_r($payment);?>
<div class="row">
<div class="">
  
   <div class="person-body">
    <h1><i class="fa fa-comments" aria-hidden="true"></i> <?=$this->title?></h1>
	<div class="chat_iframe"><iframe src="<?=Url::to(['user/route']);?>"></iframe></div>
   </div>
</div>

</div>