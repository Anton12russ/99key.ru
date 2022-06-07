<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bet".
 *
 * @property int $id
 * @property int $blog_id
 * @property int $user_id
 * @property string $price
 * @property int $currency
 * @property string $date_add
 */
class Bet extends \yii\db\ActiveRecord
{
public $price_false;
public $del;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['price'], 'required'],
            [['blog_id', 'user_id', 'currency', 'status', 'del'], 'integer'],
            [['date_add'], 'safe'],
            [['price'], 'string', 'max' => 11],
			[['price'], 'validatePrice'],
        ];
		
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'blog_id' => 'Blog ID',
            'user_id' => 'User ID',
            'price' => 'Ставка',
            'currency' => 'Валюта',
            'date_add' => 'Дата добавления',
			'blog' => 'Объявление',
			
        ];
    }
	
	 public function validatePrice($attribute, $params, $validator)
    {
        $field = BlogField::find()->Where(['message' => $this->blog_id, 'field' => '481'])->One();
        $blog = Blog::findOne($this->blog_id);
		if($this->price <= $field->value ) {
		    $this->addError('price', 'Ставка должна быть не ниже предыдущей');
		}
		
		if($blog->user_id == Yii::$app->user->id ) {
		    $this->addError('price', 'Вы не можете учавствовать в ставках собственного лота');
		}

	
	}
	
	
	    //Связь с Автором
	public function getAuthor() {
       return $this->hasOne(User::className(),['id'=>'user_id']);
	}
	
	
	public function getBlog() {
       return $this->hasOne(Blog::className(),['id'=>'blog_id']);
	}
	
	public function getRates() {
       return $this->hasOne(Rates::className(),['id'=>'currency']);
	}
}
