<?php

namespace backend\controllers;

use Yii;
use common\models\BlogServices;
use common\models\BlogServicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BlogservicesController implements the CRUD actions for BlogServices model.
 */
class BlogservicesController extends Controller
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
     * Lists all BlogServices models.
     * @return mixed
     */
    public function actionIndex()
    {

		//$this->layout = 'main_none';
          $searchModel = new BlogServicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }




	
	
    /**
     * Displays a single BlogServices model.
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
     * Creates a new BlogServices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

       public function actionCreate()
    {
	
	     $model_update = array();
		 $model_up = BlogServices::find()->where(['blog_id' =>Yii::$app->request->get()['blog_id']])->all();
		   foreach($model_up as $res) {
			   $model_update[$res['type']] = $res;
		   }
 
		   $model = new BlogServices();	 
		

		 		
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

	    
	if (isset($model_up)) {	
		$array = array();
		foreach($model_up as $res) {
			$array[$res['type']] = array('date_add'=>$res['date_add'],'date_end'=>$res['date_end']);
		}
		BlogServices::deleteAll(['blog_id' => $model->blog_id]);
       }
    
       foreach($model->type as $key => $res) {
            $date_add = '';

		  if ($res || $array[$res]) {

                   if ($array[$key]) {
	
					   $date_add = $array[$key]['date_add'];
	
				   }else{
					   $date_add = date('Y-m-d H:i:s');
				   }
                   $customer = new BlogServices();
                   $customer->blog_id = $model->blog_id;
                   $customer->type  = $key;
                   $customer->date_add  = $date_add;
				   $customer->date_end  = $res;
                   $customer->save();
		   }
		
       }

 Yii::$app->cache->delete('services'); Yii::$app->cacheFrontend->delete('services');
 return $this->redirect(['create', 'blog_id' => $model->blog_id]);
   }

        return $this->render('create', [
            'model' => $model,
			'model_update' => $model_update,
        ]);
    }




        public function actionCreateNone()
    {
		$this->layout = 'main_none';
        
		
	     $model_update = array();
		 $model_up = BlogServices::find()->where(['blog_id' =>Yii::$app->request->get()['blog_id']])->all();
		   foreach($model_up as $res) {
			   $model_update[$res['type']] = $res;
		   }
 
		   $model = new BlogServices();	 
		

		 		
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

	    
	if (isset($model_up)) {	
		$array = array();
		foreach($model_up as $res) {
			$array[$res['type']] = array('date_add'=>$res['date_add'],'date_end'=>$res['date_end']);
		}
		BlogServices::deleteAll(['blog_id' => $model->blog_id]);
       }
    
       foreach($model->type as $key => $res) {
            $date_add = '';

		  if ($res || isset($array[$res])) {

                   if (isset($array[$key])) {
	
					   $date_add = $array[$key]['date_add'];
	
				   }else{
					  $date_add = date('Y-m-d H:i:s');
				   }
                   $customer = new BlogServices();
                   $customer->blog_id = $model->blog_id;
                   $customer->type  = $key;
                   $customer->date_add  = $date_add;
				   $customer->date_end  = $res;
                   $customer->save();
		   }
		
       }

 Yii::$app->cache->delete('services'); Yii::$app->cacheFrontend->delete('services');
 return $this->redirect(['create-none', 'blog_id' => $model->blog_id]);
   }

        return $this->render('create_none', [
            'model' => $model,
			'model_update' => $model_update,
        ]);
    }
	
	
	





    /**
     * Deletes an existing BlogServices model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->cache->delete('services'); Yii::$app->cacheFrontend->delete('services');
        return $this->redirect(['index']);
	
    }

    /**
     * Finds the BlogServices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlogServices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
   protected function findModel($id)
    {
        if (($model = BlogServices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
