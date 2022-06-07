<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Passanger;

/**
 * PassangerSearch represents the model behind the search form of `common\models\Passanger`.
 */
class PassangerSearch extends Passanger
{
    /**
     * {@inheritdoc}
     */
	public $author;
	
    public function rules()
    {
        return [
            [['id', 'user_id', 'price', 'appliances', 'mesta', 'pol'], 'integer'],
            [['date_add', 'img', 'ot', 'kuda', 'time', 'author'], 'safe'],
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
        $query = Passanger::find()->joinWith(@author);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			 'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
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
            'date_add' => $this->date_add,
            'user_id' => $this->user_id,
            'time' => $this->time,
            'price' => $this->price,
            'appliances' => $this->appliances,
            'mesta' => $this->mesta,
            'pol' => $this->pol,
        ]);

        $query->andFilterWhere(['like', 'img', $this->img])
		    ->andFilterWhere(['like', 'user.email', $this->author])
            ->andFilterWhere(['like', 'ot', $this->ot])
            ->andFilterWhere(['like', 'kuda', $this->kuda]);

        return $dataProvider;
    }
}
