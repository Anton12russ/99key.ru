<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "block".
 *
 * @property int $id
 * @property string $name
 * @property string $text
 * @property string $position
 * @property int $status
 * @property string $date_add
 * @property string $date_del
 * @property string $action
 * @property int $registr
 * @property int $category
 * @property int $region
 * @property int $sort
 * @property int $header_ok
 */
class Block extends \yii\db\ActiveRecord
{
		
		
public function behaviors( ) {
	    return [
	        [
	            'class' => 'sjaakp\sortable\Sortable',
	        ],
	    ];
	}
	const STATUS_LIST = ['На модерации','Опубликовано'];
	const POSITION_LIST = [
	    'top' => 'Под шапкой',
        'footer' => 'Перед подвалом',
        'right' => 'Справа',
		'add' => 'Между объявлениями',
		];
	const ACTION_LIST = [
        'all' => 'Везде',
		'blog/index' => 'Главная страница',
		'blog/' => 'Объявление (На всех страницах)',
		'blog/category' => 'Объявление (Только в рубриках)',
		'blog/notepad' => 'Объявление (Избранные)',
		'blog/one' => 'Объявление (На странице объявления)',
		'blog/add' => 'Объявление (Подача объявления)',
		'shop/' => 'Магазтины, на всех страницах',
		'user/' => 'Личный кабинет пользователя',
		'passanger/' => 'Попутчики',
		'auction/' => 'Аукционы',
		];
		
	
		
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'block';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
		$date = date('Y-m-d');

        return [
            [['name', 'text', 'position', 'status',  'action', 'registr', 'header_ok'], 'required'],
            [['text'], 'string'],
			[['sort'], 'default', 'value'=> 10000],
			[['date_add'], 'default', 'value'=> $date],
            [['status', 'registr', 'category', 'region', 'sort'], 'integer'],
            [['date_add', 'date_del'], 'safe'],
            [['name'], 'string', 'max' => 30],
            [['position'], 'string', 'max' => 10],
            [['action'], 'string', 'max' => 20],
			[['header_ok'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
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
            'registr' => 'Только для зарегистрированных',
            'category' => 'Категория',
            'region' => 'Регион',
            'sort' => 'Сортировка',
			'header_ok' => 'Показывать шапку блока',
			'Header' => 'Показывать шапку блока',
			'Action' => 'Компонент',
			'Registr' => 'Только для зарегистрированных',
        ];
    }
	
		//Статус
	public function getStatus() {
    $arrey = ['0'=>['На модерации'],'1'=>['Опубликовано'],'2'=>['Удалено']];
	return $arrey[$this->status][0];	
	}
			//Позиция
	public function getPosition() {
    $arrey = [
	    'top' => ['Под шапкой'],
        'footer' => ['Перед подвалом'],
        'right' => ['Справа'],
		'add' => ['Между объявлениями'],
	];
	return $arrey[$this->position][0];	
	}
	
				//Компонент
	public function getAction() {
    $arrey = [
	    'all' => ['Везде'],
		'blog/index' => ['Главная страница'],
		'blog/' => ['Объявление (На всех страницах)'],
		'blog/category' => ['Объявление (Только в рубриках)'],
		'blog/notepad' => ['Объявление (Избранные)'],
		'blog/one' => ['Объявление (На странице объявления)'],
		'blog/add' => ['Объявление (Подача объявления)'],
		'shop/' => ['Магазтины, на всех страницах'],
		'user/' => ['Личный кабинет пользователя'],
		'passanger/' => ['Попутчики'],
		'auction/' => ['Аукционы'],
	];
	return $arrey[$this->action][0];	
	}
	
	//Видно
	public function getRegistr() {
    $arrey = [
	    '0' => ['Видно всем'],
        '1' => ['Только для зарегистрированных'],
	];
	return $arrey[$this->registr][0];	
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
