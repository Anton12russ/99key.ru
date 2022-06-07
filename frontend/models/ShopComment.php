<?php

namespace frontend\models;
use common\models\User;
use frontend\models\Shop;
use Yii;

/**
 * This is the model class for table "shop_comment".
 *
 * @property int $id
 * @property int $blog_id
 * @property int $user_id
 * @property string $date
 * @property string $text
 * @property string $user_name
 * @property string $user_email
 * @property int $status
 */
class ShopComment extends \yii\db\ActiveRecord
{

const STATUS_LIST = ['На модерации','Активирован'];


	public $reCaptcha;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
/*		//Капча видимая
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
  $capcha['action'] = 'shop/comment';

}

if (!isset($capcha)) {
  $capcha = [['reCaptcha'], 'default', 'value'=> 1];
}

*/

        return [
		//$capcha,
            [['blog_id', 'date', 'text', 'user_name', 'user_email', 'status'], 'required'],
            [['blog_id', 'user_id', 'status', 'too'], 'integer'],
            [['date'], 'safe'],
            [['text'], 'string'],
            [['user_name'], 'string', 'max' => 100],
            [['user_email'], 'string', 'max' => 70],
			[['user_email'], 'email'],
			[['user_id'], 'default', 'value'=> '0'],
        ];
    }
	
	//Связь с Shop_field
	public function getAuthor() {
       return $this->hasOne(User::className(),['id'=>'user_id']);
	}

	//Связь с Shop_field
	public function getShop() {
       return $this->hasOne(Shop::className(),['id'=>'blog_id']);
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
           // 'id' => 'ID',
            'blog_id' => 'ID Магазина',
            'user_id' => 'Id пользователя',
            'date' => 'Дата добавления',
            'text' => 'Текст комментария',
            'user_name' => 'Имя пользователя',
            'user_email' => 'Email пользователя',
            'status' => 'Статус',
			'Status' => 'Статус',
			'too' => 'Кому',
			'author' => 'Автор',
        ];
    }
	
	
	
	//Статус
	public function getStatus() {
    $arrey = ['0'=>['На модерации'],'1'=>['Активирован']];
	return $arrey[$this->status][0];	
	}	
}
