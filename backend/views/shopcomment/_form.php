<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\ShopComment */
/* @var $form yii\widgets\ActiveForm */

$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="shop-comment-form">

    <?php $form = ActiveForm::begin(); ?>

        
	<div class="form-group">
    <label class="control-label">Пользователь</label>
      <a href="#" class="user_id btn btn-info" data-toggle="modal" data-target="#myModal">
        <?php if ($model->user_id) {echo $model['author']['username'];}else{echo 'Выбрать пользователя';}?>
     </a>
    </div>
	
    <?= $form->field($model, 'user_id')->textInput(['class' => 'form-control user_input','type' => 'hidden' ])->label(false) ?>
	
    <?= $form->field($model, 'blog_id')->textInput() ?>

    <?= $form->field($model, 'date')->textInput(['class' =>  'datepicker form-control']) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->radioList(\common\models\ShopComment::STATUS_LIST) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

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