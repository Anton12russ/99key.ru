<?php

namespace backend\controllers;

use Yii;
use common\models\Shop;
use common\models\ShopField;
use common\models\ShopImage;
use common\models\ShopSearch;
use common\models\ShopReating;
use common\models\ShopComment;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopController implements the CRUD actions for Shop model.
 */
class ShopController extends Controller
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
     * Lists all Shop models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new ShopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Shop model.
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
     * Creates a new Shop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Shop();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Shop model.
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
     * Deletes an existing Shop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
  public function actionDelete($id)
    {
		 
		$model = $this->findModel($id);

		ShopField::deleteAll('shop_id = :shop_id', [':shop_id' => $id]);
		ShopReating::deleteAll(['shop_id' => $id]);
		ShopComment::deleteAll('blog_id = :shop_id', [':shop_id' => $id]);
		$delImage = ShopImage::find()->Where('shop_id=:shop_id',['shop_id'=> $id])->all();
		if ($delImage) {
          $dir_copy_maxi = Yii::getAlias('@images').'/shop/maxi/'; $dir_copy_mini = Yii::getAlias('@images').'/shop/mini/';
		  $dir_copy_logo = Yii::getAlias('@images').'/shop/logo/';	
		  @unlink($dir_copy_logo.$model->img);	
		  
		   foreach($delImage as $res) {
		      @unlink($dir_copy_maxi.$res['image']); 
			  @unlink($dir_copy_mini.$res['image']); 
		   }
		   ShopImage::deleteAll('shop_id = :shop_id', [':shop_id' => $id]);
		}
		
	//пересчитываем объявления в рубриках
		 $data = $this->findModel($id);

         $this->findModel($id)->delete();
         return $this->redirect(['index']);
    }

    /**
     * Finds the Shop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Shop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shop::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
