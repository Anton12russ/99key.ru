<?php

namespace backend\controllers;
use yii\data\Pagination;
use Yii;
use common\models\Dispute;
use common\models\Car;
use common\models\User;
use common\models\Payment;
use common\models\DisputeMess;
use common\models\DisputeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DisputeController implements the CRUD actions for Dispute model.
 */
class DisputeController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * Lists all Dispute models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DisputeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dispute model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Dispute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dispute();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Dispute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Dispute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }








    public function actionDispute($car_id)
    {
   
	if (($model =  Dispute::find()->Where(['id_car' => $car_id])->One()) !== null) {
        $id_comm = $model->id;
	}else{
		$model = new Dispute();
		$id_comm = 0;
	}
	
    $countdispute = Dispute::find()->andWhere(['id_user' => $model->id_user])->count();

	    $car = $this->findCar($car_id);
	    if ($model->load(Yii::$app->request->post())) {
				 $mess = new DisputeMess();
				 $mess->user_id = 0;
				 $mess->bayer_id = $model->id_bayer;
				 $mess->dispute_id = $model->id;
				 $mess->text = $model->text;
				
				 if(!$id_comm) {
					$mess->flag_shop = 1;
				 }
				 $mess->flag_bayer = 1;
				 $mess->flag_admin = 0;
				 $mess->date = date('Y-m-d H:i:s');
			     $mess->save();
				 $model->date_update = date('Y-m-d H:i:s');
				 $model->flag_admin = 0;
                 $model->update();
				 
				 
 

        }



	$query = DisputeMess::find()->andWhere(['dispute_id' => $id_comm])->orderBy(['id' => SORT_DESC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
	$comment = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();



	  return $this->render('dispute', compact('model', 'car', 'comment', 'pages', 'countdispute'));
    }
	
	
	
	
	
	
	
	
	
	
	
	 public function actionClosebayer($id)
    {
	 foreach(Yii::$app->caches->rates() as $rat) {
				 if($rat['def'] == 1) {$rate = $rat;}
	  }
       $dispute = $this->findModel($id);
	   $dispute->status = 2;
	   $dispute->flag_admin = 0;
	   $dispute->update(false);

       $car = $this->findCar($dispute->id_car);
	   $car->status = 4;
	   $car->update();

       // Остаток
	   if($car->price > $dispute->cashback) {
	      $ostatok = $car->price-$dispute->cashback;
	   }else{
		  $ostatok = 0; 
	   }


	   //прибавляем к счету покупателя
	   $user = $this->findUser($dispute->id_bayer);
	   $user->balance = $user->balance+$car->price-$ostatok;
	   $user->balance_temp = $user->balance_temp-$car->price;
	   $user->update(false);
	   
	   //Вычитаем со счета продавца   
	   $user_shop = $this->findUser($dispute->id_user);
	   $user_shop->balance_temp = $user_shop->balance_temp-$car->price;
	   $user_shop->balance = $user_shop->balance+$ostatok;
	   $user_shop->update(false);
	   
	   //История продавца
	      $pay = new Payment();
          $pay->price = +$ostatok;
		  $pay->currency  = $rate['code'];
		  $pay->user_id  = $dispute->id_user;
		  $pay->system  = 'Продажа № '.$car->id;
		  $pay->blog_id  = '';
		  $pay->services  = 'shop';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false); 
	
	   //История покупателя (Возврат)
	      $pay = new Payment();
          $pay->price = $car->price-$ostatok;
		  $pay->currency  = $rate['code'];
		  $pay->user_id  = $dispute->id_bayer;
		  $pay->system  = 'Возврат с покупки № '.$car->id;
		  $pay->blog_id  = '';
		  $pay->services  = 'cachback';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false); 	
	
	   return 'Готово, спор закрыт';	
	}
	
	
	
	public function actionCloseshop($id)
    {
		
		foreach(Yii::$app->caches->rates() as $rat) {
				 if($rat['def'] == 1) {$rate = $rat;}
		}
       $dispute = $this->findModel($id);
	   $dispute->status = 2;
	   $dispute->flag_admin = 0;
	   $dispute->update(false);

       $car = $this->findCar($dispute->id_car);
	   $car->status = 4;
	   $car->update();


	   //Вычитаем у покупателя
	   $user = $this->findUser($dispute->id_bayer);
	   $user->balance_temp = $user->balance_temp-$car->price;
	   $user->update(false);
	   
	   //Прибавляем продавцу  
	   $user_shop = $this->findUser($dispute->id_user);
	   $user_shop->balance_temp = $user_shop->balance_temp-$car->price;
	   $user_shop->balance = $user_shop->balance+$car->price;
	   $user_shop->update(false);
	   
	   
	   
	   	  $pay = new Payment();
          $pay->price = +$car->price;
		  $pay->currency  = $rate['code'];
		  $pay->user_id  = $dispute->id_user;
		  $pay->system  = 'Продажа № '.$car->id;
		  $pay->blog_id  = '';
		  $pay->services  = 'shop';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false); 
	
	   return 'Готово, спор закрыт';	
	}
	
	
	protected function findCar($id)
    {
        if (($model = Car::find()->Where(['id' => $id])->One()) !== null) {
            return $model;
        }
		throw new NotFoundHttpException('The requested page does not exist.');

    }
	
	
	
    /**
     * Finds the Dispute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dispute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dispute::findOne($id)) !== null) {
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
}
