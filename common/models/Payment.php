<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property double $price
 * @property string $currency
 * @property int $user_id
 * @property int $blog_id
 * @property string $services
 * @property int $status
  * @property int $day
 * @property string $time
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }
	
	
    const STATUS_LIST = ['Ожидает оплаты','Оплачено','Ошибка оплаты'];
	const ACT_LIST = ['shop'=>'Продажа','cachback'=>'Возврат с покупки','car' => 'Гарант-Сервис','act' => 'Активация в платной рубрике', 'top' => 'Поднять в поиске (ТОП)', 'block' => 'Вывод в рекламный блок', 'bright' => 'Выделенное объявление', 'balance' => 'Пополнение баланса'];
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'currency', 'user_id', 'status', 'time', 'system'], 'required'],
            [['price'], 'number'],
            [['user_id', 'blog_id', 'status', 'day'], 'integer'],
            [['time'], 'safe'],
            [['currency'], 'string', 'max' => 10],
            [['services'], 'string', 'max' => 20],
			[['system'], 'string'],
        ];
    }

   //Связь с Автором
	public function getUser() {
    return $this->hasOne(User::className(),['id'=>'user_id']);
	}
	
	
	//Связь с объявлением
	public function getBlog() {
    return $this->hasOne(Blog::className(),['id'=>'blog_id']);
	}
    
	
	
	
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'price' => 'Зачисление',
            'currency' => 'Валюта',
            'user_id' => 'Пользователь (ID)',
            'blog_id' => 'Объявление (ID)',
            'services' => 'Действие',
            'status' => 'Статус',
			'statusid' => 'Статус',
            'time' => 'Время',
			'system' => 'Платежная система',
        ];
    }

	//Скрыть для неавторизированных	
	public function getStatusid() {
    $arrey = [['<span style="color:#eabebe">Не оплачено</span>'],['<span style="color:green">Оплачено</span>'],['<span style="color:green">Не оплачен</span>']];
	return $arrey[$this->status][0];	
	}
	
	
	//Скрыть для неавторизированных	
	public function getServic() {
		
    $arrey = [
	    '' => [''],
		'shop'=>['Продажа'],
		'cachback'=>['Возврат с покупки'],
		'car' => ['Гарант-Сервис'],
	    'act' => ['Активация объявления'],
        'top' => ['Поднять в поиске (ТОП)'],
		'block' => ['Вывод в рекламный блок'],
		'bright' => ['Выделенное объявление'],

	];

	if (!isset($arrey[$this->services][0])) {$arrey[$this->services][0] = 'Пополнение баланса';}
	return $arrey[$this->services][0];	
	}
}
