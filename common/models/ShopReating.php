<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shop_reating".
 *
 * @property int $id
 * @property int $shop_id
 * @property int $obsluga_plus
 * @property int $obsluga_minus
 * @property int $cena_plus
 * @property int $cena_minus
 * @property int $kachestvo_plus
 * @property int $kachestvo_minus
 */
class ShopReating extends \yii\db\ActiveRecord
{
	
	public $services;
	public $price;
	public $quality;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_reating';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'obsluga_plus', 'obsluga_minus', 'cena_plus', 'cena_minus', 'kachestvo_plus', 'kachestvo_minus'], 'required'],
            [['shop_id', 'obsluga_plus', 'obsluga_minus', 'cena_plus', 'cena_minus', 'kachestvo_plus', 'kachestvo_minus', 'price', 'quality', 'services'], 'integer'],
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
            'obsluga_plus' => 'Obsluga Plus',
            'obsluga_minus' => 'Obsluga Minus',
            'cena_plus' => 'Cena Plus',
            'cena_minus' => 'Cena Minus',
            'kachestvo_plus' => 'Kachestvo Plus',
            'kachestvo_minus' => 'Kachestvo Minus',
        ];
    }
}
