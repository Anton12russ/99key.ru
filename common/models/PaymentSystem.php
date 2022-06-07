<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_system".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $settings
 * @property string $settings_input
 * @property string $rates
 * @property string $comment
 * @property string $route
 * @property string $smallImage
 */
class PaymentSystem extends \yii\db\ActiveRecord
{

	
	const STATUS_LIST = ['Выключены','Задействованные'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_system';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'rates', 'settings_input', 'route'], 'required'],
			[['status'], 'integer'],
			['settings', 'each', 'rule' => ['string']],
            [['settings_input', 'comment', 'logo', 'rates', 'route'], 'string'],
            [['name'], 'string', 'max' => 30],
        ];
    }


     public function beforeSave($insert)
      {
          // если $insert== true значит, метод вызвался при создании записи, иначе при обновлении
         $saveContinue = parent::beforeSave($insert); // если $saveContinue == false, сохранение будет отменено 
          if(!$insert)
          {    
	          $this->settings = implode("\n",$this->settings);
               
          }
          return $saveContinue ;
      }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'name' => 'Название системы',
            'rates' => 'Валюта',
            'settings' => 'Настройки',
			'status' => 'Статус',
            'settings_input' => 'Настройки настроек',
			'Statusid' => 'Статус',
			'SmallImage' => 'Логотип',
			'route' => 'Название Экшена с настройками',
        ];
    }
	
	
	public function getSmallImage() {
		if($this->logo) {
	    $path = $this->logo;
	    }else{
		$path = '/uploads/images/no-photo.png';
	    }
	return $path;
	}
	
		//Скрыть для неавторизированных	
	public function getStatusid() {
    $arrey = [['Выключена'],['Задействована']];
	return $arrey[$this->status][0];	
	}
}
