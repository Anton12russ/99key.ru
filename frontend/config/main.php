<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'modules' => [
        'personalshop' => [
            'class' => 'frontend\modules\shop\Personalshop',
        ],
		'map' => [
            'class' => 'frontend\modules\map\map',
			'layout' => 'main'
        ],
		'passanger' => [
            'class' => 'frontend\modules\passanger\passanger',
			  'layout' => 'main'   
        ],
		
		  'debug' => [ // панель на хостинге 
            'class' => 'yii\debug\Module', //
            'allowedIPs' => ['*'] //
        ],
		
		'search' => [     
            'class' => 'frontend\modules\search\search',      
        ],
        'searchcat' => [     
            'class' => 'frontend\modules\searchcat\searchcat',      
        ],
    ],
	
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
	'errorHandler' => [
     'errorAction' => '/site/404',
     ],

    'reCaptcha' => [
        'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',

    ],
	
	
	//Кеширование css и js
	 'assetManager' => [
            'appendTimestamp' => true,
        ],
	
       'request' => [
            'baseUrl' => '',
			'cookieValidationKey' => $params['cookieValidationKey'],
        ],
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
     
    'rules' => [
	    'sitemap.xml'=>'sitemap/index',
		'sitemap_region.xml'=>'sitemap/region',
		'sitemap_category.xml'=>'sitemap/category',
		'sitemap_article.xml'=>'sitemap/article',
		'sitemap_shop.xml'=>'sitemap/shop',		
		'sitemap_blog.xml'=>'sitemap/blog',			
		'rss_article.xml'=>'rss/article',
		'rss_shop.xml'=>'rss/shop',		
		'rss_board.xml'=>'rss/board',	
		'rss_akcii.xml'=>'rss/akcii',
		
		
		
		
		
		'search'=>'blog/search',
		'auctions'=>'blog/auctions',
		'notepad'=>'blog/notepad',
		'add'=>'blog/add',
        'expressadd'=>'blog/expressadd',
        'expressupdate'=>'blog/expressupdate',
        'auctionadd'=>'blog/auctionadd',
        'auctionupdate'=>'blog/auctionupdate',
		'del'=>'blog/del',
		'users'=>'blog/users',
		'update'=>'blog/update',
		
        //Обновление
        'search-ajax'=>'search/default/',
        'searchcat'=>'searchcat/default/',
		//Обновление
		'map/cat-parent'=>'map/default/cat-parent',
		'map/reg-parent'=>'map/default/reg-parent',
		'map/filtr'=>'map/default/filtr',
		'map/coord'=>'map/default/coord',
		'map/all'=>'map/default/all',
		'map/one'=>'map/default/one',
		'map/notepad'=>'map/default/notepad',
		
				//Обновление
		'passanger/add'=>'passanger/default/add',
		'passanger/upload-logo'=>'passanger/default/upload-logo',
		'passanger/del-logo'=>'passanger/default/del-logo',
		'passanger/update'=>'passanger/default/update',
		'passanger/one'=>'passanger/default/one',		
		'passanger/maps'=>'passanger/default/maps',	
		'passanger/searchot'=>'passanger/default/searchot',
		'passanger/searchkuda'=>'passanger/default/searchkuda',
		'passanger/del'=>'passanger/default/del',
		'passanger/new'=>'passanger/default/new',
		'passanger/mapot'=>'passanger/default/mapot',
		'passanger/kogda'=>'passanger/default/kogda',
		'/user/passanger'=>'passanger/default/user',
        'passanger/users'=>'passanger/default/users',
		
		
		

		
		
		//Обновление 
		'user/block/'=>'blockuser/index',
		'user/block/update'=>'blockuser/update',
        'user/block/create'=>'blockuser/create',
		
		
		
		
		
		//'user/blogs'=>'personalshop/user/blogs',
		
     [
        'class' => 'common\components\BlogUrlRule', 
        // ...настройка других параметров правила...
     ],


//'/<category:[\w_\/-]+>/<url:[\d]+_[\w_\/-]+>'=>'blog/one',
//'/<category:[\w_\/-]+>'=>'blog/category',
'/'=>'blog/index',

  //вывод отдельной страницы
            [     
            'pattern'=>'site/<url:\w+>',
            'route' => 'blog/act',
            'suffix' => '.html',
            ],
 

    '<action>'=>'/<action>',
    ],
],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
			'loginUrl' => ['/user/index'],
            //'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => false],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
	
	
	

    'params' => $params,
	/*
     'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
    ],*/
];
