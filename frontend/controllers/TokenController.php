<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\MailToken;
use common\models\Blog;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use common\models\BlogTime;
class TokenController extends Controller
{

    public function actionBoard()
    {
		
	  $token = Yii::$app->request->get('token');
	  $id = Yii::$app->request->get('token_id');
	  if($token && $id) {

		     $model = $this->findModel($id, $token);
			 $token = $model;
             $tyme = $this->findTime();
             $blog = $this->findBlog($token->blog_id);
			 $blog->date_add = date('Y-m-d H:i:s');
			 $blog->date_del = date('Y-m-d H:i:s', strtotime(' + '.$tyme.' day'));
			 $blog->status_id = Yii::$app->caches->setting()['moder'];	
			 $blog->active = 1;	
			 $blog->update();
			 $model->delete();
	         return $this->render('/user/token_blog', compact('token', 'tyme'));
		  }else{
			 throw new NotFoundHttpException('The requested page does not exist.');  
		  }
	
	
	}





		protected function findTime()
    {
        if (($model = BlogTime::find()->where(['def' => '1'])->orderBy(['sort' => SORT_DESC])->asArray()->one()) !== null) {
            return $model['days'];
        }else{
			return '30';
		}
       
    }


   protected function findModel($id, $token)
    {
        if (($model = MailToken::findOne($id)) !== null && $model->token == $token) {
		
              return $model;
		
        }

        throw new NotFoundHttpException('Токен уже активирован или его несуществует');
    }
	
	
	
	protected function findBlog($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
              return $model;
        }

        throw new NotFoundHttpException('Токен уже активирован или его не существует');
    }
}