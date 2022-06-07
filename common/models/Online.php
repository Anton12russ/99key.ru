<?php

namespace common\models;

use Yii;
use common\models\User;
/**
 * This is the model class for table "online".
 *
 * @property int $id
 * @property int $user_id
 * @property string $date
 */
class Online extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'online';
    }

    /**
     * {@inheritdoc}
     */
	 
	//Связь с Пользователем
	public function getUser() {
      return $this->hasOne(User::className(),['id'=>'user_id']);
	}
	
	
    public function rules()
    {
        return [
            [['user_id', 'date'], 'required'],
            [['user_id'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'date' => 'Date',
        ];
    }
}
