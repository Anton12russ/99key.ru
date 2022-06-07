<?php
namespace frontend\modules\shop\controllers;
use Yii;
use yii\web\Controller;
use common\models\Category;
use common\models\ArticleCat;
use yii\web\BadRequestHttpException;
use common\models\Region;
use common\models\Blog;
use common\models\Online;
use common\models\User;
use common\models\Message;
use common\models\Orders;
use common\models\ShopImages;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\base\DynamicModel;
use yii\data\Pagination;
use common\models\UserAlerts;
use common\models\Timer;
use yii\web\NotFoundHttpException;

//Добавляем для COPY
use common\models\BlogField;
use common\models\CatServices;
use common\models\BlogCoord;
use common\models\BlogImage;
use common\models\LoginForm;
class AjaxController extends Controller
{

	//Страница с подкатегорими
  public function actionCatParent($id)
    {
     	$id_region = Yii::$app->request->cookies['region'];
		if ($id_region) {
		   $region = Yii::$app->caches->region()[strval($id_region)]['url'].'/';
        }
	
		 
		 $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		 $cat = Category::findOne($id)['url'];
		 $cat_arr = Category::find()->where(['parent' => $id])->orderBy('sort')->all();
         $parent_arr = [];
		  if ($cat_arr) {
		$id_region = Yii::$app->request->cookies['region'];
		 foreach ($cat_arr as $parent) {
		   if($id_region) {
			   $count = Yii::$app->userFunctions->counterboard(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $parent['id']), Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), $id_region));
		   }else{
			   $count = Yii::$app->userFunctions->counterboard(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $parent['id']), '');
		   }
			if(!isset($region)) {$region = '';}
				  $parent_arr[] = array('id' => $parent['id'], 'name' => $parent['name'], 'url' => $patch_url.'/'.$region.$cat.'/'.$parent['url'], 'parent' => $parent['parent'], 'count'=>$count);	
			 }
		  }





     return $this->render('catparent', ['parents' => $parent_arr]); 
    }	
	
  //Страница с регионами
   public function actionRegParent($id)
    { 
		$array = Yii::$app->userFunctions->regionMainAjax($id);
		if (!isset(Yii::$app->caches->Region()[$id]['parent'])) {$cach_region = '';}else{$cach_region = Yii::$app->caches->Region()[$id]['parent'];}
		if ($cach_region) {
		  $ids = Yii::$app->caches->Region()[intval($id)]['parent'];	
		  $id_one = Yii::$app->caches->Region()[intval($id)]['url'];
		}else{
			  $ids = @Yii::$app->caches->Region()[Yii::$app->caches->Region()[intval($id)]['parent']]['parent'];
			      //Проверяем, конечный ли регион
				  foreach(Yii::$app->caches->Region() as $res) {
					  if ($res['parent'] == $id) {
						  $parent = $res['id'];
						  break;
					  }
				  }
				 if (!isset($parent)) {
				 $ids = @Yii::$app->caches->Region()[Yii::$app->caches->Region()[intval($id)]['parent']]['parent'];
			     $id_one = @Yii::$app->caches->Region()[Yii::$app->caches->Region()[intval($id)]['parent']]['url'];
			 }else{
				 $ids = @Yii::$app->caches->Region()[intval($id)]['parent'];
				 $id_one = @Yii::$app->caches->Region()[intval($id)]['url']; 
			 }
		}
		return $this->render('regparent', ['array' => $array, 'ids' => $ids, 'id_one' => $id_one]); 
     }
	 
	 
	public function actionRegionAll()
    { 
	    $cookies = Yii::$app->response->cookies;
	    $cookies->remove('region');
		$cookies->remove('region_url');
		@Yii::$app->response->redirect('/')->send();
	}
	
	
	
	
	public function actionMainfiltr($act=false, $url)
    { 
	   //Ставим куку региона
			Yii::$app->response->cookies->add(new \yii\web\Cookie([
                  'name' => 'filtr',
                  'value' => $act
            ]));
		@Yii::$app->response->redirect($url)->send();
	}
	
	public function actionNotepad($id)
    { 
	 $arr = unserialize(Yii::$app->request->cookies['note']);
	 if ($arr) {
	    $search = array_search($id, $arr);

        if ($search){
             unset($arr[$search]);
        }else{
	    	 array_push($arr, $id);
	    }
	 }else{
		 $arr[1] = $id;
	 }
		
	    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'note',
                        'value' =>  serialize($arr)
                        ]));	

       return implode(',',$arr);
	}
	
	
	
	public function actionMaps($coord)
    { 
	     return $this->render('maps', compact('coord'));
	}
	
	
	public function actionCatall() 
	{
     $get = Yii::$app->request->get();	
     if (isset($get['region'])) {
		 $sql = Region::find();
	 }else{
		 $sql = Category::find();
	 }
     
     $customers = $sql->where(['parent' => $get['idcategory']])
	->orderBy('sort')
    ->all();

    foreach($customers as $row) {
       if ($row["id"] !== intval($get['id'])) {
		   if(!isset($return)) {$return= '';}
          $return.="{value:'{$row["id"]}', caption:'{$row["name"]}'} ,";
       }
	}

    //Исключение запятой стоящей в конце строки
	if(!isset($return)) {$return= '';}
    $return=substr($return,0,(strlen($return)-1));
    $return="[{$return}]";
 
    return $return;

    }
	
	
	
    public function actionExitcat()
   {
    $get = Yii::$app->request->get();	
	
	if ($get['act'] == 'reg') {
		 $arr = Yii::$app->caches->region();
		  $sel_cat = 'sel_reg';
	}else{
		 $arr = Yii::$app->caches->category();
		 $sel_cat = 'sel_cat';
	}
   	

    function linenav($cat, $cats_id, $sel_cat, $first = true)
       {
        static $array = array();
    $value = $cat[$cats_id];
	 
    if($value['parent'] != 0 && $value['parent'] != "")
       {
        linenav($cat, $value['parent'], $sel_cat, false);
       }
   $array[] = array('name' => $value['name'], 'id' => $value['id'], 'parent' => $value['parent']);
    foreach($array as $k=>$v)
        {
		$next = $v['id'];
		if(!isset($return)) {$return = '';}
		$return .= '<select class="form-control '.$sel_cat.'">';
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
	
   if(isset($get['id_reg'])) {
     unset($arr[$get['id_reg']]);
   }
      return linenav($arr, $get['idcategory'], $sel_cat);
	}
	
	
	
	
	
	//Определение региона
	public function actionRegopen()
    { 
	 $get = Yii::$app->request->get();
	 $region = $get['city'];
	 foreach(Yii::$app->caches->region() as $res) {
		 if ($res['name'] == $region) {
			 $reg_id = $res['url'];
		 }
		 }
	 
	 if ($reg_id) {
	  
	  $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
	  if(!isset($return)) {$return = '';}
	  $return .= '<div class="modal-region-mini">Ваше местоположение<br> '.$get['city'].'? </div>';	 
	  $return .= '<div class="footer-region-mini"><a href="'.$patch_url.'/'.$reg_id.'" class="btn  mini-reg-go region-redirect-mini"> Да </a> <button class="btn mini-close" data-dismiss="modal">Нет</button><div><hr><div class="modal-region-mini" style="padding: 10px 0 0 0;"><i class="fa fa-brands fa-google-play fa-2x" aria-hidden="true"></i> Установить приложение? </div><a class="btn  mini-reg-go region-redirect-mini" href="https://play.google.com/store/apps/details?id=com.prog.onetu" target="_blank"> Перейти в Google Play </a></div>';
     }else{
		$return .= '<div class="modal-region-mini"><div class="alert alert-warning">Мы не смогли автоматически определить ваш город, Вы можете выбрать местополоение самостоятельно.</div></div>';
		$return .= '<div class="footer-region-mini"><button class="btn mini-reg-go" data-dismiss="modal">Продолжить</button><div><hr><div class="modal-region-mini" style="padding: 10px 0 0 0;"><i class="fa fa-brands fa-google-play fa-2x" aria-hidden="true"></i> Установить приложение? </div><a class="btn  mini-reg-go region-redirect-mini" href="https://play.google.com/store/apps/details?id=com.prog.onetu" target="_blank"> Перейти в Google Play </a></div>';
	 }
	 return $return;
	 }
	

  	public function actionCatallart()
    {
    $arr = Yii::$app->request->get();	

    $customers = ArticleCat::find()
    ->where(['parent' => $arr['idcategory']])
	->orderBy('sort')
    ->all();

    foreach($customers as $row) {
     if ($row["id"] !== intval($arr['idcategory'])) {
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
	
	
	
	
	
		public function actionSaveRedactorImg($sub)
    {
        $this->enableCsrfValidation = false;
        if (Yii::$app->request->isPost) {
            $dir = Yii::getAlias('@images_all').'/'.$sub.'/';
        if (!file_exists($dir)) {
                FileHelper::createDirectory($dir);
            }
        // $result_link = Url::home(true) . 'uploads/images/' . $sub . '/';

            $result_link = Url::home(true).'images_all/'.$sub.'/';
            $file = UploadedFile::getInstanceByName('file');
            $model = new DynamicModel(compact('file'));
            $model->addRule('file', 'image')->validate();

            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            } else {
				//Имя файла
                $model->file->name = strtotime('now').'_'.Yii::$app->getSecurity()->generateRandomString(6) . '.' . 
				$model->file->extension;
                if ($model->file->saveAs($dir . $model->file->name)) {
					
                $imag = Yii::$app->image->load($dir . $model->file->name);
                $imag -> resize (800, NULL, Yii\image\drivers\Image::PRECISE)
                ->save($dir . $model->file->name, 85); 
                    $result = ['filelink' => $result_link . $model->file->name,'filename' => $model->file->name];
                } else {
                    $result = [
                        'error' => Yii::t('vova07/imperavi', 'ERROR_CAN_NOT_UPLOAD_FILE')
                    ];
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
           
		   return $result;
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }
	
	
	public function actionImgDel($file, $act)
    {
		
        if($act == 'article') {
	         $dir = Yii::getAlias('@images_all').'/'.$act.'/'.pathinfo($file , PATHINFO_BASENAME );
        }

        @unlink($dir);
		return 'Изображение удалено с сервера';	
	}
	
	
	
	public function actionOnline()
    {
         if(!Yii::$app->user->id) {throw new NotFoundHttpException('The requested page does not exist.');}
		 Online::deleteAll(['<','date', date('Y-m-d H:i:s', strtotime('-3 minutes'))]);
		 Online::deleteAll(['user_id' => Yii::$app->user->id]);
		 $model = New Online();
         $model->user_id = Yii::$app->user->id;
		 $model->date = date('Y-m-d H:i:s');
		 $model->save(false);
		 $user = User::findOne(Yii::$app->user->id);
		 $user->online = date('Y-m-d H:i:s');
		 $user->save(false);
		 
		$query = Message::find()->Where(['u_too'=>Yii::$app->user->id, 'flag' => 1]);
	
		

		
	//Для чата

		
		$arr['chat']['count'] = $query->count();
		return json_encode($arr);
	}
	
	
	
		
	public function actionCatandboard($patch, $category)
    { 
		$query = Category::find()->where(['parent' => $category])->orderBy('sort');
	    $pages_cat = new Pagination(['totalCount' => $query->count(), 'pageSize' => 15]);
		
	    $cat_arr = $query->offset($pages_cat->offset)->limit($pages_cat->limit)->all();
		$counter = $pages_cat->offset;
		$id_region = Yii::$app->request->cookies['region'];
		$region = $id_region;
		foreach($cat_arr as $res) {
           if($id_region) {
			   $count = Yii::$app->userFunctions->counterboard(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id']), Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), $id_region));
		   }else{
			   $count = Yii::$app->userFunctions->counterboard(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id']), '');
		   }
		    $patch = strtok($patch, '?');
			$array[$res['id']] = array('id' => $res['id'], 'name' => $res['name'], 'url' =>  '/'.$patch.'/'.$res['url'], 'image' => $res['image'], 'count'=>$count, $res['id']);
		}
		$cat_menu = $array;
		$this->layout = 'style_none';
	    return $this->render('endcategory', compact('cat_menu', 'pages_cat', 'counter', 'patch'));
	}
	
	
	
	public function actionCatandshop($patch, $category)
    { 
		$query = Category::find()->where(['parent' => $category])->orderBy('sort');
	    $pages_cat = new Pagination(['totalCount' => $query->count(), 'pageSize' => 15]);
		
	    $cat_arr = $query->offset($pages_cat->offset)->limit($pages_cat->limit)->all();
		$counter = $pages_cat->offset;
		$id_region = Yii::$app->request->cookies['region'];
		$region = $id_region;
	
	
		foreach($cat_arr as $res) {
			 $predoc_cat = str_replace('|','',explode(' | ',Yii::$app->caches->catRelative()[$res['id']]));
           if($id_region) {

			   $count = Yii::$app->userFunctions->countershop(array_merge($predoc_cat,Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id'])), $reg_all);
		   }else{
			  
			   $count = Yii::$app->userFunctions->countershop(array_merge($predoc_cat,Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id'])), '');
		   }
			$array[$res['id']] = array('id' => $res['id'], 'name' => $res['name'], 'url' =>  '/'.$patch.'/'.$res['url'], 'image' => $res['image'], 'count'=>$count, $res['id']);
		}
		if(isset($array)) {
		    $cat_menu = $array;
		}else{
			$cat_menu = '';
		}
	
	
	
		$this->layout = 'style_none';
	    return $this->render('endcategory', compact('cat_menu', 'pages_cat', 'counter', 'patch'));
	}	
	
	public function actionCatSearch($id)
    { 
	if(Yii::$app->request->get('text')){
		$rext = '?text='.Yii::$app->request->get('text');
	}else{
		$rext = '';
	}
		@Yii::$app->response->redirect('/'.Yii::$app->userFunctions->recursiveUrl($id).$rext, 301)->send();
	}
	
	
	
	public function actionCatSearchart($id)
    { 
	if(Yii::$app->request->get('text')){
		$rext = '?text='.Yii::$app->request->get('text');
		
	}else{
		$rext = '';
	}
       @Yii::$app->response->redirect('/article/'.Yii::$app->userFunctions->recursiveUrlart($id).$rext, 301)->send();
	}
		
		
		
		
		
	public function actionCatSearchshop($id)
    { 
	if(Yii::$app->request->get('text')){
		$rext = '?text='.Yii::$app->request->get('text');
		
	}else{
		$rext = '';
	}
       @Yii::$app->response->redirect('/shop/'.Yii::$app->userFunctions->recursiveUrl($id).$rext, 301)->send();
	}
	
	
	public function actionTranslite()
    { 
	   $domenss = getenv("HTTP_HOST");	
       $domens = $this->ExtractDomain($domenss);
       setcookie ("googtrans", "", time()-60, "/", $domens);
       setcookie ("googtrans", "", time()-60, "/", ""); 
	   @Yii::$app->response->redirect('/')->send();
	}
	
	



	public function actionRegsearch($text)
    { 
      $result = Region::find()->andFilterWhere(['like', 'name', $text])->asArray()->Limit('10')->all();
	  return $this->render('regsearch', compact('result'));
	}


	public function actionCount($id, $count)
    { 
	$model = $this->findBlog($id);
	$model->count = $count;
	$model->update(false);
	$this->findOrder($model);
	return $count;
	}


	public function actionSliderSort($array)
    { 
	    $idarr = json_decode($array);
		
        //$model = ShopImages::find()->where(['id' => $idarr])->all();
	    foreach($idarr as $key => $res) {
		    $model = ShopImages::find()->where(['id' => $res, 'user_id' => Yii::$app->user->id])->one();
			$model->sort = $key;
			$model->update(false);
	    }
	}


	public function actionSliderUrl($id, $url)
    { 
	  $arrurl = parse_url($url);
	  if(!isset($arrurl['scheme'])) {
		  return true;
	  }
	  if($arrurl['scheme'] != 'https') {
		  return true;
	  }
	  $model = ShopImages::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one();
	  $model->url = $url;
	  $model->update();
	  return '';
	}


    protected function findBlog($id)
    {
        if (($model = Blog::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one()) !== null) {
            return $model;
        }

        return false;
    }








	
	
	
	protected function ExtractDomain($Host, $Level = 2, $IgnoreWWW = false) {
      $Parts = explode(".", $Host);
      if($IgnoreWWW and $Parts[0] == 'www') unset($Parts[0]);
      $Parts = array_slice($Parts, -$Level);
      return implode(".", $Parts);
    }	
	
	
	
			//Оповещение о предзаказе
 protected function findOrder($blog)
    {
		$link = Url::to(['/boardone', 'id'=>$blog->id]);
		$orders = Orders::find()->where(['board_id' => $blog->id])->andWhere(['<=','colvo', $blog->count])->all();
		foreach($orders as $order) {
			Yii::$app->functionMail->order_send($order, $blog->title, $blog->count, $link, $blog->shop['domen']);
			$arrid[] = $order->id;
		}
		if(isset($arrid)) {
		   //Orders::deleteAll(['id' => $arrid]);
		}
	}
	
	
		//---------------------------Обновление координаты-------------------------------//
		public function actionCoordCounter()
    { 
	  	$get = Yii::$app->request->get();


     if(isset($get['category']) && $get['category'] > 0) {

	   $cat_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), (int)$get['category']);
       $params = ['status_id'=>1, 'blog.active'=>1, 'category'=>$cat_all];
	 }else{
		$params = ['status_id'=>1, 'blog.active'=>1];
		
	 }

	  $top = implode(', ',Yii::$app->userFunctions->services_type('top'));
	  if (!$top) {
	     $top = 0;
	  }
	  
	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $price = $res['id'];
	   }
	}  
	  

/*------------------------------------------------------------------------------*/
	  //Поиск
	    $price_arr = array();
	    $key_arr = array();
	  	$price_on_arr = array();
		$price_end_arr = array();
		$price_rt_arr = array();
        $multi_arr = array();
        $sort = array();
		$diapazon_end = array();
		$diapazon_on = array();
		
		


	 foreach($get as $key => $res ) {
		
	  if ($res > 0 || $res == '0' || isset($res)) {
       if (strpos($key, 'f_') !== false) {
		  
		 $keys = str_replace('f_','',$key);
		 //Ищем чекбоксы, тексты и селекты
		 preg_match("/^([^_multi]+)_/",$keys , $result);
         if (isset($result[1])) {
			 $multi_arr[$result[1]][] = array('id' => $result[1], 'value' => $res);
			 $search[] = $key;
		 }else{
			 $key_arr[] = array('id' => $keys, 'value' => $res);
			 $search[] = $key;
		 }
	   }
	   
	   	 //Ищем цены
          if (strpos($key, 'price_on') !== false) {
			   $key_on = str_replace('price_on_','',$key);
			   $price_on_arr[$key_on] = array('id' => $key_on, 'val' => $res);
			   $search[] = $key;
		  }
          
		
          if (strpos($key, 'price_end') !== false) {
			   $key_end = str_replace('price_end_','',$key);
			   $price_end_arr[$key_end] = array('id' => $key_end, 'val' => $res);
			   $search[] = $key;
		 }
		 
		 
	
          if (strpos($key, 'price_rt') !== false) {
			   $key_rt = str_replace('price_rt_','',$key);
			   $price_rt_arr[$key_rt] = array('id' => $key_rt, 'val' => $res);
			   $search[] = $key;
		   }
		 
	
		 
		 
  	      //Ищем Диапазоны
        if (strpos($key, 'diapazon_on') !== false) {
			   $key_d_on = str_replace('diapazon_on_','',$key);
			   $diapazon_on[$key_d_on] = array('id' => $key_d_on, 'value' => $res);
			   $search[] = $key;
		  }
		  
		  
		  if (strpos($key, 'diapazon_end') !== false) {
			   $key_d_end = str_replace('diapazon_end_','',$key);
			   $diapazon_end[$key_d_end] = array('id' => $key_d_end, 'value' => $res);
			   $search[] = $key;
		  }
		  
	   }
	 }		
	 if (isset($get['photo'])) {
	    $search[] = true;
	}
  $diapazon_arr = array_merge($diapazon_on, $diapazon_end); 
  $diapazon = array(); 
  foreach ($diapazon_arr as $res ) {
	  if (isset($diapazon_on[$res['id']])) {
		  $on = $diapazon_on[$res['id']]['value'];
	  }else{
		  $on = '0';
	  }
	  
	 if (isset($diapazon_end[$res['id']])) {
		  $off = $diapazon_end[$res['id']]['value'];
	  }else{
		  $off = '100000000';
	  }
	  

	 if (Yii::$app->caches->field()[$res['id']]['type'] != 'v') {
		 if ($on >= 1) { $on = $on-1;}
		 $off = $off-1;
	 }
	
	  $diapazon[$res['id']] = array('id'=> $res['id'],'on'=>$on,'off'=>$off,);
  }
  

//Массив с ценой	 
/*$pr_arr = array();
foreach($price_rt_arr as $res) {
	if ($price_on_arr[$res['id']]['val'] || $price_end_arr[$res['id']]['val']) {
	if ($price_end_arr[$res['id']]['val']) {
       $off = $price_end_arr[$res['id']]['val'];
	}else{
	   $off = '10000000000';
	}
		
	if ($price_on_arr[$res['id']]['val']) {
       $on = $price_on_arr[$res['id']]['val'];
	}else{
	   $on = 0;
	}	
			
	$pr_arr[] = array('id'=> $res['id'],'on' => $on, 'off' =>  $off, 'rates' => $price_rt_arr[$res['id']]['val']);
	}
}*/

  $price_arr = array_merge($price_on_arr, $price_end_arr); 
  
  $price_arr = array_merge($price_arr, $price_rt_arr);
  $pr_arr = array();
  foreach ($price_arr as $res) {
	  if (isset($price_on_arr[$res['id']])) {
		  $on = $price_on_arr[$res['id']]['val'];
	  }else{
		  $on = '0';
	  }
	 if (isset($price_end_arr[$res['id']])) {
		  $off = $price_end_arr[$res['id']]['val'];
	  }else{
		  $off = '100000000';
	  }
	  if (!isset($price_rt_arr[$res['id']]['val'])) {$price_rt_arr[$res['id']]['val'] = '';}
	  $pr_arr[$res['id']] = array('id'=> $res['id'],'on'=>$on,'off'=>$off, 'rates' => $price_rt_arr[$res['id']]['val']);
  }




	  
	$sql = Blog::find()->with('blogField')->with('imageBlog')->with('regions')->with('categorys');  
	  //---------------Обноаление координаты------------------//	
    if(isset($get['text'])) {
      $sql->andFilterWhere(['like', 'title', $get['text']]);
      $sql->LeftJoin('blog_coord coord','coord.blog_id = blog.id');
	  $sql->OrFilterWhere(['like', 'coord.text', $get['text']]);
	} 
    $query = $sql;

    if($get['coord'] > 0) {

	  $coord = explode(',',$get['coord']);
	  $sql->LeftJoin('blog_coord coords','coords.blog_id = blog.id')
	    ->where(['<','6371 * acos (
        cos ( radians('.$coord[0].') )
        * cos( radians( coords.coordlat ) )
        * cos( radians( coords.coordlon ) - radians('.$coord[1].') )
        + sin ( radians('.$coord[0].') )
        * sin( radians( coords.coordlat ) ))', $get['radius']]);
	 }

$query->andWhere($params);
if (isset($search)) {	    
//Ищем мультивыбор
if ($multi_arr) {
 foreach($multi_arr as $key => $res) {
      $id = 'bd_'.$key;
	        $sql->LeftJoin('blog_field  '.$id.'', ''.$id.'.message = blog.id')
	       ->andWhere($id.'.field = '.(int)$key.'');

		   $param = array();
		   foreach($res as $resu) {
			    $param[] = '('.$id.'.value = '.(int)$resu['value'].')';
		   }

		 $param = implode(' or ',$param);

		 $sql->andWhere($param);
			
	  }
}


  if ($key_arr) {
	 foreach($key_arr as $key => $res) {
			$id = 'bd'.$res['id'];
	        $sql->LeftJoin('blog_field  '.$id.'', $id.'.message = blog.id')
	       ->andWhere($id.'.field = '.(int)$res['id'].'')
		   ->andWhere($id.'.value = "'.$res['value'].'"')
			;
	  }
  }
  

       if ($diapazon) {
	  	 foreach($diapazon as $key => $res) {
            $sql->LeftJoin('blog_field bd'.$res['id'].'', 'bd'.$res['id'].'.message = blog.id')
	        ->andWhere('bd'.$res['id'].'.field+0 = '.(int)$res['id'].'')
			->andWhere(' bd'.$res['id'].'.value+0 BETWEEN '.$res['on'].' AND '.$res['off'].'');
	       }
       }
	   
	   if (isset($get['photo'])) {
	        $sql->innerJoin('blog_image', 'blog_image.blog_id = blog.id');
	   }

  if ($pr_arr) {
      foreach($pr_arr as $key => $res) {

		  	$rat = @Yii::$app->caches->rates()[$res['rates']]['value']; 
			if ($rat) {
			    $sql->LeftJoin('blog_field bd'.$res['id'].'', 'bd'.$res['id'].'.message = blog.id')
	            ->andWhere('bd'.$res['id'].'.field+0 = '.(int)$res['id'].'')
			    ->andWhere(' bd'.$res['id'].'.value+0 BETWEEN '.(int)str_replace(' ','',$res['on']*$rat).' AND '.(int)str_replace(' ','',$res['off']*$rat).'')
		    	->andWhere('bd'.$res['id'].'.dop = '.(int)$res['rates'].'');
			}else{
			    $sql->LeftJoin('blog_field bd'.$res['id'].'', 'bd'.$res['id'].'.message = blog.id')
	            ->andWhere('bd'.$res['id'].'.field+0 = '.(int)$res['id'].'')
			    ->andWhere(' bd'.$res['id'].'.value+0 BETWEEN '.(int)str_replace(' ','',$res['on']).' AND '.(int)str_replace(' ','',$res['off']).'');
	
			}
	  }
}

  $sql->andWhere($params);


}
/*------------------------------------------------------------------------------------------*/ 
 


	$query = $sql->asArray()->all(); 


	return count($query);

	  
}
	
	public function actionCoordadd($region)
    { 
	 $get = Yii::$app->request->get();
	 $region = str_replace(', ',',',$region);
	 $arrs = array_reverse(explode(',',str_replace('Республика ','',$region)));
	 foreach($arrs as $arr) {
	   foreach(Yii::$app->caches->region() as $res) {	   
		  if ($res['name'] == trim($arr)) {
		  	  return $res['id'];
		   }
		 }
	  }
	 }
	
		public function actionMapall()
    { 
	   $this->layout = 'style_none';
	   return $this->render('map_all');
	 }
	
	
	
	
		public function actionHtml()
    { 
	    return $this->render('html');
	}
	
	
	
	
	
	
	
	
	
	
	public function actionTimer($id)
    { 
	     if (($model = Timer::findOne($id)) !== null) {




	  /*------------------Для скрытия-------------------------*/
	  
	 /* $new_date = date_create(date("Y-m-d H:i:s"));
      date_format($new_date, 'U'); // Format UNIX 1566382042
      $second_date = (date_timestamp_get($new_date))*1000;
		  $cod = json_decode($model->cod)->code; 

		 if (strpos($cod, 'weekdays') !== false) {
	
			    preg_match_all('#"time":"(.+?)",#is', $cod, $arr);
				
			    if(isset($arr[1][0])) {
					
					//Ищем часы
					 preg_match_all('#"hours":"(.+?)"#is', $cod, $hours);
					 //Ищем минуты
				     preg_match_all('#minutes":"(.+?)"#is', $cod, $min);
					 $date_new = $hours[1][0].':'.$min[1][0];
					
					 $date_new = date('H:i', strtotime("+". $hours[1][0]." hours", strtotime($arr[1][0])));
					// $date_new = date("H:i", strtotime("+ ". $min[1][0]." minute", strtotime($date_new)));
					 $date_new = date("H:i", strtotime("+ ". $min[1][0]." minute", strtotime($date_new)));

				if(date('H:i') > $arr[1][0] && $date_new > date("H:i")) {
			  
		        }else{
					return 'Del';
				}
			  }
  
		 }else{

			 preg_match_all('#"utc":(.+?)}#is', $cod, $end);
			 
			
			 if(isset($end[1][0])) {
			
			
				
			      $date = date("Y-m-d H:i:s", time());
				   if($end[1][0] < $second_date) { 
				      return 'Del';
				   }
			 }
		 }
		 
		
		/*-------------------------------------------*/








		  $this->layout = 'style_none';
		  Yii::$app->getModule('debug')->instance->allowedIPs = [];
		  return $this->render('timer', compact('model'));
		  
		  
           }else{
			   return '';
		   }
	}
	
	
	
	
	
	
	
	
	
	
	public function actionTimergenerator()
    { 
	 $this->layout = 'style_none';
           return $this->render('generator_timer');
	}	
	
	
	
	public function actionTimeruser($id, $kod)
    { 

		$kod=urldecode($kod);
		  $this->layout = 'style_none';
		  Yii::$app->getModule('debug')->instance->allowedIPs = [];
		  return $this->render('timer_user', compact('kod', 'id'));
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
public function actionCopy($id)
 {
	 
	  if (Yii::$app->user->isGuest) {
		 return $this->redirect(['/user']);
	  }
	  $model = Blog::findOne($id);
	  $coord = BlogCoord::find()->Where(['blog_id' => $id])->One();
	  $images = BlogImage::find()->Where(['blog_id' => $id])->all();
	  //Проверка
	  if(!$model) {
		  return $this->redirect(['/add']);
	  }
	  if($model->user_id != Yii::$app->user->id) {
		  return $this->redirect(['/add']);
	  }
	  
	  $date_add = strtotime($model->date_add);
	  $date_del = strtotime($model->date_del);
	  $date_del = $date_del-$date_add;
	  $date_del = time()+$date_del;
	  $date_del = $date = date("Y-m-d H:i:s", $date_del);

	  $model_field = BlogField::find()->Where(['message' => $id])->all();
      
	  
	  $model_copy = new Blog();
      $model_copy->attributes = $model->attributes;
      $model_copy->isNewRecord = true;
      $model_copy->id = null;
	  $model_copy->status_id = 2;
       
		
	  $model->date_add =  date('Y-m-d H:i:s');
	  $model->date_del =  $date_del;
	  
	 $price_category = $this->findModprice($model->category, $model->region);
		if ($price_category) {
			 $price_datedel = $model->date_del;
			  $model_copy->active = 0;
		}else{
              $model_copy->active = 1; 	 
		}
	  
	    $model_copy->save();
	  
	  $url = $id;
	 //Сохраняем допполя
	  foreach($images as $key => $image) {
		  	 @copy(Yii::getAlias('@img').'/board/maxi/'.$image->image, Yii::getAlias('@img').'/board/maxi/'.$model_copy->id.'_'.$key.'_'.time().'.jpg');
			 @copy(Yii::getAlias('@img').'/board/mini/'.$image->image, Yii::getAlias('@img').'/board/mini/'.$model_copy->id.'_'.$key.'_'.time().'.jpg');
			 @copy(Yii::getAlias('@img').'/board/original/'.$image->image, Yii::getAlias('@img').'/board/original/'.$model_copy->id.'_'.$key.'_'.time().'.jpg');
		  
		  
		 $img_save = new BlogImage();
		 $img_save->blog_id = $model_copy->id;
		 $img_save->image = $model_copy->id.'_'.$key.'_'.time().'.jpg';
		 $img_save->save();
	   }
	   
	  //Сохраняем допполя
	   foreach($model_field as $field) {
		 $field_save = new BlogField();
		 $field_save->message = $model_copy->id;
		 $field_save->field = $field->field;
		 $field_save->value = $field->value;
		 $field_save->dop = $field->dop;
		 $field_save->save();
	   }
	   
	   
	   	   //Сохраняем Координаты
	   $coord_new = new BlogCoord();
	   $coord_new->blog_id = $model_copy->id;
	   $coord_new->coordlat = $coord->coordlat;
	   $coord_new->coordlon = $coord->coordlon;
	   $coord_new->text = $coord->text;
	   $coord_new->save();
	   
	   return $this->redirect(['/update', 'id' => $model_copy->id]);
   
  }






	protected function findModprice($cat, $reg)
    {
		$reg = Yii::$app->userFunctions->catparent($reg, 'reg');
		$cat = Yii::$app->userFunctions->catparent($cat, 'cat');
		
		$return = CatServices::find()->Where(['cat' => $cat])->asArray()->all();
		foreach ($return as $res) {
           if (in_array($res['reg'], $reg)) {
			   $ret = $res['price'];
		   }
		}		
        if (!isset($ret)) {$ret = '';}			
	    return $ret;
    }  
	
	
	
		public function actionLoginpop()
    { 
	
	 $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
			//Кука для безопасности при смене пароля
			 Yii::$app->response->cookies->add(new \yii\web\Cookie([
                 'name' => 'auth_key',
				 'domain' => '.'.DOMAIN,
                 'value' => Yii::$app->user->identity->auth_key
              ]));
            return Yii::$app->response->redirect(['/user']);
        }else{
            $model->password = '';
            $this->layout = 'style_none';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
	
	}
	
			
}
