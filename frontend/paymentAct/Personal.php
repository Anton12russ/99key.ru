<?php
namespace frontend\paymentAct;
use yii\base\Action;
use yii;
use yii\helpers\Url;
use common\models\PaymentSystem;
use common\models\Payment;
use common\models\User;
use common\models\Blog;
use common\models\Rates;
use common\models\BlogPayment;
use common\models\BlogServices;
use yii\web\NotFoundHttpException;


class Personal extends Action
{
    public function run()
    {
		//Со страницы личного кабинета	
		if ((int)Yii::$app->request->get('active')) {
			$id = (int)Yii::$app->request->get('active');
			$blog = Blog::findOne($id);
			$day_serv =  round((strtotime ($blog->date_del)-strtotime (date('Y-m-d H:i:s')))/(60*60*24));
	        $price_serv = (int)Yii::$app->userFunctions->active($blog->category, $blog->region) * (int)$day_serv;
			//-------------//
			
		  $model = PaymentSystem::find()->where(['status' => 1])->asArray()->one();
		  $pay = new Payment();
          $pay->price = $price_serv;
		  $pay->currency  = $model['rates'];
		  $pay->user_id  = $blog->user_id;
		  $pay->system  = '';
		  $pay->blog_id  = $blog->id;
		  $pay->services  = 'act';
		  $pay->status  = '0';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false);
		  return $this->controller->redirect(['personal', 'id_pay' => $pay->id]);
		}
		
		
		
		
		
		
		
		
		
		//Со страницы подачи объявления
		if ((int)Yii::$app->request->get('id_pay')) {
			//Если планая категория
			$id = (int)Yii::$app->request->get('id_pay');
			$model = $this->findModel($id);
			
			if ($model->price <= Yii::$app->user->identity->balance && $model->status == 0) {
				//действие у платежной строки
				$model->status = 1;
				$model->price = (int)('-'.$model->price);
				$model->system = 'Личный счет';
				$model->save(false);
			
				
				
				
				//Действие у объявления
				$blog = $this->findBlog($model->blog_id);
				if ($blog->active == 0) {
				$blog->active = 1;
				$blog->save(false);
			
				
				$user = $this->findUser($blog->user_id);
				$user->balance = $user->balance-(-$model->price);
				$user->save(false);
				//Обознаячение в виде
				$cat = $blog->status_id;
				return $this->controller->render('personal', compact('cat'));
				}else{
					 throw new NotFoundHttpException('Возможно объявление было активировано ранее.');
				}
			}else{
				if ($model->status != 0) {
				    throw new NotFoundHttpException('Возможно объявление было активировано ранее.');
				}

				if ((int)$model->price > (int)Yii::$app->user->identity->balance)  {
					 throw new NotFoundHttpException('У Вас недостаточно средств, пополните баланс в личном кабинете и повторите попытку.'); 
				}
			}
			
			
			
			
			
			
			
			
			
		}elseif ((Yii::$app->request->get('services') == 'top' && (int)Yii::$app->request->get('blog_id')) || (Yii::$app->request->get('services') == 'block' && (int)Yii::$app->request->get('blog_id'))  || (Yii::$app->request->get('services') == 'bright' && (int)Yii::$app->request->get('blog_id')) ) {
			//Если платные услуги - остальные


			if (Yii::$app->user->identity->balance) {
				//тут действие
			    $id = (int)Yii::$app->request->get('blog_id');
                $blog = $this->findBlogs($id);
				
				
				$serv_id = Yii::$app->request->get('services');
				
				//Достаем выбранную услугу
				$rates = $this->findBlogpay($serv_id);
				
				
				$end_blog = Yii::$app->request->get('day');
				 

				$price = round($end_blog) * $rates['price'];
				
				if ($price <= Yii::$app->user->identity->balance) {

				// Проверяем, нет ли у объвления уже указанной платной улуги
			   	$this->findBlogserv($serv_id, $blog['id']);
				
				//Отнимаем у баланса
				$user = $this->findUser($blog['user_id']);
				$user->balance = $user->balance-$price;
				$user->save(false);
				
				 $rate = $this->findRates();

				 $customer = new Payment();
                 $customer->price = (int)('-'.$price);
                 $customer->currency  = $rate['charcode'];
                 $customer->user_id  = Yii::$app->user->id;
	             $customer->system  = 'Личный счет';
	             $customer->blog_id  = $id;
	             $customer->services  = $serv_id;
	             $customer->status  = '1';
	             $customer->time  = date('Y-m-d H:i:s');
                 $customer->save(false);
		
			//Теперь создать платную услугу
			     $services = new BlogServices();
                 $services->date_add = date('Y-m-d H:i:s');
                 $services->date_end  = date('Y-m-d H:i:s', strtotime(' + '.Yii::$app->request->get('day').' day'));
                 $services->blog_id  = $id;
	             $services->type = $serv_id;
                 $services->save(false);
				//Очищаем кеш
				 Yii::$app->cache->delete('services');
				//передаем в шаблон
				$services = true;
				return $this->controller->render('personal', compact('services'));
				
             
				
				
				
				}else{
					 throw new NotFoundHttpException('У Вас недостаточно средств, пополните баланс в личном кабинете и повторите попытку.'); 
				}
				  
			
	
			}else{
		
				 throw new NotFoundHttpException('У Вас недостаточно средств, пополните баланс в личном кабинете и повторите попытку.'); 
			}
			
			
		}else{
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	protected function findBlogs($id)
    {
        if (($model = Blog::find()->where(['id' => $id, 'status_id' => '1', 'active' => '1'])->asArray()->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Объявление не опубликовано или не активировано');
    }

	protected function findBlogserv($serv_id, $blog_id)
    {

 if (($model = BlogServices::find()->where(['type' => $serv_id, 'blog_id' => $blog_id])->asArray()->one()) !== null) {
       // if (($model = BlogServices::find()->where(['type' => $serv_id, 'blog_id' => $blog_id])->andWhere(['>','date_end',date('Y-m-d H:i:s')])->asArray()->one()) !== null) {
		    throw new NotFoundHttpException('Услуга ранее была активирована, дождитесь окончания публикации и активируйте услугу повторно', 404);

        }
       
    }
	
	
	protected function findBlogpay($serv_id)
    {

        if (($model = BlogPayment::find()->where(['type' => $serv_id])->asArray()->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }	
	

	
	protected function findRates()
    {
        if (($model = Rates::find()->where(['def' => 1])->asArray()->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }	
	
	
	
	protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
   protected function findBlog($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}