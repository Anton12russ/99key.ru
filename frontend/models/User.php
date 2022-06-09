<?php

namespace frontend\models;
use common\models\UserAlerts;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $phone
 * @property string $name
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $verification_token
 */
class User extends \yii\db\ActiveRecord
{
	public $password;
	public $old_password;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            [['username', 'email', 'updated_at', 'old_password'], 'required'],
			[['balance'], 'integer'],
            [['username', 'identity'], 'string', 'max' => 255],
            [['email'], 'unique'],
			['username', 'trim'],
            ['username', 'string', 'min' => 4, 'max' => 255],  
			['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [['email', 'phone'], 'string', 'max' => 255],
			['password', 'string', 'min' => 4, 'max' => 255],  
            [['password_reset_token'], 'unique'],
			['old_password', 'validateOldPassword'],
			[['balance'], 'default', 'value'=> '0'],
			[['balance_temp'], 'default', 'value'=> '0'],
			
        ];
    }
	
	 public function validateOldPassword($attribute, $params, $validator)
    {
		
		$identity = User::findOne(Yii::$app->user->id);
		if (!Yii::$app->security->validatePassword($this->$attribute, $identity['password_hash'])) {
              $this->addError('old_password', 'Старый пароль не верный');
		}
		
	}
	
      public function beforeSave($insert)
      {
          // если $insert== true значит, метод вызвался при создании записи, иначе при обновлении
         $saveContinue = parent::beforeSave($insert); // если $saveContinue == false, сохранение будет отменено 
          if(!$insert)
          {    
	             if ($this->password) {
	                   $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
					   $this->auth_key = Yii::$app->security->generateRandomString();
		            }
                    $this->updated_at = time();
          }
          return $saveContinue ;
      }
    /**
     * {@inheritdoc}
     */
	 
	 	
	//Связь с настройками оповещения
	public function getAlert() {
         return $this->hasOne(UserAlerts::className(),['user_id'=>'id']);
	}
	 
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'password_hash' => 'Password Hash',
            'email' => 'Email',
			'password' => 'Пароль',
			'old_password' => 'Старый пароль',
			'identity' => 'Идентификация'

        ];
    }
}
