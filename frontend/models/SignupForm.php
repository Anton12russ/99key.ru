<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
	public $identity;
	public $balance;
	public $reCaptcha;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
				//Капча видимая
if (Yii::$app->caches->setting()['capcha'] == 1) {
  $capcha[0] = ['reCaptcha'];
  $capcha[1] = \himiklab\yii2\recaptcha\ReCaptchaValidator2::className();
  $capcha['secret'] = explode("\n",Yii::$app->caches->setting()['recapcha2'])[1];
  $capcha['uncheckedMessage'] = 'Пожалуйста укажите проверочный код';
}

//Капча невидимая
if (Yii::$app->caches->setting()['capcha'] == 2) {
  $capcha[0] = ['reCaptcha'];
  $capcha[1] = \himiklab\yii2\recaptcha\ReCaptchaValidator3::className();
  $capcha['secret'] = explode("\n",Yii::$app->caches->setting()['recapcha3'])[1];
  $capcha['threshold'] = 0.5;
  $capcha['action'] = 'comment/add';
  
}
        return [
		$capcha,
            ['username', 'trim'],
            ['username', 'required'],
            //['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
			['identity', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот адрес электронной почты уже занят.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
		if($this->identity) {
		  $user->identity = $this->identity;
		  $user->status = 10;
		}
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
		if(!$this->identity) {
           return $user->save() && $this->sendEmail($user);
		}else{
		   return $user->save();
		}

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->functionMail->emailAdmin() => Yii::$app->caches->setting()['email']])
            ->setTo($this->email)
            ->setSubject('Регистрация аккаунта на ' . Yii::$app->caches->setting()['site_name'])
            ->send();
    }
	
		   public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
			'status' => 'Статус',
			'balance' => 'Баланс',
			'balance_temp' => 'Временный Баланс',
			'password' => 'Новый пароль',
			'Status' => 'Статус',
			'created_at' => 'Дата создания',
			'updated_at' => 'Дата обновления',
        ];
    }
}
