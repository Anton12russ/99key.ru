<?php

namespace frontend\modules\passanger\controllers;
use common\models\Passanger;
use common\models\PassangerFields;
use common\models\Rates;
use common\models\Upload;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
/**
 * Default controller for the `passanger` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */


	public function actionUser()
    {
	$rat = Rates::find()->Where(['value'=>1])->asArray()->One();
	$get = Yii::$app->request->get();
	$sql = Passanger::find();
	$sql->where(['user_id' => Yii::$app->user->id]);
	if (isset($get['appliances_0']) && !isset($get['appliances_1'])) {
			 $sql->andWhere(['appliances'=> 0]);
	}
	if (isset($get['appliances_1']) && !isset($get['appliances_0'])) {
			 $sql->andWhere(['appliances'=> 1]);
	}
	
	

	
	if (!empty($get['date'])) {
			 $sql->andWhere(['>=', 'time', $get['date'].' 00-00-00']);
			 $sql->andWhere(['<=', 'time', $get['date'].' 24-59-59']);
	}
	
	if (isset($get['mesta']) && $get['mesta'] != '') {
			 $sql->andWhere(['>=','mesta',$get['mesta']]);
	}
	

	
	if(isset($get['ot'])){
	   $region = $this->findRegion($get['ot']);
	   $sql->andFilterWhere(['like', 'ot', $region]);
	}
	
	if(isset($get['kuda'])){
	   $region = $this->findRegion($get['kuda']);
	   $sql->andFilterWhere(['like', 'kuda', $region]);
	}
	$sort = false;
	if(isset($get['sort_tyme']) && $get['sort_tyme'] == 'ASC') {
		 $sql->orderBy(['time' => SORT_ASC]);
		 $sort = true;
	}elseif(isset($get['sort_tyme']) && $get['sort_tyme'] == 'DESC') {
		$sql->orderBy(['time' => SORT_DESC]);
		 $sort = true;
	}
	
	
	if(isset($get['sort']) && $get['sort'] == 'ASC') {
		 $sql->orderBy(['price' => SORT_ASC]);
		  $sort = true;
	}elseif(isset($get['sort']) && $get['sort'] == 'DESC') {
		$sql->orderBy(['price' => SORT_DESC]);
		 $sort = true;
	}
	
	if(!$sort) {
		$sql->orderBy(['id' => SORT_DESC]);
	}
	if(isset($get['count'])) {
		return count($sql->all());	
	}
	$query = $sql;
	$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$models = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	
	
	if(Yii::$app->request->cookies['region']) {
	$region = Yii::$app->request->cookies['region']->value;
	$region_name = ' "'.Yii::$app->caches->region()[$region]['name'].'"';
	}else{
		 $region_name = '';
	}
         return $this->render('user', compact('models', 'pages', 'rat', 'region_name'));
    }
	
	
	
	
	
		public function actionUsers($id)
    {
	$rat = Rates::find()->Where(['value'=>1])->asArray()->One();
	$get = Yii::$app->request->get();
	$sql = Passanger::find();
	$sql->where(['user_id' => $id]);
	$sql->andwhere(['>=','time', date('Y-m-d H:i:s')]);
	if (isset($get['appliances_0']) && !isset($get['appliances_1'])) {
			 $sql->andWhere(['appliances'=> 0]);
	}
	if (isset($get['appliances_1']) && !isset($get['appliances_0'])) {
			 $sql->andWhere(['appliances'=> 1]);
	}
	
	

	
	if (!empty($get['date'])) {
			 $sql->andWhere(['>=', 'time', $get['date'].' 00-00-00']);
			 $sql->andWhere(['<=', 'time', $get['date'].' 24-59-59']);
	}
	
	if (isset($get['mesta']) && $get['mesta'] != '') {
			 $sql->andWhere(['>=','mesta',$get['mesta']]);
	}
	

	
	if(isset($get['ot'])){
	   $region = $this->findRegion($get['ot']);
	   $sql->andFilterWhere(['like', 'ot', $region]);
	}
	
	if(isset($get['kuda'])){
	   $region = $this->findRegion($get['kuda']);
	   $sql->andFilterWhere(['like', 'kuda', $region]);
	}
	$sort = false;
	if(isset($get['sort_tyme']) && $get['sort_tyme'] == 'ASC') {
		 $sql->orderBy(['time' => SORT_ASC]);
		 $sort = true;
	}elseif(isset($get['sort_tyme']) && $get['sort_tyme'] == 'DESC') {
		$sql->orderBy(['time' => SORT_DESC]);
		 $sort = true;
	}
	
	
	if(isset($get['sort']) && $get['sort'] == 'ASC') {
		 $sql->orderBy(['price' => SORT_ASC]);
		  $sort = true;
	}elseif(isset($get['sort']) && $get['sort'] == 'DESC') {
		$sql->orderBy(['price' => SORT_DESC]);
		 $sort = true;
	}
	
	if(!$sort) {
		$sql->orderBy(['id' => SORT_DESC]);
	}
	if(isset($get['count'])) {
		return count($sql->all());	
	}
	$query = $sql;
	$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$models = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	
	
	if(Yii::$app->request->cookies['region']) {
	$region = Yii::$app->request->cookies['region']->value;
	$region_name = ' "'.Yii::$app->caches->region()[$region]['name'].'"';
	}else{
		 $region_name = '';
	}
	$this->layout = 'style_none';
         return $this->render('users', compact('models', 'pages', 'rat', 'region_name'));
    }
	  
	  public function actionMapot()
      {
		  return $this->render('mapot');   
	  }
	  
	  
	  

      public function actionDel($id)
      {

      $model = $this->findModel($id);
	   //Проверяем права на редактирование
	   if($model->user_id != Yii::$app->user->id) {	
		  if(!Yii::$app->user->can('updateOwnPost', ['board' => $model]) && !Yii::$app->user->can('updateBoard')) {
			   throw new NotFoundHttpException('The requested page does not exist.'); 
		  }
	   }
	  $model->time = '2000-12-12 00:00:00';
	  $model->save(false);

      return $this->render('del', compact('model')); 
	  }




	public function actionNew()
    {
      $models = Passanger::find()->limit(5)->orderBy('rand()')->all();
      $rat = Rates::find()->Where(['value'=>1])->asArray()->One();	
      return $this->render('new', compact('models', 'rat'));
	}
	 
	 



	 
	public function actionAdd()
    {
		
		$rat = Rates::find()->Where(['value'=>1])->asArray()->One();	
		$dir_name = Yii::$app->security->generateRandomString(5).'_'.time();
		
		$model = new Passanger();
	
		if ($model->load(Yii::$app->request->post())) {
			 if ($model->dir_name) {$dir_name = $model->dir_name;}			
		}
		
		
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	    
			$model->user_id = Yii::$app->user->id;
			$model->date_add = date('Y-m-d H:i:s');
            $model->time = $model->time.' '.$model->clock.':00';
              

           //Переносим лого
           $logo_dir = '/passanger/'.$dir_name.'/logo-mini/';
	       $logo = Yii::$app->userFunctions->filesDirshop($logo_dir);
	        if ($logo) 
		    {
			  $dir_logo = Yii::getAlias('@images_temp').'/passanger/'.$dir_name.'/logo-mini/';
		      $dir_copy_logo = Yii::getAlias('@img').'/passanger/logo/';
		      foreach($logo as $key => $file) {
                 rename($dir_logo.$file, $dir_copy_logo.Yii::$app->user->id.'_'.time().'_'.$key.'.jpg');
		         $model->img = Yii::$app->user->id.'_'.time().'_'.$key.'.jpg';
		      } 
			  
			  
			 //Очищаем временную папку с фото и логотипом
             Yii::$app->userFunctions->recursiveRemove(Yii::getAlias('@images_temp').'/passanger/'.$dir_name.'/');
	        }
		   $model->save();
		   $model2 = new PassangerFields();
		   $model2->passanger_id = $model->id;
		   $model2->text = $model->text;
		   $model2->marka = $model->marka;
		   $model2->phone = $model->phone;
		   $model2->coord_ot = $model->coord_ot;
		   $model2->name = $model->name ;
		   $model2->coord_kuda = $model->coord_kuda;
		   $model2->save();
		   return $this->redirect(['one', 'id' =>$model->id, 'save'=>'true']);
		}
		
		
        return $this->render('add',compact('model', 'rat','dir_name'));
    } 
	 
	 
	 
	 
	 
	 
	 
	 
