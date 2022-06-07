<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use frontend\models\Article;
use yii\web\NotFoundHttpException;
class ArticleActive extends Action
{

    public function run($id)
    {

	  $model = $this->findModel($id);
      //Проверяем права на редактирование
	  if($model->user_id != Yii::$app->user->id) {	
		 if(!Yii::$app->user->can('updateOwnPost', ['article' => $model]) && !Yii::$app->user->can('updateArticle')) {
			 throw new NotFoundHttpException('The requested page does not exist.'); 
		 }
	  }

	  $model->status = Yii::$app->caches->setting()['article_moder'];
	  $model->save(false);
	
      return $this->controller->render('active', compact('model')); 
	}
	
	
	
	protected function findModel($id)
    {
		if (($model = Article::find()->Where(['id'=> $id])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}