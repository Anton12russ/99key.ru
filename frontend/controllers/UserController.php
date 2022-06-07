<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use common\models\UserAlerts;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\User;

use frontend\models\Article;
use common\models\PaymentSystem;
use common\models\Payment;

use common\models\BlogAuction;
use common\models\BlogAuctionSearch;
use common\models\Bet;
use common\models\BetSearch;
use common\models\Blog;
use common\models\BlogSearch;
use common\models\BlogTime;
use common\models\Shop;
use common\models\Car;
use common\models\Rates;
use common\models\Message;
use common\models\MessageRoute;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use common\models\PaymentSearch;
use common\models\ShopImages;
use common\models\UploadForm;
use yii\web\UploadedFile;
use common\models\CatServices;
use common\models\BlogField;
use common\models\BlogImage;


/**
 * Site controller
 */
class UserController extends Controller
{
	
	public function beforeAction($action)
    {
        // Если $action->id в массиве ['yandex'], yandex - это пример
        if (in_array($action->id, ['social'])) {
            // отключаем защиту от CSRF
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
			
		
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
			'messenger' => [
                     'class' => 'frontend\actions\UserMessenger',
            ],
			'route' => [
                     'class' => 'frontend\actions\UserRouteMessenger',
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
			
			'car' => [
                'class' => 'frontend\actions\UserCar',
            ],
			'product' => [
                'class' => 'frontend\actions\UserProduct',
            ],
			'dispute' => [
                'class' => 'frontend\actions\UserDispute',
            ],
			
			'disputeshop' => [
                'class' => 'frontend\actions\UserDisputeshop',
            ],
			
			'disputshopone' => [
                'class' => 'frontend\actions\UserDisputeshopone',
            ],
			
			'support' => [
                'class' => 'frontend\actions\UserSupport',
            ],
			
			'supportadd' => [
                'class' => 'frontend\actions\UserSupportadd',
            ],
			'supportone' => [
                'class' => 'frontend\actions\UserSupportone',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
	 
	 
	 
	 
    public function actionIndex()
    {
      if (!Yii::$app->user->isGuest) {
		  //Форма изменения личных данных
		$model = $this->findModel(Yii::$app->user->id);
				
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if ($model->password) {
				 Yii::$app->user->logout();
                 return Yii::$app->response->redirect(['user']);
			}else{
			    return $this->render('personal', [
                   'model' => $model, 'save' => 'true'
               ]);
			}

		}
             return $this->render('personal', [
                'model' => $model,
            ]);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
			//Кука для безопасности при смене пароля
			 Yii::$app->response->cookies->add(new \yii\web\Cookie([
                 'name' => 'auth_key',
				 'domain' => '.'.DOMAIN,
                 'value' => Yii::$app->user->identity->auth_key
              ]));
            return Yii::$app->response->redirect(['user']);
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
	
	
	
	
	
	//---------------------БАЛАНС------------------------//

    public function actionBalance()
    {         
	if (Yii::$app->user->isGuest) {
			  return $this->redirect(['/user']);
		 }
	   $payment = $this->findPayment();
		
       return $this->render('balance', [
                'payment' => $payment,
       ]);
    }
	
	
	
	//---------------------История платежей------------------------//

    public function actionHistory()
    {
      $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id);
        return $this->render('history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
		//---------------------Продление магазина------------------------//

    public function actionExtendShop()
    {
	    $model = Shop::find()->where(['user_id' => Yii::$app->user->id])->one();

	    if (!$model) {throw new NotFoundHttpException('The requested page does not exist.');}
		if (!date('Y-m-d H:i:s', strtotime(' - 10 day')) > $model->date_end) {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
				
				
		if ((date('Y-m-d H:i:s', strtotime(' - 10 day')) > $model->date_end) && $model->date_end < date('Y-m-d H:i:s')) {
		    $data_end =  date('Y-m-d', strtotime($model->date_end. ' + '.Yii::$app->caches->setting()['end-shop'].' day'));
		}else{
			$data_end = date('Y-m-d', strtotime(' - '.Yii::$app->caches->setting()['end-shop'].' day'));	
		}
		
		$rates = Rates::find()->where(['def' => 1, 'value' => 1])->asArray()->one();
		return $this->render('extendshop', [
		   'data_end' => $data_end,
           'model' => $model,
		   'rates' => $rates,
        ]);   
    }
	//---------------------Продление магазина------------------------//

    public function actionExtendShopAct($shop_id, $date_end)
    {
		
		if(Yii::$app->user->identity->balance >=  Yii::$app->caches->setting()['price-shop']) { 
		$rates = Rates::find()->where(['def' => 1, 'value' => 1])->asArray()->one();
		  $pay = new Payment();
          $pay->price = -Yii::$app->caches->setting()['price-shop'];
		  $pay->currency  = $rates['charcode'];
		  $pay->user_id  = Yii::$app->user->id;
		  $pay->system  = 'Личный счет';
		  $pay->blog_id  = '';
		  $pay->services  = 'shop';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false); 
		  
		 //Вычитаем стоимость с баланса у юзера
		  $user = User::findOne(Yii::$app->user->id);
		  $user->balance = $user->balance-Yii::$app->caches->setting()['price-shop'];
		  $user->save(false); 
		  
		if (($date_end >= date('Y-m-d H:i:s', strtotime(' - 10 day'))) && $date_end <= date('Y-m-d H:i:s')  && !($date_end <= date('Y-m-d H:i:s', strtotime(' - 11 day')))) {
		    $data_end_y =  date('Y-m-d', strtotime($date_end. ' + '.Yii::$app->caches->setting()['end-shop'].' day'));
		}else{
			$data_end_y = date('Y-m-d', strtotime(' + '.Yii::$app->caches->setting()['end-shop'].' day'));	
		}
		
		   //Вычитаем стоимость с баланса у юзера
		  $shop = Shop::findOne($shop_id);
		  $shop->active = 1;
		  $shop->date_end = $data_end_y;
		  $shop->save(false);
		  
		  
		return '<div class="alert alert-info">Активация магазина продлена.</div>';
		}
		return '<div class="alert alert-danger">Неизвестная ошибка</div>';
		
	}
	//---------------------Объявления------------------------//	
  public function actionBlogs()
    {	

  $get = Yii::$app->request->get();
//Если изменяется статус объявлений


	 if (Yii::$app->request->post('check')) {
	
		 	 $post = Yii::$app->request->post('check');
			 
			 
			 
			 
			 		 //Если удаляют навсегда
      if(Yii::$app->request->post('del')) {
		foreach($post as $res) {
				$models = $this->findBlog($res);
				foreach($models->blogField as $field) {
					$model3 = $this->findBlogField($field->id);
					$model3->delete();
				}
		
				foreach($models->imageBlog as $image) {
					$model2 = $this->findBlogImage($image->id);
					@unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/images/board/maxi/'.$model2->image);
					@unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/images/board/mini/'.$model2->image);
					@unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/images/board/original/'.$model2->image);
					$model2->delete();
				}
				$models->delete();
		}
     }else{
			 
			 
			 
			 
			 
			 
			  if(!isset($get['sort']) || $get['sort'] == '') {
		
				
				foreach($post as $res) {
				$model = $this->findBlog($res);
				$model->status_id = 2;
				$model->save(false);
				}
				   
			 }
			 
			 
			 
			 
			  $tyme = $this->findTime();
			  $post = Yii::$app->request->post('check');
			  
			  
			  if(isset($get['sort']) && $get['sort'] == 'del') {	 
		  
				$status = Yii::$app->caches->setting()['moder'];
				foreach($post as $res) {
			
				$model = $this->findBlog($res);
				   if ($model->date_del < date('Y-m-d H:i:s')) {
					  $model->date_add = date('Y-m-d H:i:s');
					  $model->date_del = date('Y-m-d H:i:s', strtotime("+".$tyme." days"));
				   }

				$model->status_id = $status;
				$model->save(false);

				}			
			 }
	 }
			 if (Yii::$app->request->get('page')) {
				 $page = Yii::$app->request->get('page');
			 }else{
				 $page = '1';
			 }
	
		   
		   $act = true;
		 }else{
		   $act = false;
		 }
//Остальное действие





	
	
	$sql = Blog::find()->with('blogField')->with('imageBlog')->with('regions')->with('categorys');
		$sql->Where(['blog.auction' => '0']); 

	 $category = 0;
	
	 if(isset(Yii::$app->request->get()['category']) && Yii::$app->request->get()['category'] != '') {
		 $category = Yii::$app->request->get()['category'];
		 $cat_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $category);
		 $params = ['blog.category'=>$cat_all];
	 }else{
		 $params = '';
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
		
		
	$get = Yii::$app->request->get();

	 foreach($get as $key => $res ) {
		
	  if ($res > 0 || $res == '0' || $res != '') {
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
	$sql->Where(['blog.auction' => '0']);
 	if ($filtr = Yii::$app->request->cookies['filtr']) 
		{
		    if($filtr == 'shop') {
		        $sql->innerJoin('shop shop','shop.user_id = blog.user_id');
		    }
			
			 if($filtr == 'chas') {
		        $sql->leftJoin('shop shop','shop.user_id = blog.user_id');
				$sql->andWhere(['shop.id' => null]);
		    }
		}
 //---------------Обноаление координаты------------------//	
    if(isset($get['text'])) {
      $sql->andFilterWhere(['like', 'title', $get['text']]);
	  $sql->OrFilterWhere(['like', 'blog.id', $get['text']]);
	}
	
	  
 //Пареметры для сортировки 
	 if (Yii::$app->request->get('sort')) {  
	 if (Yii::$app->request->get('sort') == 'DESC') $sort = 'DESC'; 
	 if (Yii::$app->request->get('sort') == 'ASC') $sort = 'ASC'; 
	 }
	 
if(isset($get['coord']) && $get['coord'] > 0) {

	  $coord = explode(',',$get['coord']);
	  $sql->LeftJoin('blog_coord coord','coord.blog_id = blog.id')
	    ->where(['<','6371 * acos (
        cos ( radians('.$coord[0].') )
        * cos( radians( coord.coordlat ) )
        * cos( radians( coord.coordlon ) - radians('.$coord[1].') )
        + sin ( radians('.$coord[0].') )
        * sin( radians( coord.coordlat ) ))', $get['radius']]);
	 }
	 if (!isset($search)) {

		 if ($sort) {
	        $query = $sql
	        ->LeftJoin('blog_field','blog_field.message = blog.id and blog_field.field = '.$price.'')
	        ->with('imageBlog')->with('regions')->with('categorys')->andWhere($params);
			
			  //Для модератора
			      if(Yii::$app->user->can('updateBoard')) {
				      $sql->orWhere(['status_id'=>0]);
					  $sql->orWhere(['active'=>0]);
				  }
			
	        $sql->groupBy('blog.id')
	        ->orderBy('CAST(blog_field.value AS SIGNED) '.$sort);
	     }else{
			 


            $query = $sql->andWhere($params);

	     }
	
	 }
	

	  // Поиск
	  
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
 
    if ($sort) {
	
      $sql
	  ->LeftJoin('blog_field','blog_field.message = blog.id and blog_field.field = '.$price.'')
	  ->orderBy('CAST(blog_field.value AS SIGNED) '.$sort);
    }
}
/*------------------------------------------------------------------------------------------*/ 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	  
	  if(!isset($get['sort']) || $get['sort'] == '') {
		  
		  $sql->andWhere(['blog.status_id'=>1, 'blog.user_id' => Yii::$app->user->id, 'blog.active'=>1]);
		  
	  }elseif($get['sort'] == 'moder') {
		  
		  $sql->andWhere(['blog.status_id'=>0, 'blog.user_id' => Yii::$app->user->id]);
		  
	  }elseif($get['sort'] == 'del') {
		  
		  $sql->andWhere(['blog.status_id'=>2, 'blog.user_id' => Yii::$app->user->id]);
		  
	  }elseif($get['sort'] == 'active') {
		  
		  
		  $sql->andWhere(['blog.status_id'=>1, 'blog.user_id' => Yii::$app->user->id, 'blog.active'=>0]);
          $sql->andWhere(['>','date_del',date('Y-m-d H:i:s', strtotime("+1 days"))]);
		  //$sql->orWhere(['status_id'=>0, 'user_id' => Yii::$app->user->id, 'active'=>0]);

		  
	  }

      
	  $query = $sql->orderBy(['date_add' => SORT_DESC]);
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
      $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	  $blogs = $query->offset($pages->offset)
       ->limit($pages->limit)
       ->all();
	   
	   
	   	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	        if ($res['type'] == 'p')  {
		        $price = $res['id'];
	        }
	     }  
	     $rates = Yii::$app->caches->rates();
		 $notepad = Yii::$app->userFunctions->notepadArr(); 


         //----------------Обновление -------------//
         if (Yii::$app->request->get('sort') == 'active') {
			
		    foreach($blogs as $blogs_act) {
			   $activate[$blogs_act->id] = $this->findMod($blogs_act->category, $blogs_act->region);
		    }	
		 }


	
	
	$field = Yii::$app->userFunctions->fieldSearch($category);
      foreach ($field as $field) {
	    $fields[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
    }





	     return $this->render('blogs', compact('blogs', 'category', 'pages', 'price', 'rates', 'activate', 'valute', 'notepad', 'category_text', 'category', 'fields', 'act', 'get', 'field'));
    }
	
	
	
	
	
    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {


        Yii::$app->response->cookies->remove('auth_key');
        Yii::$app->user->logout();
		

		
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->caches->setting()['email-support'])) {
                Yii::$app->session->setFlash('success', 'Благодарим Вас за обращение к нам. Мы ответим вам как можно скорее.');
            } else {
                Yii::$app->session->setFlash('error', 'При отправке вашего сообщения произошла ошибка.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Спасибо за регистрацию. Пожалуйста, проверьте свой почтовый ящик для подтверждения по электронной почте, если письмо не пришло, проверьте папку спам.');
            return Yii::$app->response->redirect(['user']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для дальнейших инструкций.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Извините, мы не можем сбросить пароль для указанного адреса электронной почты.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                
				if(($result = $this->boardactivation($user->id)) !== null) {
					Yii::$app->session->setFlash('success', 'Ваше email подтвержден!, так же активировано Ваше объявление "'.$result.'"');
				}else{
					Yii::$app->session->setFlash('success', 'Ваше email подтвержден!');
				}
				
                //return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'К сожалению, мы не можем подтвердить ваш аккаунт с помощью предоставленного токена.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для дальнейших инструкций.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Извините, мы не можем отправить письмо с подтверждением на указанный адрес электронной почты..');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
	

    public function actionArticles()
    {

	 $params = ['user_id' => Yii::$app->user->id];
			//Пареметры для сортировки 
	 if (Yii::$app->request->get('sort')) {  
	 if (Yii::$app->request->get('sort') == 'DESC') $sort = SORT_DESC; 
	 if (Yii::$app->request->get('sort') == 'ASC') $sort = SORT_ASC; 
	 }else{
		 $sort = '';
	 }
	$sql = Article::find()->with('cats'); 
	if ($sort) {
	   $query = $sql->andWhere($params)->orderBy(['rayting' => $sort]);
	}else{
       $query = $sql->andWhere($params)->orderBy(['date_add' => SORT_DESC,]);
	}
    
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$article = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
    return $this->render('article_all', compact('article', 'pages', 'user'));
    }



	//---------------------Регистрация через соц сети------------------------//

    public function actionSocial()
    {
	
	$post = Yii::$app->request->post();
	if(!$post) {throw new NotFoundHttpException('The requested page does not exist.');}
	if(!$s = file_get_contents('https://ulogin.ru/token.php?token=' . $post['token'] . '&host=' . $_SERVER['HTTP_HOST'])) {throw new NotFoundHttpException('The requested page does not exist.');}
    $user = json_decode($s, true);
                    //$user['network'] - соц. сеть, через которую авторизовался пользователь
                    //$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
                    //$user['first_name'] - имя пользователя
                    //$user['last_name'] - фамилия пользователя
                
	   if(Yii::$app->userFunctions->socaut($user['identity'])) {
		     return Yii::$app->response->redirect(['user']); 
	   }
	   
	   
	   if(!$user) {throw new NotFoundHttpException('The requested page does not exist.');}
	   $model = new SignupForm();
       return $this->render('social', [
                'user' => $user,
				'model' => $model
       ]);
    }




 public function actionSocialRegistr()
    {
		
		 $model = new SignupForm();

		 if ($model->load(Yii::$app->request->post()) && $model->signup()) {
			 
		 $this->findSoc($model->email, Yii::$app->request->post('SignupForm')['password']);
		
		 $save = true;
		 return '<div class="alert alert-success" role="alert">Регистрация прошла успешна.</div>';

		}else{
		     return $this->render('social', [
				'model' => $model
             ]);
		}
	
		
	}
	




	
    
    public static function findSoc($email, $pass)
    {
        $model = new LoginForm();
		$model->email = $email;
		$model->password = $pass;
		if($model->login()){

			//Кука для безопасности при смене пароля
			 Yii::$app->response->cookies->add(new \yii\web\Cookie([
                 'name' => 'auth_key',
				 'domain' => '.'.DOMAIN,
                 'value' => Yii::$app->user->identity->auth_key
              ]));
            return Yii::$app->response->redirect(['user']);
		}
		
    }
	

	 protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	 protected function findPayment()
    {
        if (($model = PaymentSystem::find()->where(['status' => 1])->asArray()->all()) !== null) {
            return $model;
        }
		
        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	
	
		protected function findBlog($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	protected function findBlogField($id)
    {
        if (($model = BlogField::findOne($id)) !== null) {
            return $model;
        }
        
    }
	
	
	protected function findBlogImage($id)
    {
        if (($model = BlogImage::findOne($id)) !== null) {
            return $model;
        }
        
    }
		protected function findTime()
    {
        if (($model = BlogTime::find()->where(['def' => '1'])->orderBy('sort')->asArray()->one()) !== null) {
            return $model['days'];
        }else{
			return '30';
		}
       
    }
	
	public function actionMessage()
    {
		return $this->render('messages');
	}
	
	
 public function actionMessAll($id)
    {
		
	   $sql = Message::find(); 
	   $query = $sql->Where(['route_id'=> $id, 'u_too' => Yii::$app->user->id])->orWhere(['route_id'=> $id, 'u_from' => Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);
	   $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 60]);
	   $mess = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
	    $rout = $this->findRout($id);
	    $this->layout = 'style_none';
        $count = $query->count();
	    return $this->render('messall', compact('mess', 'rout', 'pages')); 
	
		
	}
	

    public function actionStatusbayer($id)
    {
	
        $model = Car::find()->Where(['id' => $id, 'buyer' => Yii::$app->user->id])->andWhere(['!=','status', 4])->one();
		if(isset($model->dispute) && $model->dispute > 0) {return '';}
		if($model) {
			$model->status = 4;
			$model->update();
			Yii::$app->functionMail->carshop($model->id, $model->user['username'], $model->user['email'], 4);
			if($model->pay == 1) {
				  $user = User::findOne($model->user_id);
				  $user->balance = $user->balance+$model->price;
				  $user->balance_temp = $user->balance_temp-$model->price;
				  $user->update(false);
				  
				  $user2 = User::findOne($model->buyer);
				  $user2->balance_temp = $user2->balance_temp-$model->price;
				  $user2->update(false);
		foreach(Yii::$app->caches->rates() as $rat) {
				 if($rat['def'] == 1) {$rate = $rat;}
		}
		  $pay = new Payment();
          $pay->price = +$model->price;
		  $pay->currency  = $rate['code'];
		  $pay->user_id  = $model->user_id;
		  $pay->system  = 'Продажа № '.$model->id;
		  $pay->blog_id  = '';
		  $pay->services  = 'shop';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false); 
				  
				  /*
				  $user2 = User::findOne(Yii::$app->user->id);
				  $user2->balance_temp = $user2->balance_temp-$model->price;
				  $user2->update(false);
				  */
				  
		 
			}
		}
	}		
	
	
	
	
	
	
	
	
	
	public function actionStatuscar($id, $status)
    {
		if ($status == 4) {return '';}

        $model = Car::find()->Where(['id' => $id, 'user_id' =>Yii::$app->user->id])->one();
		if ($model->status == 3 || $model->status == 4) {return '';}
			
		if($model && $model->status != 3 && $status == 3) {
			
		   if ($model->pay == 1 && $model->status != 3) {

		         //прибавляем к счету покупателя, если заказ был отменен
	            $user = $this->findUser($model->buyer);
	            $user->balance = $user->balance+$model->price;
	            $user->balance_temp = $user->balance_temp-$model->price;
	            $user->update(false);
				
				
				   //Вычитаем со счета продавца   
	              $user_shop = $this->findUser($model->user_id);
	              $user_shop->balance_temp = $user_shop->balance_temp-$model->price;
	              $user_shop->update(false);
				  
				  
				  //Определяем валюту
			foreach(Yii::$app->caches->rates() as $rat) {
				 if($rat['def'] == 1) {$rate = $rat;}
	        }
			
			
		//История Продавца (Возврат)
	      $pay = new Payment();
          $pay->price = '-'.$model->price;
		  $pay->currency  = $rate['code'];
		  $pay->user_id  = Yii::$app->user->id;
		  $pay->system  = 'Возврат с покупки № '.$model->id;
		  $pay->blog_id  = '';
		  $pay->services  = 'cachback';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(); 	
		   		

	   //История покупателя (Возврат)
	      $pay = new Payment();
          $pay->price = $model->price;
		  $pay->currency  = $rate['code'];
		  $pay->user_id  = $model->buyer;
		  $pay->system  = 'Возврат с покупки № '.$model->id;
		  $pay->blog_id  = '';
		  $pay->services  = 'cachback';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(); 	
		   }
		 }  
		   

		 if ($status == 3) {
				$product_id = explode('&',$model->id_product);
				$products = explode(',',$product_id[0]);
				
				foreach($products as $res) {
					$prod = explode('|',$res);
				if(isset($prod[1])) {
					$blog = $this->findBlogrestart($prod[0]);
					if($blog->count !== NULL) {
					    $blog->count = $blog->count+$prod[1];
					    $blog->update(false);
					}
				}
					
			}
		 }
			
		if ($model->status != 3 || $model->status !=4) {
			   $model->status = $status;
			   $model->update(false);
			   Yii::$app->functionMail->carbayer($model->id, $model->bayer['name'], $model->bayer['email'], $status);
		}
		
		}
	
	
	
	
	
	
	
	
	
	
	 public function actionAlerts()
    {
        if(!$model = UserAlerts::find()->where(['user_id'=>Yii::$app->user->id])->one()) {
		   $model = new UserAlerts();
		   $create = '';
		}else{
			$create = true;
		}

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        if(isset($create)) {
			      $model->delete();
		    }
			$model->user_id = Yii::$app->user->id;
			$model->save();
            return $this->redirect(['user/alerts']);
        }else{
			if(!$model->validate()) {
	                  echo '<pre>';print_r($model);			echo '</pre>';
	                  print_r($model->errors);
             }
		}

        return $this->render('alert_create', [
            'model' => $model, 'create' => $create
        ]);
		
	}
	
	
	
	
	
	 public function actionSlider($shop_id)
    {
    /*    $model = new ShopImages();
	 if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                // file is uploaded successfully
                return;
            }
        }*/
		
		
		 $model = new UploadForm();

        if (Yii::$app->request->isPost) {
			
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
			$model->imageFile->name = time().'.'.$model->imageFile->extension;
            if ($model->upload()) {
				$model2 = new ShopImages();
				$model2->image = $model->imageFile->name;
				$model2->url = Yii::$app->request->post()['UploadForm']['url'];
				$model2->user_id = Yii::$app->user->id;
				$model2->shop_id = $shop_id;
				$model2->save();
			}
        }
 $sliders = $this->findSliders();

        return $this->render('slider', compact(['model', 'sliders']));
		
	}
	
	
	 public function actionSliderdel($id)
    {
		$model = ShopImages::find()->andWhere(['user_id'=>Yii::$app->user->id, 'id' => $id])->one();
		$url = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/shop/slider/'.$model->image;
		unlink($url);
		$model->delete();
	}
	
	
	
	
	
	
	protected function findRout($id)
    {
		if (($model = MessageRoute::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	protected function boardactivation($id)
    {
        if ($blog = Blog::find()->andWhere(['user_id'=>$id])->one()){
			$blog->status_id = 1;
			$blog->update();
            return $blog->title;
         }
		
    }
	
		
	protected function findBlogrestart($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	
		protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	
	
	
		protected function findSliders()
        {
            $model = ShopImages::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['sort' => SORT_ASC])->all();
            return $model;
        }





    protected function findMod($cat, $reg)
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


/*
    public function actionAuction()
	{
	$query = BlogAuction::find()->andWhere(['user_id' => Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);	
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$product = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	    $searchModel = new BlogAuctionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id);
        return $this->render('auction', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

*/



	//---------------------Объявления------------------------//	
  public function actionAuction()
    {	

  $get = Yii::$app->request->get();
//Если изменяется статус объявлений


	 if (Yii::$app->request->post('check')) {
	
		 	 $post = Yii::$app->request->post('check');
			 
			 
			 
			 
			 		 //Если удаляют навсегда
					  if(Yii::$app->request->post('del') == 'null') {
						return ''; 
					  }
					
      if(Yii::$app->request->post('del') == 'true') {
	
		foreach($post as $res) {
			
			
				$models = $this->findBlog($res);
				
				
				   if($models->reserv_user_id > 0  && $models->auction != 3) {
		                 Yii::$app->functionMail->reservdellot($models);
				   }	
			
				
				
				//Отменяем все ставки и отсылаем всем сообщения об отмене
				if($models->status_id == 1 ) {
				 if(!$models->reserv_user_id) {	
				$bets = Bet::find()->where(['blog_id'=> $models->id])->orderBy(['id' => SORT_DESC])->all();
			    foreach($bets as $betmail) {
					$betarr[$betmail->user_id] = $betmail;
					break;
				}
				if(isset($betarr)) {
						
					foreach($betarr as $ba) {
					
						 Yii::$app->functionMail->betdelmail($ba, $models);
				     }
			    }
				}
		        }
				////////////////////////////////////////
				
				
				
				Bet::deleteAll(['blog_id'=> $models->id]);
				foreach($models->blogField as $field) {
					$model3 = $this->findBlogField($field->id);
					$model3->delete();
				}
		
				foreach($models->imageBlog as $image) {
					$model2 = $this->findBlogImage($image->id);
					@unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/images/board/maxi/'.$model2->image);
					@unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/images/board/mini/'.$model2->image);
					@unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/images/board/original/'.$model2->image);
					$model2->delete();
				}
				$models->delete();
		}
     }else{
			 
			 
			 
			 
			 
			 
			  if(!isset($get['sort']) || $get['sort'] == '') {
		
				
				foreach($post as $res) {
				$model = $this->findBlog($res);
				
				//Отменяем все ставки и отсылаем всем сообщения об отмене
				if(!$model->reserv_user_id  && $model->auction != 3) {	
				$bets = Bet::find()->where(['blog_id'=> $model->id])->orderBy(['id' => SORT_DESC])->all();
			    foreach($bets as $betmail) {
					$betarr[$betmail->user_id] = $betmail;
					break;
				}
				if(isset($betarr)) {
						
					foreach($betarr as $ba) {
					
						 Yii::$app->functionMail->betdelmail($ba, $model);
				     }
			    }
				}
				////////////////////////////////////////
				
				$model->status_id = 2;
				$model->save(false);
				}
				   
			 }
			 
			 
			 
			 
			  $tyme = $this->findTime();
			  $post = Yii::$app->request->post('check');
			  
			  
		
			 
			 
			  
			  if(isset($get['sort']) && $get['sort'] == 'del') {	 

				$status = Yii::$app->caches->setting()['moder'];
				foreach($post as $res) {
		
				$model = $this->findBlog($res);

				   if ($model->date_del < date('Y-m-d H:i:s')) {
					 
					 //Следующие три строки поместить в это условие, если нужно сделать так, чтобы дата изменялась только у объявлений у которых кончился срок публикации
					 
					  } 
					   $day = round((strtotime ($model->date_del)-strtotime ($model->date_add))/(60*60*24));
					  $model->date_add = date('Y-m-d H:i:s');
					  $model->date_del = date('Y-m-d H:i:s', strtotime("+".$day." days"));
					  
					  
					  /*Вот тут сомнения берут, если что удалить!!!!!!*/
				      $auction = BlogAuction::find()->where(['blog_id' => $model->id])->One();
                      $auction->date_add = date('Y-m-d H:i:s');
					  $auction->date_end = date('Y-m-d H:i:s', strtotime("+".$day." days"));
					  $auction->update(false);
					  
					  
					  
					  
					  
                if($model->reserv_user_id  && $model->auction != 3) {
		           Yii::$app->functionMail->reservdellot($model);
				}	

				$model->status_id = $status;
				$model->reserv_user_id = '';
				$model->auction = 1;
				$model->save(false);
				
				
			  
             
				Bet::deleteAll(['blog_id'=> $model->id]);
				
				
				$field = BlogField::find()->where(['field'=> 481, 'message' => $model->id])->One();
				$field->value = $model->auctions->price_add;
				$field->update(false);
				
				
				}			
			 }
	 }
			 if (Yii::$app->request->get('page')) {
				 $page = Yii::$app->request->get('page');
			 }else{
				 $page = '1';
			 }
	
		   
		   $act = true;
		 }else{
		   $act = false;
		 }
//Остальное действие





	
	
	$sql = Blog::find()->with('blogField')->with('imageBlog')->with('regions')->with('categorys');
	$sql->Where(['!=','blog.auction', '0']);
	 $category = 0;
	
	 if(isset(Yii::$app->request->get()['category']) && Yii::$app->request->get()['category'] != '') {
		 $category = Yii::$app->request->get()['category'];
		 $cat_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $category);
		 $params = ['blog.category'=>$cat_all];
	 }else{
		 $params = '';
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
		
		
	$get = Yii::$app->request->get();

	 foreach($get as $key => $res ) {
		
	  if ($res > 0 || $res == '0' || $res != '') {
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
$sql->Where(['!=','blog.auction', '0']);
 	if ($filtr = Yii::$app->request->cookies['filtr']) 
		{
		    if($filtr == 'shop') {
		        $sql->innerJoin('shop shop','shop.user_id = blog.user_id');
		    }
			
			 if($filtr == 'chas') {
		        $sql->leftJoin('shop shop','shop.user_id = blog.user_id');
				$sql->andWhere(['shop.id' => null]);
		    }
		}
 //---------------Обноаление координаты------------------//	
    if(isset($get['text'])) {
      $sql->andFilterWhere(['like', 'title', $get['text']]);
	  $sql->OrFilterWhere(['like', 'blog.id', $get['text']]);
	}
	
	  
 //Пареметры для сортировки 
	 if (Yii::$app->request->get('sort')) {  
	 if (Yii::$app->request->get('sort') == 'DESC') $sort = 'DESC'; 
	 if (Yii::$app->request->get('sort') == 'ASC') $sort = 'ASC'; 
	 }
	 

	 if (!isset($search)) {

		 if ($sort) {
	        $query = $sql
	        ->LeftJoin('blog_field','blog_field.message = blog.id and blog_field.field = '.$price.'')
	        ->with('imageBlog')->with('regions')->with('categorys')->andWhere($params);
			
			  //Для модератора
			      if(Yii::$app->user->can('updateBoard')) {
				      $sql->orWhere(['status_id'=>0]);
					  $sql->orWhere(['active'=>0]);
				  }
			
	        $sql->groupBy('blog.id')
	        ->orderBy('CAST(blog_field.value AS SIGNED) '.$sort);
	     }else{
			 


            $query = $sql->andWhere($params);

	     }
	
	 }
	

	  // Поиск
	  
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
 
    if ($sort) {
	
      $sql
	  ->LeftJoin('blog_field','blog_field.message = blog.id and blog_field.field = '.$price.'')
	  ->orderBy('CAST(blog_field.value AS SIGNED) '.$sort);
    }
}
/*------------------------------------------------------------------------------------------*/ 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	  
	  if(!isset($get['sort']) || $get['sort'] == '') {
		  
		  $sql->andWhere(['blog.status_id'=>1, 'blog.user_id' => Yii::$app->user->id, 'blog.active'=>1]);
		  
	  }elseif($get['sort'] == 'moder') {
		  
		  $sql->andWhere(['blog.status_id'=>0, 'blog.user_id' => Yii::$app->user->id]);
		  
	  }elseif($get['sort'] == 'del') {
		  
		  $sql->andWhere(['blog.status_id'=>2, 'blog.user_id' => Yii::$app->user->id]);
		  
	  }elseif($get['sort'] == 'active') {
		  
		  
		  $sql->andWhere(['blog.status_id'=>1, 'blog.user_id' => Yii::$app->user->id, 'blog.active'=>0]);
          $sql->andWhere(['>','date_del',date('Y-m-d H:i:s', strtotime("+1 days"))]);
		  //$sql->orWhere(['status_id'=>0, 'user_id' => Yii::$app->user->id, 'active'=>0]);

		  
	  }

      
	  $query = $sql->orderBy(['date_add' => SORT_DESC]);
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
      $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	  $blogs = $query->offset($pages->offset)
       ->limit($pages->limit)
       ->all();
	   
	   
	   	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	        if ($res['type'] == 'p')  {
		        $price = $res['id'];
	        }
	     }  
	     $rates = Yii::$app->caches->rates();
		 $notepad = Yii::$app->userFunctions->notepadArr(); 


         //----------------Обновление -------------//
         if (Yii::$app->request->get('sort') == 'active') {
			
		    foreach($blogs as $blogs_act) {
			   $activate[$blogs_act->id] = $this->findMod($blogs_act->category, $blogs_act->region);
		    }	
		 }


	
	
	$field = Yii::$app->userFunctions->fieldSearch($category);
      foreach ($field as $field) {
	    $fields[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
    }





	     return $this->render('auction', compact('blogs', 'category', 'pages', 'price', 'rates', 'activate', 'valute', 'notepad', 'category_text', 'category', 'fields', 'act', 'get', 'field'));
    }



    public function actionBet()
	{
	$query = Bet::find()->andWhere(['user_id' => Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);	
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$product = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	    $searchModel = new BetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id);
        return $this->render('bet', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	
	
	
	
	
	public function actionReserv()
	{
	$rates = Rates::find()->all();
foreach($rates as $rat) {

	$ratest[$rat['id']] = $rat['text'];
}

	$query = Blog::find()->andWhere(['user_id' => Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);	
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$product = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	    $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id);
        return $this->render('reserv', [
		'ratest' => $ratest,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function actionSuccesslot()
	{
		
	$rates = Rates::find()->all();
foreach($rates as $rat) {
	$ratest[$rat['id']] = $rat['text'];
}

	$query = Blog::find()->andWhere(['user_id' => Yii::$app->user->id, 'date_del' => date('Y-m-d H:i:s') ])->orderBy(['id' => SORT_DESC]);	
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$product = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	    $searchModel = new BlogSearch();
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id, true);//Третий параметр для сортировки выиграшных торгов
        return $this->render('successlot', [
		'ratest' => $ratest,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

    }