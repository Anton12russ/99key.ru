<?php

namespace common\models;
use common\models\Blog;
use common\models\Shop;
use common\models\Article;
use common\models\User;
use Yii;

/**
 * This is the model class for table "message_route".
 *
 * @property int $id
 * @property string $route
 * @property int $message
 * @property int $user_too
 * @property int $user_from
 */
class MessageRoute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_route';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['route', 'message', 'user_too', 'user_from'], 'required'],
            [['message', 'user_too', 'user_from'], 'integer'],
            [['route'], 'string', 'max' => 20],
        ];
    }
	
	//Связь с Автором
	public function getAuthorToo() {
      return $this->hasOne(User::className(),['id'=>'user_too']);
	}
	public function getAuthorFrom() {
       return $this->hasOne(User::className(),['id'=>'user_from']);
	}	
	//Связь с объявлением
	public function getBlogs() {
      return $this->hasOne(Blog::className(),['id'=>'message']);
	}
	
	//Связь с Магазином
	public function getShops() {
      return $this->hasOne(Shop::className(),['id'=>'message']);
	}
	
	//Связь со статьей
	public function getArticles() {
      return $this->hasOne(Article::className(),['id'=>'message']);
	}

	//Связь c Попутчиком
	public function getPassangers() {
      return $this->hasOne(Passanger::className(),['id'=>'message']);
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route' => 'Route',
            'message' => 'Message',
            'user_too' => 'User Too',
            'user_from' => 'User From',
        ];
    }
}
