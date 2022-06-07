<?php

namespace frontend\modules\map\controllers;
use Yii;
use yii\web\Controller;
use frontend\models\Blog;
use common\models\BlogField;
use common\models\BlogCoord;
use common\models\Category;
use common\models\RegionCase;
use common\models\Rates;
use frontend\models\BlogComment;
use yii\helpers\Html;

use yii\web\NotFoundHttpException;
use yii\data\Pagination;
/**
 * Default controller for the `map` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
//Список объявлений на главной
	
    public function actionIndex()
    {
      return $this->render('main', compact('blogs', 'cat_main', 'meta', 'price', 'rates'));
    }
	 
	


  public function actionAll($array1 = false, $array2 = false,  $array3 = false,  $array4 = false)
  {


  if(!$array1) {
	   $this->layout = 'style_none';
	   $blogs = '';
	   return $this->render('all', compact('blogs', 'rates', 'notepad', 'price', 'pages',  'fields', 'user_id'));
  }



    $sql = Blog::find();
	$sql->leftJoin('blog_coord', 'blog_coord.blog_id = blog.id');
	//$sql->andWhere(['blog.status_id' => 1, 'blog.active' => 1]);
	//$sql->andWhere(['BETWEEN','blog_coord.coordlat', $array1, $array3]);
    //$sql->andWhere(['BETWEEN','blog_coord.coordlon', 98.032406, 100.032406]);
	$sql->andWhere('blog_coord.coordlat BETWEEN '.$array1.' AND '.$array3.'');
	$sql->andWhere('blog_coord.coordlon BETWEEN '.$array2.' AND '.$array4.'');
	
	$sql->andWhere(['auction' => 0]);
	
	$query = $sql;
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
	$this->layout = 'style_none';


    return $this->render('all', compact('blogs', 'rates', 'price', 'pages',  'fields', 'user_id'));

}	
	 
	 
	 
	 
	 
	//Страница объявления
	public function actionOne($id)
    {
   if ($blog = Blog::find()->with('blogField.fields')->andWhere(['id'=>$id])->one()){
	$blog->views = $blog->views+1;   
	$blog->update(false);




	      $rates = Yii::$app->caches->rates();
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
		 
 
	 
	$query = BlogComment::find()->andWhere(['blog_id' => $blog->id, 'status' => 1])->orderBy(['id' => SORT_DESC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
	$comment = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
	$querysimilar = Blog::find()->andWhere(['status_id' => 1, 'active' => 1, 'auction' => 0])
	->andFilterWhere(['like', 'title', $blog->title])->andWhere(['not in', 'id', $blog->id])
	->orWhere(['category' => $blog->category])->andWhere(['not in', 'id', $blog->id])->andWhere(['status_id' => 1, 'active' => 1])
	->orderBy(['id' => SORT_DESC]);
    $pagessimilar = new Pagination(['totalCount' => $querysimilar->count(), 'pageSize' => 4]);
	$blogssimilar = $querysimilar->offset($pagessimilar->offset)
    ->limit($pagessimilar->limit)
    ->all();
	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $pricesimilar = $res['id'];
	   }
	}
	$ratessimilar = Yii::$app->caches->rates();
	$notepadsimilar = Yii::$app->userFunctions->notepadArr();
		 $this->layout = 'style_none';
          return $this->render('one', compact('blog', 'notepadsimilar', 'pricesimilar', 'ratessimilar', 'meta', 'fields', 'price', 'notepad', 'coord', 'comment', 'comment_add', 'comment_save', 'pages', 'blogssimilar', 'pagessimilar'));
	
    }
	throw new NotFoundHttpException('Такого бъявления нет или оно находится на модерации');
}
	
	 
	 
	 
	 
	 
	 
	 

public function actionCoord()
    {
		
	$get = Yii::$app->request->get();

	if(isset($get['category'])) {
		//Получаем всех родителей последней категории в url и передаем в выборку, в итоге получаем в корневой категории объявления потомков
	    $cat_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), (int)$get['category']);
	   //Получаем список категорий на странице выбранной категории

	}  

	
	if(isset($get['region'])){
	   $region = $get['region'];
	}	



	if(isset($get['category'])) {
	
	   if (isset($region)) {
	     $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region);
         $params = ['status_id'=>1, 'blog.active'=>1, 'category'=>$cat_all, 'region' => $reg_all, 'auction' => 0];
	     
		 }else{
			
	     $params = ['status_id'=>1, 'blog.active'=>1, 'category'=>$cat_all, 'auction' => 0];
	   }

	}else{
	   if (isset($region)) {
	     $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region);
         $params = ['status_id'=>1, 'blog.active'=>1, 'region' => $reg_all, 'auction' => 0];
	     }else{
	     $params = ['status_id'=>1, 'blog.active'=>1, 'auction' => 0];
	   }
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


	  

	  
	$sql = Blog::find()->with('blogField')->with('imageBlog')->with('regions')->with('categorys')->with('coord');  
    $sql->LeftJoin('blog_coord coord','coord.blog_id = blog.id');
    $sql->andWhere(['>','coordlon', '0']);


	 if (!isset($search)) {

		 if ($sort) {
	        $query = $sql
	        ->LeftJoin('blog_field','blog_field.message = blog.id and blog_field.field = '.$price.'')
	        ->with('imageBlog')->with('regions')->with('categorys')->andWhere($params);
			
	

	        $sql->groupBy('blog.id')
	        ->orderBy('CAST(blog_field.value AS SIGNED) '.$sort);
	     }else{

    if(isset($get['note'])) {
	   $arr = Yii::$app->userFunctions->notepadArr();
	   $note = ['blog.id'=> $arr];
	   $sql->andWhere($note);
	}
             $query = $sql->andWhere($params);
		
	     }
	
	 }
	

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
 
 
    if(isset($get['text'])) {
      $sql->andFilterWhere(['like', 'title', $get['text']]);
	}
	
	
	
	
	
	
	
	
	
	

	

	
	$query = $sql->limit(2000)->asArray()->all(); 
	//Показываем кол-во записей
	if(isset($get['count'])) {
	    return count($query);
	}
	
	
foreach($query as $res) {
	$coordin = array($res['coord']['coordlat'],$res['coord']['coordlon']);
	$arrs[] = [
	"type" => "Circle",
	"id"  => $res['id'],
	"geometry"  => ["type" => "Point", "coordinates" => $coordin],

 	];
}

 
 
    if(!isset($arrs)) {
	   $arrs = '';
	}
    $arrays = [
	   "type" => "FeatureCollection",
	   "features" =>  $arrs
	   ];






	   return  json_encode($arrays);

   	}
	
	
	
	
	  //Страница с регионами
   public function actionRegParent($id = 0, $parent = false)
    { 
	if(isset($id)) {
      $idorig = $id;
	}else{
		$idorig = '';
	}
	  if($parent == '1') {
		 $id = Yii::$app->caches->Region()[intval($id)]['parent'];
	  }
		$array = Yii::$app->userFunctions->regionMainAjax($id);
        if(isset(Yii::$app->caches->Region()[intval($id)]['id'])) {
		    if (!isset(Yii::$app->caches->Region()[Yii::$app->caches->Region()[intval($id)]['id']]['parent'])) {$cach_region = '';}else{$cach_region = Yii::$app->caches->Region()[$id]['parent'];}
        }else{
			$cach_region = '';
		}
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
			     $id_one = $id;
				 
			 }else{
				 $ids = @Yii::$app->caches->Region()[intval($id)]['parent'];
				 $id_one = $id; 
			 }
		}
	
		return $this->render('regparent', ['array' => $array, 'ids' => $ids, 'id_one' => $id_one, 'idorig' => $idorig]); 
     }
	


	
	  //Страница с регионами
   public function actionCatParent($id, $parent = false)
    { 
      if(isset($id)) {
        $idorig = $id;
	  }else{
		$idorig = '';
	  }
	  if($parent == '1') {
		 $id = Yii::$app->caches->Category()[intval($id)]['parent'];
	  }
		$array = $this->categoryMainAjax($id);
        if(isset(Yii::$app->caches->Category()[intval($id)]['id'])) {
		    if (!isset(Yii::$app->caches->Category()[Yii::$app->caches->Category()[intval($id)]['id']]['parent'])) {$cach_cat = '';}else{$cach_cat = Yii::$app->caches->Category()[$id]['parent'];}
        }else{
			$cach_cat = '';
		}
		if ($cach_cat) {
		  $ids = Yii::$app->caches->Category()[intval($id)]['parent'];	
		  $id_one = Yii::$app->caches->Category()[intval($id)]['id'];
		}else{
			  $ids = @Yii::$app->caches->Category()[Yii::$app->caches->Category()[intval($id)]['parent']]['parent'];
			      //Проверяем, конечный ли регион
				  foreach(Yii::$app->caches->Category() as $res) {
					  if ($res['parent'] == $id) {
						  $parent = $res['id'];
						  break;
					  }
				  }
				 if (!isset($parent)) {
				 $ids = @Yii::$app->caches->Category()[Yii::$app->caches->Category()[intval($id)]['parent']]['parent'];
			     $id_one = $id;
				 
			 }else{
				 $ids = @Yii::$app->caches->Category()[intval($id)]['parent'];
				 $id_one = $id; 
			 }
		}
		return $this->render('catparent', ['array' => $array, 'ids' => $ids, 'id_one' => $id_one, 'idorig' => $idorig]); 
     }
	 

	

	  //Страница c фильтром
     public function actionFiltr($id = 0)
    { 
	 $field = Yii::$app->userFunctions->fieldSearch($id);
      foreach ($field as $field) {
	    $fields[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
      }
      $rates = Yii::$app->caches->rates();
	  $this->layout = 'style_none';
	  return $this->render('cat_search', [
		'fields' => $fields,
		'rates' => $rates,
        ]); 
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


	
	
	public function categoryMainAjax($id) 
   {
	  
	  $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];	 
	  $arr = Yii::$app->caches->Category();
	  
        foreach(Yii::$app->caches->Category() as $res) {
			    $arr_child[$res['parent']] = array('id' => $res['id']);
		   }
	  foreach ($arr as $res) {
		  if ($res['parent'] == $id)   {
			 $child = '';
			 if (!isset($arr_child[$res['id']]['id'])) {$arr_child[$res['id']]['id'] = '';}
			 $child = $arr_child[$res['id']]['id'];
			  if(isset($res['img'])) {
				 $img = $res['img'];
			 }else{
				 $img = '';
			 }
			 
			if(isset($res['image'])) {
				 $img = $res['image'];
			 }else{
				 $img = '';
			 }
			  $array[] = array('id' => $res['id'], 'name' => $res['name'], 'children' => $child, 'img' => $img);
		  }
	  }

	if (!isset($array)) {
	
		$id = Yii::$app->caches->Category()[intval($id)]['parent'];
          foreach(Yii::$app->caches->Category() as $res) {
			    $arr_child[$res['parent']] = array('id' => $res['id']);
				 break;
		   }
		foreach ($arr as $res) {
		  if ($res['parent'] == $id)   {
			 $child = '';
			 if (!isset($arr_child[$res['id']]['id'])) {$arr_child[$res['id']]['id'] = '';}
			 $child = $arr_child[$res['id']]['id'];
			 if(isset($res['image'])) {
				 $img = $res['image'];
			 }else{
				 $img = '';
			 }
		
			 $array[] = array('id' => $res['id'], 'name' => $res['name'], 'children' => $child, 'img' => $img);
		  }
	    }
	}
	
	 
		return $array;
   }
}
