<?php

namespace common\models;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Blog;

/**
 * BlogSearch represents the model behind the search form of `common\models\Blog`.
 */
class BlogSearch extends Blog
{
	
	public $author;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'category', 'region', 'active', 'express'], 'integer'],
            [['title', 'text', 'url', 'date_add', 'author','date_del'], 'safe'],
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
    public function search($params, $user_id_reserv = false, $lot_success = false, $express = false)
    {
		if(!$user_id_reserv) {
           $query = Blog::find()->joinWith(@author)->joinWith('services');
		}else{
		   $query = Blog::find()->where(['reserv_user_id' => $user_id_reserv]);
		   if($lot_success) {
			   $query->andWhere(['auction' => 3]);
		   }
		   $query->joinWith(@author)->joinWith('services');
		}

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([

	'query' => $query,
	 'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ],
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
if ($this->region) {
 $region = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(),$this->region);
}
if ($this->category) {
   $category =  Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $this->category);
}
        if (!isset($category)) {$category = '';}
		if (!isset($region)) {$region = '';}
        if($express === true) {
            $query->andFilterWhere(['express' => '1']);
        }else{
            $query->andFilterWhere(['!=', 'express', '1']);
        }
        $query->andFilterWhere([
            'blog.id' => $this->id,
            'status_id' => $this->status_id,
			'category' => $category,
			'region' => $region,
			'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
			->andFilterWhere(['like', 'user.email', $this->author])
			->andFilterWhere(['like', 'blog.date_add', $this->date_add])
			->andFilterWhere(['like', 'date_del', $this->date_del])
            ->andFilterWhere(['=', 'url', $this->url])
			;

        return $dataProvider;
    }
}
