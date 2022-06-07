<?php

namespace frontend\controllers;

use Yii;
use common\models\ShopReating;
use common\models\Shop;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopreatingController implements the CRUD actions for ShopReating model.
 */
class ShopreatingController extends Controller
{

    public function actionCreate($id)
    {
if(@unserialize(Yii::$app->request->cookies['votes']->value)) {
    if(in_array($id, unserialize(Yii::$app->request->cookies['votes']))) {
	   $vote = false;
	}else{
	   $vote = true;
	}
}elseif(isset(Yii::$app->request->cookies['votes']) && Yii::$app->request->cookies['votes'] == $id){
    $vote = false;
}else{
	$vote = true;
}



       if($vote) {    
		$model = $this->findModel($id);
		if($model) {
		if ($model->load(Yii::$app->request->post())) {
	    if($model->services) { 
                $model->obsluga_plus = $model->obsluga_plus+1;
				$model->obsluga_minus = $model->obsluga_minus+0;
			 }else{
				$model->obsluga_plus = $model->obsluga_plus+0; 
				$model->obsluga_minus = $model->obsluga_minus+1;
			 }
	         if($model->price) { 
                $model->cena_plus = $model->cena_plus+1;
				$model->cena_minus = $model->cena_minus+0;
			 }else{
				$model->cena_minus = $model->cena_minus+1;
				$model->cena_plus = $model->cena_plus+0; 
			 }
			 if($model->quality) { 
                $model->kachestvo_plus = $model->kachestvo_plus+1; 
				$model->kachestvo_minus = $model->kachestvo_minus+0; 
			 }else{
				$model->kachestvo_minus = $model->kachestvo_minus+1;
				$model->kachestvo_plus = $model->kachestvo_plus+0; 
			 }
			 
            if($model->save()) {
				
					
		//-------------------------Проверяем, голосовал ли за эту организацию -------------------------//  
			if(@unserialize(Yii::$app->request->cookies['votes']->value)) {
				$arr = unserialize(Yii::$app->request->cookies['votes']);
				array_push($arr, $id);
				$arr = serialize($arr);
				}else{
					if (Yii::$app->request->cookies['votes']) {
						$arr = array(Yii::$app->request->cookies['votes']->value, $id);
						$arr = serialize($arr);
					}else{
					    $arr = $id;
					}
				}

				Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'votes',
                        'value' => $arr
                        ]));   
		   		//-------------------------Конец проверки -------------------------//  
					
			//Считаем общее количество положительных голосов и записываем в таблицу магазинов, чтобы потом можно было сделать быструю сортировку
			$summa = ($model->cena_plus+$model->kachestvo_plus+$model->obsluga_plus)-($model->cena_minus+$model->kachestvo_minus+$model->obsluga_minus);
			$shop = $this->findShop($id);	
			$shop->rayting = $summa;
			$shop->save(false);
			}
        }
		}else{
		$model = new ShopReating();	
		if ($model->load(Yii::$app->request->post())) {
	    if($model->services) { 
                $model->obsluga_plus = 1;
				$model->obsluga_minus = 0;
			 }else{
				$model->obsluga_plus = 0; 
				$model->obsluga_minus = 1;
			 }
	         if($model->price) { 
                $model->cena_plus = 1;
				$model->cena_minus = 0;
			 }else{
				$model->cena_minus = 1;
				$model->cena_plus = 0; 
			 }
		
			 if($model->quality) { 
                $model->kachestvo_plus = 1; 
				$model->kachestvo_minus = 0; 
			 }else{
				$model->kachestvo_minus = 1;
				$model->kachestvo_plus = 0; 
			 }
            if($model->save()) {

		    //-------------------------Проверяем, голосовал ли за эту организацию -------------------------//  
			if(@unserialize(Yii::$app->request->cookies['votes']->value)) {
				$arr = unserialize(Yii::$app->request->cookies['votes']);
				array_push($arr, $id);
				$arr = serialize($arr);
				}else{
					if (Yii::$app->request->cookies['votes']) {
						$arr = array(Yii::$app->request->cookies['votes']->value, $id);
						$arr = serialize($arr);
					}else{
					    $arr = $id;
					}
				}

				Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'votes',
                        'value' => $arr
                        ]));   
		   		//-------------------------Конец проверки -------------------------//  
				
			
			//Считаем общее количество положительных голосов и записываем в таблицу магазинов, чтобы потом можно было сделать быструю сортировку
			$summa = ($model->cena_plus+$model->kachestvo_plus+$model->obsluga_plus)-($model->cena_minus+$model->kachestvo_minus+$model->obsluga_minus);
			$shop = $this->findShop($id);	
			$shop->rayting = $summa;
			$shop->save(false);
			}
        }
		}
		return 'Спасибо, Ваш голос учтен.';
	}else{
		return 'Вы уже оценивали этот магазин';exit();
	}
    }











   protected function findShop($id)
    {
        if (($model = Shop::findOne($id)) !== null) {
            return $model;
        }

        return false;
    }




    protected function findModel($id)
    {
        if (($model = ShopReating::find()->where(['shop_id' => $id])->one()) !== null) {
            return $model;
        }

        return false;
    }
}
