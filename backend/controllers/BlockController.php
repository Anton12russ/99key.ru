<?php

namespace backend\controllers;

use Yii;
use common\models\Block;
use common\models\BlockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * BlockController implements the CRUD actions for Block model.
 */
class BlockController extends Controller
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
     * Lists all Block models.
     * @return mixed
     */
    public function actionIndex()
    {
      if(Yii::$app->request->post()) {
        $searchModel = new BlockSearch();
        $dataProvider = new ActiveDataProvider( [
	        'query' => Block::find( )->orderBy( 'sort' ),	// notice the orderBy clause
	        'sort' => false,
	        'pagination' => false
	    ] );
		    return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	  }else{

        $searchModel = new BlockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
       }
    
    }


    public function actionOrder( )   {
        $post = Yii::$app->request->post();
        if (isset($post['key'], $post['pos'] ))   {
            $this->findModel( $post['key'] )->order( $post['pos'] );
        }
	Yii::$app->cache->delete('block'); Yii::$app->cacheFrontend->delete('block');
    }

    /**
     * Displays a single Block model.
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
     * Creates a new Block model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Block();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->cache->delete('block'); Yii::$app->cacheFrontend->delete('block');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Block model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->cache->delete('block'); Yii::$app->cacheFrontend->delete('block');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Block model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->cache->delete('block'); Yii::$app->cacheFrontend->delete('block');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Block model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Block the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		
        if (($model = Block::findOne($id)) !== null) {

            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
