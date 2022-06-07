<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
$this->title = 'Настройки оповещений';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="row">
   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
<div class="person-body alert_user">
   <h1><i class="fa fa-commenting-o" aria-hidden="true"></i> <?=$this->title?></h1>
   <br>
    <?php if(!$create) {$chex = [1 => 'Оповещение о новых сообщениях в чате', 'checked' => 'checked'];}else{$chex = [1 => 'Оповещение о новых сообщениях в чате'];} $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'message', ['template' => '{error}{label}<div class="add_chex">{input}</div>'])->checkbox($chex)->label(false); ?>
    <?= $form->field($model, 'comment', ['template' => '{error}{label}<div class="add_chex">{input}</div>'])->checkbox($chex)->label(false); ?>
<br>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

</div>
</div>