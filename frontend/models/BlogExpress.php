<?php

namespace frontend\models;
use yii\web\UploadedFile;
use common\models\BlogField;
use common\models\BlogServices;
use common\models\BlogImage;
use common\models\BlogAuction;
use common\models\BlogTime;
use common\models\Field;
use common\models\User;
use common\models\Region;
use common\models\Category;
//---Обновление координаты ---//
use common\models\BlogCoord;
use common\models\BlogKey;
use common\components\behaviors\StatusBehavior;
use yii\helpers\Url;
use Yii;
$date = (new \DateTime('now', new \DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s');
/**
 * This is the model class for table "blog".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property string $url
 * @property int $status_id
 * @property int $date_add
 * @property int $date_update
 * @property string $image
 * @property int $category
 * @property int $region
 * @property int $active
 * @property int $views
 * @property int $count
 * @property int $price
 * @property int $phone
 */
class BlogExpress extends \yii\db\ActiveRecord
{
const STATUS_LIST = ['На модерации','Опубликовано','Удалено'];
public $file;
public $dir_name;
public $reCaptcha;
public $username;
public $password;
public $password2;
public $email;
public $balance_minus;
//Обновление
public $coordlat;
public $coordlon;
public $address;
public $status_id_false;
public $active_false;
public $price;
public $phone;
    /**
     * {@inheritdoc}
     */
	 
//	 аукцион
public $auk_price_add;
public $auk_price_moment;
public $auk_time;
public $auk_rates;
public $auk_shag;
public $auk_price;
public $userreservauthor;
public $pricepay;
public $key;
public $check;
    public static function tableName()
    {
        return 'blog';
    }



    /**
     * {@inheritdoc}
     */
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
  $capcha['action'] = 'blog/add';
  
}
if (!isset($capcha) || Yii::$app->user->can('updateBoard')) {
  $capcha = [['reCaptcha'], 'default', 'value'=> 1];
}




$check = $this->check();

if($check) {
	//Если это страница update
	if(Yii::$app->controller->action->id == 'expressupdate') {
	    $required =  [['title', 'region', 'address', 'phone', 'reCaptcha', 'key'], 'required'];
	}else{
		$required =  [['title', 'region', 'address', 'phone', 'reCaptcha'], 'required'];
	}
}else{
	$required =  [['title', 'region', 'address', 'phone'], 'required'];
}


if(Yii::$app->controller->action->id == 'expressupdate' || Yii::$app->user->id) {
	$capcha = [['reCaptcha'], 'default', 'value'=> 1]; 
 }
 
	if ($this->id) {$date_update = date('Y-m-d H:i:s');}else{$date_update = '';};
        return [	
		    $capcha,
	        [['email'], 'email'],
	 	    [['user_id', 'category', 'region', 'status_id', 'active', 'count', 'views', 'balance_minus', 'discount', 'auk_time', 'auk_shag', 'auk_rates', 'auction', 'reserv_user_id', 'status_id_false', 'express', 'active_false'], 'integer'],
            $required,
			[['text', 'dir_name','date_del','coordlat','coordlon','address', 'discount_text', 'price', 'phone'], 'string'],
            [['title', 'url'], 'string', 'max' => 150],
			[['date_add'], 'date', 'format'=>'php:Y-m-d H:i:s' ],
			[['discount_date'], 'date', 'format'=>'php:Y-m-d'],
			[['date_update'], 'date', 'format'=>'php:Y-m-d H:i:s'],
			[['date_update'], 'default', 'value'=> $date_update],
			[['status_id'], 'default', 'value'=> '1'],
			[['express'], 'default', 'value'=> '1'],
			[['file'], 'image'],
			[['author'], 'safe'],
			[['key'], 'validateKey'],
		    //['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],

        ];
    }
	
	public function check() {
		//Задае проверку по умолчанию

		if(Yii::$app->user->id && Yii::$app->user->id == $this->user_id) {
			$check = false;
		}elseif(Yii::$app->user->can('updateBoard')){
			$check = false;
		}else{
			$check = true; 
		}

	
		 return $check;
	
	}
	public function validateKey($attribute, $params, $validator)
    {
		if (!$time = BlogKey::find()->where(['key' => $this->$attribute])
		    ->andWhere(['blog_id' => $this->id])->one()) {	
			$this->addError('key', 'Неверное значение');
		  }		
	}

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
			'date_add' => 'Дата создания',
			'date_update' => 'Дата редактирования',
			'date_del' => 'Срок публикации',
            'title' => 'Заголовок',
            'text' => 'Описание объявления',
            'url' => 'Url',
            'status_id' => 'Статус',
			'category' => 'Категория',
			'region' => 'Регион',
			'image' => 'Фото',
			'address' => 'Адрес',
			'phone' => 'Телефон',
			'price' => 'Цена',
			'key' => 'Секретный Ключ'
        ];
    }
		
	//---Обновление координаты ---//
	//Связь с Таблицей координат
    public function getCoord() {
       return $this->hasOne(BlogCoord::className(),['blog_id'=>'id']);
	}
	//Связь с Платными услугами
    public function getServices() {
    return $this->hasMany(BlogServices::className(),['blog_id'=>'id']);
	}
	//Связь с Магазином
    public function getShop() {
    return $this->hasOne(Shop::className(),['user_id'=>'user_id']);
	}
    //Связь с Автором
	public function getAuthor() {
    return $this->hasOne(User::className(),['id'=>'user_id']);
	}
	//Связь с Регионом
	public function getRegions() {
    return $this->hasOne(Region::className(),['id'=>'region']);
	}
   //Связь с Категорией
	public function getCategorys() {
    return $this->hasOne(Category::className(),['id'=>'category']);
	}
    //Связь с Blog_field
	public function getBlogField() {
    return $this->hasMany(BlogField::className(),['message'=>'id']);
	}
	
	//Связь с Blog_field
	public function getField() {
    return $this->hasMany(Field::className(),['id'=>'field'])->via('blogField');
	}

	
	//Связь с Blog_image
	public function getImageBlog() {
    return $this->hasMany(BlogImage::className(),['blog_id'=>'id']);
	}

	
		//Связь с Blog_image на главной во фронте
	public function getImageBlogOne() {
    return $this->hasOne(BlogImage::className(),['blog_id'=>'id']);
	}
	
		//Связь с Blog_image на главной во фронте
	public function getAuctions() {
    return $this->hasOne(BlogAuction::className(),['blog_id'=>'id']);
	}	

	public function getReservuser() {
       return $this->hasOne(User::className(),['id'=>'reserv_user_id']);
	}
		
}