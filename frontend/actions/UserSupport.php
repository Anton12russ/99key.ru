<?php
namespace frontend\actions;
use yii\base\Action;
use common\models\SupportSubject;
use yii;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
class UserSupport extends Action
{

    public function run()
    {

	$query = SupportSubject::find()->andWhere(['user_id' => Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
	$route = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
	 
    if(!$route) {
	  return $this->controller->redirect(['user/supportadd']);
    }
	 
	return $this->controller->render('support', compact('car', 'route', 'pages'));
	}
	

}