//-------------------------------------------UPDATE-----------------------------------------------//
	 
	public function actionUpdate($id)
    {
		$rat = Rates::find()->Where(['value'=>1])->asArray()->One();	
		
        $model = $this->findModel($id);
		
		if($model->user_id != Yii::$app->user->id) {	
		     //Проверяем права на редактирование
		      if(!Yii::$app->user->can('updateOwnPost', ['passanger' => $model]) && !Yii::$app->user->can('updateShop')) {
		        	 throw new NotFoundHttpException('The requested page does not exist.'); 
		      }
		 }
		
	    $dir_name = $model->id;
	    $model2 = $this->findModel2($model->id);
	
		$model['attributes'] = $model2['attributes'];
		if (!Yii::$app->request->post() && $model->img) {
		  $this->findFile($model->id, $model->img);
		}
		
		if ($model->load(Yii::$app->request->post())) {
			 if ($model->dir_name) {$dir_name = $model->dir_name;}			
		}
		
		

		
		
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->user_id = Yii::$app->user->id;
			$model->date_add = date('Y-m-d H:i:s');
            $model->time = $model->time.' '.$model->clock;
              

           //Переносим лого
           $logo_dir = '/passanger/'.$dir_name.'/logo-mini/';
	       $logo = Yii::$app->userFunctions->filesDirshop($logo_dir);
	        if ($logo) 
		    {
			  $dir_logo = Yii::getAlias('@images_temp').'/passanger/'.$dir_name.'/logo-mini/';
		      $dir_copy_logo = Yii::getAlias('@img').'/passanger/logo/';
		      foreach($logo as $key => $file) {
                 rename($dir_logo.$file, $dir_copy_logo.Yii::$app->user->id.'_'.time().'_'.$key.'.jpg');
		         $model->img = Yii::$app->user->id.'_'.time().'_'.$key.'.jpg';
		      } 
			  
			  
			 //Очищаем временную папку с фото и логотипом
             Yii::$app->userFunctions->recursiveRemove(Yii::getAlias('@images_temp').'/passanger/'.$dir_name.'/');
	        }else{
					 @unlink(Yii::getAlias('@img').'/passanger/logo/'.$model->img);
				     $model->img = '';
			}

		   $model->save();
		 
		   
		   //$model2->passanger_id = $model->id;
		   $model2->text = $model->text;
		   $model2->marka = $model->marka;
		   $model2->phone = $model->phone;
		   $model2->coord_ot = $model->coord_ot;
		   $model2->coord_kuda = $model->coord_kuda;
		   $model2->name = $model->name ;
		   $model2->update();
		   
		   
		   
		   return $this->redirect(['one', 'id' =>$model->id, 'save'=>'true']);
		}
		
		
        return $this->render('add',compact('model', 'rat','dir_name'));
    } 
	
	
	
	
	
	
	 
	 
	 
	 
	 
	 
    public function actionIndex()
    {
	$rat = Rates::find()->Where(['value'=>1])->asArray()->One();
	$get = Yii::$app->request->get();
	$sql = Passanger::find();
	$sql->where(['>=','time', date('Y-m-d H:i:s')]);
	if (isset($get['appliances_0']) && !isset($get['appliances_1'])) {
			 $sql->andWhere(['appliances'=> 0]);
	}
	if (isset($get['appliances_1']) && !isset($get['appliances_0'])) {
			 $sql->andWhere(['appliances'=> 1]);
	}
	
	
	if (isset($get['pol_0']) && !isset($get['pol_1'])) {
			 $sql->andWhere(['pol'=> 0]);
	}
	if (isset($get['pol_1']) && !isset($get['pol_0'])) {
			 $sql->andWhere(['pol'=> 1]);
	}
	

	
	if (isset($get['photo'])) {
			 $sql->andWhere(['>=', 'img', 1]);
	}
	
	if (!empty($get['date'])) {
			 $sql->andWhere(['>=', 'time', $get['date'].' 00-00-00']);
			 $sql->andWhere(['<=', 'time', $get['date'].' 24-59-59']);
	}
	
	if (isset($get['mesta']) && $get['mesta'] != '') {
			 $sql->andWhere(['>=','mesta',$get['mesta']]);
	}
	

	
	if(isset($get['ot'])){
	   $region = $this->findRegion($get['ot']);
	   $sql->andFilterWhere(['like', 'ot', $region]);
	}
	
	if(isset($get['kuda'])){
	   $region = $this->findRegion($get['kuda']);
	   $sql->andFilterWhere(['like', 'kuda', $region]);
	}
	$sort = false;
	if(isset($get['sort_tyme']) && $get['sort_tyme'] == 'ASC') {
		 $sql->orderBy(['time' => SORT_ASC]);
		 $sort = true;
	}elseif(isset($get['sort_tyme']) && $get['sort_tyme'] == 'DESC') {
		$sql->orderBy(['time' => SORT_DESC]);
		 $sort = true;
	}
	
	
	if(isset($get['sort']) && $get['sort'] == 'ASC') {
		 $sql->orderBy(['price' => SORT_ASC]);
		  $sort = true;
	}elseif(isset($get['sort']) && $get['sort'] == 'DESC') {
		$sql->orderBy(['price' => SORT_DESC]);
		 $sort = true;
	}
	
	if(!$sort) {
		$sql->orderBy(['id' => SORT_DESC]);
	}
	if(isset($get['count'])) {
		return count($sql->all());	
	}
	$query = $sql;
	$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$models = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	
	
	if(Yii::$app->request->cookies['region']) {
	$region = Yii::$app->request->cookies['region']->value;
	$region_name = ' "'.Yii::$app->caches->region()[$region]['name'].'"';
	}else{
		$region_name = '';
	}
         return $this->render('index', compact('models', 'pages', 'rat', 'region_name'));
    }
	
	
	
	
	
	
	
	
	
	/*-------------------------------------ONE ------------------------------*/
	
	
	
	public function actionOne($id)
    {
	    $one = $this->findModel($id);

		if(isset(Yii::$app->request->get()['mesta'])) {

	   //Проверяем права на редактирование
	   if( $one->user_id != Yii::$app->user->id) {	
		  if(!Yii::$app->user->can('updateOwnPost', ['board' =>  $one]) && !Yii::$app->user->can('updateBoard')) {
			   throw new NotFoundHttpException('The requested page does not exist.'); 
		  }
	   }
	     $one->mesta = (int)Yii::$app->request->get()['mesta'];
	     $one->update(false);
		}
		
		$rat = Rates::find()->Where(['value'=>1])->asArray()->One();
	
		$fields = $this->findModel2($id);
		$fields->views = $fields->views+1;   
	    $fields->update(false);
		//$this->layout = 'style_none';
		return $this->render('one', compact('one', 'rat'));
	}
	
	
	public function actionMaps($coord_ot, $ot, $coord_kuda, $kuda)
    {	

	   $this->layout = 'style_none';
	   return $this->render('maps', compact('coord_ot', 'ot','coord_kuda', 'kuda'));
	}
	

	
	
	
	
	 public function actionSearchot($text)
    {
		
				
		if($text) {
         $sql = Passanger::find()->select(['ot']);
		 $sql->where(['>=','time', date('Y-m-d H:i:s')]);
		 $sql->andFilterWhere(['like', 'ot', $text]);
		 

		  
		  $blogs = $sql->Limit(7)->asArray()->all();
		  $blogi = [];
		  foreach($blogs as $blog) {
			 
			  $blogi[] = array('title' => Yii::$app->userFunctions2->textName2($blog['ot'], $text));
		  }
		  
		  
	       $blogi =  array_unique($blogi, SORT_REGULAR);
	        $act = 'ot';
	       return $this->render('auto', compact('blogi', 'region', 'text', 'act'));
		}
		 
    }
	
	
	
	
	 public function actionSearchkuda($text)
    {
		
				
		if($text) {
         $sql = Passanger::find()->select(['kuda']);
		 $sql->where(['>=','time', date('Y-m-d H:i:s')]);
		 $sql->andFilterWhere(['like', 'kuda', $text]);
		 

		  
		  $blogs = $sql->Limit(7)->asArray()->all();
		  $blogi = [];
		  foreach($blogs as $blog) {
			 
			  $blogi[] = array('title' => Yii::$app->userFunctions2->textName2($blog['kuda'], $text));
		  }
		  
		  
	       $blogi =  array_unique($blogi, SORT_REGULAR);
		   $act = 'kuda';
	       return $this->render('auto', compact('blogi', 'region', 'text', 'act'));
		}
		 
    }	
	
	
			/*-------------------------------------Для логотипа ------------------------------*/	
	
	
	 public function actionUploadLogo()
    {
		if (Yii::$app->request->isPost) {
            $model = new Upload();

			$model->file = UploadedFile::getInstance($model, 'logo');

			if($model->load(Yii::$app->request->post()) && $model->validate() ) {
				
							
	   if ($this->filesIdlogo($model->dir) >= 1) {
		   $result = [
                    'error' => 'Привышено количество файлов, не более (1)'
                ];

        Yii::$app->response->format = Response::FORMAT_JSON;			 
			 return  $result;
		}
		
		
            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            } else {	

				$dir = Yii::getAlias('@images_temp').'/passanger/';
			    $dir = $dir.$model->dir.'/';
			    @mkdir($dir, 0700);
				@mkdir($dir.'logo-mini/', 0700);
			    @mkdir($dir.'logo-original/', 0700);
				
					//Имя файла
                $model->file->name = strtotime('now').'.jpg';
				
		//Сохраняем файл оригинал		
       if ($model->file->saveAs($dir.'logo-original/' . $model->file->name)) {
		   
		//Берем оригинал и делаем копии
         $imag = Yii::$app->image->load($dir.'logo-original/' . $model->file->name);


         //Сохраняем в папку с маленьким размером 
	     $imag->background('#FFF','0');
		 $imag->resize(200, 200,Yii\image\drivers\Image::INVERSE);
		 $imag->crop(200, 200);
		 $imag->save($dir.'logo-mini/'.$model->file->name, 80);
			        if (!isset($result_link)) {$result_link = '';}
                    $result = ['filelink' => $result_link . $model->file->name,'filename' => $model->file->name];
                } else {
                    $result = [
                        'error' => Yii::t('vova07/imperavi', 'ERROR_CAN_NOT_UPLOAD_FILE')
                    ];
                }	
			}
			}else{
				
				 $result = [
                    'error' => $model->hasErrors()
                ];
			}
       Yii::$app->response->format = Response::FORMAT_JSON;
           
		   return $result;
        } else {
            throw new BadRequestHttpException('Что-то не так');
        }
  
  
    }
