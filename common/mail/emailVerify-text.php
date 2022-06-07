<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['user/verify-email', 'token' => $user->verification_token]);
?>
Здравствуйте, <?= $user->username ?>,

Перейдите по ссылке ниже, чтобы подтвердить свою электронную почту:

<?= $verifyLink ?>

https://1tu.ru/Polzovatelskoe-soglashenie.htm - Пользовательское соглашение
https://1tu.ru/Politika-konfidencialnosti.htm - Политика конфиденциальности
