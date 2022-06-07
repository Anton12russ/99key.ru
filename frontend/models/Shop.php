<?php

namespace frontend\models;
use common\models\Region;
use common\models\Category;
use common\models\ShopImages;
use common\models\ShopField;
use common\models\ShopReating;
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
class Shop extends \yii\db\ActiveRecord
{
	
	public $dir_name;
	public $address;
	public $coord;
	public $shop_id;
	public $payment;
	public $delivery;
	public $grafik;
	public $stocks;
	public $grafik_arr;
	public $phone;
	public $balance;
	public $site;
	public $reCaptcha;
	public $balance_minus;
	public $text_search;
	public $pay_delivery;	
	public $private_payment;
	public $сhoice_pay;
	public $file;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop';
		
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
  $capcha['action'] = 'user/signup';
  
}
if (!isset($capcha) || (Yii::$app->user->can('updateShop'))) {
  $capcha = [['reCaptcha'], 'default', 'value'=> 1];
}
        return [
		 $capcha,
            [['name', 'user_id', 'text', 'category', 'region', 'status', 'date_end', 'date_add', 'phone',
			'payment', 'delivery', 'address', 'coord', 'grafik', 'active', 'balance', 'domen', 'сhoice_pay'], 'required'],
            [['user_id', 'category', 'region', 'status', 'rayting', 'balance_minus','pay_delivery', 'сhoice_pay'], 'integer'],
            [['text', 'dir_name', 'address', 'coord', 'payment', 'delivery','grafik','stocks', 'address', 'domen', 'coord', 'grafik', 'site', 'private_payment', 'file'], 'string'],
            [['date_end', 'date_add'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['img'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 20],
			['balance', 'validateBalance'],
			['grafik_arr', 'validateArr'],
			[['site'], 'validateSite'],
			[['domen'], 'unique'],
        ];
    }

	
//Валидация Массива
  public function validateArr($attribute, $params, $validator)
    {

	}
	
//Валидация Сайта
  public function validateSite($attribute, $params, $validator)
    {
        if(!parse_url($this->$attribute, PHP_URL_HOST)) {
		    $this->addError('site', 'Неверное значение "Адреса сайта".');
	    }
	}
	
	
  public function validateBalance($attribute, $params, $validator)
    {

      if (!isset($this->balance)) {
		  if (Yii::$app->caches->setting()['price-shop'] > Yii::$app->user->identity->balance) {	
		     $this->addError('balance', 'Недостаточно средств на балансе.');
		  }
	    }
		  
	}
	
	
	
	
	
	
	
	public function beforeSave($insert)
	{
    if (parent::beforeSave($insert)) {
        if ($insert) {
            if($this->domen) {
			     $this->domen = Yii::$app->userFunctions->transliteration($this->domen); 
		    }
        }else{
            if($this->domen) {
			     $this->domen = Yii::$app->userFunctions->transliteration($this->domen); 
		    }
		}
        return true;
    } else {
        return false;
    }
}
	
	
	
	
	//Связи
	public function getField() {
       return $this->hasOne(ShopField::className(),['shop_id'=>'id']);
	}

   //Связь с Регионом
	public function getRegions() {
    return $this->hasOne(Region::className(),['id'=>'region']);
	}
   //Связь с Категорией
	public function getCategorys() {
    return $this->hasOne(Category::className(),['id'=>'category']);
	}
	
		//Связь с Shop_image
	public function getImageShop() {
    return $this->hasMany(ShopImages::className(),['shop_id'=>'id'])->orderBy(['sort' => SORT_ASC]);
	}
	
		//Связь с Shop_image
	public function getShopField() {
    return $this->hasMany(ShopField::className(),['shop_id'=>'id']);
	}
	
	
	//Связь c рейтингом
	public function getReating() {
       return $this->hasOne(ShopReating::className(),['shop_id'=>'id']);
	}
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название магазина',
            'user_id' => 'Пользователь',
            'text' => 'Описание',
            'category' => 'Категория',
            'region' => 'Регион',
            'status' => 'Status',
            'active' => 'Active',
            'date_end' => 'Date End',
            'date_add' => 'Date Add',
            'img' => 'Img',
            'phone' => 'Контактный телефон',
            'rayting' => 'Rayting',
			'address' => 'Адрес магазина',
			'coord' => 'Адрес на карте',
			'payment' => 'Информация о приеме платежей',
			'delivery' => 'Информация о доставке',
			'stocks' => 'Планируемые Акции',
            'domen' => 'Название на латинице',
			'сhoice_pay' => 'Прием платежей'
			
        ];
    }
}
