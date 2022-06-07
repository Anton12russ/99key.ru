<?php

namespace common\models;
use common\models\CarBuyer;
use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property int $id
 * @property string $data_add
 * @property string $data_end
 * @property int $status
 * @property string $id_product
 * @property int $pay
 * @property int $price
 * @property int $user_id
 * @property int $buyer
 */
class Car extends \yii\db\ActiveRecord
{
	public $dostavka;
	const STATUS_LIST = ['На рассмотрении','Отправлен','Доставлен','Отменен','Завершен'];
	const PAY_LIST = ['По договору с продавцом','Оплачен (Гарант-Сервис)'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_add', 'status', 'id_product', 'pay', 'price', 'user_id', 'shop_id'], 'required'],
            [['data_add', 'data_end'], 'safe'],
            [['status', 'pay', 'price', 'user_id', 'buyer', 'shop_id'], 'integer'],
            [['id_product'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data_add' => 'Дата покупки',
            'data_end' => 'Дата завершения сделки',
            'status' => 'Статус',
            'id_product' => 'Продукты',
            'pay' => 'Оплата',
            'price' => 'Стоимость',
            'user_id' => 'Продавец',
            'buyer' => 'Покупатель',
			'author' => 'Продавец',
			'shop' => 'Магазин',
			'user' => 'ID Покупателя',
			'dostavka' => 'Доставка'
        ];
    }
	
	
	//Связь с Продавцом
    public function getUser() {
      return $this->hasOne(User::className(),['id'=>'user_id']);
	}
	//Связь с Продавцом
    public function getAuthor() {
      return $this->hasOne(User::className(),['id'=>'user_id']);
	}
    public function getShop(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id']);
    }


    
    public function getBayer(){
        return $this->hasOne(CarBuyer::className(),['car_id'=>'id']);
    }

    public function getDispute(){
        return $this->hasOne(Dispute::className(),['id_car'=>'id']);
    }
	    
    public function getBayerid(){
        return $this->hasOne(User::className(),['id'=>'buyer']);
    }
	
    public function getNote(){
        return $this->hasMany(CarNote::className(),['id_car'=>'id']);
    }
	
			//Оплата
	public function getPay() {
    $arrey = ['0'=>['По договору <br> с продавцом'],'1'=>['Оплачен <br> (Гарант-Сервис)'],'2'=>['Доставлен'],'2'=>['Отменен'],'3'=>['Завершен']];
	return $arrey[$this->pay][0];	
	}	
		//Статус
	public function getStatus() {
    $arrey = ['0'=>['На рассмотрении'],'1'=>['Отправлен'],'2'=>['Доставлен'],'3'=>['Отменен'],'4'=>['Завершен']];
	return $arrey[$this->status][0];	
	}
	
}
