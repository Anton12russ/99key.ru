<?php

namespace common\models;
use common\models\Car;
use Yii;

/**
 * This is the model class for table "dispute".
 *
 * @property int $id
 * @property int $id_car
 * @property int $id_user
 * @property int $id_bayer
 * @property int $cashback
 * @property int $status
 * @property string $date
 * @property string $date_update
 * @property string $flag_bayer
 * @property string $flag_shop
 * @property string $flag_admin 
 */
class Dispute extends \yii\db\ActiveRecord
{
	const STATUS_LIST = ['Ожидает','На рассмотрении','Закрыто'];
	public $text;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dispute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_car', 'id_user', 'id_bayer', 'status','text', 'cashback'], 'required'],
            [['id_car', 'id_user', 'id_bayer', 'status', 'cashback', 'flag_bayer', 'flag_shop', 'flag_admin'], 'integer'],
            [['text', 'date', 'date_update'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_car' => 'ID корзины',
            'id_user' => 'Продавец',
            'id_bayer' => 'Покупатель',
            'status' => 'Статус',
			'text' => 'Текст претензии',
			'date' => 'Дата добавления',
			'date_update' => 'Дата обновления',			
			'cashback' => 'Сумма возврата',
			'selleradmin' => 'Продавец',
			'bayeradmin' => 'Покупатель'
        ];
    }
	
	//Связь с корзиной
    public function getCar() {
      return $this->hasOne(Car::className(),['id'=>'id_car']);
	}
	
	//Связь с Продавцом
	public function getSelleradmin() {
        return $this->hasOne(User::className(),['id'=>'id_user']);
	}

	//Связь с Покупателем
	public function getBayeradmin() {
        return $this->hasOne(User::className(),['id'=>'id_bayer']);
	}

	//Статус
	public function getStatus() {
      $arrey = ['0'=>['Ожидает'],'1'=>['На рассмотрении'],'2'=>['Закрыто']];
	  return $arrey[$this->status][0];	
	}

}
