<?php

namespace common\models;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BlockUser;

/**
 * BlockSearch represents the model behind the search form of `common\models\Block`.
 */
class BlockuserSearch extends Block
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'registr', 'category', 'sort'], 'integer'],
            [['name', 'text', 'position', 'date_add', 'action', 'date_del'], 'safe'],
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
        $query = BlockUser::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>[
              'defaultOrder'=>[
                     'sort'=>SORT_ASC
                ]],
        ]);

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
$query->Where(['user_id' => Yii::$app->user->id]);
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'date_add' => $this->date_add,
            'date_del' => $this->date_del,
    	    'category' => @$category,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'action', $this->action]);

        return $dataProvider;
    }
}
