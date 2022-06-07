<?php

namespace frontend\controllers;
use \yii\base\DynamicModel;
use Yii;
use frontend\models\Blog;
use common\models\Field;
use common\models\BlogField;
use common\models\Category;
use common\models\User;
use common\models\RegionCase;
use common\models\Rates;
use frontend\models\BlogComment;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use yii\helpers\Url;
/**
 * BlogController
 */
class AuctionController extends Controller
{
	


//Список объявлений на главной
	
    public function actionIndex($patch)
    {


	   //Получаем список категорий на странице выбранной категории
	  

		//Список меню для категорий
		
		$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		$array = [];
		
		
		
		$query = Category::find()->where(['parent' => 0])->orderBy('sort');
	    $pages_cat = new Pagination(['totalCount' => $query->count(), 'pageSize' => 15]);
	    $cat_arr = $query->offset($pages_cat->offset)->limit($pages_cat->limit)->all();
		
	
		$id_region = Yii::$app->request->cookies['region'];
		$region = $id_region;
		if($id_region) {
		    $region_arr =  Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), $id_region);
		}
		foreach($cat_arr as $res) {
           if($id_region) {
			   $count = Yii::$app->userFunctions3->counterboard2(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id']), $region_arr);
		   }else{
			   $count = Yii::$app->userFunctions3->counterboard2(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id']), '');
		   }
			$array[$res['id']] = array('id' => $res['id'], 'name' => $res['name'], 'url' =>  $patch_url.'/'.$patch.'/'.$res['url'], 'image' => $res['image'], 'count'=>$count, $res['id']);
		}
		$cat_menu = $array;
	
		
		
		
		
		
		
		

       	$get = Yii::$app->request->get();
	  
	    //Если регион не выбран, меняем параметры без региона 
	if ($region && (!isset($get['coord']) || $get['coord'] == '')) {
	   $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region->value);
       $params = ['status_id'=>1, 'blog.active'=>1, 'blog.region' => $reg_all];
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
      $sql->LeftJoin('blog_coord coord','coord.blog_id = blog.id');
	  $sql->OrFilterWhere(['like', 'coord.text', $get['text']]);
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
	        ->with('imageBlog')->with('regions')->with('categorys');
			$sql->andWhere($params);

			
			  //Для модератора
			      if(Yii::$app->user->can('updateBoard')) {
				      $sql->orWhere(['blog.status_id'=>0]);
					  $sql->orWhere(['blog.active'=>0]);
				  }
			
	        $sql->groupBy('blog.id')
	        ->orderBy('CAST(blog_field.value AS SIGNED) '.$sort);
	     }else{
			 


             $query = $sql->andWhere($params);
			 
			

			 
			   //Для модератора
			      if(Yii::$app->user->can('updateBoard')) {
				
				      $sql->orWhere(['blog.status_id'=>0]);
					  $sql->orWhere(['blog.active'=>0]);
				  }
				   //---------------Обновление координаты------------------//
			 $sql->orderBy([new \yii\db\Expression('FIELD (blog.id, '.$top.') DESC'), 'date_add' => SORT_DESC,]);
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


 //---------------Обноаление координаты------------------//

    if(isset($get['text'])) {
      $sql->OrWhere(['blog.id' => $get['text']]);
	}
    $sql->andWhere(['!=','blog.auction', '0']);	
	$query = $sql->groupBy('blog.id'); 

    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$blogs = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
	$field = Yii::$app->userFunctions->fieldSearch(0);
      foreach ($field as $field) {
	    $fields[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
    }
	 
	 

    foreach(Yii::$app->caches->rates() as $res) {
		if ($res['def']) {
			$valute = $res['text'];
		}
	}
	$rates = Yii::$app->caches->rates();
	//Записываю параметры для вида (title, keywords,description)

	$metaCat = Yii::$app->userFunctions3->metaCat(false,  $region);
	$notepad = Yii::$app->userFunctions->notepadArr();
	$category_text = '';
	
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
	
	
	  if(Yii::$app->request->cookies['style'] == 'grid') {
		return $this->render('all_block', compact('blogs', 'pages', 'metaCat', 'pages_cat' , 'cat_menu', 'price', 'rates', 'valute', 'notepad', 'category_text', 'category', 'cat_all', 'reg_all', 'fields'));
	  }else{
		return $this->render('all', compact('blogs', 'pages', 'metaCat', 'pages_cat' , 'cat_menu', 'price', 'rates', 'valute', 'notepad', 'category_text', 'category', 'cat_all', 'reg_all', 'fields'));
	  }
        
     }
	
	
	

	
	


/*--------------------------------------------------------------------------------------------------------------------------------------------*/





 public function actionCategory($category, $patch)
    {


		//Получаем всех родителей последней категории в url и передаем в выборку, в итоге получаем в корневой категории объявления потомков
	    $cat_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $category);
	   //Получаем список категорий на странице выбранной категории
	  

		//Список меню для категорий
		
		$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		$array = [];
		
		
		
		$query = Category::find()->where(['parent' => $category])->orderBy('sort');
	    $pages_cat = new Pagination(['totalCount' => $query->count(), 'pageSize' => 15]);
	    $cat_arr = $query->offset($pages_cat->offset)->limit($pages_cat->limit)->all();
		
		
		$id_region = Yii::$app->request->cookies['region'];
		$region = $id_region;
		if($id_region) {
		    $region_arr =  Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), $id_region);
		}
		foreach($cat_arr as $res) {
           if($id_region) {
			   $count = Yii::$app->userFunctions3->counterboard2(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id']), $region_arr);
		   }else{
			   $count = Yii::$app->userFunctions3->counterboard2(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id']), '');
		   }
			$array[$res['id']] = array('id' => $res['id'], 'name' => $res['name'], 'url' =>  $patch_url.'/'.$patch.'/'.$res['url'], 'image' => $res['image'], 'count'=>$count, $res['id']);
		}
		$cat_menu = $array;
		
		
		
		
		
		
		
		

       	$get = Yii::$app->request->get();
	  
	    //Если регион не выбран, меняем параметры без региона 
	if ($region && (!isset($get['coord']) || $get['coord'] == '')) {
	   $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region->value);
       $params = ['status_id'=>1, 'blog.active'=>1, 'blog.category'=>$cat_all, 'blog.region' => $reg_all];
	}else{
	   $params = ['status_id'=>1, 'blog.active'=>1, 'blog.category'=>$cat_all];
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
      $sql->LeftJoin('blog_coord coord','coord.blog_id = blog.id');
	  $sql->OrFilterWhere(['like', 'coord.text', $get['text']]);
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
	        ->with('imageBlog')->with('regions')->with('categorys');
			$sql->andWhere($params);

			
			  //Для модератора
			      if(Yii::$app->user->can('updateBoard')) {
				      $sql->orWhere(['blog.status_id'=>0]);
					  $sql->orWhere(['blog.active'=>0]);
				  }
			
	        $sql->groupBy('blog.id')
	        ->orderBy('CAST(blog_field.value AS SIGNED) '.$sort);
	     }else{
			 


             $query = $sql->andWhere($params);
			 
			

			 
			   //Для модератора
			      if(Yii::$app->user->can('updateBoard')) {
				
				      $sql->orWhere(['blog.status_id'=>0]);
					  $sql->orWhere(['blog.active'=>0]);
				  }
				   //---------------Обновление координаты------------------//
			 $sql->orderBy([new \yii\db\Expression('FIELD (blog.id, '.$top.') DESC'), 'date_add' => SORT_DESC,]);
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


 //---------------Обноаление координаты------------------//

    if(isset($get['text'])) {
      $sql->OrWhere(['blog.id' => $get['text']]);
	}
 $sql->andWhere(['!=','blog.auction', '0']);	
	$query = $sql->groupBy('blog.id'); 

    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$blogs = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
	$field = Yii::$app->userFunctions->fieldSearch($category);
      foreach ($field as $field) {
	    $fields[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
    }
	 
	 

    foreach(Yii::$app->caches->rates() as $res) {
		if ($res['def']) {
			$valute = $res['text'];
		}
	}
	$rates = Yii::$app->caches->rates();
	//Записываю параметры для вида (title, keywords,description)

	$metaCat = Yii::$app->userFunctions3->metaCat($category, $region);
	$notepad = Yii::$app->userFunctions->notepadArr();
	$category_text = Yii::$app->caches->category()[$category]['text'];
	
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
	
	
	  if(Yii::$app->request->cookies['style'] == 'grid') {
		return $this->render('all_block', compact('blogs', 'pages', 'metaCat', 'pages_cat' , 'cat_menu', 'price', 'rates', 'valute', 'notepad', 'category_text', 'category', 'cat_all', 'reg_all', 'fields'));
	  }else{
		return $this->render('all', compact('blogs', 'pages', 'metaCat', 'pages_cat' , 'cat_menu', 'price', 'rates', 'valute', 'notepad', 'category_text', 'category', 'cat_all', 'reg_all', 'fields'));
	  }
   	}
	







/*------------------------------------------------------------------------------------------*/ /*------------------------------------------------------------------------------------------*/ /*------------------------------------------------------------------------------------------*/ 








    public function actionNotepad()
    {
	$arr = Yii::$app->userFunctions->notepadArr();
    $query = Blog::find()->with('blogField')->with('imageBlog')->andWhere(['id' => $arr, 'status_id'=>1, 'blog.active'=>1])->orderBy('date_add desc');
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
    return $this->render('all', compact('blogs', 'pages', 'price', 'rates', 'notepad', 'metaCat'));
	}
    
	
	
/*--------------------------------------------------------------------------------------------------------------------------------------------*/	
	
	
	
      public function actionSearch()
    {
		
	$get = Yii::$app->request->get();
	 
	$text = Yii::$app->request->get('text');
	if ($text) {
	$text = Html::encode($text);
	}
	if(isset(Yii::$app->request->cookies['region'])) {
	$region = Yii::$app->request->cookies['region']->value;
	}

	if (isset($region) && (!isset($get['coord']) || $get['coord'] == '')) {
	   $dop_h1 = ', в регионе ('. Yii::$app->caches->region()[$region]['name'].')';
	   $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region);
       $params = ['status_id'=>1, 'blog.active'=>1, 'region' => $reg_all];
	}else{

	   $params = ['status_id'=>1, 'blog.active'=>1];
	   $dop_h1 = '';
	}

    $query = Blog::find()->with('blogField')->with('imageBlog')
	->andFilterWhere(['like', 'title', $text]);
	
	$query->LeftJoin('blog_coord coord','coord.blog_id = blog.id');
	
	
	if(isset($get['coord']) && $get['coord'] > 0) {

	  $coord = explode(',',$get['coord']);

	    $query->andWhere(['<','6371 * acos (
        cos ( radians('.$coord[0].') )
        * cos( radians( coord.coordlat ) )
        * cos( radians( coord.coordlon ) - radians('.$coord[1].') )
        + sin ( radians('.$coord[0].') )
        * sin( radians( coord.coordlat ) ))', $get['radius']]);
	 }
	
	
	$query->OrFilterWhere(['like', 'coord.text', $text])
	
	->andWhere($params)
	->OrWhere(['blog.id' => $get['text']])
	->orderBy('date_add desc');
	$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
    $notepad = Yii::$app->userFunctions->notepadArr();
	$blogs = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $price = $res['id'];
	   }
	}
	$rates = Yii::$app->caches->rates();



	 $metaCat['title'] = 'Поиск на сайте "'.Yii::$app->caches->setting()['title-site'].'"';
	 if($text) {
     $metaCat['h1'] = 'Поиск по запросу "'.$text.'"'. $dop_h1;
	 }else{
	 $metaCat['h1'] = 'Пустой поисковый запрос'; 
	 }
	 $metaCat['keywords'] = 'Поиск на сайте "'.Yii::$app->caches->setting()['title-site'].'"';
	 $metaCat['description'] = 'Поиск на сайте "'.Yii::$app->caches->setting()['title-site'].'"';
	 $metaCat['breadcrumbs'][] = 'Поиск';
	 $search = $text;
    return $this->render('all', compact('blogs', 'pages', 'price', 'rates', 'notepad', 'metaCat', 'search'));
	}






