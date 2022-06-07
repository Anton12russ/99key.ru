<?php

namespace common\models;
use common\models\MessageRoute;
use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $route_id
 * @property int $u_from
 * @property int $u_too
 * @property string $text
 * @property string $date
 * @property int $flag
 */
class Message extends \yii\db\ActiveRecord
{
	public $route_add;
	public $message;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['route_id', 'u_from', 'u_too', 'text'], 'required'],
            [['route_id', 'u_from', 'u_too', 'flag'], 'integer'],
            [['text', 'date', 'route_add', 'message'], 'string'],
            [['route_id'], 'string', 'max' => 20],
        ];
    }
	public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        if ($insert) {
		  if(Yii::$app->user->id) {
			 $id_user = Yii::$app->user->id;
		  }else{
             $id_user = Yii::$app->request->cookies['chat']->value;
		  }
			
           $this->date =  date('Y-m-d H:i:s');
		   $this->u_from = $id_user;
		   $this->flag = 1;
		   if($this->route_add) {
			   $route = new MessageRoute();
			   $route->message = $this->message;
			   $route->route = $this->route_add;
			   $route->user_too = $this->u_too;
			   $route->user_from = $id_user;
		       $route->save(false);
			   $this->route_id = $route->id;   
		   }
        }
        return true;
    } else {
        return false;
    }
}
	//Связь с объявлением
	public function getRoutes() {
      return $this->hasOne(MessageRoute::className(),['id'=>'route_id']);
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route_id' => 'Route ID',
            'u_from' => 'U From',
            'u_too' => 'U Too',
            'text' => 'Text',
            'flag' => 'Flag',
        ];
    }
}
