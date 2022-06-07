<?php

namespace backend\controllers;

use Yii;
use common\models\Region;
use common\models\RegionCoord;
use common\models\RegionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\RegionCase;
/**
 * RegionController implements the CRUD actions for Region model.
 */
class RegionController extends Controller
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
     * Lists all Region models.
     * @return mixed
     */
	 
	 
	 
	 
	public function actionCoordall()
    {
		$arr = Region::find()->asArray()->orderBy(['id' => SORT_ASC])->all();
		//echo '<pre>';print_r($arr);echo'<pre>';
		if($_GET['id'] == 0) {
	       RegionCoord::deleteAll();
		}
         $i = 0;
		foreach($arr as $res) {
		 $i++;
		 if($res['id'] > $_GET['id']) {	
		 echo $res['id'] .' ---'.$res['name'].'<br>';
		  $coordin = new RegionCoord();
		  $coordin->region_id = $res['id'];
          $coord = $this->findCoord($res['name']);
		  $coordin->coordlat = $coord[1];
		  $coordin->coordlon = $coord[0];
          $coordin->save();
	
		  if($i == 200) {
			  break;
			  
		  }
		 }
		}
		
		
	}
	 
	 

    public function actionIndex()
    {
        $searchModel = new RegionSearch();
		
		//->andFilterWhere(['=', 'parent','0'])->orderBy(['id'=>'ASC']
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Region model.
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
     * Creates a new Region model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		
        $model = new Region();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		$coord = $this->findCoord($model->name);
		$coordin = new RegionCoord();
		$coordin->region_id = $model->id;


	    if($model->coordlat && $model->coordlon) {
			$coordin->coordlat = $model->coordlat;
		    $coordin->coordlon = $model->coordlon;
		}else{
		    $coord = $this->findCoord($model->name);
		    $coordin->coordlat = $coord[1];
		    $coordin->coordlon = $coord[0];
		}


		$coordin->save();

			
			// Удаляем кеш
			Yii::$app->cache->delete('region'); Yii::$app->cacheFrontend->delete('region');  Yii::$app->cache->delete('regRelative'); Yii::$app->cacheFrontend->delete('regRelative');  // Удаляем кеш
            Yii::$app->cache->delete('coord'); Yii::$app->cacheFrontend->delete('coord');
			Yii::$app->cache->delete('regCase'); Yii::$app->cacheFrontend->delete('regCase'); // Удаляем кеш               
			   //Добавляем склонение       
                $customer = new RegionCase();
			 	$customer->id_region = $model->id;
                $customer->name = $model->regionCase;
                $customer->save();           

		   return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Region model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$coordin = RegionCoord::find()->andWhere(['region_id' => $model->id])->one();
		if($coordin) {
		  $model->coordlat = $coordin->coordlat;
		  $model->coordlon = $coordin->coordlon;
		}else{
			$cordno = true;
		}
        if ($model->load(Yii::$app->request->post() ) && $model->save()) {
			
     
		if(!$coordin) {
			$coordin = new RegionCoord();
			$coordin->region_id = $model->id;
		}
		if($model->coordlat && $model->coordlon) {
			$coordin->coordlat = $model->coordlat;
		    $coordin->coordlon = $model->coordlon;
		}else{
	
		    $coord = $this->findCoord($model->name);
		    $coordin->coordlat = $coord[1];
		    $coordin->coordlon = $coord[0];
		}
		
		if(isset($cordno)) {
			$coordin->save();
		}else{
			$coordin->update();
		}
	
	
			// Удаляем кеш
			Yii::$app->cache->delete('coord'); Yii::$app->cacheFrontend->delete('coord');
			Yii::$app->cache->delete('region'); Yii::$app->cacheFrontend->delete('region');  Yii::$app->cache->delete('regRelative'); Yii::$app->cacheFrontend->delete('regRelative');  // Удаляем кеш
            Yii::$app->cache->delete('regCase'); Yii::$app->cacheFrontend->delete('regCase'); // Удаляем кеш      
		   //Обновляем склонение 
			   $customer = RegionCase::find()->andWhere(['id_region' => $model->id])->one();
			   $customer->id_region = $model->id;
               $customer->name = $model->regionCase;
               $customer->update();			
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Region model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
	 
	 
	 
	 
    public function actionDelete($id)
    {
   $region = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(),$id);
   Region::deleteAll(['id' => $region]);
   RegionCase::deleteAll(['id_region' => $region]);
   RegionCoord::deleteAll(['region_id' => $region]);
    // Удаляем кеш
	Yii::$app->cache->delete('region'); Yii::$app->cacheFrontend->delete('region');  Yii::$app->cache->delete('regRelative'); Yii::$app->cacheFrontend->delete('regRelative');  // Удаляем кеш
    Yii::$app->cache->delete('regCase'); Yii::$app->cacheFrontend->delete('regCase'); // Удаляем кеш        
	  return $this->redirect(['index']);
    }
	

	

	
	
 public function actionCats($id)
    {	
$customers = Region::find()
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
     * Finds the Region model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Region the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Region::findOne($id)) !== null) {
			$model->regionCase = $model->cases['name'];
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	
	

	
	
	
  



    public function actionCatall()
    {
$arr = Yii::$app->request->get();		
$customers = Region::find()
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
 if (!isset($return)) {$return = '';}
$return=substr($return,0,(strlen($return)-1));
$return="[{$return}]";
 
return $return;

}

// Функция Ajax - Изменение сортировки
public function actionSort()
{
$get = Yii::$app->request->get();		 
if ($get['id'] && $get['sort']) {
$customer = Region::findOne($get['id']);
$customer->sort = $get['sort'];
$customer->save();
// Удаляем кеш
Yii::$app->cache->delete('region'); Yii::$app->cacheFrontend->delete('region');  Yii::$app->cache->delete('regRelative'); Yii::$app->cacheFrontend->delete('regRelative');  // Удаляем кеш
Yii::$app->cache->delete('regCase'); Yii::$app->cacheFrontend->delete('regCase'); // Удаляем кеш      
return 'Сортировка изменена обновление';
}

 }
 
 
 


public function actionExit()
 {
$get = Yii::$app->request->get();	
$arr = Yii::$app->caches->region();	

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
		if (!isset($return)) {$return = '';}
		$return .= '<select class="form-control sel_reg">';
        $return .= '<option value="false">Не выбрано</option>';
//$sort = array_column($cat, 'sort');
//array_multisort($sort, SORT_ASC, $cat);
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
	
if(isset($get['id_reg'])) {
unset($arr[$get['id_reg']]);
}

 return linenav($arr, $get['idcategory']);
	}
	
	
	

	
	protected function findCoord($regname) {
		$url = 'https://geocode-maps.yandex.ru/1.x/?format=json&apikey='.(string)Yii::$app->caches->setting()['api_key_yandex'].'&geocode='.urlencode($regname);
		$datas = json_decode(file_get_contents($url));
		if(isset($datas->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos)) {
          $coord = $datas->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
		}else{
			return '';
		}
		$data = explode(' ',$coord);
		return $data;
	}
}