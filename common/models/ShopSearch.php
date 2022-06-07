<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Shop;

/**
 * ShopSearch represents the model behind the search form of `common\models\Shop`.
 */
class ShopSearch extends Shop
{
	
	public $author;
		
		
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category', 'region', 'active', 'status',  'rayting'], 'integer'],
            [['name', 'text', 'date_end', 'author', 'date_add', 'img', 'phone'], 'safe'],
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
    public function search($params)
    {
        $query = Shop::find()->joinWith(@author);

        // add conditions that should always apply here

   
   $dataProvider = new ActiveDataProvider([

	'query' => $query,
        'pagination' => [
        'pageSize' => 20,
      ],
     ]);
	 
	  // Сортировка по связной таблице
   $dataProvider->sort->attributes['author'] = [
        // The tables are the ones our relation are configured to
        // in my case they are prefixed with "tbl_"
        'asc' => ['user.email' => SORT_ASC],
        'desc' => ['user.email' => SORT_DESC],
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
            'user_id' => $this->user_id,
            'category' => $this->category,
            'region' => $this->region,
            'shop.status' => $this->status,
			'active' => $this->active,
           /* 'date_end' => $this->date_end,
            'date_add' => $this->date_add,*/
            'rayting' => $this->rayting,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'text', $this->text])
			
			->andFilterWhere(['like', 'date_add', $this->date_add])	
            ->andFilterWhere(['like', 'date_end', $this->date_end])	
			
			->andFilterWhere(['like', 'user.email', $this->author])
            ->andFilterWhere(['like', 'img', $this->img])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
