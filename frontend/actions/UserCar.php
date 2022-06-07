<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use frontend\models\Shop;
use common\models\Car;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use common\models\CarshopSearch;
class UserCar extends Action
{

    public function run()
    {

	    $searchModel = new CarshopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id);
        return $this->controller->render('car', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	

}