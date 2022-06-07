<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use common\models\MessageRoute;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
class UserRouteMessenger extends Action
{

    public function run()
    {
       $sql = MessageRoute::find(); 
	   $query = $sql->Where(['user_too'=> Yii::$app->user->id])->orWhere(['user_from'=> Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);
	   $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	   $model = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
	   $this->controller->layout = 'style_none';
       return $this->controller->render('messengeroute', compact('model', 'pages')); 
	}
	
	
	

}