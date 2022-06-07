<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
$this->title = 'Продолжение регистрации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
	<br>
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
	<?if (isset($save)) {?>Отправлена<?}?>
        <div class="col-lg-5">
    <?php Pjax::begin(); ?>

            <?php $form = ActiveForm::begin(['action' => '/user/social-registr','options' => ['data-pjax' => true]]);?>
			    <? if (isset($user)) {?>
				    <?= $form->field($model, 'username')->hiddenInput(['value' => $user['first_name']])->label(false) ?>
					<?= $form->field($model, 'identity')->hiddenInput(['value' => $user['identity']])->label(false) ?>
                <? }else{ ?>
				    <?= $form->field($model, 'username')->hiddenInput()->label(false) ?>
					<?= $form->field($model, 'identity')->hiddenInput()->label(false) ?>
				<? } ?>
                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
 
  <? if (Yii::$app->caches->setting()['capcha'] == 1) { ?>
 
<div class="col-md-12">
<div class="capcha">
       <?= $form->field($model, 'reCaptcha',['template' => '{error}{input}'])->widget(\himiklab\yii2\recaptcha\ReCaptcha2::className(),['siteKey' => preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['recapcha2'])[0])])->label(false) ?>
</div>
</div>
<? } ?>

<? if (Yii::$app->caches->setting()['capcha'] == 2) { ?>
   <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha3::className(),['siteKey' => preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['recapcha3'])[0]),'action' => 'blog/add'])->label(false) ?>
<? } ?>
                <div class="form-group">
                    <?= Html::submitButton('Отправить данные', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

	<?php Pjax::end(); ?>
        </div>
    </div>
</div>
