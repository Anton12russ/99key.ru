<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SupportSubject;

/**
 * SupportSubjectSearch represents the model behind the search form of `common\models\SupportSubject`.
 */
class SupportSubjectSearch extends SupportSubject
{
    /**
     * {@inheritdoc}
     */
	 
	public $author; 
	
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'flag_user', 'flag_admin'], 'integer'],
            [['subject', 'date_add', 'date_update', 'author'], 'safe'],
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
        $query = SupportSubject::find()->joinWith(@author);

        // add conditions that should always apply here

          $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>[
              'defaultOrder'=>[
                     'flag_admin'=>SORT_DESC
                ]],
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
            //'user_id' => $this->user_id,
            //'date_add' => $this->date_add,
            //'date_update' => $this->date_update,
            'status' => $this->status,
            'flag_user' => $this->flag_user,
            'flag_admin' => $this->flag_admin,
        ]);
		
		
        $query->andFilterWhere(['like', 'date_add', $this->date_add])
		   ->andFilterWhere(['like', 'user.email', $this->author])
		   ->andFilterWhere(['like', 'date_update', $this->date_update])
           ->andFilterWhere(['like', 'subject', $this->subject]);

        return $dataProvider;
    }
}
