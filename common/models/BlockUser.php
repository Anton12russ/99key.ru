<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "block_user".
 *
 * @property int $id
 * @property string $name
 * @property string $text
 * @property string $position
 * @property int $status
 * @property string $date_add
 * @property string $date_del
 * @property int $category
 * @property int $header_ok
 * @property int $sort
 */
class BlockUser extends \yii\db\ActiveRecord
{
		
public function behaviors( ) {
	    return [
	        [
	            'class' => 'sjaakp\sortable\Sortable',
	        ],
	    ];
	}

	const STATUS_LIST = ['Ожидает','Опубликовано'];
	const POSITION_LIST = [
	    'top' => 'Под шапкой',
        'footer' => 'Перед подвалом',
        'right' => 'Слева',
		'add' => 'Между объявлениями',
		];
		
		
		
	const ACTION_LIST = [
	    'all' => 'Везде',
		'index' => 'Главная страница',
		'product' => 'Список товара (Только в рубриках)',
		'boardone' => 'Страница товара',
		'mynotepad' => 'Список товара (Избранные)',
		'delivery' => 'Страница "Доставка"',
		'payment' => 'Страница "Оплата"',
		'contact' => 'Страница "Контакты"',
		'cort' => 'Страница "Корзина"',
		];


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
		$date = date('Y-m-d H:i:s');
        return [
            [['name', 'text', 'position', 'status',  'action', 'header_ok'], 'required'],
            [['text'], 'string'],
			[['sort'], 'default', 'value'=> 10000],
			[['date_add'], 'default', 'value'=> $date],
            [['status','category', 'sort', 'user_id'], 'integer'],
            [['date_add', 'date_del'], 'safe'],
            [['name'], 'string', 'max' => 30],
            [['position'], 'string', 'max' => 10],
            [['action'], 'string', 'max' => 20],
			[['header_ok'], 'string', 'max' => 1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'text' => 'Текст',
            'position' => 'Позиция',
			'Position' => 'Позиция',
            'status' => 'Статус',
			'Status' => 'Статус',
            'date_add' => 'Дата добавления',
            'date_del' => 'Дата отключения',
            'action' => 'Компонент',
            'category' => 'Категория',
            'region' => 'Регион',
            'sort' => 'Сортировка',
			'header_ok' => 'Показывать шапку блока',
			'Header' => 'Показывать шапку блока',
			'Action' => 'Статус',

        ];
    }
	
	
	
	
		//Статус
	public function getStatus() {
    $arrey = ['0'=>['Ожидает'],'1'=>['Опубликовано']];
	return $arrey[$this->status][0];	
	}
	
	
			//Позиция
	public function getPosition() {
    $arrey = [
	    'top' => ['Под шапкой'],
        'footer' => ['Перед подвалом'],
        'right' => ['Слева'],
		'add' => ['Между объявлениями'],
	];
	return $arrey[$this->position][0];	
	}
	
	
				//Компонент
	public function getAction() {
    $arrey = [
	    'all' => ['Везде'],
		'index' => ['Главная страница'],
		'product' => ['Список товара (Только в рубриках)'],
		'boardone' => ['Страница товара'],
		'mynotepad' => ['Список товара (Избранные)'],
		'delivery' => ['Страница "Доставка"'],
		'payment' => ['Страница "Оплата"'],
		'contact' => ['Страница "Контакты"'],
		'cort' => ['Страница "Корзина"'],
	];
	return $arrey[$this->action][0];	
	}
	
	
	
	 	//Видно
	public function getHeader() {
    $arrey = [
	    '0' => ['Не показывать'],
        '1' => ['Показывать'],
	];
	return $arrey[$this->header_ok][0];	
	}
	
	
	
	
	

	
	
	
	
	
}
