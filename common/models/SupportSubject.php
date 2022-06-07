<?php

namespace common\models;

use Yii;
use common\models\User;
/**
 * This is the model class for table "support_subject".
 *
 * @property int $id
 * @property int $user_id
 * @property string $subject
 * @property string $date_add
 * @property string $date_update
 * @property int $status
 * @property int $flag_user
 * @property int $flag_admin
 */
class SupportSubject extends \yii\db\ActiveRecord
{
	
	const STATUS_LIST = ['В архиве','В работе'];
	
	const STATUS_FLAG = ['Прочитано','Не прочитано'];
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'support_subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'subject', 'date_add', 'date_update', 'status', 'flag_user', 'flag_admin'], 'required'],
            [['user_id', 'status', 'flag_user', 'flag_admin'], 'integer'],
            [['date_add', 'date_update'], 'safe'],
            [['subject'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'subject' => 'Тема',
            'date_add' => 'Дата создания',
            'date_update' => 'Дата обновления',
            'status' => 'Статус',
            'flag_user' => 'Прочитано пользователем',
            'flag_admin' => 'Прочитано админом',
			'author' => 'Пользователь',
        ];
    }
	
	
	
		//Статус
	public function getStatus() {
      $arrey = ['0'=>['В архиве'],'1'=>['В работе']];
	  return $arrey[$this->status][0];	
	}
	
		//Флаг
	public function getFlag() {
      $arrey = ['0'=>['<span style="color: grey;">Прочитано</span>'],'1'=>['<span style="color: green; font-weight: 600;">Не прочитано</span>']];
	  return $arrey[$this->flag_admin][0];	
	}
	
	
	//Связь с Автором
	public function getAuthor() {
        return $this->hasOne(User::className(),['id'=>'user_id']);
	}

			//Флаг
	public function getFlaguser() {
      $arrey = ['0'=>['<span style="color: grey;">Прочитано</span>'],'1'=>['<span style="color: green; font-weight: 600;">Не прочитано</span>']];
	  return $arrey[$this->flag_user][0];	
	}
}
