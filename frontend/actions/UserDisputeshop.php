<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use common\models\Car;
use common\models\Dispute;

use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use common\models\CarshopSearch;
class UserDisputeshop extends Action
{

    public function run()
    {
	$query = Dispute::find()->andWhere(['id_user' => Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
	$comment = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();

	  return $this->controller->render('disputeshop', compact('model', 'car', 'comment', 'pages'));
	}
	

}