<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BlogServices;

/**
 * BlogTimeSearch represents the model behind the search form of `common\models\BlogServices`.
 */
class BlogServicesSearch extends BlogServices
{
	
	public $blog;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'blog_id'], 'integer'],
            [['type', 'date_end', 'blog', 'date_add'], 'safe'],
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
        $query = BlogServices::find()->joinWith(@blog);
        // add conditions that should always apply here

 $dataProvider = new ActiveDataProvider([
	'query' => $query,
    'pagination' => [
        'pageSize' => 20,
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
			'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'date_end', $this->date_end])
		->andFilterWhere(['like', 'blog.title', $this->blog])
		->andFilterWhere(['like', 'date_add', $this->date_add]);

        return $dataProvider;
    }
}
