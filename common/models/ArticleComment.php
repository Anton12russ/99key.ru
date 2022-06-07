<?php

namespace common\models;
use common\models\User;
use common\models\Article;
use Yii;

/**
 * This is the model class for table "article_comment".
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
class ArticleComment extends \yii\db\ActiveRecord
{

const STATUS_LIST = ['На модерации','Активирован'];


	public $reCaptcha;
	public $no_captcha;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_comment';
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
	
	//Связь с Article_field
	public function getAuthor() {
       return $this->hasOne(User::className(),['id'=>'user_id']);
	}

	//Связь с Article_field
	public function getArticle() {
       return $this->hasOne(Article::className(),['id'=>'blog_id']);
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
           // 'id' => 'ID',
            'blog_id' => 'ID Статьи',
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
