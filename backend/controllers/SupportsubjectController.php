<?php

namespace backend\controllers;

use Yii;
use common\models\SupportSubject;
use common\models\Support;
use common\models\SupportSubjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
/**
 * SupportsubjectController implements the CRUD actions for SupportSubject model.
 */
class SupportsubjectController extends Controller
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
     * Lists all SupportSubject models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SupportSubjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SupportSubject model.
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
	
	
	
    public function actionEdite($id)
    {
    
     $model = new Support();
     
	 $subject = $this->findSubject($id);
	 $subject->flag_admin = 0;
	 $subject->update();
	 
	 
	 $query = Support::find()->andWhere(['subject_id' => $id])
	->orderBy(['id' => SORT_ASC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '12']);
	$support = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
	 
    if ($model->load(Yii::$app->request->post())) {
		//Записываем тему
		$subject_add = $subject;
		$subject_add->date_update = date('Y-m-d H:i:s');
		$subject_add->flag_admin = 0;
		$subject_add->flag_user = 1;
		$subject_add->update();

		//Записываем сообщение
		$model->subject_id = $id;
		$model->date_add = date('Y-m-d H:i:s');
		$model->user_id = $subject_add->user_id;
		$model->subject = 'true';
		$model->admin = 1;
		$model->save();
        $model = new Support();
		
		
	$query = Support::find()->andWhere(['subject_id' => $id])
	->orderBy(['id' => SORT_ASC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '12']);
	$support = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
    }
	 
	 return $this->render('edite', compact('model', 'support', 'subject', 'pages'));
    }
    /**
     * Creates a new SupportSubject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SupportSubject();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SupportSubject model.
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
     * Deletes an existing SupportSubject model.
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
     * Finds the SupportSubject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SupportSubject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SupportSubject::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	
	protected function findSubject($id)
    {
		
       if (($model = SupportSubject::find()->where(['id' => $id])->one()) !== null) {
            return $model;
       }
		

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
