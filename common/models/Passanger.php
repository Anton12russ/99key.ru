<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "passanger".
 *
 * @property int $id
 * @property string $date_add
 * @property int $user_id
 * @property string $ot
 * @property string $kuda
 * @property string $time
 * @property int $price
 * @property int $appliances
 * @property int $mesta
 * @property string $img
 */
class Passanger extends \yii\db\ActiveRecord
{
	const APPLIANCESS = ['Водитель', 'Пассажир'];
	public $coord_ot;
	public $coord_kuda;
	public $text;
	public $marka;
	public $phone;
	public $dir_name;
	public $clock;
	public $name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'passanger';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ot', 'kuda', 'time', 'appliances', 'coord_ot', 'coord_kuda', 'phone', 'pol', 'clock', 'mesta'], 'required'],
            [['date_add', 'time'], 'safe'],
            [['user_id', 'price', 'appliances', 'mesta', 'pol'], 'integer'],
            [['ot', 'kuda', 'coord_ot', 'coord_kuda', 'img', 'marka', 'phone', 'name'], 'string', 'max' => 300],
			[['text', 'dir_name', 'img', 'clock'], 'string'],
        ];
    }



	//Связь
	public function getFields() {
    return $this->hasOne(PassangerFields::className(),['passanger_id'=>'id']);
	}
   //Связь с Автором
	public function getAuthor() {
    return $this->hasOne(User::className(),['id'=>'user_id']);
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_add' => 'Дата добавления',
            'user_id' => 'User ID',
            'ot' => 'Место отправления',
            'kuda' => 'Завершение маршрута',
            'time' => 'Время отбытия',
            'price' => 'Цена',
            'appliances' => 'Пренадлежность',
            'mesta' => 'Места',
			'phone' => 'Телефон',
			'img' => 'Фото',
			'pol' => 'Пол',
			'clock' => 'Время',
			'author' => 'Автор'
        ];
    }
	
		//Статус
	public function getAppliancess() {
    $arrey = ['0'=>['Водитель'],'1'=>['Пассажир']];
	return $arrey[$this->appliances][0];	
	}
}
