<?php

namespace common\models;
use common\models\Blog;
use Yii;

/**
 * This is the model class for table "blog_services".
 *
 * @property int $id
 * @property string $date_end
 * @property string $date_add
 * @property int $blog_id
 * @property string $type
 */
class BlogServices extends \yii\db\ActiveRecord
{

		const TYPE_LIST = [
	    'top' => 'Топ',
        'block' => 'Правый блок',
        'bright' => 'Выделить цветом',
		'complex' => 'Комплекс',
		];
		
		
		
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['blog_id'], 'required'],
            [['id', 'blog_id'], 'integer'],
			[['type'], 'safe'],
        ];
    }
	

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
              'id' => 'ID',
              'type' => 'Услуга',
              'block' => 'Правый блок',
              'bright' => 'Выделить цветом',
		      'complex' => 'Комплекс',
			  'blog_id' => 'ID объявления',
			  'date_end' => 'Окончание услуги',
			  'date_add' => 'Начало услуги',
			  'blog' => 'Название объявления',
        ];
    }
	
  //Связь с объявлением
	public function getBlog() {
    return $this->hasOne(Blog::className(),['id'=>'blog_id']);
	}
			//Статус
	public function getType() {
    $arrey = [
	
	    'top' => ['Топ'],
        'block' => ['Правый блок'],
        'bright' => ['Выделить цветом'],
		'complex' => ['Комплекс'],
	];
	return $arrey[$this->type][0];	
	}
}
