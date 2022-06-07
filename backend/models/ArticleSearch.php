<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Article;

/**
 * ArticleSearch represents the model behind the search form of `common\models\Article`.
 */
class ArticleSearch extends Article
{
	
	public $author;
	public $userupdate;	
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'cat', 'rayting'], 'integer'],
            [['title', 'text', 'author', 'user_update', 'userupdate', 'date_update', 'date_add', 'date_end'], 'safe'],
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

        $query =  Article::find()->joinWith(@author);

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

        // add conditions that should always apply here


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
            'status' => $this->status,
			'rayting' => $this->rayting,
            'cat' => $this->cat,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'user.email', $this->author])
			->andFilterWhere(['like', 'date_add', $this->date_add])
			->andFilterWhere(['like', 'date_end', $this->date_end])
			->andFilterWhere(['like', 'date_update', $this->date_update])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}
