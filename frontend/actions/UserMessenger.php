<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use common\models\Message;
use common\models\Blog;
use common\models\User;
use common\models\Shop;
use common\models\Passanger;
use common\models\Article;
use common\models\MessageRoute;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
class UserMessenger extends Action
{


    public function run($id)
    {
		
		if(Yii::$app->user->id) {
			$id_user = Yii::$app->user->id;
		}else{
            $id_user = Yii::$app->request->cookies['chat'];
		}
		
		
	
		if(isset(Yii::$app->request->get()['route'])) {
			$get_route = Yii::$app->request->get()['route'];
		}else{
			$get_route = '';
		}
		
		
		
		$rout = $this->findModel($id, $get_route);
        if(isset($rout['redirect'])) {
		     return $this->controller->redirect(['/user/messenger','id'=>$rout['id']]);
        }
		
		if(!$rout) {
		if($get_route) {
		   if(Yii::$app->request->get()['route'] == 'blog') {
			   $rout = $this->findModelBlog($id);
			   $route_name = 'blog';
		   }
		   
		  if(Yii::$app->request->get()['route'] == 'shop') {
			   $rout = $this->findModelShop($id);
			   $route_name = 'shop'; 
		   }
	      if(Yii::$app->request->get()['route'] == 'passanger') {
			   $rout = $this->findModelPassanger($id);
			   $route_name = 'passanger'; 

		   }
		   
		  if(Yii::$app->request->get()['route'] == 'article') {
			   $rout = $this->findModelArticle($id);
			   $route_name = 'article';
		   }
       }
		}else{
						$route_name = '';
		}


	   
	

       $model = new Message();
		if(Yii::$app->request->post('Message')['route_id']) {
			$id = Yii::$app->request->post('Message')['route_id'];
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
		
      
				  $user = User::findOne($model->u_too);
			       if(!isset($user->alert['message']) || (isset($user->alert['message']) && $user->alert['message'] == 1)) {
					  

					  if($user) {
                           if(!Yii::$app->user->id) {
							   $to = 'Гость';
						   }else{
							   $to = Yii::$app->user->identity->username;
						   }
			             Yii::$app->functionMail->chat_email($user, $to);
			          }
			        }
				
				if(isset(Yii::$app->request->post('Message')['route_add'])) {
					return  $this->controller->redirect(['/user/messenger','id'=>$model->route_id]);
				}
			}
		}


	   $sql = Message::find(); 
	   $query = $sql->Where(['route_id'=> $id, 'u_too' => $id_user])->orWhere(['route_id'=> $id, 'u_from' => $id_user])->orderBy(['id' => SORT_DESC]);
	   $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 30]);
	   $mess = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

	  $this->controller->layout = 'style_none';
      $count = $query->count();
	    return $this->controller->render('messenger', compact('mess', 'rout', 'model', 'pages', 'count', 'route_name')); 
	}
	

	
	protected function findModel($id, $route = false)
    {
		
		if(Yii::$app->user->id) {
			$id_user = Yii::$app->user->id;
		}else{
            $id_user = Yii::$app->request->cookies['chat'];
		}	
	if($route) {
	   if (($models = MessageRoute::find()->Where(['message'=>$id, 'user_from' => $id_user, 'route' => $route])->orWhere(['message'=>$id, 'user_too' => $id_user, 'route' => $route])->One()) !== null) {
			$redirect['redirect'] = true;
			$redirect['id'] = $models->id;
			return $redirect;
	   }
	}else{   
		if (($model = MessageRoute::findOne($id)) !== null) {
			return $model;
        }
	}
    }
	
	
	protected function findModelBlog($id)
    {
		if (($model = Blog::findOne($id)) !== null) {
            return $model;
        }
		 throw new NotFoundHttpException('The requested page does not exist.'); 
    }
	
	protected function findModelShop($id)
    {	
		if (($model = Shop::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	
	
	protected function findModelPassanger($id)
    {	
		if (($model = Passanger::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	
	
	
	
	protected function findModelArticle($id)
    {
		if (($model = Article::findOne($id)) !== null) {
            return $model;
        }
		 throw new NotFoundHttpException('The requested page does not exist.');
    }
}