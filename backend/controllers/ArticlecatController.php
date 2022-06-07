<?php

namespace backend\controllers;

use Yii;
use common\models\ArticleCat;
use common\models\ArticleCatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticlecatController implements the CRUD actions for ArticleCat model.
 */
class ArticlecatController extends Controller
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
     * Lists all ArticleCat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleCatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ArticleCat model.
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
     * Creates a new ArticleCat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ArticleCat();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->cache->delete('artcat'); Yii::$app->cacheFrontend->delete('artcat');  // Удаляем кеш
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ArticleCat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		 Yii::$app->cache->delete('artcat'); Yii::$app->cacheFrontend->delete('artcat');  // Удаляем кеш
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ArticleCat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->cache->delete('artcat'); Yii::$app->cacheFrontend->delete('artcat');  // Удаляем кеш
        return $this->redirect(['index']);
    }

    /**
     * Finds the ArticleCat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleCat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArticleCat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	// Функция Ajax - Изменение сортировки
public function actionSort()
{
$get = Yii::$app->request->get();		 
if ($get['id'] && $get['sort']) {
$customer = Category::findOne($get['id']);
$customer->sort = $get['sort'];
$customer->save();
      Yii::$app->cache->delete('category'); Yii::$app->cacheFrontend->delete('category');
	  Yii::$app->cache->delete('catRelative'); Yii::$app->cacheFrontend->delete('catRelative');  // Удаляем кеш
return 'Сортировка изменена обновление';
}

 }
 
 
 
 
 
 
 
 
 
 
 
 public function actionExit()
 {

$get = Yii::$app->request->get();	
$arr = ArticleCat::find()->orderBy('sort')->asArray()->all();
function linenav($cat, $cats_id, $first = true)
    {
  static $array = array();
    $value = ArticleCat::find()->where(['id' => $cats_id])->orderBy('sort')->asArray()->one();

    if($value['parent'] != 0 && $value['parent'] != "")
       {
           linenav($cat, $value['parent'], false);
       }
   $array[] = array('name' => $value['name'], 'id' => $value['id'], 'parent' => $value['parent']);
    foreach($array as $k=>$v)
        {
		$next = $v['id'];
		if(!isset($return)) {$return = '';}
		$return .= '<select class="form-control sel_cat">';
        $return .= '<option value="false">Не выбрано</option>';	
		foreach($cat as $row) {
		
		if ($row['parent']==$v['parent']) {
			$select = '';
		   if ($row['id']==$v['id']) {$select = 'selected="selected"';}
			$return .= '<option '.$select.' value="'.$row['id'].'">'.$row['name'].'</option>';
			
		 }
		}
		 $return .= '</select>';
		 
	
        }

    return $return;
    }
	
if(isset($get['id_cat'])) {
unset($arr[$get['id_cat']]);
}
 return linenav($arr, $get['idcategory']);
	}
	
	

	    public function actionCatall()
    {
$arr = Yii::$app->request->get();		
$customers = ArticleCat::find()
    ->where(['parent' => $arr['idcategory']])
	->orderBy('sort')
    ->all();

foreach($customers as $row) {
if ($row["id"] !== intval($arr['id'])) {
	if (!isset($return)) {$return = '';}
$return.="{value:'{$row["id"]}', caption:'{$row["name"]}'} ,";
}
	}

 //Исключение запятой стоящей в конце строки
 if(!isset($return)) {$return = '';}
$return=substr($return,0,(strlen($return)-1));
$return="[{$return}]";
 
return $return;

}
}
