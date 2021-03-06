<?php

namespace backend\controllers;

use Yii;
use common\models\ArticleComment;
use common\models\ArticleCommentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * ArticlecommentController implements the CRUD actions for ArticleComment model.
 */
class ArticlecommentController extends Controller
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
     * Lists all ArticleComment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ArticleComment model.
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
     * Creates a new ArticleComment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ArticleComment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ArticleComment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
       $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //???????????????????? ?????????????????? ?? ?????????? ?????????????????????? ????????????
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
        $url = Url::to(['article/one', 'category'=>$data->article->cats['url'], 'id'=>$data->article->id, 'name'=>Yii::$app->userFunctions->transliteration($data->article->title)]);
        $url = str_replace('/admin','',$url); 
        $title = Html::a(\yii\helpers\StringHelper::truncate($data->article->title,25,'...'), $url,['target'=>'_blank', 'data-pjax'=>"0"]); 
        $username = $data->article->author->username;
        $useremail = $data->article->author->email;
        Yii::$app->functionMail->commentMailAuthor($title, $username,  $useremail);
    }

    /**
     * Deletes an existing ArticleComment model.
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
     * Finds the ArticleComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArticleComment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
