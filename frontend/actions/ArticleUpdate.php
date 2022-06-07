<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use frontend\models\Blog;
use frontend\models\Article;
use yii\web\NotFoundHttpException;
use \yii\base\DynamicModel;
class ArticleUpdate extends Action
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
		
	 $meta['title'] = 'Редактировать статью';$meta['h1'] = $meta['title'];$meta['keywords'] = 'статья, редактировать'; $meta['description'] = 'Редактировать статью';
     //Хлебные крошки
	if (Yii::$app->request->cookies['region']) {
	   $reg_url = '/'.Yii::$app->caches->region()[Yii::$app->request->cookies['region']->value]['url'];
	}else{
       $reg_url = '';
	}
		  $meta['breadcrumbs'][] = array('label'=> 'Статьи', 'url' => $reg_url.'/article/');
		  $meta['breadcrumbs'][] = array('label'=> $meta['title']);
    

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

		$model->user_update = Yii::$app->user->id;	
		$model->date_update = date('Y-m-d H:i:s'); 
		$model->update();
		
          return $this->controller->render('add', [
              'save' => true,
			  'meta' => $meta
          ]);
        }else{
		  return $this->controller->render('add', [
              'model' => $model,
			  'meta' => $meta
          ]);
		}

     

    }

	
		protected function findModel($id)
    {
        if (($model = Article::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        }

      throw new NotFoundHttpException('The requested page does not exist.');
    }
}