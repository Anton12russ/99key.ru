<?php

namespace common\models;
use common\models\User;
use common\models\Blog;
use Yii;

/**
 * This is the model class for table "blog_comment".
 *
 * @property int $id
 * @property int $blog_id
 * @property int $user_id
 * @property string $date
 * @property string $text
 * @property string $user_name
 * @property string $user_email
 * @property int $status
 */
class BlogComment extends \yii\db\ActiveRecord
{

const STATUS_LIST = ['На модерации','Активирован'];


	public $reCaptcha;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [

            [['blog_id', 'date', 'text', 'user_name', 'user_email', 'status'], 'required'],
            [['blog_id', 'user_id', 'status', 'too'], 'integer'],
            [['date'], 'safe'],
            [['text'], 'string'],
            [['user_name'], 'string', 'max' => 100],
            [['user_email'], 'string', 'max' => 70],
			[['user_email'], 'email'],
        ];
    }
	
	//Связь с Blog_field
	public function getAuthor() {
       return $this->hasOne(User::className(),['id'=>'user_id']);
	}

	//Связь с Blog_field
	public function getBlog() {
       return $this->hasOne(Blog::className(),['id'=>'blog_id']);
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
           // 'id' => 'ID',
            'blog_id' => 'ID объявления',
            'user_id' => 'Id пользователя',
            'date' => 'Дата добавления',
            'text' => 'Текст комментария',
            'user_name' => 'Имя пользователя',
            'user_email' => 'Email пользователя',
            'status' => 'Статус',
			'Status' => 'Статус',
			'too' => 'Кому',
			'author' => 'Автор',
        ];
    }
	
	
	
	//Статус
	public function getStatus() {
    $arrey = ['0'=>['На модерации'],'1'=>['Активирован']];
	return $arrey[$this->status][0];	
	}	
}
