<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "field".
 *
 * @property int $id
 * @property int $cat
 * @property string $name
 * @property string $values
 * @property int $max
 * @property string $type
 * @property string $type_string
 * @property int $req
 * @property int $hide
 * @property int $block
 * @property int $sort
 * @typeField
 * @typeStringField
 * @requiredField
 * @hidenField
 * @blockField
 */
class Field extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'field';
    }



	//Начало функции последовательности категорий
function linenav($arr, $cats_id, $first = true) {
$cats_id = $arr[$cats_id];
 static $array = array();
    if($cats_id['parent'] != 0 && $cats_id['parent'] != "")
        {
     Field::linenav($arr, $cats_id['parent'], false);
		}else{
		$array = array();
		}
   $array[] = array('name' => $cats_id['name'], 'id' => $cats_id['id']);
    foreach($array as $k=>$v)
        {
        $return .= $v['name'];
        if($k != (count($array)-1)){$return .= ' > ';}
        }
		if ($return) {
   return  $return;
		}else{
   return  'Для всех';
  
		}

    }








//Тип поля
	public function getTypeField() {
    $arrey = ['v'=>['Строковое значение text'],'t'=>['Текстовая область textarea'],'s'=>['Выпадающий список select'],'c'=>['Флажки для выбора checkbox'],'r'=>['Переключатель radio'],'p'=>['Цена'],'y'=>['Ссылка на ролик youtube'],'j'=>['Метка на Яндекс.Картах']];
	return $arrey[$this->type][0];	
	}
//Тип Строки	
	public function getTypeStringField() {
    $arrey = ['n'=>['Число'],'l'=>['Латинские символы'],'t'=>['Телефонный номер'],'x'=>['Факс'],'u'=>['Адрес сайта'],'a'=>['Адрес'],'q'=>['Торг'], ''=>['Не задано']];
	return $arrey[$this->type_string][0];	
	}
//Обязательное для заполнения
	public function getRequiredField() {
    $arrey = ['1'=>['Обязательно для заполнения'],'2'=>['Не обязательно для заполнения']];
	return $arrey[$this->req][0];	
	}
//Скрыть для неавторизированных	
	public function getHidenField() {
    $arrey = [['Показывать'],['Скрыть']];
	return $arrey[$this->hide][0];	
	}
	
	//Показывать при отборе в фильтре
	public function getBlockField() {
    $arrey = [['Скрыть'],['Показывать']];
	return $arrey[$this->block][0];	
	}







    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'max', 'req', 'hide', 'block', 'sort', 'cat'], 'integer'],
            [['name'], 'required'],
            [['values'], 'string'],
            [['name'], 'string', 'max' => 1000],
            [['type', 'type_string'], 'string', 'max' => 1],
			[['req'], 'default', 'value'=> '2'],
			[['hide'], 'default', 'value'=> '0'],
			[['block'], 'default', 'value'=> '1'],
			[['type'], 'default', 'value'=> 'v'],
			[['cat'], 'default', 'value'=> 0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat' => 'Категория',
            'name' => 'Название поля',
            'values' => 'Значения',
            'max' => 'Ограничение в символах',
            'type' => 'Тип поля',
            'type_string' => 'Тип строки',
            'req' => 'Обязательное для заполения',
            'hide' => 'Скрыть для незарегистрированных в объявлении',
            'block' => 'Показывать при поиске',
            'sort' => 'Сортировка',
			'typeField' => 'Тип поля',
			'typeStringField' => 'Тип стоки',
            'requiredField' => 'Обязательное для заполнения',
            'hidenField' => 'Скрыть для незарегистрированных в объявлении',
			'blockField' => 'Показывать при отборе, в фильтре',

        ];
    }
}
