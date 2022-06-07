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


class RobokassaForm extends Action
{
    public function run()
    {
	if ((Yii::$app->user->id && (int)Yii::$app->request->get('sum')) || (int)Yii::$app->request->get('id_pay') || (Yii::$app->request->get('blog_id') && Yii::$app->request->get('services'))) {
     /*-------------------------------ДИНАМИКА--------------------------------*/
     $route = 'robokassa';	
	 /*----------------------------------------------------------------------*/
	 
    //Получаем настройки платежной системы
     $model = $this->findPayment($route);
     $settings = explode("\n", $model['settings']);
     
     $id_system = $settings[0];
     //$secret = $settings[1];
 
 
      //URL сайта	
      
      $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];	
	  
      //Если указана сумма или оплата услуги
	if (Yii::$app->request->get('id_pay') == '' || (Yii::$app->request->get('blog_id') && Yii::$app->request->get('services'))) {
		//Если указана сумма
	  if (Yii::$app->request->get('sum')) {
	     $sum = Yii::$app->request->get('sum');
		 $blog_id = ""; 
		 $services = "balance";
		 $sum = (int)Yii::$app->request->get('sum');
		 $user_id = Yii::$app->user->id;
	  }else{
        // Для платных услуг к объявлениям 
		// Ищем стоимость услуги в базе и после этого записываем в платежку
		 $blog_id = (int)Yii::$app->request->get('blog_id'); 
		 $services = Yii::$app->request->get('services');
		 $model_p = BlogPayment::find()->Where(['type' => $services])->one();
		 $blog_p = Blog::findOne($blog_id);
	
		 $end_blog = Yii::$app->request->get('day');
		 $sum = $model_p['price']*round($end_blog);
		 $user_id = $blog_p->user_id;
	  }		  
      $customer = new Payment();
      $customer->price = $sum;
      $customer->currency  = $model['rates'];
      $customer->user_id  = $user_id;
	  $customer->system  = $model['name'];
	  $customer->blog_id  = $blog_id;
	  $customer->services  = $services;
	  $customer->status  = '0';
	  $customer->time  = date('Y-m-d H:i:s');
	  $customer->day = Yii::$app->request->get('day');
      $customer->save();
	  
	  $id_pay = $customer->id;
	  if (isset(Yii::$app->user->identity->email)) {
	      $email = Yii::$app->user->identity->email;
	  }else{
		  $email = $this->findUser($customer->user_id)['email'];
	  }
	  }else{
		  
	  $id_pay = (int)Yii::$app->request->get('id_pay');
	   
		  //Добавляем в платежку, выбранную систему
		   $customer = Payment::findOne($id_pay);
		   $customer->currency  = $model['rates'];
           $customer->system  = $model['name'];
           $customer->save();   
	  $sum = $customer->price;
	  if (isset(Yii::$app->user->identity->email)) {
	      $email = Yii::$app->user->identity->email;
	  }else{
		  $email = $this->findUser($customer->user_id)['email'];
	  }
	  }

       /*-------------------------------ДИНАМИКА--------------------------------*/

      $crc  = md5("$settings[0]:$sum:$customer->id:$settings[1]");
      $return ='		
       <form style="display: none;" method="POST" action="https://auth.robokassa.ru/Merchant/Index.aspx"> 
           <input type="hidden" name="MerchantLogin" value="'.$settings[0].'"> 
           <input type="hidden" name="OutSum" value="'.$sum.'">
		   
		   <!--Поле ниже раскоментировать, если нужен тест-->
		   <!--<input type="hidden" name="IsTest" value="1">-->
		   
		   
		   <input type="hidden" name="InvId" value="'.$id_pay.'">
		   <input type=hidden name=Description value="Пополнение баланса пользователем '.$email.', сайт '.Yii::$app->caches->setting()['site_name'].'">
		   <input type=hidden name=SignatureValue value='.$crc.'>
           <input type="submit" value="Оплатить"> 
      </form>';
	  /*----------------------------------------------------------------------*/
	  
          return $this->controller->render('pay', compact('return'));
	
	  }else{
		  $exception = Yii::$app->errorHandler->exception;
		  return $this->controller->render('/site/error', ['exception' => $exception]);
	  }
    }
	
	
	
	protected function findPayment($action)
    {
        if (($model = PaymentSystem::find()->where(['status' => 1, 'route' => $action])->asArray()->one()) !== null) {
            return $model;
        }
		
        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
		protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
    }
	

}