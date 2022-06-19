<?php
return [

    'components' => [
	
	
	  'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
		
        'db' => [ 'class' => 'yii\db\Connection', 'dsn' => 'mysql:host=127.0.0.1; dbname=99', 'username' => 'root', 'password' => '', 'charset' => 'utf8', ],

		'mailer' => function(){

			 if(Yii::$app->caches->setting()['email_smtp'] == 1) {
			    
			 }
			 
		$setting_mail =  explode("\n",Yii::$app->caches->setting()['smtp']);
		if(Yii::$app->caches->setting()['email_smtp'] == 1) {
	if(!isset($setting_mail[4])) { $encryption = ''; }else{ $encryption = preg_replace('/\s+/', '',$setting_mail[4]); }
          return Yii::createObject([
              'class' => 'yii\swiftmailer\Mailer',
			  'viewPath' => '@common/mail',
              'useFileTransport' => false,
              'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => preg_replace('/\s+/', '',$setting_mail[0]),
                    'port' => preg_replace('/\s+/', '',$setting_mail[1]),
                    'username' => preg_replace('/\s+/', '',$setting_mail[2]),
                    'password' => preg_replace('/\s+/', '',$setting_mail[3]),
                    'encryption' => $encryption,
		          ]	  
          ]);
		}else{
			 return Yii::createObject([
              'class' => 'yii\swiftmailer\Mailer',
			  'viewPath' => '@common/mail',
              'useFileTransport' => false,  
          ]);
			
			
		}
        },
    ],
];
