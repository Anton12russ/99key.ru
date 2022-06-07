<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Car;

/**
 * CarSearch represents the model behind the search form of `common\models\Car`.
 */
class CarSearch extends Car
{

	public $author;
	public $shop;
	public $user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'pay', 'price', 'user_id', 'buyer'], 'integer'],
            [['data_add', 'data_end', 'id_product', 'author', 'shop', 'user'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $buyer, $shop_id = false)
    {
        $query = Car::find()->joinWith(@author)->joinWith(@shop)->joinWith(@user)->joinWith(@note)->with('shop');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        		'sort'=>[
              'defaultOrder'=>[
                     'id'=>SORT_DESC
                ]],
        ]);
 // Сортировка по связной таблице
   $dataProvider->sort->attributes['author'] = [
        // The tables are the ones our relation are configured to
        // in my case they are prefixed with "tbl_"
        'asc' => ['user.email' => SORT_ASC],
        'desc' => ['user.email' => SORT_DESC],
    ];

 // Сортировка по связной таблице
   $dataProvider->sort->attributes['shop'] = [
        // The tables are the ones our relation are configured to
        // in my case they are prefixed with "tbl_"
        'asc' => ['shop.name' => SORT_ASC],
        'desc' => ['shop.name' => SORT_DESC],
    ];		
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		if ($buyer) {
			 $this->buyer = $buyer;
		}
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
           
            'data_end' => $this->data_end,
            'car.status' => $this->status,
            'pay' => $this->pay,
            'price' => $this->price,
            'user_id' => $this->user_id,
            'buyer' => $this->buyer,
        ]);
	if($shop_id) {$query->andFilterWhere(['shop_id' => $shop_id]);}
        $query->andFilterWhere(['like', 'user.email', $this->author]);
		$query->andFilterWhere(['like', 'shop.name', $this->shop]);
		$query->andFilterWhere(['like', 'user.id', $this->user]);
        $query->andFilterWhere(['like', 'id_product', $this->id_product]);
        $query->andFilterWhere(['like', 'data_add', $this->data_add]);
        return $dataProvider;
    }
}