//Удаление файлов при клике на кнопку удаления в представлении
    public function actionDelLogo()
    {
if (Yii::$app->request->post('key')) {
$file = Yii::$app->request->post('key');

@unlink($file);

@unlink(str_replace('logo-mini','logo-original',$file));

return true;
}
return false;
    }
	
	//Выгрузка фото из временной папки
 function filesIdlogo($dir_name) {
	$dir = Yii::getAlias('@images_temp').'/passanger/'.$dir_name.'/logo-mini/';
	$list = @scandir($dir);
	return @count($list)-2;
	} 
	
	
	
	
	
	
	protected function findModel($id)
    {
        if (($model = Passanger::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        }

      throw new NotFoundHttpException('The requested page does not exist.');
    }
	



	protected function findModel2($id)
    {
        if (($model2 = PassangerFields::find()->where(['passanger_id' => $id])->one()) !== null) {
            return $model2;
        }

     //   throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	
	
	
	
	
	
	
	
protected function findRegion($region) {

	foreach(Yii::$app->caches->region() as $reg) {

		if($reg['name'] == $region) {
			
			
			 if($reg['name'] == 'Россия') {
			    return $reg['name'];
			 }else{
				return $reg['name'].',';
			 }
		}
	}

	return $region;
}
	
	
			 //Создание папки для файлов при редактировании объявления и перемищения файлов во временные папки для дальнейшей работы с ними 
 protected function findFile($id, $logo)
    {
		$url = $id;
		Yii::$app->userFunctions->delDir(Yii::getAlias('@images_temp').'/shop/'.$url);
       if ($logo) { 
	        @mkdir(Yii::getAlias('@images_temp').'/passanger/'.$url, 0700);
	
            if($logo) {
				@mkdir(Yii::getAlias('@images_temp').'/passanger/'.$url.'/logo-mini/', 0700);
				@copy(Yii::getAlias('@img').'/passanger/logo/'.$logo, Yii::getAlias('@images_temp').'/passanger/'.$url.'/logo-mini/'.$logo);
			}

     }
	}
	
}
