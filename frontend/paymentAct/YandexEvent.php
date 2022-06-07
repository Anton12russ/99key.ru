<?php
namespace frontend\paymentAct;
use yii\base\Action;
use yii;
use yii\helpers\Url;
use common\models\PaymentSystem;
use common\models\Payment;
use common\models\User;
use common\models\Blog;
use common\models\BlogPayment;
use common\models\BlogServices;
use yii\web\NotFoundHttpException;


class YandexEvent extends Action
{
    public function run()
    {

		if ($_POST) {
		
       /*-------------------------------ДИНАМИКА--------------------------------*/
        $route = 'yandex';	
	   /*----------------------------------------------------------------------*/
		
         //Получаем настройки платежной системы
        $model = $this->findPayment($route);
        $settings = explode("\n", $model['settings']);
        $secret = $settings[1];
 
        $post = Yii::$app->request->post();

			

       /*-------------------------------ДИНАМИКА--------------------------------*/
        $hash = sha1($post['notification_type'].'&'.
        $post['operation_id'].'&'.
        $post['amount'].'&'.
        $post['currency'].'&'.
        $post['datetime'].'&'.
        $post['sender'].'&'.
        $post['codepro'].'&'.
        $secret.'&'.
        $post['label']);

        if ($post['sha1_hash'] != $hash || $post['codepro'] === true || isset($post['unaccepted']) && $post['unaccepted'] === true) { $error = '1';}
       /*----------------------------------------------------------------------*/
      

        if(isset($error)) {
		     $status = 2;
        }else{
		     $status = 1;
        }

  
        $customer = Payment::findOne((int)$post['label']);
        $customer->status  = $status;
        $customer->time  = date('Y-m-d H:i:s');
        $customer->update(); 


 
	    if (isset($customer->blog_id)) {
			$blog = Blog::findOne($customer->blog_id);
			

			
			//Действие у платной услуги объявления
			if ($customer->services == 'act') {
                 $blog->active = 1;
				 $blog->update();
				 $active = 'act';
			}
			
			
			//Действие у платной услуги объявления
			if (isset($customer->services) && $customer->services != 'act') {
				
		//Включаем плтную услугу у объявления
		$blod_services = new BlogServices();	
        $blod_services->date_add = date('Y-m-d H:i:s');
        $blod_services->date_end = date('Y-m-d H:i:s', strtotime(' + '.$customer->day.' day'));
		$blod_services->blog_id = $customer->blog_id;
		$blod_services->type = $customer->services ;
        $blod_services->save(); 
		
				$model = $this->findPayment($route);
			    $active = $customer->services;
				Yii::$app->cache->delete('services');
			}
			
			
			
			
			
			
			//Сохраняем
			 $blog->save();
		}
		

          if ($status == 1 && $customer->services == 'balance') {
              $users = User::findOne($customer->user_id);
	          $users->balance = $users->balance+$customer->price;
              $users->update();
//Доработка
			  if(Yii::$app->caches->setting()['email_balance']) {
			      Yii::$app->functionMail->balance_admin($customer->price, $users->email, Yii::$app->caches->setting()['email_balance']);
			  }
          }		  
		  

      }else{
	       $status = 0;
		  
      }
	  $return = '';
	  //Если страница возврата после успешной оплаты
	    if(isset(Yii::$app->request->get()['success'])) {
		    $status = 1;
	    }
	  	return $this->controller->render('pay', compact('status','return', 'active'));  
	  
	}
	
	
	protected function findPayment($action)
    {
        if (($model = PaymentSystem::find()->where(['status' => 1, 'route' => $action])->asArray()->one()) !== null) {
            return $model;
        }
		
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}