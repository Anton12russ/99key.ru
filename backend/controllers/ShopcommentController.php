<?php

namespace backend\controllers;

use Yii;
use common\models\ShopComment;
use common\models\ShopCommentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * ShopcommentController implements the CRUD actions for ShopComment model.
 */
class ShopcommentController extends Controller
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
     * Lists all ShopComment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopComment model.
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
     * Creates a new ShopComment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopComment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShopComment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
       $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //Отправляем сообщение о новом комментарии автору
            if($model->status == 1) {
                $this->mailAuthor($model);
            }
               return $this->redirect(['view', 'id' => $model->id]);
		
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function mailAuthor($data) {
        $url = Url::to(['shop/one', 'region'=>$data->shop->regions['url'], 'category'=>$data->shop->categorys['url'], 'id'=>$data->shop->id, 'name'=>Yii::$app->userFunctions->transliteration($data->shop->name)]);

        $url = str_replace('/admin','',$url); 
        $title = Html::a(\yii\helpers\StringHelper::truncate($data->shop->name,25,'...'), $url,['target'=>'_blank', 'data-pjax'=>"0"]); 
        $username = $data->shop->author->username;
        $useremail = $data->shop->author->email;
        Yii::$app->functionMail->commentMailAuthor($title, $username,  $useremail);
    }


    /**
     * Deletes an existing ShopComment model.
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

    /**
     * Finds the ShopComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopComment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
