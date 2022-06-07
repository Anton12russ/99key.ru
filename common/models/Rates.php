<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rates".
 *
 * @property int $id
 * @property int $numcode
 * @property string $charcode
 * @property string $name
 * @property string $text
 * @property int $def
 */
class Rates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numcode', 'charcode', 'name', 'text', 'def'], 'required'],
            [['numcode', 'value', 'def'], 'integer'],
            [['charcode'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 20],
            [['text'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
			'name' => 'Наименование',
            'numcode' => 'Код валюты (цифровой)',
            'charcode' => 'Символ (rur)',
            'text' => 'Иконка валюты ',
			'value' => 'Относительно главной валюты',
            'def' => 'По умолчанию',
			'DefRates' => 'По умолчанию',
        ];
    }
	
	
	//Скрыть для неавторизированных	
	public function getDefRates() {
    $arrey = [['Нет'],['Основная']];
	return $arrey[$this->def][0];	
	}
}
