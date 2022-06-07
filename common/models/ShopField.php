<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shop_field".
 *
 * @property int $id
 * @property int $shop_id
 * @property string $payment
 * @property string $delivery
 * @property string $grafik
 * @property string $stocks
 * @property string $address
 * @property string $coord
 * @property string $phone
 * @property string $site 
 * @property int $pay_delivery
 * @property string $private_payment
 * @property int $private_payment
 */
class ShopField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'payment', 'delivery', 'grafik', 'stocks', 'address', 'coord', 'phone', 'сhoice_pay'], 'required'],
            [['shop_id', 'pay_delivery', 'сhoice_pay'], 'integer'],
            [['payment', 'delivery', 'stocks', 'site', 'private_payment', 'price'], 'string'],
            [['grafik', 'address'], 'string', 'max' => 200],
            [['coord'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 15],
			[['pay_delivery'], 'string', 'max' => 11],
			
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'payment' => 'Payment',
            'delivery' => 'Delivery',
            'grafik' => 'Grafik',
            'stocks' => 'Stocks',
            'address' => 'Address',
            'coord' => 'Coord',
            'phone' => 'Phone',
			'site' => 'Site',
			'private_payment' => 'private_payment',
			'pay_delivery'=>'Стоимость фиксированной доставки',
			'сhoice_pay' => 'Возможность оплаты'
        ];
    }
}