/*
    public function actionAuctions()
    {
		
	$get = Yii::$app->request->get();
	 
	$text = Yii::$app->request->get('text');
	if ($text) {
	$text = Html::encode($text);
	}
	if(isset(Yii::$app->request->cookies['region'])) {
	$region = Yii::$app->request->cookies['region']->value;
	}

	if (isset($region)) {
	   $dop_h1 = ', в регионе ('. Yii::$app->caches->region()[$region]['name'].')';
	   $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region);
       $params = ['blog.status_id'=>1, 'blog.active'=>1,'blog.auction'=>1, 'region' => $reg_all];
	   $params2 = ['blog.status_id'=>1, 'blog.active'=>1, 'blog.auction'=>2, 'region' => $reg_all];
	}else{

	   $params = ['blog.status_id'=>1, 'blog.active'=>1,'blog.auction'=>1];
	   $params2 = ['blog.status_id'=>1, 'blog.active'=>1,'blog.auction'=>2,];
	   $dop_h1 = '';
	}

	  


    $query = Blog::find()->with('blogField')->with('imageBlog');

	
	
	$query->andWhere($params);
	$query->orWhere($params2);

	

	$query->orderBy('date_add desc');
	$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
    $notepad = Yii::$app->userFunctions->notepadArr();
	$blogs = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $price = $res['id'];
	   }
	}
	$rates = Yii::$app->caches->rates();



	 $metaCat['title'] = 'Поиск на сайте "'.Yii::$app->caches->setting()['title-site'].'"';
     $metaCat['h1'] = 'Торги'; 
	 $metaCat['keywords'] = 'Торги на сайте "'.Yii::$app->caches->setting()['title-site'].'"';
	 $metaCat['description'] = 'Торги  на сайте "'.Yii::$app->caches->setting()['title-site'].'"';
	 $metaCat['breadcrumbs'][] = 'Торги ';
	 $search = $text;
    return $this->render('all', compact('blogs', 'pages', 'price', 'rates', 'notepad', 'metaCat', 'search'));
	}
*/
/*--------------------------------------------------------------------------------------------------------------------------------------------*/	
	
	
	
public function actions()
{
    return [
            'add' => [
                'class' => 'frontend\actions\BlogAdd',
            ],
			
			'update' => [
                'class' => 'frontend\actions\BlogUpdate',
            ],
			
			'del' => [
                'class' => 'frontend\actions\BlogDel',
            ],
			
			'users' => [
                'class' => 'frontend\actions\BlogUser',
            ],
    ];
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
	
	
	
	
	
	
	
	
	
	
	public function check_mobile_device() { 
    $mobile_agent_array = array('ipad', 'iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);    
    // var_dump($agent);exit;
    foreach ($mobile_agent_array as $value) {    
        if (strpos($agent, $value) !== false) return true;   
    }       
    return false; 
}
}