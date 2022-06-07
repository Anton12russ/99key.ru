<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use frontend\models\Shop;
use common\models\Car;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use common\models\CarSearch;
class UserProduct extends Action
{

    public function run()
    {
	$query = Car::find()->andWhere(['buyer' => Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);	
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$product = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	    $searchModel = new CarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id);
        return $this->controller->render('product', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	
	/*
	protected function findModel($id)
    {
		if (($model = CarBuyer::find()->Where(['id'=> $id])->all()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }*/
}