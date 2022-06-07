<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Dispute;

/**
 * DisputeSearch represents the model behind the search form of `common\models\Dispute`.
 */
class DisputeSearch extends Dispute
{
	
	public $bayeradmin;
	public $selleradmin;	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_car', 'cashback', 'status', 'flag_shop', 'flag_admin', 'flag_bayer'], 'integer'],
            [['date', 'date_update', 'selleradmin', 'bayeradmin'], 'safe'],
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
        $query = Dispute::find()->joinWith(@selleradmin)
	->joinWith([
    @bayeradmin => function ($q) {
        $q->from('user bayeradmin');
    },
])
		;


	

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>[
              'defaultOrder'=>[
                     'id'=>SORT_DESC
                ]],
        ]);
		
		  // Сортировка по связной таблице
   $dataProvider->sort->attributes['selleradmin'] = [
        // The tables are the ones our relation are configured to
        // in my case they are prefixed with "tbl_"
        'asc' => ['user.email' => SORT_ASC],
        'desc' => ['user.email' => SORT_DESC],
    ];
	
	
		  // Сортировка по связной таблице
   $dataProvider->sort->attributes['bayeradmin'] = [
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
            'id_car' => $this->id_car,

            'cashback' => $this->cashback,
            'dispute.status' => $this->status,
            'flag_shop' => $this->flag_shop,
            'flag_admin' => $this->flag_admin,
            'flag_bayer' => $this->flag_bayer,
        ]);
		
		 $query->andFilterWhere(['like', 'date', $this->date])
		 	->andFilterWhere(['like', 'user.email', $this->selleradmin])
			->andFilterWhere(['like', 'bayeradmin.email', $this->bayeradmin])
            ->andFilterWhere(['like', 'date_update', $this->date_update]);

        return $dataProvider;
    }
}
