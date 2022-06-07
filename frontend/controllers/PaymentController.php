<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use common\models\PaymentSystem;
use common\models\Payment;
use common\models\User;
use common\models\Blog;
use common\models\BlogPayment;
use common\models\BlogServices;
use yii\web\NotFoundHttpException;

class PaymentController extends Controller
{

	 public function beforeAction($action)
    {

		// отключаем защиту от CSRF
        if (in_array($action->id, ['yandex-event'])) { $this->enableCsrfValidation = false;}
		if (in_array($action->id, ['robokassa-event'])) { $this->enableCsrfValidation = false;}
	    if (in_array($action->id, ['success'])) { $this->enableCsrfValidation = false;}
		if (in_array($action->id, ['error'])) { $this->enableCsrfValidation = false;}
        return parent::beforeAction($action);
    }
	
	
	
		//Дополнительные страницы для систем оплаты
   public function actionSuccess()
    {
		$status = 1;
		 return $this->render('pay', compact('status','return', 'active'));  
	}
	public function actionError()
    {
		$status = 0;
		 return $this->render('pay', compact('status','return', 'active'));  
	}

public function actions()
  {
      return [
	          'personal' => [
                  'class' => 'frontend\paymentAct\Personal',
              ],
              'yandex-form' => [
                  'class' => 'frontend\paymentAct\YandexForm',
              ],
			    'yandex-event' => [
                  'class' => 'frontend\paymentAct\YandexEvent',
              ],
			  
			  
			  //Робокасса
			  
			 'robokassa-form' => [
                  'class' => 'frontend\paymentAct\RobokassaForm',
              ],
			 'robokassa-event' => [
                  'class' => 'frontend\paymentAct\RobokassaEvent',
              ],
			  
      ];
  }



}