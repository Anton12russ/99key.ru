<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
	public $reCaptcha;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
		
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
  $capcha['action'] = 'user/contact';
  
}

if (!isset($capcha)) {
  $capcha = [['reCaptcha'], 'default', 'value'=> 1];
}

if(!Yii::$app->user->can('updateOwnPost', ['article' => '']) && !Yii::$app->user->can('updateArticle')) {}else{
  $capcha = [['reCaptcha'], 'default', 'value'=> 1];
}
        return [
		    $capcha,
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */

	public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
			'email' => 'email',
			'subject' => 'Тема сообщения',
			'body' => 'Текст сообщения',
			'verifyCode' => 'Проверочный код',
        ];
    }
    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo(Yii::$app->caches->setting()['email-support'])
            ->setFrom(Yii::$app->caches->setting()['email'])
            ->setReplyTo([$this->email => $this->name])
            ->setSubject('Вопрос с сайта '.Yii::$app->caches->setting()['site_name'].' ('.$this->subject.')')
            ->setTextBody($this->body)
            ->send();
    }
}
