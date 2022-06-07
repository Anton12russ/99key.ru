<?php
//Раскоментировать, если сайт использует протовол https - Это переадресация на https
/*
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    
}
*/
if(isset($_SERVER['HTTPS'])) {define('PROTOCOL',  'https://');}else{define('PROTOCOL',  'http://');}

define('SUBDOMAIN',  false);


defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
define('DOMAIN',  $_SERVER['HTTP_HOST']);
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';
require __DIR__ . '/../../common/config/global-function.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'

);
(new yii\web\Application($config))->run();