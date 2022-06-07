<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Payment;

/**
 * PaymentSearch represents the model behind the search form of `common\models\Payment`.
 */
class PaymentSearch extends Payment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'blog_id', 'status'], 'integer'],
            [['price'], 'number'],
            [['currency', 'system', 'services', 'time'], 'safe'],
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

    public function search($params, $user)
    {
        $query = Payment::find();

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
		if ($user) {
			 $this->user_id = $user;
		}
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'user_id' => $this->user_id,
            'blog_id' => $this->blog_id,
            'status' => $this->status,
 
        ]);

        $query->andFilterWhere(['like', 'time', $this->time])
		    ->andFilterWhere(['like', 'system', $this->system])
            ->andFilterWhere(['like', 'services', $this->services]);

        return $dataProvider;
    }
}
