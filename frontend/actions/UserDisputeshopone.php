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
class UserDisputeshopone extends Action
{

    public function run($id)
    {
    $car_id = $id;
	if (($model =  Dispute::find()->Where(['id' => $car_id, 'id_user' => Yii::$app->user->id])->One()) !== null) {
        $id_comm = $model->id;

	}else{
		throw new NotFoundHttpException('The requested page does not exist.');
	}


	    $car = $this->findCar($model->id_car);

	    if ($model->load(Yii::$app->request->post())) {
				 $mess = new DisputeMess();
				 $mess->user_id = Yii::$app->user->id;
				 $mess->bayer_id = $model->id_bayer;
				 $mess->dispute_id = $model->id;
				 $mess->text = $model->text;
				
				 if(!$id_comm) {
					$mess->flag_shop = 1;
				 }
				 $mess->flag_bayer = 1;
				 $mess->flag_admin = 1;
				 $mess->date = date('Y-m-d H:i:s');
			     $mess->save();
				 $model->date_update = date('Y-m-d H:i:s');
                 $model->update();
        }



	$query = DisputeMess::find()->andWhere(['dispute_id' => $id])
	

	->orderBy(['id' => SORT_DESC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
	$comment = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();

	  return $this->controller->render('disputeshopone', compact('model', 'car', 'comment', 'pages'));
	}
	
	
	
	
	
	
	
	
	protected function findCar($id)
    {
        if (($model = Car::find()->Where(['id' => $id])->One()) !== null) {
            return $model;
        }
		
		throw new NotFoundHttpException('The requested page does not exist.');

    }
}