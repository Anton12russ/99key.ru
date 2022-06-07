<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>
<?php Pjax::begin(['enablePushState' => false,'timeout' => 5000,'id' => 'pjax_form']); ?>


<?php if($save !== false) {?>
  <br>
  <?php if($save !== true) {?>
       <div class="alert alert-warning"> Вы уже подписаны на этот товар</div>
  <?php }else{ ?>
	  <div class="alert alert-success">Отправлено. Продавец свяжтся с Вами, как только товар поступит на склад в нужном количестве.</div>
  <?php } ?>  
<?php }else{ ?>
<div class="orders-form">

    <?php 
	$form = ActiveForm::begin(['options' => ['data-pjax' => true,'enctype' => 'multipart/form-data'],'id' => 'dynamic-form']);
	?>
	
	<?= $form->field($model, 'colvo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), ['mask' => Yii::$app->caches->setting()['mask']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php } ?>
<?php Pjax::end(); ?>