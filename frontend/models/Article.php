<?php

namespace frontend\models;
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
	public $reCaptcha;
	
	
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
	
    {		//Капча видимая
	
	

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

if (!isset($capcha)) {
  $capcha = [['reCaptcha'], 'default', 'value'=> 1];
}

if(!Yii::$app->user->can('updateOwnPost', ['article' => '']) && !Yii::$app->user->can('updateArticle')) {}else{
  $capcha = [['reCaptcha'], 'default', 'value'=> 1];
}

        return [
			$capcha,
            [['title', 'cat', 'text'], 'required'],
            [['user_id', 'status', 'cat', 'user_update', 'rayting'], 'integer'],
            [['text', 'date_add', 'date_end', 'date_update'], 'string'],
            [['title'], 'string', 'max' => 300],
			[['author'], 'safe'],
        ];
    }
	
	public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        if ($insert) {

			$this->date_add = date('Y-m-d H:i:s'); 
			$this->user_id = Yii::$app->user->id; 
			$this->date_end = date('Y-m-d H:i:s', strtotime(' + '.Yii::$app->caches->setting()['article-time'].' day'));; 
			$this->status = Yii::$app->caches->setting()['article_moder'];
			
        } else {
	
        }
        return true;
    } else {
        return false;
    }
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
			'author.email' => 'Автор',		
			'userupdate.email' => 'Редактор',				
			
        ];
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
