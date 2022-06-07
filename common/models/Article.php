<?php

namespace common\models;
use common\models\User;
use common\models\ArticleCat;
use Yii;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property int $status
 * @property int $cat
 * @property string $text
 * @property string $rayting
 */
class Article extends \yii\db\ActiveRecord
{
	
	const STATUS_LIST = ['На модерации','Опубликовано','Удалено'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'user_id', 'status', 'cat', 'text'], 'required'],
            [['user_id', 'status', 'cat', 'user_update', 'rayting'], 'integer'],
            [['text', 'date_add', 'date_end', 'date_update'], 'string'],
            [['title'], 'string', 'max' => 300],
			[['author'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название статьи',
            'user_id' => 'Пользователь',
            'status' => 'Статус',
            'cat' => 'Категория',
            'text' => 'Текст',
            'author.username' => 'Автор',
			'cats.name' => 'Категория',
			'date_add' => 'Дата добавления',
			'date_end' => 'Дата окончания',
			'date_update' => 'Дата редактирования',
		    'user_update' => 'Редактор',
			'Status' => 'Статус',
			'author' => 'Автор',
			'author.email' => 'Автор',		
			'userupdate.email' => 'Редактор',				
			
        ];
    }
	
	public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        if ($insert) {
			$this->date_add = date('Y-m-d H:i:s'); 
        } else {
            $this->date_update = date('Y-m-d H:i:s'); 
			$this->user_update = Yii::$app->user->id; 
        }
        return true;
    } else {
        return false;
    }
}
	  //Связь с Автором
	public function getAuthor() {
     return $this->hasOne(User::className(),['id'=>'user_id']);
	}
	
		  //Связь с редактором
	public function getUserupdate() {
     return $this->hasOne(User::className(),['id'=>'user_update']);
	}
	//Связь с Катигорией
	public function getCats() {
     return $this->hasOne(ArticleCat::className(),['id'=>'cat']);
	}
	//Статус
	public function getStatus() {
    $arrey = ['0'=>['На модерации'],'1'=>['Опубликовано'],'2'=>['Удалено']];
	return $arrey[$this->status][0];	
	}
}
