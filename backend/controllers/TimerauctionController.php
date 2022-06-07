<?php

namespace backend\controllers;

use Yii;
use common\models\Timer;
use common\models\TimerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Settings;
/**
 * CarController implements the CRUD actions for Car model.
 */
class TimerauctionController extends Controller
{
     public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actionIndex()
    {

		 $searchModel = new TimerSearch();
		 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }




  public function actionViews()
    {
		
		  
	
		
		  /*------------------Для скрытия-------------------------*/
	  
	 /* $new_date = date_create(date("Y-m-d H:i:s"));
      date_format($new_date, 'U'); // Format UNIX 1566382042
      $second_date = (date_timestamp_get($new_date))*1000;
		  $cod = json_decode($model->cod)->code; 

		 if (strpos($cod, 'weekdays') !== false) {
	
			    preg_match_all('#"time":"(.+?)",#is', $cod, $arr);
				
			    if(isset($arr[1][0])) {
					
					//Ищем часы
					 preg_match_all('#"hours":"(.+?)"#is', $cod, $hours);
					 //Ищем минуты
				     preg_match_all('#minutes":"(.+?)"#is', $cod, $min);
					 $date_new = $hours[1][0].':'.$min[1][0];
					
					 $date_new = date('H:i', strtotime("+". $hours[1][0]." hours", strtotime($arr[1][0])));
					// $date_new = date("H:i", strtotime("+ ". $min[1][0]." minute", strtotime($date_new)));
					 $date_new = date("H:i", strtotime("+ ". $min[1][0]." minute", strtotime($date_new)));

				if(date('H:i') > $arr[1][0] && $date_new > date("H:i")) {
			  
		        }else{
					return '<span style="color: red" class="center">Ожидает включения, так как цикличен</span><br> Включится в '.$arr[1][0].' <br>и отключится в '.$date_new;
				}
			  }
  
		 }else{

			 preg_match_all('#"utc":(.+?)}#is', $cod, $end);
			 
			
			 if(isset($end[1][0])) {
			
			
				
			      $date = date("Y-m-d H:i:s", time());
				   if($end[1][0] < $second_date) { 
				      return 'Завершен';
				   }
			 }
		 }
		 
		
		/*-------------------------------------------*/
		  $this->layout = 'style_none';
		  Yii::$app->getModule('debug')->instance->allowedIPs = [];
		  return $this->render('views');
	}
	
	
	
	
	  public function actionUpdate()
    {
	
		  return $this->render('update');
	}
	
	
	 public function actionSaveindex()
    {
	
		  return $this->render('save');
	}
	
	public function actionSave()
    {
		$post = Yii::$app->request->post();
		$model = new Timer();
		$model->cod = json_encode($post);
        $model->id_block = $this->gen_password(2);
		$model->tyme = date('Y-m-d H:i:s');
		$model->save();

		return $model->id;
    }
	
	
	
	
	
	public function actionUpdatesave()
    {
		$post = Yii::$app->request->post();
        $model = Settings::findOne(55);
		$model->value = json_encode($post);

		$model->update(false);
	Yii::$app->cache->delete('settings'); Yii::$app->cacheFrontend->delete('settings');  // Удаляем кеш
		return json_encode($post);
    }
	
	
	
	
	
	
	
	
	    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->cache->delete('field'); // Удаляем кеш
        return $this->redirect(['index']);
    }
	
	
	
	
	
public function gen_password($length = 6)
{
	$password = '';
	$arr = array(
		'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
		'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z' 
		
	);
 
	for ($i = 0; $i < $length; $i++) {
		$password .= $arr[random_int(0, count($arr) - 1)];
	}
	return $password.time();
}











    protected function findModel($id)
    {
        if (($model = Timer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}




