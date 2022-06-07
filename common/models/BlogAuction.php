<?php

namespace common\models;
use common\models\Bet;
use Yii;

/**
 * This is the model class for table "blog_auction".
 *
 * @property int $id
 * @property string $price_add
 * @property string $price_moment
 * @property string $date_add
 * @property string $date_end
 * @property int $blog_id
 * @property int $user_id
 */
class BlogAuction extends \yii\db\ActiveRecord
{
public $auction;
public $status;
public $url;
	const AUCTION_LIST = array(1 => 'Торги активны',2 => 'Зарезервиновано',3 => 'Снято с торгов');
	const STATUS_LIST = ['На модерации','Опубликовано','Удалено'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_auction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price_add', 'date_add', 'date_end', 'blog_id', 'user_id'], 'required'],
            [['blog_id', 'user_id'], 'integer'],
            [['date_add', 'date_end'], 'safe'],
            [['price_add', 'price_moment'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'price_add' => 'Начальная цена',
            'price_moment' => 'Цена моментальной продажи',
            'date_add' => 'Дата добавления',
            'date_end' => 'Дата окончания',
            'blog_id' => 'Blog ID',
            'user_id' => 'User ID',
			'blog' => 'Товар',
			'auction' => 'Аукцион',
			'status' => 'Статус объявления'
        ];
    }


	public function getUser() {
       return $this->hasOne(User::className(),['id'=>'user_id']);
	}
	
		
  	public function getBlog() {
       return $this->hasOne(Blog::className(),['id'=>'blog_id']);
	}

  	public function getBets() {
       return $this->hasMany(Bet::className(),['blog_id'=>'blog_id']);
	}	
	
			//Статус
	public function getAuction() {

      $arrey = ['0' => '1', '1'=>['Торги активны'],'2'=>['Зарезервиновано'],'3'=>['Снято с торгов']];
	  if(isset($arrey[$this->auction][0])) {
	  return $arrey[$this->auction][0];	
	  }
	}
}
