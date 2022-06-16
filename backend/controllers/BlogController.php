<?php

namespace backend\controllers;

use Yii;
use common\models\Blog;
use common\models\BlogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Field;
use common\models\Category;
use common\models\Rates;
use \yii\base\DynamicModel;
use common\models\BlogField;
use common\models\BlogImage;
use common\models\BlogTime;
use common\models\BlogComment;
use common\models\BlogCoord;
use common\models\BlogServices;


/**
 * BlogController implements the CRUD actions for Blog model.
 */
 
 

 
class BlogController extends Controller
{
	public $body_id = 'movies'; 
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
     * Lists all Blog models.
     * @return mixed
     */
 
	 
	 
	 
	public function actions()
{
    return [
            'imageSave' => [
                'class' => 'common\components\ActionBlog\ActionBlogImage',
            ],
			
    ];
} 
	 
	 
	 
    public function actionIndex()
    {
		
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionExpress()
    {
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, false, false, true);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Blog model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$rat = Rates::find()->all();	
        foreach ($rat as $res) {
          $rates[$res['id']] = array('name' => $res['text'], 'value' => $res['value']);
        }	
$model = $this->findModel($id);	
$blog_field = $model->blogField;
$fields = array();
$price = array();
$coord = array();
		
		foreach($blog_field as $bFields) {

		  
	
	 if ($bFields['fields']['values']) {$bFields['value'] = explode("\n",$bFields['fields']['values'])[$bFields['value']];}
        if ($bFields['fields']['type']) {
	       $fields[] = array(
		   'type' => $bFields['fields']['type'], 
		   'name' => $bFields['fields']['name'], 
		   'type_string' => $bFields['fields']['type_string'], 
		   'hide' => $bFields['fields']['hide'], 
		   'value' => $bFields['value'],
		   );

	    }

	  
}
        return $this->render('view', [
            'model' => $model,
			'rates' => $rates,
        ]);
    }

    /**
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {	
	
	   $times = BlogTime::find()->where(['def'=>'1'])->orderBy([ 'sort' => SORT_ASC])->all();	
	   $dir_name = Yii::$app->security->generateRandomString(5).'_'.time();
       $model = new Blog();
       $catid = $model->category;
        if (Yii::$app->request->post('Pjax_category')) {
             $catid =   Yii::$app->request->post('Pjax_category');
        }
		if(@Yii::$app->request->post()['Blog']['category']) {
               $catid =  Yii::$app->request->post()['Blog']['category'];	
		}
   
	foreach (Yii::$app->userFunctions->fieldarr($catid) as $field) {
	    $model_view[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
	    $mod[] = 'f_'.$field['id'];
	    $max[] = array('id' => 'f_'.$field['id'],'max' => $field['max']);
	    //Массив обязательных к заполнению
            if ($field['req'] == '1') {
	            $required[] = 'f_'.$field['id'];
	        }
	    //Массив проверки только на строковое значение text и textarea
	     if ($field['type'] == 'v' || $field['type'] == 't' || $field['type'] == 'y') {
	          $string[] = 'f_'.$field['id'];
	     }
	    //Массив проверки только на целое число
	     if ($field['type_string'] == 'n' || $field['type'] == 'p' || $field['type'] == 'r' || $field['type'] == 's') {
              $integer[] = 'f_'.$field['id'];
	     }
	    //Массив проверки только на целое число для чекбоксов. Потому что, иногда возвращается массивом более одного значения
         if ($field['type'] == 'c') {
		      $integer_с[] = 'f_'.$field['id'];
	     }
	   //Массив проверки телефонного номера
	     if ($field['type_string'] == 't' || $field['type_string'] == 'x') {
              $phone[] = 'f_'.$field['id'];
	     }
	
	   //Массив Проверки ссылки на ютуб
	     if ($field['type'] == 'y') {
              $youtube[] = 'f_'.$field['id'];
	     }
	
	  //Массив Проверки ссылки
	     if ($field['type_string'] == 'u') {
              $url[] = 'f_'.$field['id'];
	     }
	
      //Проверяем есть ли у категории, поле цена
	   if ($field['type'] == 'p') {
            $rate = true;
	        $price[] = 'f_'.$field['id'];
	        $price_r[] = 'f_'.$field['id'].'_rates';
	        $integer[] = 'f_'.$field['id'];
	        $integer[] = 'f_'.$field['id'].'_rates';
	   }
	 //Проверяем есть ли у категории, поле координаты
	   if ($field['type'] == 'j') {
           $cordin[] = 'f_'.$field['id'].'_address';
	   }
    }
        if ($rate)
		{
           $rat = Rates::find()->all();	
              foreach ($rat as $res) {
                   $rates[] = array('id' =>  $res['id'], 'name' => $res['name'], 'value' => $res['value']);
              }	
	               $mod = array_merge($price_r,$mod);
         }	
		
		if (isset($cordin)) {
        $mod = array_merge($mod,$cordin);	
        $string = array_merge($string,$cordin);			
        }		
		

		$model2 = new DynamicModel($mod);
		
        $model2 
		->addRule($required, 'required',['message'=>'Поле не может быть пустым'])
		//->addRule($required, function ($attribute) use ($model2, $price){ foreach($price as $res) {if($attribute == $res)if ($model2->attributes[$attribute] == 0) {$model2->addError($attribute,  'Поле не может быть 0.');}}})
		->addRule($string, 'string',['message'=>'Должны быть введены только строковые значения'])
		->addRule($integer, 'integer',['message'=>'Должны быть введены только целые числа'])
		//Проверка на телефон
        ->addRule($phone, function ($attribute) use ($model2) {$pattern = "#^\+[0-9] {1,2}\s?\([0-9]{3}\)\s?[0-9]+\-[0-9]+\-[0-9]+$#"; if(preg_match($pattern, $model2->attributes[$attribute], $out)){}else{$model2->addError($attribute,  'Не верный формат номера');}})
        //Проверка на Ютуб
	    ->addRule($youtube, function ($attribute) use ($model2) {$res = 'https://www.youtube.com/watch?v=';if (strpos($model2->attributes[$attribute], $res) !== false) {$headers = get_headers($model2->attributes[$attribute]);if (!strpos($headers[0], '200')) { $model2->addError($attribute,  'Видео не существует');}}else{ $model2->addError($attribute,  'Не верный формат ссылки на ролик');}})
        //Проверка ограничения символов
        ->addRule($mod, function ($attribute) use ($model2,$max) {
			if (is_array($model2->attributes[$attribute])) {foreach($model2->attributes[$attribute] as $res) {$strlen = strlen($res); foreach($max as $res) {if ($res['id'] == $attribute) {if ($strlen > $res['max']) {$model2->addError($attribute,  'Привышено допустимое количество символов ('.$res['max'].')');}}}}}
            if (!is_array($model2->attributes[$attribute])) {$strlen = strlen($model2->attributes[$attribute]);foreach($max as $res) {if ($res['id'] == $attribute) {if ($strlen > $res['max']) {$model2->addError($attribute,  'Привышено допустимое количество символов ('.$res['max'].')');}}} }})
        //Проверка адреса сайта
	    ->addRule($url, function ($attribute) use ($model2) {if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $model2->attributes[$attribute], $out)){}else{ $model2->addError($attribute,  'Не верный формат URL (Адреса сайта)');}})
	    ->addRule($integer_с, function ($attribute) use ($model2) {if (is_array($model2->attributes[$attribute])) { foreach($model2->attributes[$attribute] as $res) {if(!is_numeric($res)){$model2->addError($attribute,  'Вы совершиили ошибку в заполнении данного поля.');}} }if (!is_array($model2->attributes[$attribute])) { if(!is_numeric($model2->attributes[$attribute])){ $model2->addError($attribute,  'Не верный формат URL (Адреса сайта)');}} })
         ;
	 
	 
   /*
        if (!Yii::$app->request->post()) {
             $model2_arr =  $this->findModel2($id);
		        foreach ($model2['attributes'] as $key =>$result) {
		           $model2[$key] = $model2_arr[$key];
		        }
	     }	
	*/	
		
		
        $array_post = Yii::$app->request->post();
           if($model2->load($array_post) && $model2->validate() ){
                $model2_result = true;
           }

