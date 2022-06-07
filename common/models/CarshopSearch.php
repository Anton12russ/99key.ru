<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Car;

/**
 * CarSearch represents the model behind the search form of `common\models\Car`.
 */
class CarshopSearch extends Car
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'pay', 'price', 'user_id', 'buyer'], 'integer'],
            [['data_add', 'data_end', 'id_product'], 'safe'],
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
    public function search($params, $buyer)
    {
        $query = Car::find()->with('shop');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        		'sort'=>[
              'defaultOrder'=>[
                     'id'=>SORT_DESC
                ]],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		$this->user_id = $buyer;
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
           
            'data_end' => $this->data_end,
            'status' => $this->status,
            'pay' => $this->pay,
            'price' => $this->price,
            'user_id' => $this->user_id,
            'buyer' => $this->buyer,
        ]);


        $query->andFilterWhere(['like', 'id_product', $this->id_product]);
        $query->andFilterWhere(['like', 'data_add', $this->data_add]);
        return $dataProvider;
    }
}
