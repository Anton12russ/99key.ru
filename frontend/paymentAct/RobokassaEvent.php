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


class RobokassaEvent extends Action
{
    public function run()
    {
		
		if ($_POST) {
		
       /*-------------------------------ДИНАМИКА--------------------------------*/
        $route = 'robokassa';	
	   /*----------------------------------------------------------------------*/
		
         //Получаем настройки платежной системы
        $model = $this->findPayment($route);
        $settings = explode("\n", $model['settings']);
        $post = Yii::$app->request->post();


       /*-------------------------------ДИНАМИКА--------------------------------*/

	    $customer = Payment::findOne((int)$post['inv_id']);
		
		$price_c = number_format($customer->price, 6, '.', '');
	    $hash_baza = strtoupper(md5("$price_c:$customer->id:$settings[2]"));

		if(strtoupper($post['SignatureValue']) != $hash_baza) {
			$error = 1;
		}

       /*----------------------------------------------------------------------*/


        if(isset($error)) {
		     $status = 2;
        }else{
		     $status = 1;
        }

  

        //$customer = Payment::findOne((int)$post['label']);
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
              $users = User::findOne($customer['user_id']);
	          $users->balance = $users->balance+$customer['price'];
              $users->update();
          }		  
		  
        if ($status == 1) {
	  
		    print "Content-type: text/html\n\nOK".$customer->id."\n";
		    exit();
        }
      }else{
	       $status = 0;
		  
      }
	  $return = '';
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