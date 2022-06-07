<?php

namespace frontend\models;

use Yii;



/**
 * This is the model class for table "shop".
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property string $text
 * @property int $category
 * @property int $region
 * @property int $status
 * @property int $active
 * @property string $date_end
 * @property string $date_add
 * @property string $img
 * @property string $site
 * @property string $phone
 * @property int $rayting
 
 */
class Contshopperson extends \yii\db\ActiveRecord
{
	
	public $name;
	public $email;
	public $text;
	public $reCaptcha;
	
	
 

    public function rules()
    {
		//Капча видимая
if (Yii::$app->caches->setting()['capcha'] == 1) {
  $capcha[0] = ['reCaptcha'];
  $capcha[1] = \himiklab\yii2\recaptcha\ReCaptchaValidator2::className();
  $capcha['secret'] = explode("\n",Yii::$app->caches->setting()['recapcha2'])[1];
  $capcha['uncheckedMessage'] = 'Пожалуйста укажите проверочный код';
}

//Капча невидимая
if (Yii::$app->caches->setting()['capcha'] == 2) {
  $capcha[0] = ['reCaptcha'];
  $capcha[1] = \himiklab\yii2\recaptcha\ReCaptchaValidator3::className();
  $capcha['secret'] = explode("\n",Yii::$app->caches->setting()['recapcha3'])[1];
  $capcha['threshold'] = 0.5;
  $capcha['action'] = 'contact';
  
}
if (!isset($capcha) || (Yii::$app->user->can('updateShop'))) {
  $capcha = [['reCaptcha'], 'default', 'value'=> 1];
}
        return [
		 $capcha,
            [['name', 'email', 'text', 'reCaptcha'], 'required'],
            [['name', 'email', 'text'], 'string'],
           
	
        ];
    }

    public function attributeLabels()
    {
        return [
		    'reCaptcha' => 'Проверочный код',
            'name' => 'Ваше имя',
			'email' => 'Ваш Email',
			'text' => 'Текст сообщения',
        ];
    }
}
