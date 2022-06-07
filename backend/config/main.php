<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [

    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
	'adminFunctions' => [
           'class' => 'common\components\FunctionAdmin',
	    ],

	  
	'cacheFrontend' => [
    'class' => 'yii\caching\FileCache',
    'cachePath' => Yii::getAlias('@frontend') . '/runtime/cache'
    ],

    'request' => [
    'baseUrl' => '/admin',
	'cookieValidationKey' => $params['cookieValidationKey'],
    ],
	
    'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
			  [
           'class' => 'backend\components\BlogUrlRule', 
           // ...настройка других параметров правила...
          ],
        '' => 'site/index',     
		
        '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
		

    ],
],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
			/**/'loginUrl' => ['/site/login'],
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
			],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
 
    ],
	
	'controllerMap' => [
        'elfinder' => [
		  'managerOptions' => [
                'handlers' => [
             'select' => 'function(event, elfinderInstance) {
                                    console.log(elfinderInstance);
                       
                                }',
                ],  
            ],
			'class' => 'mihaildev\elfinder\PathController',
			'access' => ['@'],
			'root' => [
			'baseUrl'=>'/images_all',
            'basePath'=>'@images_all',
            'name' => 'Files'
			],
		
			'watermark' => [
						'source'         => __DIR__.'/logo.png', // Path to Water mark image
						 'marginRight'    => 5,          // Margin right pixel
						 'marginBottom'   => 5,          // Margin bottom pixel
						 'quality'        => 95,         // JPEG image save quality
						 'transparency'   => 70,         // Water mark image transparency ( other than PNG )
						 'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP, // Target image formats ( bit-field )
						 'targetMinPixel' => 200         // Target image minimum pixel size
			]
		]
    ],
	
	 'modules' => [
        'rbac' => [
            'class' => 'mdm\admin\Module',
        ],
		
		   'controllerMap' => [
                 'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    /* 'userClassName' => 'app\models\User', */
                    'idField' => 'id',
                    'usernameField' => 'username',
                ],
            ],
		
    ],
	
	
    'params' => $params,
	

	
	
	'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
       'allowActions' => [
			'site/login',
            'site/signup',			
        ],
		

		

		
    ],
	
	
];
