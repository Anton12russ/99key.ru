<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BlogAuction;

/**
 * BlogAuctionSearch represents the model behind the search form of `common\models\BlogAuction`.
 */
class BlogAuctionSearch extends BlogAuction
{
		public $blog;
        public $auction;
		public $status;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'blog_id', 'user_id', 'rates', 'shag', 'status'], 'integer'],
            [['price_add', 'price_moment', 'date_add', 'date_end', 'blog', 'auction'], 'safe'],
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
			$query = BlogAuction::find()->Where(['blog_auction.user_id' => $user_id])->joinWith(@blog);
		}else{
			$query = BlogAuction::find()->joinWith(@blog);
		
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
            //'blog_auction.user_id' => $this->user_id,
            'rates' => $this->rates,
            'shag' => $this->shag,
        ]);

        $query->andFilterWhere(['like', 'price_add', $this->price_add])
		->andFilterWhere(['like', 'date_add', $this->date_add])
		->andFilterWhere(['like', 'date_end', $this->date_end])
		->andFilterWhere(['like', 'blog.title', $this->blog])
		->andFilterWhere(['like', 'blog.auction', $this->auction])
		->andFilterWhere(['like', 'blog.status_id', $this->status])
            ->andFilterWhere(['like', 'price_moment', $this->price_moment]);

        return $dataProvider;
    }
}
