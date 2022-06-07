<?php

namespace frontend\modules\shop\controllers;
use \yii\base\DynamicModel;
use Yii;
use yii\helpers\Html;
use frontend\models\Shop;
use frontend\models\User;
use common\models\Online;
use common\models\Rates;
use frontend\models\Blog;
use frontend\models\Contshopperson;
use common\models\Payment;
use common\models\Message;
use yii\helpers\Url;
use common\models\ShopField;
use frontend\models\ShopComment;
use common\models\ShopImage;
use common\models\BlogComment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Category;
use common\models\Article;
use common\models\ArticleComment;
use common\models\ShopReating;
use yii\data\Pagination;
use common\models\Orders;
use common\models\Field;
use common\models\LoginForm;
use frontend\models\SignupForm;
/**
 * Default controller for the `personalshop` module
 */
class DefaultController extends Controller
{
	
	
	
	

	
		
//Страница объявления
	public function actionIndex($url)
    {



		$arr = $this->findModel($url);
		$this->layout = 'shop';
	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];
      //Отбираем объявления
	  
	  if(isset($_COOKIE['region'])  && $_COOKIE['region'] > 0) {
	  	$id_region = $_COOKIE['region'];
		$region = $id_region;	
	  }else{
		  $region = '';
	  }


	      if ($region) {
		       $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region);
			   $blog = Blog::find()->andWhere([ 'status_id' => 1, 'active' => 1, 'user_id' => $shop->user_id, 'region' => $reg_all])->orWhere([ 'status_id' => 1, 'auction' => 1, 'active' => 1, 'user_id' => $shop->user_id, 'region' => $reg_all])->orderBy('date_add desc')->limit(12)->all();
		  }else{
		       $blog = Blog::find()->andWhere([ 'status_id' => 1, 'active' => 1, 'user_id' => $shop->user_id])->orWhere([ 'status_id' => 1, 'auction' => 1, 'active' => 1, 'user_id' => $shop->user_id])->orderBy('date_add desc')->limit(12)->all();
		  }
		
    foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $price = $res['id'];
	   }
	}
	
	


             return $this->render('index', compact('is_mobile_device','shop', 'rates', 'notepad', 'price', 'blog', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'count_com', 'vote', 'count_art'));

    
    }	
	
	
	
	
	
	
	
		public function actionMynotepad($url)
    {
		
		$post = Yii::$app->request->post();
	if(isset($post['style']) && $post['style'] == 'grid') {

			  Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'style',
                        'value' => 'grid'
              ]));
			  $url_red = str_replace('?style=grid','',$_SERVER['REQUEST_URI']);
			  $url_red = str_replace('style=grid','',$url_red);
		  return $this->redirect([$url_red]);
	  
	}
	if(isset($post['style']) && $post['style'] == 'list') {
		  Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'style',
                        'value' => ''
              ]));
			  $url_red = str_replace('?style=list','',$_SERVER['REQUEST_URI']);
			  $url_red = str_replace('style=list','',$url_red);
			 
		      return $this->redirect([$url_red]);
	}

    	$arr = $this->findModel($url);
		$this->layout = 'shop';
	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];


	$this->layout = 'shop';
	$arr = Yii::$app->userFunctions->notepadArr();
    $query = Blog::find()->with('blogField')->with('imageBlog')->andWhere(['id' => $arr, 'user_id' => $shop->user_id, 'status_id'=>1, 'blog.active'=>1])->orderBy('date_add desc');
	$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$blogs = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
	$notepad = $arr;
	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $price = $res['id'];
	   }
	}
	 $rates = Yii::$app->caches->rates();
	
	 $metaCat['title'] = 'Избранное на сайте "'.Yii::$app->caches->setting()['title-site'].'"';
     $metaCat['h1'] = 'Избранное на сайте "'.Yii::$app->caches->setting()['title-site'].'"';
	 $metaCat['keywords'] = 'Избранное, "'.Yii::$app->caches->setting()['title-site'].'"';
	 $metaCat['description'] = 'Избранное на сайте "'.Yii::$app->caches->setting()['title-site'].'"';
	 $metaCat['breadcrumbs'][] = 'Избранное';
	  if(Yii::$app->request->cookies['style'] == 'grid') {
		   return $this->render('blog_grid', compact('blogs', 'pages', 'rates', 'notepad', 'metaCat','shop', 'price', 'blog', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'count_com', 'vote', 'count_art'));
	  }else{
		 return $this->render('blog_all', compact('blogs', 'pages', 'rates', 'notepad', 'metaCat','shop', 'price', 'blog', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'count_com', 'vote', 'count_art'));  
	  }
   
	}
	
	
	

		
	public function actionAuction($url, $category = false, $patch = false)
    {
		
$post = Yii::$app->request->post();
	if(isset($post['style']) && $post['style'] == 'grid') {

			  Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'style',
                        'value' => 'grid'
              ]));
			  $url_red = str_replace('?style=grid','',$_SERVER['REQUEST_URI']);
			  $url_red = str_replace('style=grid','',$url_red);
		  return $this->redirect([$url_red]);
	  
	}
	if(isset($post['style']) && $post['style'] == 'list') {
		  Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'style',
                        'value' => ''
              ]));
			  $url_red = str_replace('?style=list','',$_SERVER['REQUEST_URI']);
			  $url_red = str_replace('style=list','',$url_red);
			 
		      return $this->redirect([$url_red]);
	}
		
    	$arr = $this->findModel($url);
		$this->layout = 'shop';
	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];
    $text = Yii::$app->request->get('text');
	  if ($text) {
	        $text = Html::encode($text);
	    }	
	$this->layout = 'shop';
	
	
		    //Если регион не выбран, меняем параметры без региона 

	 
	 
	 
	 
	 
	 
	
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





	$get = Yii::$app->request->get();

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

   








  
			//Получаем всех родителей последней категории в url и передаем в выборку, в итоге получаем в корневой категории объявления потомков
		if ($category) {
	       $cat_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $category);
		}else{
			$category = 0;
			$cat_all = '';
		}
		
		if(isset($_COOKIE['region'])  && $_COOKIE['region'] > 0) {
		   $id_region = $_COOKIE['region'];
		   $region = $id_region;	
		}else{
			$region = '';
		}


	 if ($category) {
	      if ($region) {
		    $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region);
		    $params = ['status_id'=>1, 'blog.active'=>1, 'user_id' => $shop->user_id,  'category'=>$cat_all, 'blog.region' => $reg_all];
		  }else{
		     $params = ['status_id'=>1, 'blog.active'=>1, 'user_id' => $shop->user_id,  'category'=>$cat_all];
		  }

		}else{
		  if ($region) {
			 $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region);
		     $params = ['status_id'=>1, 'blog.active'=>1,'user_id' => $shop->user_id, 'blog.region' => $reg_all];
	
		 }else{
		      $params = ['status_id'=>1, 'blog.active'=>1,'user_id' => $shop->user_id];
		  }
		}
	
	
  /* $query = Blog::find()->with('blogField')->with('imageBlog')->andWhere(['user_id' => $shop->user_id, 'status_id'=>1, 'blog.active'=>1])->andWhere($params)->andFilterWhere(['like', 'title', $text])->orderBy('date_add desc');
	$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$blogs = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
	*/	
	
		  
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
			   //Для модератора
			      if(Yii::$app->user->can('updateBoard')) {
				      $sql->orWhere(['status_id'=>0]);
					  $sql->orWhere(['active'=>0]);
				  }
			 $sql->orderBy([new \yii\db\Expression('FIELD (id, '.$top.') DESC'), 'date_add' => SORT_DESC,]);
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



  $sql->andWhere(['auction' => 1]);

   if(isset($get['text'])) {
      $sql->andFilterWhere(['like', 'title', $get['text']]);
	}
	$query = $sql->groupBy('blog.id'); 

    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$blogs = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();













	 
		
		
	$notepad = $arr;
	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $price = $res['id'];
	   }
	}
	$rates = Yii::$app->caches->rates();

	 //----------------------------------------------------//
	 if(!$text) {
	 	$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		$array = [];
		$cat_arr = Category::find()->where(['parent' => $category])->orderBy('sort')->all();

	 	foreach($cat_arr as $res) {
			$count = Yii::$app->userFunctions2->counterauction(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id']), $shop->user_id);

			$array[$res['id']] = array('id' => $res['id'], 'name' => $res['name'], 'url' =>  $patch_url.'/'.$patch.'/'.$res['url'], 'image' => $res['image'], 'count'=>$count, $res['id']);
		}
		
		$cat_menu = $array;
		$metaCat = Yii::$app->userFunctions2->metaCatsitemagazine($category, $shop->name);
	 }
	//-----------------------------------------------------//	
$notepad = Yii::$app->userFunctions->notepadArr();
	$field = $this->fieldSearch($category);
      foreach ($field as $field) {
	    $fields[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
    }


	 if(Yii::$app->request->cookies['style'] == 'grid') {
		return $this->render('auction_grid', compact('blogs', 'cat_menu', 'pages', 'rates', 'notepad', 'metaCat','shop', 'price', 'blog', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'cat_all', 'count_com', 'vote', 'count_art', 'category'));
	  }else{
	
		return $this->render('auction_all', compact( 'blogs', 'cat_menu', 'pages', 'rates', 'notepad', 'metaCat','shop', 'price', 'blog', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'cat_all', 'count_com', 'vote', 'count_art', 'category'));
	  }
    
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function actionProduct($url, $category = false, $patch = false)
    {
		
$post = Yii::$app->request->post();
	if(isset($post['style']) && $post['style'] == 'grid') {

			  Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'style',
                        'value' => 'grid'
              ]));
			  $url_red = str_replace('?style=grid','',$_SERVER['REQUEST_URI']);
			  $url_red = str_replace('style=grid','',$url_red);
		  return $this->redirect([$url_red]);
	  
	}
	if(isset($post['style']) && $post['style'] == 'list') {
		  Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'style',
                        'value' => ''
              ]));
			  $url_red = str_replace('?style=list','',$_SERVER['REQUEST_URI']);
			  $url_red = str_replace('style=list','',$url_red);
			 
		      return $this->redirect([$url_red]);
	}
		
    	$arr = $this->findModel($url);
		$this->layout = 'shop';
	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];
    $text = Yii::$app->request->get('text');
	  if ($text) {
	        $text = Html::encode($text);
	    }	
	$this->layout = 'shop';
	
	
		    //Если регион не выбран, меняем параметры без региона 

	 
	 
	 
	 
	 
	 
	
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





	$get = Yii::$app->request->get();

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









  
			//Получаем всех родителей последней категории в url и передаем в выборку, в итоге получаем в корневой категории объявления потомков
		if ($category) {
	       $cat_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $category);
		}else{
			$category = 0;
			$cat_all = '';
		}
		
		if(isset($_COOKIE['region'])  && $_COOKIE['region'] > 0) {
		   $id_region = $_COOKIE['region'];
		   $region = $id_region;	
		}else{
			$region = '';
		}


	 if ($category) {
	      if ($region) {
		    $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region);
		    $params = ['status_id'=>1, 'blog.active'=>1, 'user_id' => $shop->user_id,  'category'=>$cat_all, 'blog.region' => $reg_all];
		  }else{
			
		     $params = ['status_id'=>1, 'blog.active'=>1, 'user_id' => $shop->user_id,  'category'=>$cat_all];
		  }

		}else{
		  if ($region) {
			 $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region);
		     $params = ['status_id'=>1, 'blog.active'=>1, 'user_id' => $shop->user_id, 'blog.region' => $reg_all];
	         $params2 = ['status_id'=>1,'blog.auction' => 1,  'blog.active'=>1, 'user_id' => $shop->user_id, 'blog.region' => $reg_all];
		 }else{
			 $params2 = ['status_id'=>1,'blog.auction' => 1, 'blog.active'=>1,'user_id' => $shop->user_id];
		      $params = ['status_id'=>1, 'blog.active'=>1,'user_id' => $shop->user_id];
		  }
		}
	
	
  /* $query = Blog::find()->with('blogField')->with('imageBlog')->andWhere(['user_id' => $shop->user_id, 'status_id'=>1, 'blog.active'=>1])->andWhere($params)->andFilterWhere(['like', 'title', $text])->orderBy('date_add desc');
	$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$blogs = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
	*/	
	
		  
 //Пареметры для сортировки 
	 if (Yii::$app->request->get('sort')) {  
	 if (Yii::$app->request->get('sort') == 'DESC') $sort = 'DESC'; 
	 if (Yii::$app->request->get('sort') == 'ASC') $sort = 'ASC'; 
	 }
		
		 if (!isset($search)) {

		 if ($sort) {
	        $query = $sql
	        ->LeftJoin('blog_field','blog_field.message = blog.id and blog_field.field = '.$price.'')
	        ->with('imageBlog')->with('regions')->with('categorys')->andWhere($params)->orWhere($params2);
			
			  //Для модератора
			      if(Yii::$app->user->can('updateBoard')) {
				      $sql->orWhere(['status_id'=>0]);
					  $sql->orWhere(['active'=>0]);
				  }
			
	        $sql->groupBy('blog.id')
	        ->orderBy('CAST(blog_field.value AS SIGNED) '.$sort);
	     }else{
			 
                $sql->andWhere($params); 
			 if(isset($params2)) {
			    $sql->orWhere($params2);
			 }
             $query = $sql;
			   //Для модератора
			      if(Yii::$app->user->can('updateBoard')) {
				      $sql->orWhere(['status_id'=>0]);
					  $sql->orWhere(['active'=>0]);
				  }
			 $sql->orderBy([new \yii\db\Expression('FIELD (id, '.$top.') DESC'), 'date_add' => SORT_DESC,]);
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





   if(isset($get['text'])) {
      $sql->andFilterWhere(['like', 'title', $get['text']]);
	}
	$query = $sql->groupBy('blog.id'); 

    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$blogs = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();













	 
		
		
	$notepad = $arr;
	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $price = $res['id'];
	   }
	}
	$rates = Yii::$app->caches->rates();

	 //----------------------------------------------------//
	 if(!$text) {
	 	$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		$array = [];
		$cat_arr = Category::find()->where(['parent' => $category])->orderBy('sort')->all();

	 	foreach($cat_arr as $res) {
			$count = Yii::$app->userFunctions2->counterboardmagazin(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id']), $shop->user_id);

			$array[$res['id']] = array('id' => $res['id'], 'name' => $res['name'], 'url' =>  $patch_url.'/'.$patch.'product/'.$res['url'], 'image' => $res['image'], 'count'=>$count, $res['id']);
		}
		$cat_menu = $array;
		$metaCat = Yii::$app->userFunctions->metaCatsitemagazine($category, $shop->name);
	 }
	//-----------------------------------------------------//	
$notepad = Yii::$app->userFunctions->notepadArr();
	$field = $this->fieldSearch($category);
      foreach ($field as $field) {
	    $fields[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
    }


	 if(Yii::$app->request->cookies['style'] == 'grid') {
		return $this->render('blog_grid', compact('blogs', 'cat_menu', 'pages', 'rates', 'notepad', 'metaCat','shop', 'price', 'blog', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'cat_all', 'count_com', 'vote', 'count_art', 'category'));
	  }else{
	
		return $this->render('blog_all', compact( 'blogs', 'cat_menu', 'pages', 'rates', 'notepad', 'metaCat','shop', 'price', 'blog', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'cat_all', 'count_com', 'vote', 'count_art', 'category'));
	  }
    
	}
	
	
	
	
	
	
	
		public function actionArticle($url)
      {
    	$arr = $this->findModel($url);
		$this->layout = 'shop';
	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];

        $text = Yii::$app->request->get('text');
	    if ($text) {
	        $text = Html::encode($text);
	    }	
       $sql = Article::find()->with('cats'); 
	   $sql->andFilterWhere(['like', 'title', $text]);
	   $query = $sql->andWhere(['status'=>1])->andWhere(['user_id'=>$shop->user_id])->orderBy(['date_add' => SORT_DESC,]);
       $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	   $article = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
		$this->layout = 'shop';
		 return $this->render('article_all', compact('article', 'pages', 'user_id', 'shop', 'rates', 'notepad', 'price', 'blog', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'count_com', 'vote', 'count_art'));
 
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function actionBoardone($url, $id)
      {
		
    	$arr = $this->findModel($url);
		$this->layout = 'shop';
	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];

      
		$this->layout = 'shop';
		
		
		
		if ($blog = Blog::find()->with('blogField.fields')->andWhere(['id'=>$id, 'user_id' => $shop->user_id])->one()){

	    $url_original = Yii::$app->caches->region()[$blog['region']]['url'] . '/' . Yii::$app->caches->category()[$blog['category']]['url'] . '/' . $blog['url'].'_'.$blog['id'].'.html';
	    $rates = Yii::$app->caches->rates();
        foreach($rates as $resrat) {
			if($resrat['def'] == 1) {
		      $rate_def = $resrat;
			}
		}
			  //Получаем мета теги
	  $meta = Yii::$app->userFunctions->metaOne($blog->category, '', $blog->title, 'product');

      $blog_field = $blog->blogField;
      $fields = array();
      $price = array();
      $coord = array();
    foreach($blog_field as $bFields) {
	  if ($bFields['fields']['type'] == 'p') {	  
		$value_pp = ((int)$bFields['value']/(int)$rates[$bFields['dop']]['value']);
		$value_p = number_format(round($value_pp), 0, '.', ' ');
		$price[] = array('id'=>$bFields['fields']['id'], 'name'=>$bFields['fields']['name'], 'hide'=>$bFields['fields']['hide'], 'value'=>$value_p,'rates'=> $rates[$bFields['dop']]);
	  }
	  if ($bFields['fields']['type'] == 'j') {
		$coord[] = array('id'=>$bFields['fields']['id'], 'name'=>$bFields['fields']['name'], 'hide'=>$bFields['fields']['hide'], 'value'=>$bFields['value'], 'address'=> $bFields['dop']);
	  }  
		  
	 if ($bFields['fields']['type'] != 'p' && $bFields['fields']['type'] != 'j') { 
	 if ($bFields['fields']['values']) {$bFields['value'] = explode("\n",$bFields['fields']['values'])[$bFields['value']];}
        if ($bFields['fields']['type']) {
	       $fields[] = array(
		   'type' => $bFields['fields']['type'], 
		   'name' => $bFields['fields']['name'], 
		   'type_string' => $bFields['fields']['type_string'], 
		   'hide' => $bFields['fields']['hide'], 
		   'value' => $bFields['value'],
		  // 'values' => explode("\n",$bFields['fields']['values']),
		   );
		  //echo '<pre>'; print_r($bFields); echo '</pre>';
	    }
	 }
	  
}
          $notepad = Yii::$app->userFunctions->notepadArr();
		 
		 	 
	 
	 //Комментарии к объявлению
	 
      
		 
		 //Добавление комментария
        $comment_add = new BlogComment();
		$comment_save = '';
		
		
	//Сохраняем	Комментарий ----------------------------------------------------------------------------------
     if ($comment_add->load(Yii::$app->request->post())) {
    
		  $comment_add->blog_id = $blog->id;
		  $comment_add->date = date('Y-m-d H:i:s');
		  $comment_add->status = Yii::$app->caches->setting()['moder_blog_comment'];
	      if ($comment_add->too) {
			  $comment_add->text = preg_replace('/^.+\n/', '', $comment_add->text);
		  }
		  
		  //Добавляем данные юзера, если он зарегистрирован
		  if (!Yii::$app->user->isGuest) {
			 $comment_add->user_id = Yii::$app->user->id;
			 $comment_add->user_email = Yii::$app->user->identity['email'];
		     $comment_add->user_name = Yii::$app->user->identity['username'];
		  }
          
          $comment_add->save();
		  $comment_save = true;
		  
		  	  	$user_c = User::findOne($blog->user_id);
			     if(!isset($user_c->alert['comment']) || (isset($user_c->alert['comment']) && $user_c->alert['comment'] == 1)) {
					$url = Url::to(['/boardone', 'id'=>$blog->id]);
			         Yii::$app->functionMail->comment_board($user_c, $url);
			    }
		  
    }
	//Сохранили	Комментарий ----------------------------------------------------------------------------------		 
	$query = BlogComment::find()->andWhere(['blog_id' => $blog->id, 'status' => 1])->orderBy(['id' => SORT_DESC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
	$comment = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
		  
	$blogssimilar = Blog::find()->andWhere(['status_id' => 1, 'auction' => 0, 'active' => 1, 'user_id' => $shop->user_id])->andFilterWhere(['like', 'title', $blog->title])->andWhere(['not in', 'id', $blog->id])->orderBy(['id' => SORT_DESC])
	->orWhere(['category' => $blog->category])->andWhere(['not in', 'id', $blog->id])->andWhere(['status_id' => 1, 'auction' => 0, 'active' => 1, 'user_id' => $shop->user_id])
	->limit(4)->all();
	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $pricesimilar = $res['id'];
	   }
	}
	$ratessimilar = Yii::$app->caches->rates();
	$notepadsimilar = Yii::$app->userFunctions->notepadArr();

          return $this->render('boardone', compact('blog', 'rate_def', 'notepadsimilar', 'pricesimilar', 'ratessimilar', 'meta', 'fields', 'price', 'notepad', 'coord', 'comment', 'comment_add', 'comment_save', 'pages', 'blogssimilar', 'pagessimilar',
		  'article', 'user_id', 'shop', 'rates', 'price', 'blog', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'count_com', 'vote', 'count_art'
		  ));
	
    }
	throw new NotFoundHttpException('Такого бъявления нет или оно находится на модерации');
		 
 
	}
	
	
	
	//Рейтинг
	
	    public function actionReating($id)
    {
if(@unserialize(Yii::$app->request->cookies['votes']->value)) {
    if(in_array($id, unserialize(Yii::$app->request->cookies['votes']))) {
	   $vote = false;
	}else{
	   $vote = true;
	}
}elseif(isset(Yii::$app->request->cookies['votes']) && Yii::$app->request->cookies['votes'] == $id){
    $vote = false;
}else{
	$vote = true;
}



       if($vote) {    
		$model = $this->findReating($id);
		if($model) {
		if ($model->load(Yii::$app->request->post())) {
	    if($model->services) { 
                $model->obsluga_plus = $model->obsluga_plus+1;
				$model->obsluga_minus = $model->obsluga_minus+0;
			 }else{
				$model->obsluga_plus = $model->obsluga_plus+0; 
				$model->obsluga_minus = $model->obsluga_minus+1;
			 }
	         if($model->price) { 
                $model->cena_plus = $model->cena_plus+1;
				$model->cena_minus = $model->cena_minus+0;
			 }else{
				$model->cena_minus = $model->cena_minus+1;
				$model->cena_plus = $model->cena_plus+0; 
			 }
			 if($model->quality) { 
                $model->kachestvo_plus = $model->kachestvo_plus+1; 
				$model->kachestvo_minus = $model->kachestvo_minus+0; 
			 }else{
				$model->kachestvo_minus = $model->kachestvo_minus+1;
				$model->kachestvo_plus = $model->kachestvo_plus+0; 
			 }
			 
            if($model->save()) {
				
					
		//-------------------------Проверяем, голосовал ли за эту организацию -------------------------//  
			if(@unserialize(Yii::$app->request->cookies['votes']->value)) {
				$arr = unserialize(Yii::$app->request->cookies['votes']);
				array_push($arr, $id);
				$arr = serialize($arr);
				}else{
					if (Yii::$app->request->cookies['votes']) {
						$arr = array(Yii::$app->request->cookies['votes']->value, $id);
						$arr = serialize($arr);
					}else{
					    $arr = $id;
					}
				}

				Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'votes',
                        'value' => $arr
                        ]));   
		   		//-------------------------Конец проверки -------------------------//  
					
			//Считаем общее количество положительных голосов и записываем в таблицу магазинов, чтобы потом можно было сделать быструю сортировку
			$summa = ($model->cena_plus+$model->kachestvo_plus+$model->obsluga_plus)-($model->cena_minus+$model->kachestvo_minus+$model->obsluga_minus);
			$shop = $this->findShop($id);	
			$shop->rayting = $summa;
			$shop->save(false);
			}
        }
		}else{
		$model = new ShopReating();	
		if ($model->load(Yii::$app->request->post())) {
	    if($model->services) { 
                $model->obsluga_plus = 1;
				$model->obsluga_minus = 0;
			 }else{
				$model->obsluga_plus = 0; 
				$model->obsluga_minus = 1;
			 }
	         if($model->price) { 
                $model->cena_plus = 1;
				$model->cena_minus = 0;
			 }else{
				$model->cena_minus = 1;
				$model->cena_plus = 0; 
			 }
		
			 if($model->quality) { 
                $model->kachestvo_plus = 1; 
				$model->kachestvo_minus = 0; 
			 }else{
				$model->kachestvo_minus = 1;
				$model->kachestvo_plus = 0; 
			 }
            if($model->save()) {

		    //-------------------------Проверяем, голосовал ли за эту организацию -------------------------//  
			if(@unserialize(Yii::$app->request->cookies['votes']->value)) {
				$arr = unserialize(Yii::$app->request->cookies['votes']);
				array_push($arr, $id);
				$arr = serialize($arr);
				}else{
					if (Yii::$app->request->cookies['votes']) {
						$arr = array(Yii::$app->request->cookies['votes']->value, $id);
						$arr = serialize($arr);
					}else{
					    $arr = $id;
					}
				}

				Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'votes',
                        'value' => $arr
                        ]));   
		   		//-------------------------Конец проверки -------------------------//  
				
			
			//Считаем общее количество положительных голосов и записываем в таблицу магазинов, чтобы потом можно было сделать быструю сортировку
			$summa = ($model->cena_plus+$model->kachestvo_plus+$model->obsluga_plus)-($model->cena_minus+$model->kachestvo_minus+$model->obsluga_minus);
			$shop = $this->findShop($id);	
			$shop->rayting = $summa;
			$shop->save(false);
			}
        }
		}
		return 'Спасибо, Ваш голос учтен.';
	}else{
		return 'Вы уже оценивали этот магазин';exit();
	}
    }
	
	
	
	
	

	
	
	public function actionNotepads($id)
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
	
	
	
	protected function findModel($url)
    {
        if (($shop = Shop::find()->with('field')->andWhere(['domen'=>$url, 'status' => 1, 'active' => 1])->one()) !== null) {
			
			
							//Получаем мета теги
	$arr['meta'] = Yii::$app->userFunctions->metaOne($shop->category, $shop->region, $shop->name, 'shop');


     //Достаем количество комментариев
	$query = ShopComment::find()->andWhere(['blog_id' => $shop->id, 'status' => 1])->orderBy(['id' => SORT_DESC]);  
	$arr['count_com'] = $query->count();

	   //Манипуляции с графиком
	     $shop->grafik = array();
	     $grafik_post = explode(' | ',$shop->field->grafik);
		  foreach($grafik_post as $key => $res) {
			  if ($res == 'False') {
			       $shop->grafik[$key]['vih'] = 1;
			  }else{
				   $obed = explode(' && ', $res); //Обед

				    if(isset($obed[1]) && $obed[1] != 'obed_none') {
                        $obed_arr =  explode(' - ',$obed[1]);
						$shop->grafik[$key]['obed'] = $obed_arr[0].' - '.$obed_arr[1];
					}
					$days = explode(' && ', $res);
					$days = explode(' - ', $days[0]); 
					if(isset($days[1])) {
				       $shop->grafik[$key]['time'] = $days[0].' - '.$days[1];
				   }
			  }
		  }

 //Проверяем, голосовал ли юзер  
	if(@unserialize(Yii::$app->request->cookies['votes']->value)) {
    if(in_array($id, unserialize(Yii::$app->request->cookies['votes']))) {	
	         $vote = true;
	      }else{
		    $vote = true;
	      }
     }elseif(isset(Yii::$app->request->cookies['votes']) && Yii::$app->request->cookies['votes']->value == $shop->id){
       $vote = true;
     }else{
	   $vote = false;
     }
	$arr['vote'] = $vote;
	$arr['rates'] = Yii::$app->caches->rates();
	$arr['notepad'] = Yii::$app->userFunctions->notepadArr();

    $query_art = Article::find()->andWhere(['user_id' => $shop->user_id, 'status' => 1]);
	$arr['count_art'] = $query_art->count();
	$arr['model'] =	$shop;
            return $arr;
          
        }

        throw new NotFoundHttpException('Такого магазина не существует');
    }
	
	
	
	
	
	
	
	//Страница Статьи
	public function actionArticleone($id, $url)
    {
        $arr = $this->findModel($url);
		$this->layout = 'shop';
	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];

      
		$this->layout = 'shop';
	    $art = $this->findArticle($id, $shop->user_id);


 
		//Получаем мета теги
	   $meta = Yii::$app->userFunctions->metaOneart($art['cats']['id'], $art->title, 'article');


	 //Добавление комментария
        $comment_add = new ArticleComment();
		$comment_save = '';
		
		
	//Сохраняем	Комментарий ----------------------------------------------------------------------------------
     if ($comment_add->load(Yii::$app->request->post())) {
		  $comment_add->blog_id = $art->id;
		  $comment_add->date = date('Y-m-d H:i:s');
		  $comment_add->status = Yii::$app->caches->setting()['article_moder'];
	      if ($comment_add->too) {
			  $comment_add->text = preg_replace('/^.+\n/', '', $comment_add->text);
		  }
		  
		  //Добавляем данные юзера, если он зарегистрирован
		  if (!Yii::$app->user->isGuest) {
			 $comment_add->user_id = Yii::$app->user->id;
			 $comment_add->user_email = Yii::$app->user->identity['email'];
		     $comment_add->user_name = Yii::$app->user->identity['username'];
		  }
          
          $comment_add->save();
		  $comment_save = true;
		  
		  
		  
		  	  	$user_c = User::findOne($shop->user_id);

			    if(!isset($user_c->alert['comment']) || (isset($user_c->alert['comment']) && $user_c->alert['comment'] == 1)) {
					$url = Url::to(['/articleone', 'id'=>$art->id]);
			         Yii::$app->functionMail->comment_article($user_c, $url);
			    }
    }
	//Сохранили	Комментарий ----------------------------------------------------------------------------------		 
	$query = ArticleComment::find()->andWhere(['blog_id' => $art->id, 'status' => 1])->orderBy(['id' => SORT_DESC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
	$comment = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
		  

         $like = Yii::$app->userFunctions->likeArr();
         if(isset($like[$art->id])){
			 $like = true;
		 }else{
			 $like = '';
		 }
          return $this->render('articleone', compact('art','pages', 'meta', 'comment', 'comment_add', 'comment_save', 'count_com', 'vote', 'like',
		  'blogs', 'pages', 'rates', 'notepad', 'metaCat','shop', 'price', 'blog', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'count_com', 'vote', 'count_art'
		  ));
	 

     }
	
	
	
	
	
	
	public function actionLike($id)
    {
	$arr = unserialize(Yii::$app->request->cookies['like']);
	 if ($arr) {
	    $search = array_search($id, $arr);

        if ($search){
             unset($arr[$search]);
			  $re = 0;
        }else{
	    	 array_push($arr, $id);
	          $re = 1;
	    }
	 }else{
		 $arr[1] = $id;
		 $re = 1;
	 }
		
	    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'like',
                        'value' =>  serialize($arr)
                        ]));
		
		if ($re) {
              $model = $this->findLike($id);
	      if($re == 1) {  	
              $model->rayting = $model->rayting + 1;
			  $model->save(false);	
			  return 1;
	      }elseif($re == 0){
              $model->rayting = $model->rayting - 1;
			  if($model->rayting >= 0) {
			     $model->save(false);	
			  }
			  return 0;	
		  }	  
		}else{
			  $model = $this->findLike($id);
              $model->rayting = $model->rayting - 1;
			    if($model->rayting >= 0) {
			     $model->save(false);	
			  }
			  return 0;
		  }		
         	  
	}
	
	
	
	
	public function actionDelivery($url)
    {
		
    	$arr = $this->findModel($url);
		$this->layout = 'shop';
	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];


	$this->layout = 'shop';

	$rates = Yii::$app->caches->rates();

    return $this->render('delivery', compact('shop', 'price', 'comment', 'vote', 'count_art'));
		
	}

		public function actionPayment($url)
    {
		
    	$arr = $this->findModel($url);
		$this->layout = 'shop';
	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];


	$this->layout = 'shop';

	$rates = Yii::$app->caches->rates();

    return $this->render('payment', compact('shop', 'price', 'comment', 'vote', 'count_art'));
		
	}
	
	
	
	
	
	
	
	public function actionContact($url)
    {
		
    	$arr = $this->findModel($url);
		$this->layout = 'shop';
	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];


	$this->layout = 'shop';

	$rates = Yii::$app->caches->rates();
	 
	 
	   $model = new Contshopperson();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
          	
			Yii::$app->functionMail->personal($shop->user_id, $model->name, $model->email, $model->text, PROTOCOL.$_SERVER['HTTP_HOST']);
			$form_ok = true;
        }


	 
	 
	
	
	
	

    return $this->render('contact', compact('model', 'shop', 'form_ok', 'price', 'comment', 'vote', 'count_art'));
		
	}
	
	public function actionMaps($coord)
    { 
	     return $this->render('maps', compact('coord'));
	}
	
	
	

	
	
	
	//Карта сайта
	public function actionSitemap($url)
    {
    $arr = $this->findModel($url);
	$shop = $arr['model'];
	
	
if(Yii::$app->request->get('act') == 'all') {
	    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
	    $act = 'all';
		return $this->render('sitemap', compact('blogs', 'act', 'pages', 'counter'));
		
}elseif(Yii::$app->request->get('act') == 'article') {		

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$articles = Article::find()->Where(['user_id' => $shop->user_id, 'status' => 1])->orderBy(['id' => SORT_DESC])->all();
		$act = 'article';
		return $this->render('sitemap', compact('act', 'articles'));
		
		
		
}elseif(Yii::$app->request->get('act') == 'static') {		

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$act = 'static';
		
		return $this->render('sitemap', compact('act', 'shop'));
		
		
}elseif(Yii::$app->request->get('act') == 'category') {
	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
	    $blog = Blog::find()->Where(['user_id' => $shop->user_id, 'status_id' => 1, 'auction' => 0, 'active' => 1])->orderBy(['id' => SORT_DESC])->all();
		foreach($blog as $blogone) {
			$cat_arr[] = $blogone->category;
		}
		$catarr= Category::find()->Where(['id' => $cat_arr])->asArray()->all();
		foreach($catarr as $cat) {
           $categors[] = Yii::$app->userFunctions->linens(Yii::$app->caches->category(), $cat['id']);
		}
		

	    $category[] = Category::find()->Where(['id' => array_unique (call_user_func_array('array_merge', $categors))])->asArray()->all();

	    $category = call_user_func_array('array_merge', $category);
		$act = 'category';
       return $this->render('sitemap', compact('act', 'category'));
		
}else{

		Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
    	$query = Blog::find()->andWhere(['user_id' => $shop->user_id, 'status_id' => 1, 'auction' => 0, 'active' => 1])->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 1000]);
		$counter = $query->count();
	    $blogs = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

		return $this->render('sitemap', compact('blogs', 'pages', 'counter'));
}

		
    }
	
	








    public function actionRss($url)
    {

	$arr = $this->findModel($url);
	$shop = $arr['model'];
	
	if(!Yii::$app->request->get('act')) {
	$blog = Blog::find()->andWhere(['user_id'=>$shop->user_id,'status_id' => 1, 'auction' => 0, 'active' => 1])->orderBy(['id' => SORT_DESC])->limit(20)->all();
	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$act = 'board';
		return $this->render('rss', compact('blog', 'act', 'shop'));
	}elseif(Yii::$app->request->get('act') == 'article'){
		
	$article = Article::find()->andWhere(['user_id'=>$shop->user_id,'status' => 1])->orderBy(['id' => SORT_DESC])->limit(20)->all();
	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$act = 'article';
		return $this->render('rss', compact('article', 'act', 'shop'));
	}else{
		exit();
	}
    }
	
	

  public function actionCaradd($id, $act, $price, $note = false, $count = false)
    {

     //Получить куку
  $arr = unserialize(Yii::$app->request->cookies['car']);
  $arr_note = unserialize(Yii::$app->request->cookies['arr_note']);
  if($act == 'plus') {
	 
    if(isset($arr[$id])) {
			$arr[$id] = array('count'=>$count, 'price'=>$price);
	}else{
		    $arr[$id] = array('count'=>$count, 'price'=>$price);
	}
	if($note) {
        $arr_note[$id] = $note; 
	}else{
		unset($arr_note[$id]);
	}
	
  }else{
	if(isset($arr[$id]) && $arr[$id] > 0) {
			$arr[$id] = $arr[$id]['count']-$count; 
	}
	if(isset($arr[$id]) && $arr[$id] == 0) {
		unset($arr_note[$id]);
		unset($arr[$id]);
	}
	
	

  }
	Yii::$app->response->cookies->add(new \yii\web\Cookie([
      'name' => 'car',
      'value' =>  serialize($arr)
    ]));	
	
	
	Yii::$app->response->cookies->add(new \yii\web\Cookie([
      'name' => 'arr_note',
      'value' =>  serialize($arr_note)
    ]));
	

  $this->layout = false;
	}


	protected function findArticle($id, $user_id)
    {
        if (($model = Article::find()->andWhere(['id'=>$id, 'user_id' => $user_id])->one()) !== null) {
            return $model;
        }

            throw new NotFoundHttpException('Такой статьи не существует или она еще не активирована');
    }



    protected function findLike($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



  public function actionCardel($id)
    {
	  $arr_note = unserialize(Yii::$app->request->cookies['arr_note']);
	  $arr = unserialize(Yii::$app->request->cookies['car']);	
	  unset($arr[$id]);
	  unset($arr_note[$id]['count']);
	  
	Yii::$app->response->cookies->add(new \yii\web\Cookie([
      'name' => 'arr_note',
      'value' =>  serialize($arr_note)
    ]));
	 Yii::$app->response->cookies->add(new \yii\web\Cookie([
      'name' => 'car',
      'value' =>  serialize($arr)
    ]));	
	}



  public function actionCaredite($id, $count)
    {
	if($count < 0) { return ;} 
     //Получить куку
  $arr = unserialize(Yii::$app->request->cookies['car']);
  $arr[$id]['count'] = $count;
  
  	if(isset($arr[$id]) && $arr[$id] == 0) {
		unset($arr[$id]);
	}
  
  
	Yii::$app->response->cookies->add(new \yii\web\Cookie([
      'name' => 'car',
      'value' =>  serialize($arr)
    ]));	
	

  $this->layout = false;
	}

	public function actionOnline()
    {
		if(Yii::$app->user->id) {
			$id_user = Yii::$app->user->id;
		}elseif(Yii::$app->request->cookies['chat']){
			$id_user = Yii::$app->request->cookies['chat']->value;
		}else{
            throw new NotFoundHttpException('The requested page does not exist.');
		}

		 Online::deleteAll(['<','date', date('Y-m-d H:i:s', strtotime('-3 minutes'))]);
		 Online::deleteAll(['user_id' => $id_user]);
		 $model = New Online();
         $model->user_id = $id_user;
		 $model->date = date('Y-m-d H:i:s');
		 $model->save(false);
		 if(Yii::$app->user->id) {
		    $user = User::findOne($id_user);
		    $user->online = date('Y-m-d H:i:s');
		    $user->save(false);
		 }
		 
		$query = Message::find()->Where(['u_too'=>$id_user, 'flag' => 1]);
		$arr['chat']['count'] = $query->count();
		return json_encode($arr);
	}



	public function actionTranslite()
    { 
	   $domenss = getenv("HTTP_HOST");	
       $domens = $this->ExtractDomain($domenss);
       setcookie ("googtrans", "", time()-60, "/", $domens);
       setcookie ("googtrans", "", time()-60, "/", ""); 
	   @Yii::$app->response->redirect('/')->send();
	}
	
	
	
	
	

	
	public function actionOrder($id)
    {
		$save = false;
        $model = new Orders();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
           
			$blog = Blog::findOne($id);
	
			 $order = $this->findOrder($blog->id, $model->email);
			if (!$order) {
			  $model->user_id = $blog->user_id;
			  $model->data = date('Y-m-d H:i:s');
			  $model->board_id = $blog->id;
			  $model->save();
              $save = true;
			  
              $link = Url::to(['/boardone', 'id'=>$blog->id]);
			  Yii::$app->functionMail->order_pred($blog->author, $link, $model->colvo, $model->name, $model->email, $model->phone, $model->text );
			  
			}else{
				$save = 'duble';
			}
        }
		$this->layout = 'style_none';
        return $this->render('order', compact('model', 'save'));
	}
	
	
	

	
	
	
	
	

    protected function findReating($id)
    {
        if (($model = ShopReating::find()->where(['shop_id' => $id])->one()) !== null) {
            return $model;
        }

        return false;
    }
	

    protected function findOrder($board_id, $email)
    {
        if (($model = Orders::find()->where(['board_id' => $board_id, 'email' => $email])->one()) !== null) {
            return $model;
        }

        return false;
    }
	
	   protected function findShop($id)
    {
        if (($model = Shop::findOne($id)) !== null) {
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


public function actions()
{
    return [
            'cart' => [
                'class' => 'frontend\modules\shop\action\Cart',
            ],
			
			
    ];
}




	public function check_mobile_device() { 
    $mobile_agent_array = array('ipad', 'iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);    
    // var_dump($agent);exit;
    foreach ($mobile_agent_array as $value) {    
        if (strpos($agent, $value) !== false) return true;   
    }       
    return false; 
    }
	
	
	
	
	public function actionTimeruser($id, $kod)
    { 

		$kod=urldecode($kod);
		  $this->layout = 'style_none';
		  Yii::$app->getModule('debug')->instance->allowedIPs = [];
		  return $this->render('timer_user', compact('kod', 'id'));
	 }		
	
	
	
		public function FieldSearch($id) {
 $cat = Yii::$app->request->get('cat');
//Достаем массив категорий, к которым пренадлежит переданная категорий в переменрой $id потом достать все поля этих категорий
$cat_array = Yii::$app->userFunctions->linens(Yii::$app->caches->category(),$id);
array_unshift($cat_array, "0");

//Выбираем поля фильтра всех категорий, которым преналдежит $id
$customers = Field::find()->where(['cat' => $cat_array])->andWhere(['block' => 1])->orderBy(['sort' => SORT_ASC])->all();

foreach ($customers as $rows) {
	if ($rows['type'] == 'p') {
      $rate = true;
	}
}

return $customers;
/*if ($rate) {
$rates = Rates::find()->all();		
}

  $model = $customers;
   return $this->render('field', [
            'model' => $model,
			'rates' => $rates,
			
   ]);
*/
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	 //Страница с регионами
   public function actionRegParent($id)
    { 
		$array = Yii::$app->userFunctions->regionMainAjax($id);
		if (!isset(Yii::$app->caches->Region()[$id]['parent'])) {$cach_region = '';}else{$cach_region = Yii::$app->caches->Region()[$id]['parent'];}
		if ($cach_region) {
		  $ids = Yii::$app->caches->Region()[intval($id)]['parent'];	
		  $id_one = Yii::$app->caches->Region()[intval($id)]['id'];
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
				 $id_one = @Yii::$app->caches->Region()[intval($id)]['id']; 
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
	
	
	
	
	public function actionRegsearch($text)
    { 
      $result = Region::find()->andFilterWhere(['like', 'name', $text])->asArray()->Limit('10')->all();
	  return $this->render('regsearch', compact('result'));
	}
	
	
	public function actionLoginpop($redirect = '/user')
    { 
	
	 $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
			//Кука для безопасности при смене пароля
			 Yii::$app->response->cookies->add(new \yii\web\Cookie([
                 'name' => 'auth_key',
				 'domain' => '.'.DOMAIN,
                 'value' => Yii::$app->user->identity->auth_key
              ]));
			  
			$redirect = str_replace('https://'.$_SERVER['HTTP_HOST'],'', $redirect);
            return Yii::$app->response->redirect([$redirect]);
        }else{
            $model->password = '';
            $this->layout = 'style_none';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
	
	}
	
	
	
		
	public function actionSignuppop()
    { 
     $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Спасибо за регистрацию. Пожалуйста, проверьте свой почтовый ящик для подтверждения по электронной почте, если письмо не пришло, проверьте папку спам.');
            return Yii::$app->response->redirect(['user']);
        }
		  $this->layout = 'style_none';
        return $this->render('signup', [
            'model' => $model,
        ]);
	
	}

}
