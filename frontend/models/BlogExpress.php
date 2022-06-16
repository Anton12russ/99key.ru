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

	if ($this->id) {$date_update = date('Y-m-d H:i:s');}else{$date_update = '';};
        return [	
		    $capcha,
	        [['email'], 'email'],
	 	    [['user_id', 'category', 'region', 'status_id', 'active', 'count', 'views', 'balance_minus', 'discount', 'auk_time', 'auk_shag', 'auk_rates', 'auction', 'reserv_user_id', 'status_id_false', 'express'], 'integer'],
			//Обновленная строка
            [['title', 'region', 'address', 'phone'], 'required'],
            //Обновленная строка
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
		    //['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
			['email', 'validateUserlogin'],
			
			['date_del', 'validateDatedel'],
			[['auction'], 'validateAuk'],
            
        ];
    }
	
	
 public function validateAuk($attribute, $params, $validator)
    {

		if($this->auction == 1) {	
		  if(!$this->auk_price_add) {
				$this->addError('auk_price_add', 'Не заполнено поле');
		  }	
		 /* if(!$this->auk_price_moment) {
				$this->addError('auk_price_moment', 'Не заполнено поле');
		  }*/
		  
		   if(!$this->auk_time) {
				$this->addError('auk_time', 'Не заполнено поле');
		  }
		  
		    if(!$this->auk_shag) {
				$this->addError('auk_shag', 'Не заполнено поле');
		  }
		}
	}
	
	
	
	
	
	
	
	
	
	
//Валидация Даты удаления
 public function validateDatedel($attribute, $params, $validator)
    {
	
		if (!$time = BlogTime::find()->where(['def'=>'1', 'days' => $this->$attribute])->one()) {	
		  $this->addError('date_del', 'Неверное значение');
		}
		
	}

	
	
//Валидация Логина и пароля
 public function validateUserlogin($attribute, $params, $validator)
    {

   if (Yii::$app->user->isGuest) {
	   
	   if (Yii::$app->request->post()['Blog']['password'] != Yii::$app->request->post()['Blog']['password2']){
		   $this->addError('password', 'Пароли не совпадают.'); 
           $this->addError('password2', 'Пароли не совпадают.');		   
	   }else{
		   
		   
        $password = Yii::$app->request->post()['Blog']['password']; 
		if ($this->$attribute && $password && Yii::$app->request->post()['Blog']['username']) {
		    if ($identity = User::findOne(['email'=>$this->$attribute])){

                 if (Yii::$app->security->validatePassword($password, $identity['password_hash'])) {
					if ($identity['status'] == 10) {
                       Yii::$app->user->login($identity);
					   
					   
			//Кука для безопасности при смене пароля
			Yii::$app->response->cookies->add(new \yii\web\Cookie([
                 'name' => 'auth_key',
                 'value' => Yii::$app->user->identity->auth_key
              ]));
			  
			  
					}else{
						$this->addError('email', 'Аккаунт не подтвержден, проверьте почту и активируйте аккаунт.');
					}
                 } else {
					 
				   $this->addError('password', 'Логин или Пароль не верный.');
                   $this->addError($attribute, 'Логин или Пароль не верный.');
				   
                 }	
			
				

		   }else{
			   	
			    //При таком условии , регистрируем пользователя в контроллере
		   }
		}
	 	
       }
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
			'email' => 'Email',
			'username' => 'Ваше имя',
			'password' => 'Пароль',
			'password2' => 'Повторный пароль',
			'count' => 'Количество товара',
			'address' => 'Адрес',
			'auk_shag' => 'Шаг',
			'auk_time' => 'Количество дней',
            'auction' => 'Аукцион',
			'auk_price' => 'Стоимость резервации',
			'userreservauthor' => 'Информация продавца', 
			'pricepay' => 'Стоимость выкупа',
			'phone' => 'Телефон',
			'price' => 'Цена'
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