		$model->url = Yii::$app->userFunctions->transliteration(Yii::$app->request->post('Blog')['title']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		if ($model->dir_name) {$dir_name = $model->dir_name;}
             if ($model2_result) {
		//		 

		if (count($times) > 1) {
		    if (count($times) == 1) {$time_d = $times[0]['days'];}else{$time_d = $model->date_del;}
		}else{
		    $time_d = 30;   
		} 
           $model->date_add =  date('Y-m-d H:i:s');
		   $model->date_del =  date('Y-m-d H:i:s', strtotime(' + '.$time_d.' day'));
                  $model->save();
     
     BlogField::deleteAll('message = :message', [':message' => $model->id]);
	 
	





	//Обрабатываем массив с данными и группируем валюту и адрес в поле dop
	 $post_save = array();

	 foreach($model2['attributes'] as $key => $res) {
		 if(isset($model2['attributes'][$key.'_rates'])) {
	       // $res = ($res * Yii::$app->caches->rates()[$model2['attributes'][$key.'_rates']]);
		   $res = ((int)$res * (int)Yii::$app->caches->rates()[$model2['attributes'][$key.'_rates']]['value']);
			$dop = $model2['attributes'][$key.'_rates'];
		 }
	      if(isset($model2['attributes'][$key.'_address'])) {
		    $dop = $model2['attributes'][$key.'_address']; 
		 }
		 
		  if (strpos($key, '_rates') === false && strpos($key, '_address') === false) {
		         if ($res !== '') {
		              $post_save[] = array('id' => (int)str_replace('f_','',$key), 'value' => $res, 'dop' => $dop);
		         }
		 }
		  $dop = '';
	 }


     foreach($post_save as $res) {
	           if (!is_array($res['value'])) {	
			   
			 
                   $customer = new BlogField();
                   $customer->message = $model->id;
                   $customer->field  = $res['id'];
                   $customer->value  = strval($res['value']);
				   $customer->dop  = $res['dop'];
                   $customer->save();
	
               }else{
                   foreach($res['value'] as $result) {
                   $customer = new BlogField();
                   $customer->message = $model->id;
                   $customer->field  = $res['id'];
                   $customer->value  = strval($result);
				   $customer->dop  = $res['dop'];
                   $customer->save();	
	                }
               }
		 
            }
			
		            $dir_maxi = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
		            $dir_mini = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/mini/';
		            $dir_original = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/original/';
					
				    $dir_copy_maxi = Yii::getAlias('@images').'/board/maxi/';
		            $dir_copy_mini = Yii::getAlias('@images').'/board/mini/';
		            $dir_copy_original = Yii::getAlias('@images').'/board/original/';
			//Функция перемещения изображений из временной папки в папку с фото
	    $list = $this->filesDir($dir_name);
		if ($list) 
		{
			BlogImage::deleteAll('blog_id = :blog_id', [':blog_id' => $model->id]);
		   foreach($list as $key => $file) {
            @rename($dir_maxi.$file, $dir_copy_maxi.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.png');
			@rename($dir_mini.$file, $dir_copy_mini.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.png');
			@rename($dir_original.$file, $dir_copy_original.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.png');
			
			//Сохраняем название файла в базу данных
			      $blogimage = new BlogImage();
				   $blogimage->id = '';
                   $blogimage->blog_id = $model->id;
                   $blogimage->image  = $model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.png';
                   $blogimage->save();
		   }			
	    }	
		$this->recursiveRemove(Yii::getAlias('@images_temp').'/board/'.$dir_name.'/');
		


	     return $this->redirect(['view', 'id' => $model->id]);
          }	
	   }

      
		  //Создаем правильный массив чтобы в представлении скормить его в хелпер
		  if (count($times) > 1) {
		     foreach($times as $res) {
			    $time[$res['days']] = $res['text'];
		     }
		  }
		
		  
        if ($model->dir_name) {$dir_name = $model->dir_name;}
         return $this->render('create', [
            'model' => $model,
			'model2' => $model2,
			'model_view' => $model_view,
			'rates' => $rates,
			'dir_name' => $dir_name,
			'time' => $time,
        ]);  
    }
	


    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	//*UPDATE*//
    public function actionUpdate($id)
    {
   
       $model = $this->findModel($id);
	   
	   //---Обновление координаты ---//
	   if(isset($model->coord->text)) {
	   $model->address = $model->coord->text;
	   $model->coordlat = $model->coord->coordlat;
	   $model->coordlon = $model->coord->coordlon;
	   }
	   $status_id =  $model->status_id;
	//Копируем изображения во временную папку
	$dir_name = $id;
	if (!Yii::$app->request->post()) {
	$base = BlogImage::find()->where(['blog_id'=>$id])->select(['image'])->asArray()->all();
	$this->findFile($id, $base);
	}
	
       $catid = $model->category;
        if (Yii::$app->request->post('Pjax_category')) {
             $catid =   Yii::$app->request->post('Pjax_category');
        }
		if(isset(Yii::$app->request->post()['Blog']['category'])) {
               $catid =  Yii::$app->request->post()['Blog']['category'];	
		}
   
	foreach (Yii::$app->userFunctions->fieldarr($catid) as $field) {
	    $model_view[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
	    $mod[] = 'f_'.$field['id'];
	    $max[] = array('id' => 'f_'.$field['id'],'max' => $field['max']);
	    //Массив обязательных к заполнению
            if ($field['req'] == '1') {
	            $required[] = 'f_'.$field['id'];
	        }
	    //Массив проверки только на строковое значение text и textarea
	     if ($field['type'] == 'v' || $field['type'] == 't' || $field['type'] == 'y') {
	          $string[] = 'f_'.$field['id'];
	     }
	    //Массив проверки только на целое число
	     if ($field['type_string'] == 'n' || $field['type'] == 'p' || $field['type'] == 'r' || $field['type'] == 's') {
              $integer[] = 'f_'.$field['id'];
	     }
	    //Массив проверки только на целое число для чекбоксов. Потому что, иногда возвращается массивом более одного значения
         if ($field['type'] == 'c') {
		      $integer_с[] = 'f_'.$field['id'];
	     }
	   //Массив проверки телефонного номера
	     if ($field['type_string'] == 't' || $field['type_string'] == 'x') {
              $phone[] = 'f_'.$field['id'];
	     }
	
	   //Массив Проверки ссылки на ютуб
	     if ($field['type'] == 'y') {
              $youtube[] = 'f_'.$field['id'];
	     }
	
	  //Массив Проверки ссылки
	     if ($field['type_string'] == 'u') {
              $url[] = 'f_'.$field['id'];
	     }
	
      //Проверяем есть ли у категории, поле цена
	   if ($field['type'] == 'p') {
            $rate = true;
	        $price[] = 'f_'.$field['id'];
	        $price_r[] = 'f_'.$field['id'].'_rates';
	        $integer[] = 'f_'.$field['id'];
	        $integer[] = 'f_'.$field['id'].'_rates';
	   }
	 //Проверяем есть ли у категории, поле координаты
	   if ($field['type'] == 'j') {
           $cordin[] = 'f_'.$field['id'].'_address';
	   }
    }
        if ($rate) {
        $rat = Rates::find()->all();	

        foreach ($rat as $res) {

          $rates[] = array('id' =>  $res['id'], 'name' => $res['name'], 'value' => $res['value']);
        }	
	    $mod = array_merge($price_r,$mod);
        }	
		
		if (isset($cordin)) {
        $mod = array_merge($mod,$cordin);	
        $string = array_merge($string,$cordin);			
        }		
		

		$model2 = new DynamicModel($mod);
		
        $model2 
		->addRule($required, 'required',['message'=>'Поле не может быть пустым'])
		//->addRule($required, function ($attribute) use ($model2, $price){ foreach($price as $res) {if($attribute == $res)if ($model2->attributes[$attribute] == 0) {$model2->addError($attribute,  'Поле не может быть 0.');}}})
		->addRule($string, 'string',['message'=>'Должны быть введены только строковые значения'])
		->addRule($integer, 'integer',['message'=>'Должны быть введены только целые числа'])
		//Проверка на телефон
        ->addRule($phone, function ($attribute) use ($model2) {$pattern = "#^\+[0-9] {1,2}\s?\([0-9]{3}\)\s?[0-9]+\-[0-9]+\-[0-9]+$#"; if(preg_match($pattern, $model2->attributes[$attribute], $out)){}else{$model2->addError($attribute,  'Не верный формат номера');}})
        //Проверка на Ютуб
	    ->addRule($youtube, function ($attribute) use ($model2) {$res = 'https://www.youtube.com/watch?v=';if (strpos($model2->attributes[$attribute], $res) !== false) {$headers = get_headers($model2->attributes[$attribute]);if (!strpos($headers[0], '200')) { $model2->addError($attribute,  'Видео не существует');}}else{ $model2->addError($attribute,  'Не верный формат ссылки на ролик');}})
        //Проверка ограничения символов
        ->addRule($mod, function ($attribute) use ($model2,$max) {
			if (is_array($model2->attributes[$attribute])) {foreach($model2->attributes[$attribute] as $res) {$strlen = strlen($res); foreach($max as $res) {if ($res['id'] == $attribute) {if ($strlen > $res['max']) {$model2->addError($attribute,  'Привышено допустимое количество символов ('.$res['max'].')');}}}}}
            if (!is_array($model2->attributes[$attribute])) {$strlen = strlen($model2->attributes[$attribute]);foreach($max as $res) {if ($res['id'] == $attribute) {if ($strlen > $res['max']) {$model2->addError($attribute,  'Привышено допустимое количество символов ('.$res['max'].')');}}} }})
        //Проверка адреса сайта
	    ->addRule($url, function ($attribute) use ($model2) {})
	    ->addRule($integer_с, function ($attribute) use ($model2) {if (is_array($model2->attributes[$attribute])) { foreach($model2->attributes[$attribute] as $res) {if(!is_numeric($res)){$model2->addError($attribute,  'Вы совершиили ошибку в заполнении данного поля.');}} }if (!is_array($model2->attributes[$attribute])) { if(!is_numeric($model2->attributes[$attribute])){ $model2->addError($attribute,  'Не верный формат URL (Адреса сайта)');}} })
         ;
        if (!Yii::$app->request->post()) {    }	
			
             $model2_arr =  $this->findModel2($id);
			
			 
		        foreach ($model2['attributes'] as $key =>$result) {
					if (!isset($model2_arr[$key]['value'])) {$model2_arr[$key]['value'] = '';}
		           $model2[$key] = $model2_arr[$key]['value'];

				   if (strpos($key, '_address') !== false){
					   $key_addr = str_replace('_address', '', $key);
					   if(isset($model2_arr[$key_addr]['dop'])) {
				         $model2[$key] = $model2_arr[$key_addr]['dop'];
					   }
					   $key_addr = '';
				   }
			          //Преобразуем цену из дефолтной в указанную пользователем
			          foreach($price as $res) {	 
					      if ($key == $res) {
							  if ($model2[$res]) {
					            $model2[$key] = ($model2[$res] / Yii::$app->caches->rates()[$model2_arr[$key]['dop']]['value']);
								$model2[$key.'_rates'] = $model2_arr[$key]['dop'];
							  }
			              }
					  }
		        }
	 
		
        $array_post = Yii::$app->request->post();
           if($model2->load($array_post) && $model2->validate() ){
                $model2_result = true;
           }

		$model->url = Yii::$app->userFunctions->transliteration(Yii::$app->request->post('Blog')['title']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	
             if (isset($model2_result)) {
                  $model->save();
			//------Обновление координаты----------------//
			$coord = $this->findCoord($model->id);
			if($coord) {
			  if($model->coordlat) {
               $coord->blog_id = $model->id;
               $coord->coordlat  = $model->coordlat;
               $coord->coordlon  = $model->coordlon;
			   $coord->text  = $model->address;
               $coord->update();
			  }
			}else{
			  if($model->coordlat) {
			   $coord = new BlogCoord();
               $coord->blog_id = $model->id;
               $coord->coordlat  = $model->coordlat;
               $coord->coordlon  = $model->coordlon;
			   $coord->text  = $model->address;
               $coord->save();
			  }
			}

     BlogField::deleteAll('message = :message', [':message' => $model->id]);
	 
	 
	 
	 
//Обрабатываем массив с данными и группируем валюту и адрес в поле dop
	 $post_save = array();

	 foreach($model2['attributes'] as $key => $res) {
		 if(isset($model2['attributes'][$key.'_rates'])) {
	       // $res = ($res * Yii::$app->caches->rates()[$model2['attributes'][$key.'_rates']]);
		   $res = ((int)$res * (int)Yii::$app->caches->rates()[$model2['attributes'][$key.'_rates']]['value']);
			$dop = $model2['attributes'][$key.'_rates'];
		 }
	      if(isset($model2['attributes'][$key.'_address'])) {
		    $dop = $model2['attributes'][$key.'_address']; 
		 }
		 
		  if (strpos($key, '_rates') === false && strpos($key, '_address') === false) {
		         if ($res !== '') {
		              $post_save[] = array('id' => (int)str_replace('f_','',$key), 'value' => $res, 'dop' => $dop);
		         }
		 }
		  $dop = '';
	 }


     foreach($post_save as $res) {
	           if (!is_array($res['value'])) {	
			   
			 
                   $customer = new BlogField();
                   $customer->message = $model->id;
                   $customer->field  = $res['id'];
                   $customer->value  = strval($res['value']);
				   $customer->dop  = $res['dop'];
                   $customer->save();
	
               }else{
                   foreach($res['value'] as $result) {
                   $customer = new BlogField();
                   $customer->message = $model->id;
                   $customer->field  = $res['id'];
                   $customer->value  = strval($result);
				   $customer->dop  = $res['dop'];
                   $customer->save();	
	                }
               }
		 
            }

				 $dir_maxi = Yii::getAlias('@images_temp').'/board/'.$id.'/maxi/';
		         $dir_mini = Yii::getAlias('@images_temp').'/board/'.$id.'/mini/';
		         $dir_original = Yii::getAlias('@images_temp').'/board/'.$id.'/original/';
				    $dir_copy_maxi = Yii::getAlias('@images').'/board/maxi/';
		            $dir_copy_mini = Yii::getAlias('@images').'/board/mini/';
		            $dir_copy_original = Yii::getAlias('@images').'/board/original/';
			//Функция перемещения изображений из временной папки в папку с фото
	    $list = $this->filesDir($id);
		
		$base = BlogImage::find()->where(['blog_id'=>$id])->select(['image'])->asArray()->all();
		foreach($base as $res) {
			       @unlink($dir_copy_maxi.$res['image']);
				   @unlink($dir_copy_mini.$res['image']);
				   @unlink($dir_copy_original.$res['image']);
		}
		$mod = BlogImage::deleteAll('blog_id = :blog_id', [':blog_id' => $id]);
		if ($list) 
		{
		$name_rand = Yii::$app->getSecurity()->generateRandomString(6);
		   foreach($list as $key => $file) {	
			@rename($dir_maxi.$file, $dir_copy_maxi.$key.'_'.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$name_rand.'.png');
			@rename($dir_mini.$file, $dir_copy_mini.$key.'_'.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$name_rand.'.png');
			@rename($dir_original.$file, $dir_copy_original.$key.'_'.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$name_rand.'.png');
			

			//Сохраняем название файла в базу данных
			      $blogimage = new BlogImage();
				   $blogimage->id = '';
                   $blogimage->blog_id = $model->id;
                   $blogimage->image  = $key.'_'.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$name_rand.'.png';
                   $blogimage->save();
		   }	
		   

		}			
			if ($base) {
			foreach ($base as $res) {
				
				 @unlink($dir_copy_maxi.$res['image']);
				  @unlink($dir_copy_mini.$res['image']);
				   @unlink($dir_copy_original.$res['image']);
			}
			}
			
		$this->recursiveRemove(Yii::getAlias('@images_temp').'/board/'.$dir_name.'/');
		
	
		return $this->redirect(['view', 'id' => $model->id]);
          }	
	   }
	   
	   
    
		

        //if ($model->dir_name) {$dir_name = $model->dir_name;}

		//$dir_name = $model->url;
         return $this->render('update', [
            'model' => $model,
			'model2' => $model2,
			'model_view' => $model_view,
			'rates' => $rates,
			'dir_name' => $dir_name,
        ]);
    }
	
	
	
	

























		//*UPDATE*//
		public function actionUpdateexpress($id)
		{
	   
		   $model = $this->findModel($id);
		   
		   //---Обновление координаты ---//
		   if(isset($model->coord->text)) {
		   $model->address = $model->coord->text;
		   $model->coordlat = $model->coord->coordlat;
		   $model->coordlon = $model->coord->coordlon;
		   }
		   $status_id =  $model->status_id;
		//Копируем изображения во временную папку
		$dir_name = $id;
		if (!Yii::$app->request->post()) {
		$base = BlogImage::find()->where(['blog_id'=>$id])->select(['image'])->asArray()->all();
		$this->findFile($id, $base);
		}
		
		   $catid = $model->category;
			if (Yii::$app->request->post('Pjax_category')) {
				 $catid =   Yii::$app->request->post('Pjax_category');
			}
			if(isset(Yii::$app->request->post()['Blog']['category'])) {
				   $catid =  Yii::$app->request->post()['Blog']['category'];	
			}
	   
		foreach (Yii::$app->userFunctions->fieldarr($catid) as $field) {
			$model_view[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
			$mod[] = 'f_'.$field['id'];
			$max[] = array('id' => 'f_'.$field['id'],'max' => $field['max']);
			//Массив обязательных к заполнению
				if ($field['req'] == '1') {
					$required[] = 'f_481';
					$required[] = 'f_475';
				}
	       //Массив проверки телефонного номера
	       if ($field['type_string'] == 't' || $field['type_string'] == 'x') {
		     $phone[] = 'f_'.$field['id'];
           }
		  //Проверяем есть ли у категории, поле цена
		   if ($field['type'] == 'p') {
				$rate = true;
				$price[] = 'f_'.$field['id'];
				$price_r[] = 'f_'.$field['id'].'_rates';
				$integer[] = 'f_'.$field['id'];
				$integer[] = 'f_'.$field['id'].'_rates';
		   }
		 //Проверяем есть ли у категории, поле координаты
		   if ($field['type'] == 'j') {
			   $cordin[] = 'f_'.$field['id'].'_address';
		   }
		}
			if ($rate) {
			$rat = Rates::find()->all();	
	
			foreach ($rat as $res) {
	
			  $rates[] = array('id' =>  $res['id'], 'name' => $res['name'], 'value' => $res['value']);
			}	
			$mod = array_merge($price_r,$mod);
			}	
			
			if (isset($cordin)) {
			$mod = array_merge($mod,$cordin);	
			$string = array_merge($string,$cordin);			
			}		
			
	
			$model2 = new DynamicModel($mod);
			
			$model2 
			->addRule($required, 'required',['message'=>'Поле не может быть пустым'])
			//->addRule($required, function ($attribute) use ($model2, $price){ foreach($price as $res) {if($attribute == $res)if ($model2->attributes[$attribute] == 0) {$model2->addError($attribute,  'Поле не может быть 0.');}}})
			->addRule($string, 'string',['message'=>'Должны быть введены только строковые значения'])
			->addRule($integer, 'integer',['message'=>'Должны быть введены только целые числа'])
			//Проверка на телефон
			->addRule($phone, function ($attribute) use ($model2) {$pattern = "#^\+[0-9] {1,2}\s?\([0-9]{3}\)\s?[0-9]+\-[0-9]+\-[0-9]+$#"; if(preg_match($pattern, $model2->attributes[$attribute], $out)){}else{$model2->addError($attribute,  'Не верный формат номера');}})
			//Проверка на Ютуб
			->addRule($youtube, function ($attribute) use ($model2) {$res = 'https://www.youtube.com/watch?v=';if (strpos($model2->attributes[$attribute], $res) !== false) {$headers = get_headers($model2->attributes[$attribute]);if (!strpos($headers[0], '200')) { $model2->addError($attribute,  'Видео не существует');}}else{ $model2->addError($attribute,  'Не верный формат ссылки на ролик');}})
			//Проверка ограничения символов
			->addRule($mod, function ($attribute) use ($model2,$max) {
				if (is_array($model2->attributes[$attribute])) {foreach($model2->attributes[$attribute] as $res) {$strlen = strlen($res); foreach($max as $res) {if ($res['id'] == $attribute) {if ($strlen > $res['max']) {$model2->addError($attribute,  'Привышено допустимое количество символов ('.$res['max'].')');}}}}}
				if (!is_array($model2->attributes[$attribute])) {$strlen = strlen($model2->attributes[$attribute]);foreach($max as $res) {if ($res['id'] == $attribute) {if ($strlen > $res['max']) {$model2->addError($attribute,  'Привышено допустимое количество символов ('.$res['max'].')');}}} }})
			//Проверка адреса сайта
			->addRule($url, function ($attribute) use ($model2) {})
			->addRule($integer_с, function ($attribute) use ($model2) {if (is_array($model2->attributes[$attribute])) { foreach($model2->attributes[$attribute] as $res) {if(!is_numeric($res)){$model2->addError($attribute,  'Вы совершиили ошибку в заполнении данного поля.');}} }if (!is_array($model2->attributes[$attribute])) { if(!is_numeric($model2->attributes[$attribute])){ $model2->addError($attribute,  'Не верный формат URL (Адреса сайта)');}} })
			 ;

				 $model2_arr =  $this->findModel2($id);
								 
					foreach ($model2['attributes'] as $key =>$result) {
						if (!isset($model2_arr[$key]['value'])) {$model2_arr[$key]['value'] = '';}
					   $model2[$key] = $model2_arr[$key]['value'];
	
					   if (strpos($key, '_address') !== false){
						   $key_addr = str_replace('_address', '', $key);
						   if(isset($model2_arr[$key_addr]['dop'])) {
							 $model2[$key] = $model2_arr[$key_addr]['dop'];
						   }
						   $key_addr = '';
					   }
						  //Преобразуем цену из дефолтной в указанную пользователем
						  foreach($price as $res) {	 
							  if ($key == $res) {
								  if ($model2[$res]) {
									$model2[$key] = ($model2[$res] / Yii::$app->caches->rates()[$model2_arr[$key]['dop']]['value']);
									$model2[$key.'_rates'] = $model2_arr[$key]['dop'];
								  }
							  }
						  }
					}
				
			
			$array_post = Yii::$app->request->post();
			   if($model2->load($array_post) && $model2->validate() ){
					$model2_result = true;
			   }
	
			$model->url = Yii::$app->userFunctions->transliteration(Yii::$app->request->post('Blog')['title']);
			if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		
				 if (isset($model2_result)) {
					  $model->save();
				//------Обновление координаты----------------//
				$coord = $this->findCoord($model->id);
				if($coord) {
				  if($model->coordlat) {
				   $coord->blog_id = $model->id;
				   $coord->coordlat  = $model->coordlat;
				   $coord->coordlon  = $model->coordlon;
				   $coord->text  = $model->address;
				   $coord->update();
				  }
				}else{
				  if($model->coordlat) {
				   $coord = new BlogCoord();
				   $coord->blog_id = $model->id;
				   $coord->coordlat  = $model->coordlat;
				   $coord->coordlon  = $model->coordlon;
				   $coord->text  = $model->address;
				   $coord->save();
				  }
				}
	
		 BlogField::deleteAll('message = :message', [':message' => $model->id]);
		 
		 
		 
		 
	//Обрабатываем массив с данными и группируем валюту и адрес в поле dop
		 $post_save = array();
	
		 foreach($model2['attributes'] as $key => $res) {
			 if(isset($model2['attributes'][$key.'_rates'])) {
			   // $res = ($res * Yii::$app->caches->rates()[$model2['attributes'][$key.'_rates']]);
			   $res = ((int)$res * (int)Yii::$app->caches->rates()[$model2['attributes'][$key.'_rates']]['value']);
				$dop = $model2['attributes'][$key.'_rates'];
			 }
			  if(isset($model2['attributes'][$key.'_address'])) {
				$dop = $model2['attributes'][$key.'_address']; 
			 }
			 
			  if (strpos($key, '_rates') === false && strpos($key, '_address') === false) {
					 if ($res !== '') {
						  $post_save[] = array('id' => (int)str_replace('f_','',$key), 'value' => $res, 'dop' => $dop);
					 }
			 }
			  $dop = '';
		 }
	
	
		 foreach($post_save as $res) {
				   if (!is_array($res['value'])) {	
				   
				 
					   $customer = new BlogField();
					   $customer->message = $model->id;
					   $customer->field  = $res['id'];
					   $customer->value  = strval($res['value']);
					   $customer->dop  = $res['dop'];
					   $customer->save();
		
				   }else{
					   foreach($res['value'] as $result) {
					   $customer = new BlogField();
					   $customer->message = $model->id;
					   $customer->field  = $res['id'];
					   $customer->value  = strval($result);
					   $customer->dop  = $res['dop'];
					   $customer->save();	
						}
				   }
			 
				}
	
					 $dir_maxi = Yii::getAlias('@images_temp').'/board/'.$id.'/maxi/';
					 $dir_mini = Yii::getAlias('@images_temp').'/board/'.$id.'/mini/';
					 $dir_original = Yii::getAlias('@images_temp').'/board/'.$id.'/original/';
						$dir_copy_maxi = Yii::getAlias('@images').'/board/maxi/';
						$dir_copy_mini = Yii::getAlias('@images').'/board/mini/';
						$dir_copy_original = Yii::getAlias('@images').'/board/original/';
				//Функция перемещения изображений из временной папки в папку с фото
			$list = $this->filesDir($id);
			
			$base = BlogImage::find()->where(['blog_id'=>$id])->select(['image'])->asArray()->all();
			foreach($base as $res) {
					   @unlink($dir_copy_maxi.$res['image']);
					   @unlink($dir_copy_mini.$res['image']);
					   @unlink($dir_copy_original.$res['image']);
			}
			$mod = BlogImage::deleteAll('blog_id = :blog_id', [':blog_id' => $id]);
			if ($list) 
			{
			$name_rand = Yii::$app->getSecurity()->generateRandomString(6);
			   foreach($list as $key => $file) {	
				@rename($dir_maxi.$file, $dir_copy_maxi.$key.'_'.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$name_rand.'.png');
				@rename($dir_mini.$file, $dir_copy_mini.$key.'_'.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$name_rand.'.png');
				@rename($dir_original.$file, $dir_copy_original.$key.'_'.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$name_rand.'.png');
				
	
				//Сохраняем название файла в базу данных
					  $blogimage = new BlogImage();
					   $blogimage->id = '';
					   $blogimage->blog_id = $model->id;
					   $blogimage->image  = $key.'_'.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$name_rand.'.png';
					   $blogimage->save();
			   }	
			   
	
			}			
				if ($base) {
				foreach ($base as $res) {
					
					 @unlink($dir_copy_maxi.$res['image']);
					  @unlink($dir_copy_mini.$res['image']);
					   @unlink($dir_copy_original.$res['image']);
				}
				}
				
			$this->recursiveRemove(Yii::getAlias('@images_temp').'/board/'.$dir_name.'/');
			
		
			return $this->redirect(['view', 'id' => $model->id]);
			  }	
		   }
		   
		   
		
			
	
			//if ($model->dir_name) {$dir_name = $model->dir_name;}
	
			//$dir_name = $model->url;
			 return $this->render('updateexpress', [
				'model' => $model,
				'model2' => $model2,
				'model_view' => $model_view,
				'rates' => $rates,
				'dir_name' => $dir_name,
			]);
		}
	
	
	//Дествие со списком - ajax запрос
   public function actionAct()
   {	
  
     $arr = Yii::$app->request->post();

	 
      $arrays = $arr['check'];
      if ($arr) {
      if ($arr['check'] && $arr['act_id']) {
      if ($arr['act_id'] == 'm') {$arr['act_id'] = 0;}
	   if ($arr['act_id'] != 3 && $arr['act_id'] != 4) {
         foreach($arr['check'] as $id => $ids) {
				$blog_check =  Blog::findOne($id);
				$blog_check->status_id = $arr['act_id'];
				$blog_check->update(false); 
				$id_serv[] = $id;
		    }
			
			if ($arr['act_id'] == 2) {
			   	BlogServices::deleteAll(['blog_id' => $id_serv]);
	            Yii::$app->cache->delete('services'); Yii::$app->cacheFrontend->delete('services');
		   }
	   }
if ($arr['act_id'] == 3) {
	
 foreach($arr['check'] as $id => $ids) {
	BlogField::deleteAll('message = :message', [':message' => $id]);
	//Обновлеление координаты
	$coord = BlogCoord::deleteAll('blog_id = :blog_id', [':blog_id' => $id]);
    //$coord->delete();
	
		$delImage = BlogImage::find()->Where('blog_id=:blog_id',['blog_id'=> $id])->all();
		if ($delImage) {
        $dir_copy_maxi = Yii::getAlias('@images').'/board/maxi/'; $dir_copy_mini = Yii::getAlias('@images').'/board/mini/';$dir_copy_original = Yii::getAlias('@images').'/board/original/';	
		   foreach($delImage as $res) {
		      @unlink($dir_copy_maxi.$res['image']); 
			  @unlink($dir_copy_mini.$res['image']); 
			  @unlink($dir_copy_original.$res['image']);	
		   }
		   BlogImage::deleteAll('blog_id = :blog_id', [':blog_id' => $id]);

		}
        $this->findModel($id)->delete();
		$id_serv[] = $id;
         }
		 
		 
	BlogServices::deleteAll(['blog_id' => $id_serv]);
	Yii::$app->cache->delete('services'); Yii::$app->cacheFrontend->delete('services');
	      }  
		  
		  
		  
		  if ($arr['act_id'] == 4) {
			   foreach($arr['check'] as $id => $ids) {
				  $blog_check =  Blog::findOne($id);
				  $blog_check->status_id = 1;
				  $blog_check->active = 1;
				  $blog_check->date_del = date('Y-m-d H:i:s', strtotime(' +365 day'));
				  $blog_check->update(false); 
		       }
			   //Blog::updateAll( ['status_id' => 1, 'active'=>0, 'date_del'=> date('Y-m-d H:i:s', strtotime(' +30 day'))], ['id'=>$arr['check']]);
		  }

       //  return json_encode($arr['check']);
          }else {
              if (!$arr['check']) {return 'Нет выбранных строк';}
              if (!$arr['act_id']) {return 'Не указан статус';}
          }
       }




	 
	 	 foreach($arr['check'] as  $arrs) {   
         $array = explode(',',$arrs);

	 }
	 
	 return 'Готово';
	 
	
   }
	



    /**
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		 if (($model = Blog::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	

	
	//Обновление
	protected function findModel2($id)
    {
        if (($models = BlogField::find()->Where('message=:message',['message'=> $id])->asArray()->all()) !== null) {
            foreach($models as $res) {	
		     $model3['f_'.$res['field']][] = array('value' => $res['value'], 'dop' =>$res['dop']);
		    }
			if(isset($model3)) {
		foreach($model3 as $key => $res) {
	
	
			if(count($res) > 1) {
			  foreach($res as $resu) {
				 $model33[$key]['value'][] = $resu['value'];  
			  }
			}else{
				$model33[$key] = $res[0];
			}
		}
			
		return $model33;
		}
        }
     }



	 
//Создание папки для файлов при редактировании объявления и перемищения файлов во временные папки для дальнейшей работы с ними 
 protected function findFile($id, $base)
    {
		$url = $id;
		$this->delDir(Yii::getAlias('@images_temp').'/board/'.$url);
  if ($base) { 
	            @mkdir(Yii::getAlias('@images_temp').'/board/'.$url, 0755);
				@mkdir(Yii::getAlias('@images_temp').'/board/'.$url.'/mini/', 0755);
			    @mkdir(Yii::getAlias('@images_temp').'/board/'.$url.'/maxi/', 0755);
			    @mkdir(Yii::getAlias('@images_temp').'/board/'.$url.'/original/', 0755);
           foreach($base as $key => $res) {
	         @copy(Yii::getAlias('@images').'/board/maxi/'.$res['image'], Yii::getAlias('@images_temp').'/board/'.$url.'/maxi/'.$key.'_'.time().'.png');
			 @copy(Yii::getAlias('@images').'/board/mini/'.$res['image'], Yii::getAlias('@images_temp').'/board/'.$url.'/mini/'.$key.'_'.time().'.png');
			 @copy(Yii::getAlias('@images').'/board/original/'.$res['image'], Yii::getAlias('@images_temp').'/board/'.$url.'/original/'.$key.'_'.time().'.png');
           }

     }
	}

//Формируем массив с файлами в правильном порядке
 function filesDir($dir_name) {
	$dir = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
	$list = Blog::myscandir($dir);	 
if ($list) {	
   foreach($list as $res) {
     $str=strpos($res, "_");
     $row=substr($res, 0, $str);	
     $array[$row] = $res;
    }
   ksort($array);
    foreach($array as $res) {
       $rows[] = $res;
    }
    }	
	if (!isset($rows)) {$rows = '';}
	return $rows;
  } 
  
  
//Функция удаления всех временных файлов перед записью
function delDir($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            $this->delDir(realpath($path) . '/' . $file);
        }

        return @rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}





	
  function recursiveRemove($dir) {
    if ($objs = glob($dir."/*")) {
       foreach($objs as $obj) {
         is_dir($obj) ? $this->recursiveRemove($obj) : unlink($obj);
       }
    }
    @rmdir($dir);
  }
  
  
  
  
  

  
  
  
      /**
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
	
    {
		
		BlogField::deleteAll('message = :message', [':message' => $id]);
		BlogComment::deleteAll('blog_id = :shop_id', [':shop_id' => $id]);
		$delImage = BlogImage::find()->Where('blog_id=:blog_id',['blog_id'=> $id])->all();
		if ($delImage) {
        $dir_copy_maxi = Yii::getAlias('@images').'/board/maxi/'; $dir_copy_mini = Yii::getAlias('@images').'/board/mini/';$dir_copy_original = Yii::getAlias('@images').'/board/original/';	
		   foreach($delImage as $res) {
			  $imagess = BlogImage::find()->where(['image'=>$res['image']])->all();
			  if(count($imagess) > 1) {
				   }else{
		      @unlink($dir_copy_maxi.$res['image']); 
			  @unlink($dir_copy_mini.$res['image']); 
			  @unlink($dir_copy_original.$res['image']);
				   }			  
		   }
		   BlogImage::deleteAll('blog_id = :blog_id', [':blog_id' => $id]);
		}
		
	//пересчитываем объявления в рубриках
		 $data = $this->findModel($id);

		  //Удалаем объявление
		  $this->findModel($id)->delete();
       return $this->redirect(['index']);
    }
	
	
	
	
		
	//------Обновление координаты----------------//

	protected function findCoord($id)
    {
		if (($model = BlogCoord::find()->where(['blog_id' => $id])->one()) !== null) {
            return $model;
        }
    }

}