<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use common\models\Car;
use common\models\Dispute;
use common\models\DisputeMess;

use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use common\models\CarshopSearch;
class UserDispute extends Action
{

    public function run($car_id)
    {

	if (($model =  Dispute::find()->Where(['id_car' => $car_id, 'id_bayer' => Yii::$app->user->id])->One()) !== null) {
        $id_comm = $model->id;
	}else{
		$model = new Dispute();
		$id_comm = 0;
	}

	
	    $car = $this->findCar($car_id);
	    if ($model->load(Yii::$app->request->post())) {
			 $model->id_car = $car_id;
			 $model->id_user = $car->user_id;
			 $model->id_bayer = Yii::$app->user->id;
			 $model->status = 1;
			 if(!$id_comm) {
			    $model->date = date('Y-m-d H:i:s');
			 }
			   $model->date_update = date('Y-m-d H:i:s');
			   $model->flag_shop = 1;
			   $model->flag_admin = 1;
			 if($model->save()) {
				 $mess = new DisputeMess();
				 $mess->user_id = Yii::$app->user->id;
				 $mess->bayer_id = Yii::$app->user->id;
				 $mess->dispute_id = $model->id;
				 $mess->text = $model->text;
				
				 if(!$id_comm) {
					$mess->flag_shop = 1;
				 }
				 $mess->flag_admin = 1;
				 $mess->date = date('Y-m-d H:i:s');
			     $mess->save();
				 
				 
		         $car->dispute = 1;
				 $car->update();
				 
				 
				 		//-------------------------Отправка оповещение админу-----------------------------//
			      if(Yii::$app->caches->setting()['email_dispute']) {
			              Yii::$app->functionMail->dispute_admin(Yii::$app->user->identity->email, Yii::$app->caches->setting()['email_dispute']);
			      }
			 }
		   
        }



	$query = DisputeMess::find()->andWhere(['dispute_id' => $id_comm, 'bayer_id' => Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
	$comment = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();



	  return $this->controller->render('dispute', compact('model', 'car', 'comment', 'pages'));
	}
	
	
	
	
	
	
	
	
	protected function findCar($id)
    {
        if (($model = Car::find()->Where(['id' => $id, 'buyer' => Yii::$app->user->id])->One()) !== null) {
            return $model;
        }
		
		throw new NotFoundHttpException('The requested page does not exist.');

    }
}