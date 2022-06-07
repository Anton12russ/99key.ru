<?php

namespace backend\controllers;

use Yii;
use common\models\Category;
use common\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			      Yii::$app->cache->delete('category'); Yii::$app->cacheFrontend->delete('category');
	              Yii::$app->cache->delete('catRelative'); Yii::$app->cacheFrontend->delete('catRelative');  // Удаляем кеш
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
		
  
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post() ) && $model->save()) {
			      Yii::$app->cache->delete('category'); Yii::$app->cacheFrontend->delete('category');
	  Yii::$app->cache->delete('catRelative'); Yii::$app->cacheFrontend->delete('catRelative');  // Удаляем кеш
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,

        ]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
	 
	 
	 
	 
	 
	 
	 
   public function actionDelete($id)
    {
   $category = Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(),$id);
   Category::deleteAll(['id' => $category]);
    // Удаляем кеш
      Yii::$app->cache->delete('category'); Yii::$app->cacheFrontend->delete('category');
	  Yii::$app->cache->delete('catRelative'); Yii::$app->cacheFrontend->delete('catRelative');  // Удаляем кеш
      return $this->redirect(['index']);
    }
	

	
 public function actionCats($id)
    {	
$customers = Category::find()
    ->where(['id' => $this->parent])
    ->all();
foreach($customers as $row) {
$return.="{value:'{$row["id"]}', caption:'{$row["name"]}'} ,";
	}

 //Исключение запятой стоящей в конце строки
$return=substr($return,0,(strlen($return)-1));
$return="[{$return}]";
return $return;
}
    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	


    public function actionCatall()
    {
$arr = Yii::$app->request->get();		
$customers = Category::find()
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
$arr = Yii::$app->caches->category();	


function linenav($cat, $cats_id, $first = true)
    {
  
  
  
  static $array = array();
    $value = $cat[$cats_id];
	 
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
	
	


}