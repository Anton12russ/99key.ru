<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Bet;

/**
 * BetSearch represents the model behind the search form of `common\models\Bet`.
 */
class BetSearch extends Bet
{
	
	public $blog;
	
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'blog_id', 'user_id', 'currency', 'status'], 'integer'],
            [['price', 'date_add', 'blog',], 'safe'],
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
    public function search($params, $user_id = false)
    {
       	if ($user_id) {
			$query = Bet::find()->Where(['bet.user_id' => $user_id])->joinWith(@blog);
		}else{
			$query = Bet::find()->joinWith(@blog);
		
		}

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([

	'query' => $query,
	 'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ],


     ]);
		
				 // Сортировка по связной таблице
   $dataProvider->sort->attributes['blog'] = [
        // The tables are the ones our relation are configured to
        // in my case they are prefixed with "tbl_"
        'asc' => ['blog.title' => SORT_ASC],
        'desc' => ['blog.title' => SORT_DESC],
    ];
		

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'blog_id' => $this->blog_id,
            'user_id' => $this->user_id,
            'currency' => $this->currency,
            'date_add' => $this->date_add,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'price', $this->price])
->andFilterWhere(['like', 'blog.title', $this->blog]);
        return $dataProvider;
    }
}
