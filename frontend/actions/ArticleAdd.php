<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use yii\helpers\Url;
use frontend\models\Blog;
use frontend\models\Article;
use yii\web\NotFoundHttpException;
use \yii\base\DynamicModel;
class ArticleAdd extends Action
{
    public function run()
    {
        
		 if (Yii::$app->user->isGuest) {
			  return $this->controller->redirect(['/user']);
		 }	
		 
	 $meta['title'] = 'Написать статью';$meta['h1'] = $meta['title'];$meta['keywords'] = 'статья, написать'; $meta['description'] = 'Добавить статью';

     //Хлебные крошки
	if (Yii::$app->request->cookies['region']) {
	   $reg_url = '/'.Yii::$app->caches->region()[Yii::$app->request->cookies['region']->value]['url'];
	}else{
       $reg_url = '';
	}
		  $meta['breadcrumbs'][] = array('label'=> 'Статьи', 'url' => $reg_url.'/article/');
		  $meta['breadcrumbs'][] = array('label'=> $meta['title']);
        $model = new Article();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			
		 //-------------------------Отправка оповещение админу-----------------------------//
			  if(Yii::$app->caches->setting()['email_article']) {
				  $url = Url::to(['article/one', 'category'=>$model->cats['url'], 'id'=>$model->id, 'name'=>Yii::$app->userFunctions->transliteration($model->title)]);
			      Yii::$app->functionMail->article_admin($url, Yii::$app->user->identity->email, Yii::$app->caches->setting()['email_article']);
			  }	
			
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

	

}