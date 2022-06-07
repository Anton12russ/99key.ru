<?php
return [
'language' => 'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
	
	
	
	/*Параметры для мультивхода*/
	//Так же указать у куки домен, если основной домен третьего уровня. auth_key , в контроллере user - frontend
	'user' => [
  //'class' => 'yii\web\User',
  'identityClass' => 'app\models\Users',
  'enableAutoLogin' => true,
  'identityCookie' => [
    'name' => '_identity',
    'httpOnly' => true,
    'path' => '/',
    'domain' => '.99key.ru',
  ],
],
'session' => [
  //'savePath' => '\app\session',
  'cookieParams' => [
    'domain' => '.99key.ru',
    //'httpOnly' => true,
    //'path' => '/',
  ],
],
    'request' => [
        // ...
        'csrfCookie' => [
            'name' => '_csrf',
            'path' => '/',
            'domain' => ".".DOMAIN,
        ],
    ],
		/*-------------------*/
	
	
	
	
	'authManager' => [
	        'cache' => 'cache',
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
      ],
	  
	  /*
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
		*/
		'caches' => [
           'class' => 'common\components\Caches',
	    ],
		'userFunctions' => [
           'class' => 'common\components\FunctionUser',
	    ],
		'userFunctions2' => [
           'class' => 'common\components\FunctionUser2',
	    ],
		
'userFunctions3' => [
           
   'class' => 'common\components\FunctionUser3',
	    
],
		'functionCron' => [
           'class' => 'common\components\FunctionCron',
	    ],
		'block' => [
           'class' => 'common\components\Block',
	    ],
		
		'blockshop' => [
           'class' => 'common\components\Blockshop',
	    ],
		'functionSeo' => [
           'class' => 'common\components\FunctionSeo',
	    ],
		'functionMail' => [
           'class' => 'common\components\FunctionMail',
	    ],
		'image' => [
        	 	'class' => 'yii\image\ImageDriver',
        		'driver' => 'GD',  //GD or Imagick
				//Если выйдет ошика, заиенить imagick на GD
        		],
    ],

];
