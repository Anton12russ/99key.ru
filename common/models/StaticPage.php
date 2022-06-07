<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "static_page".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $title
 * @property string $text
 * @property string $description
 * @property string $keywords
 */
class StaticPage extends \yii\db\ActiveRecord
{
	
	const STATUS_LIST = ['Не показывать','Показывать'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'static_page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url', 'title', 'text', 'menu', 'description', 'keywords'], 'required'],
            [['text'], 'string'],
            [['name', 'title', 'description'], 'string', 'max' => 200],
            [['url', 'keywords'], 'string', 'max' => 100],
			[['url'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название статьи',
            'url' => 'Url',
            'title' => 'Мета Title',
            'text' => 'Текст',
            'menu' => 'Показывать в меню - в подвале',			
            'description' => 'Краткое описание (Мета Description)',
            'keywords' => 'Ключевые слова (Мета Keywords)',
			'Menu' =>  'Показывать в меню',      
			];
    }
	
	
	
	//Меню
	public function getMenu() {
    $arrey = ['0'=>['Не показывать'],'1'=>['Показывать']];
	return $arrey[$this->menu][0];	
	}
}
