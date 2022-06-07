<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "car_buyer".
 *
 * @property int $id
 * @property string $name
 * @property string $family
 * @property string $e-mail
 * @property string $phone
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $address
 * @property int $postcode
 * @property int $car_id

 */
class CarBuyer1 extends \yii\db\ActiveRecord
{ 
    public $private_payment;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_buyer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
		    [['email'], 'email'],
            [['name', 'family', 'email', 'phone', 'private_payment'], 'required'],
            [['car_id', 'private_payment'], 'integer'],
            [['name', 'family', 'email'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 20],
            [['country', 'region', 'city','postcode'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 300],
			[['postcode'], 'string', 'max' => 11],
			//['private_payment', 'validatePrivatePayment'],
        ];
    }



 /*public function validatePrivatePayment($attribute, $params, $validator)
    {
	   if($this->$attribute == 0 || $this->$attribute == 2) { }
		   Yii::$app->request->post()['Car']['password'] 
	       $this->addError('private_payment',  $this->$attribute); 	
	  
	}*/
	
	
	
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'family' => 'Фамилия',
            'email' => 'E-Mail',
            'phone' => 'Телефон',
            'country' => 'Страна',
            'region' => 'Регион',
            'city' => 'Город',
            'address' => 'Адрес',
            'postcode' => 'Индекс',
			'private_payment' => 'Способ оплаты',
			'dostavkashop' => 'Доставка'
			
			
        ];
    }
}
