<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
/* @var $this yii\web\View */
/* @var $model common\models\BlogServices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-services-form">

    <?php $form = ActiveForm::begin();?>
	

	
    <? if (isset(Yii::$app->request->get()['blog_id'])) {?><div class="hidden"><?}?>
           <?= $form->field($model, 'blog_id')->textInput(['value'=>Yii::$app->request->get()['blog_id']]) ?>
	<? if (isset(Yii::$app->request->get()['blog_id'])) {?></div><?}?>

<table class="table table-bordered">
      <thead>
        <tr>

          <th>Название услуги</th>
          <th>Начало действия</th>
		  <th>Остаток в днях</th>
          <th>Окончание</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>ТОП</td>
		  <? if(!isset($model_update['top']['date_add'])) {$model_update['top']['date_add'] = '';}
		  if(!isset( $model_update['top']['date_end'])) { $model_update['top']['date_end'] = '';}
		  if(!isset( $model_update['block']['date_add'])) {  $model_update['block']['date_add'] = '';}
		  if(!isset( $model_update['block']['date_end'])) {  $model_update['block']['date_end'] = '';}
		  if(!isset( $model_update['bright']['date_add'])) {  $model_update['bright']['date_add'] = '';}
		  if(!isset( $model_update['bright']['date_end'])) {  $model_update['bright']['date_end'] = '';}
		  ?>
          <td><?=$model_update['top']['date_add']?></td>
		  <td><?  if ($model_update['top']['date_end']) {$arr = (strtotime ($model_update['top']['date_end'] )-strtotime(date('Y-m-d H:i:s')))/(60*60*24); echo (int)$arr;} ?></td>
          <td><?= $form->field($model, 'type[top]')->textInput(['autocomplete'=>"off", 'class'=> 'date_services form-control', 'value'=> $model_update['top']['date_end']])->label('') ?></td>
        </tr>
       <tr>
          <td>Правый Блок</td>
          <td><?=$model_update['block']['date_add']?></td>
		  <td><? if ($model_update['block']['date_end']) { $arr_block = (strtotime ($model_update['block']['date_end'] )-strtotime (date('Y-m-d H:i:s')))/(60*60*24); echo (int)$arr_block;} ?></td>
          <td><?= $form->field($model, 'type[block]')->textInput(['autocomplete'=>"off",'class'=> 'date_services form-control', 'value'=> $model_update['block']['date_end']])->label('') ?></td>
       </tr>
	   
	  <tr>
          <td>Выделить цветом</td>
          <td><?=$model_update['bright']['date_add']?></td>
		  <td><? if ($model_update['bright']['date_end']){ $arr_bright = (strtotime ($model_update['bright']['date_end'] )-strtotime (date('Y-m-d H:i:s')))/(60*60*24); echo (int)$arr_bright; }?></td>
          <td><?= $form->field($model, 'type[bright]')->textInput(['autocomplete'=>"off",'class'=> 'date_services form-control', 'value'=> $model_update['bright']['date_end']])->label('') ?></td>
      </tr>
	 

      </tbody>
    </table>
   
	
   
	


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
