<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;

$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
?>
<script src="//ulogin.ru/js/ulogin.js"></script>
<div class="site-login">
<br>
    <h1><?= Html::encode($this->title) ?></h1>
	<div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'email')->textInput(['type'=>'email','autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

                <?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня') ?>
<div id="uLogin" data-ulogin="display=panel;theme=classic;fields=first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=<?=$patch_url.Url::to(['user/social'])?>;mobilebuttons=0;"></div>
    <br>
                <div style="color:#999;margin:1em 0">
				    <?= Html::a('<i class="fa fa-user-plus" aria-hidden="true"></i> Зарегистрироваться ', ['user/signup']) ?><br>
                    Забыли логин или пароль <?= Html::a('Сбросить', ['user/request-password-reset']) ?>.
                    <br>
                    Подтвердить аккаунт <?= Html::a('Прислать повторно', ['user/resend-verification-email']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

	