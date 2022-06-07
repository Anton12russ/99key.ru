<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use frontend\models\Blog;
use common\models\BlogTime;
use common\models\BlogImage;
use common\models\BlogField;
use yii\web\NotFoundHttpException;
class BlogDel extends Action
{
    public function run($id)
    {
	  $model = $this->findModel($id);
	   //Проверяем права на редактирование
	   if($model->user_id != Yii::$app->user->id) {	
		  if(!Yii::$app->user->can('updateOwnPost', ['board' => $model]) && !Yii::$app->user->can('updateBoard')) {
			   throw new NotFoundHttpException('The requested page does not exist.'); 
		  }
	   }
	  $model->status_id = 2;
	  $model->save(false);

      return $this->controller->render('del', compact('model')); 
	}
	
	
	
	protected function findModel($id)
    {
		if (($model = Blog::find()->Where(['id'=> $id])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}