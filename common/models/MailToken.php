<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mail_token".
 *
 * @property int $id
 * @property string $token
 * @property int $blog_id
 * @property int $user_id
 * @property string $data
 */
class MailToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mail_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token', 'blog_id', 'user_id', 'data'], 'required'],
            [['blog_id', 'user_id'], 'integer'],
            [['data'], 'safe'],
            [['token'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'blog_id' => 'Blog ID',
            'user_id' => 'User ID',
            'data' => 'Data',
        ];
    }
	
	
	
	//Связь с Объявлением
	public function getBlog() {
       return $this->hasOne(Blog::className(),['id'=>'blog_id']);
	}
}
