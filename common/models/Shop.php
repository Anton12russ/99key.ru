<?php

namespace common\models;
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
	
	const STATUS_LIST = ['На модерации','Опубликовано','Снято с публикации'];
	const STATUS_ACTIVE = ['Ожидает','Оплачен'];
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
        return [
            [['name', 'user_id', 'text', 'category', 'region', 'status', 'date_end', 'date_add', 'phone',
			'payment', 'delivery', 'address', 'coord', 'grafik', 'active', 'domen'], 'required'],
            [['user_id', 'category', 'region', 'status', 'rayting'], 'integer'],
            [['text', 'dir_name', 'address', 'coord', 'payment', 'domen', 'delivery','grafik','stocks', 'address', 'coord', 'grafik', 'site'], 'string'],
            [['date_end', 'date_add'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['img'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 20],
			[['grafik_arr'], 'validateArr'],
			[['domen'], 'unique'],
        ];
    }
	
//Валидация Массива
  public function validateArr($attribute, $params, $validator)
    {
	
	}
	
	    //Связь с Автором
	public function getAuthor() {
    return $this->hasOne(User::className(),['id'=>'user_id']);
	}
	
	

	//Связь
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
			'author' => 'Автор',
			'author.username' => 'Автор',
            'phone' => 'Контактный телефон',
            'rayting' => 'Rayting',
			'address' => 'Адрес магазина',
			'coord' => 'Адрес на карте',
			'payment' => 'Информация о приеме платежей',
			'delivery' => 'Информация о доставке',
			'stocks' => 'Планируемые Акции',	
			'status' => 'Статус',	
			'active' => 'Активировация',	
			'date_add' => 'Дата активации',
			'date_end' => 'Дата окончания',
			'domen' => 'Название магазина на латинице',
        ];
    }
	
	
		//Статус
	public function getStatus() {
    $arrey = ['0'=>['На модерации'],'1'=>['Опубликовано'],'2'=>['Снят с публикации']];
	return $arrey[$this->status][0];	
	}
	
		//Статус
	public function getActive() {
    $arrey = ['0'=>['Ожидает'],'1'=>['Оплачен']];
	return $arrey[$this->active][0];	
	}	
}
