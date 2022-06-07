<?php
 
namespace common\components;

use yii\base\Component;
use yii\helpers\Html;
use common\models\Category;
use common\models\Subscription;
use yii\web\NotFoundHttpException;
use common\models\ExecutorBoard;
use common\models\Blog;
use common\models\MessageRoute;
use common\models\Message;
use common\models\Organization;
use common\models\Shop;
use common\models\Field;
use common\models\Passanger;
use yii;

class FunctionUser2 extends Component { 	






function myscandir($dir, $sort=0)
{
	$list = @scandir($dir, $sort);
	
	// если директории не существует
	if (!$list) return false;
	
	// удаляем . и .. (я думаю редко кто использует)
	if ($sort == 0) unset($list[0],$list[1]);
	else unset($list[count($list)-1], $list[count($list)-1]);
	return $list;
}


 function previewconfig($dir_name) {

	$dir = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
	$files = Yii::$app->userFunctions->myscandir($dir);
	if ($files ) {
		
	
foreach($files as $res) {
$str=strpos($res, "_");
$row=substr($res, 0, $str);	
$array[$row] = array('caption'=>$res, 'size' => filesize($dir.$res), 'key'=>$dir.$res);
}
	
		ksort($array);
	foreach($array as $res) {
	$files_arr[] = $res;	
	}

	    return $files_arr;
	}else{
		 return '';
	}
} 



//Выгрузка фото из временной папки
 function files($dir_name) {
	$dir = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
	$files = Yii::$app->userFunctions2->myscandir($dir);
	
	if ($files) {
	foreach($files as $key => $res) {
     $str=strpos($res, "_");
     $row=substr($res, 0, $str);
     $files_res[$key] = '/uploads/temp/images/board/'.$dir_name.'/maxi/'.$res;
	}
	ksort($files_res);
	
	foreach($files_res as $res) {
	$files_ress[] = $res;	
	}

	    return $files_ress;
	}else{
		 return '';
	}
	}


















/*Файл прайса*/
	
	
	 function previewconfigshopprice($dir_name) {

	$dir = Yii::getAlias('@images_temp').'/shop/'.$dir_name.'/file/';
	$files = Yii::$app->userFunctions->myscandir($dir);
	if ($files ) {
    foreach($files as $res) {
        $str=strpos($res, "_");
        $row=substr($res, 0, $str);	
        $array[$row] = array('caption'=>$res, 'size' => filesize($dir.$res), 'key'=>$dir.$res);
    }
		ksort($array);
	foreach($array as $res) {
	$files_arr[] = $res;	
	}

	    return $files_arr;
	}else{
		 return '';
	}
	} 
	
	
	
	
	
	
	
	
	
	
	
	

 //Выгрузка фото из временной папки для логотипа
 function filesshopprice($dir_name) {
	$dir = Yii::getAlias('@images_temp').'/shop/'.$dir_name.'/file/';
	$files = Yii::$app->userFunctions->myscandir($dir);
	if ($files) {
	foreach($files as $res) {
     $str=strpos($res, "_");
     $row=substr($res, 0, $str);
     $files_res[$row] = '/uploads/temp/images/shop/'.$dir_name.'/file/'.$res;
	}
	ksort($files_res);
	foreach($files_res as $res) {
	$files_ress[] = $res;	
	}
	    return $files_ress;
	}else{
		 return '';
	}
	} 
	
	 function urlshop($dir) {
    foreach(Yii::$app->request->get() as $key => $res) {
				  if ($key != '_pjax' &&  $key != 'sort' && $key != 'sort_tyme' && $key != 'page' && $key != 'patch' && $key != 'category' ) {
					  if(!isset($return)) {$return = '';}
					  $return .= $key.'='.$res.'&';
				  }  
			  }
			  if (!isset($return)) {$return = '';}
           return  '/'.Yii::$app->request->pathInfo.'?'.$return;
	 }
	
	
	
	
	
	
	
	
	
	
		
	//---------------------Обновление - автопоиск-----------------------//
	
	
	public function recursiveUrl($id, $region) 
	{	
		if($region) {
		  $reg = Yii::$app->caches->region()[$region]['url']. '/';
	    }else{
		  $reg = '';
		}
	   return $reg.implode('/',$this->line(Yii::$app->caches->category(), $id));
    }
	
	 function line($arr, $cats_id, $first = true) 
	   {
           $cats_id =  $arr[$cats_id];
           static $array = array();
           if($cats_id['parent'] != 0 && $cats_id['parent'] != "")
            {
                $this->line($arr, $cats_id['parent'], false);
		    }else{
		        $array = array();
		    }
        $array[] = $cats_id['url'];
        return  $array;
       }
	   
	   
	   
	function searchname($cat) 
	   {  
	$cat_name = Yii::$app->caches->category()[$cat]['name'];
    $count = 0; 
	 foreach(Yii::$app->caches->category() as $res) {
	if ($res['id'] == $cat) {
			$parent_id = $res['parent'];
		}
		
		if ($res['name'] == $cat_name) {
	       $count++;
		}
	}
	if($count > 1) {
	   $cat = Yii::$app->caches->category()[$parent_id]['name'].' > ';
	}else{
		$cat = "";	
	}
	return $cat.$cat_name;
	 }
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 function textName($text, $get) 
	   {  

	    $texts = explode(' ',$text);
        $count = 0;
		foreach($texts as $res) {
			 $count++;
			 if (strpos(mb_strtolower($res), mb_strtolower($get)) !== false) {
			     //$end = preg_replace("/[^а-яёa-z]/iu", '', $res);
				 // $end = preg_replace("/[^а-яёa-z\.-]/iu", '', $res);
				 $end = $res;
				 break;
			  }
			}
			
		if(!isset($end)) {
		  $count = 0;
		   foreach($texts as $res) {
			 $count++;
			 if (strpos(mb_strtolower($get), mb_strtolower($res)) !== false) {
			     //$end = preg_replace("/[^а-яёa-z]/iu", '', $res);
				 $end = $res;
				 break;
			  }
			}
		}
		  if(!preg_match('/[a-zа-яё ]+$/ui',$end) ){
				return preg_replace("/[^а-яёa-z]$/ui", '', $end);
			}
	
	
		if(isset($texts[$count])) {

			$end2 = preg_replace("/[^а-яёa-z]/iu", '', $texts[$count]);  
		// if(strlen($end2) > 2) {
			if(!preg_match('#[0-9]+#',$texts[$count]) ){
				
				if(preg_match('/[а-яёa-z]/iu',  $texts[$count]) ){	
			       //$end .= ' '.preg_replace("/[^а-яёa-z]/iu", '', $texts[$count]); 
				   $end .= ' '.$texts[$count];
			    }
				
			if(!preg_match('/[a-zа-яё ]+$/ui',$texts[$count]) ){
				return ' '.preg_replace("/[^а-яёa-z]$/ui", '', $texts[$count]);
			}
			}
		// }
		}

			
		
	if(isset($texts[$count+1])) {
			$end2 = preg_replace("/[^а-яёa-z]/iu", '', $texts[$count+1]);  
		 //if(strlen($end2) > 2) {
			if(!preg_match('#[0-9]+#',$texts[$count]) ){
			    if(preg_match('/[а-яёa-z]/iu',  $texts[$count]) ){	
			      // $end .= ' '.preg_replace("/[^а-яёa-z]/iu", '', $texts[$count+1]); 
				  $end .= ' '.$texts[$count+1];
				}
			}
		// }
		}
		
        return $end;
	 }
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 //--------------------Попутчики----------------------------//

  //Выгрузка фото из временной папки для логотипа
 function filespassangerlogo($dir_name) {
	$dir = Yii::getAlias('@images_temp').'/passanger/'.$dir_name.'/logo-mini/';
	$files = Yii::$app->userFunctions->myscandir($dir);
	if ($files) {
	foreach($files as $res) {
     $str=strpos($res, "_");
     $row=substr($res, 0, $str);
     $files_res[$row] = '/uploads/temp/images/passanger/'.$dir_name.'/logo-mini/'.$res;
	}
	ksort($files_res);
	foreach($files_res as $res) {
	$files_ress[] = $res;	
	}
	    return $files_ress;
	}else{
		 return '';
	}
	} 	
	
	
	
	 function previewconfigpassangerlogo($dir_name) {

	$dir = Yii::getAlias('@images_temp').'/passanger/'.$dir_name.'/logo-mini/';
	$files = Yii::$app->userFunctions->myscandir($dir);
	if ($files ) {
		
	
foreach($files as $res) {
$str=strpos($res, "_");
$row=substr($res, 0, $str);	
$array[$row] = array('caption'=>$res, 'size' => filesize($dir.$res), 'key'=>$dir.$res);
}
	
		ksort($array);
	foreach($array as $res) {
	$files_arr[] = $res;	
	}

	    return $files_arr;
	}else{
		 return '';
	}
	} 
	
	
	

	
function new_time($a) { // преобразовываем время в нормальный вид
 date_default_timezone_set('Europe/Moscow');
 $ndate = date('d.m', $a);
 $ndate_time = date('H:i', $a);
 $ndate_exp = explode('.', $ndate);
 $nmonth = array(
  1 => 'января',
  2 => 'февраля',
  3 => 'марта',
  4 => 'апреля',
  5 => 'мая',
  6 => 'июня',
  7 => 'июля',
  8 => 'августа',
  9 => 'сенября',
  10 => 'октября',
  11 => 'ноября',
  12 => 'декбря'
 );

 foreach ($nmonth as $key => $value) {
  if($key == intval($ndate_exp[1])) $nmonth_name = $value;
 }

 if($ndate == date('d.m')) return 'сегодня в '.$ndate_time;
 elseif($ndate == date('d.m', strtotime('-1 day'))) return 'вчера в '.$ndate_time;
 else return $ndate_exp[0].' '.
 $nmonth_name.' '.
' в '.
 $ndate_time;
}





function address($text) {
	$text = str_replace('село ','',$text);
	$text = str_replace('деревня ','',$text);
    $text = str_replace('поселок городского типа ','',$text);
    $text = str_replace(' , ',', ',$text);
	$text = str_replace(',Россия','',$text);

	$text = str_replace(', ',',',$text);
	$arr = explode(',',$text);
	$region = '';
	$i = 0;
	foreach($arr as $reg) {
		$i++;
		$region .= $reg.', '; 
		if($i == 4) {
			break;
		}
	}
	return rtrim($region, ", ");
}



	 function textName2($text, $get) 
	   {  
	  
		$text = str_replace('село ','',$text);
    	$text = str_replace('деревня ','',$text);
        $text = str_replace('поселок городского типа, ','',$text);
	    $text = str_replace('деревня ','',$text);
		$text = str_replace('поселок городского типа ','',$text);

	    $text = str_replace(' , ',', ',$text);
		$text = str_replace(',',', ',$text);
	    $texts = explode(',',$text);
		
        $count = 0;
		foreach($texts as $res) {
			 $count++;
			 if (strpos(mb_strtolower($res), mb_strtolower($get)) !== false) {
				 $end = $res;
				 break;
			  }
			}
		
			if(isset($end)) {
               return trim($end, " ");
			}
	 }


 function coordtext($text) {
	//$text = mb_eregi_replace('[0-9]', '', $text);

	$text = str_replace(', ',',',rtrim($text));

	$arr_reg = array();
	$i = 0;
   $array = array_reverse(explode(',',str_replace('Республика ','',$text)));
   foreach($array as $arr) {

	 $arr_reg[] = $arr;
      foreach(Yii::$app->caches->region() as $region) { 
			 if ($region['name'] ==  $arr) {
			  $stop = true;
	       }
       }

	   if(isset($stop)) {
		    break;
	   }
    }
	

   return implode(' > ', array_reverse($arr_reg));
 }

	 function passangerreg($region_id) 
	   {  
	   
	   $region = @Yii::$app->caches->region()[Yii::$app->request->cookies['region']->value]['name'];

	   $sql = Passanger::find();
	   $sql->where(['>=','time', date('Y-m-d H:i:s')]);
	  // $sql->andFilterWhere(['like', 'ot',  $region]);
	     if($region == 'Россия') {
			     $sql->andFilterWhere(['like', 'ot',  $region]);
			 }else{
				$sql->andFilterWhere(['like', 'ot',  $region.',']);
			 }
	   $sql->asArray()->All();

        return $sql->count();
	   }
	   
	   
	   
	  function auctioncount($region_id) 
	   {  
	    $sql = Blog::find();
		$sql->Where(['!=','blog.auction', '0'])->andWhere(['status_id' => 1, 'active' => 1]);
	if(isset($region_id->value)) {
		
       $regions = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), $region_id->value);
	     //$sql->andWhere(['region' => $regions, 'auction' => 1])->orWhere(['region' => $regions, 'auction' => 2]);
		  $sql->andWhere(['region' => $regions]);
	   }
	   $sql->asArray()->All();

        return $sql->count();
	   }
   
   
   
   
   
   
   
   
   	  function auctionshop($user_id) 
	   {  
	 
   
	     $sql = Blog::find();
	     $sql->Where(['!=','blog.auction', '0'])->andWhere(['status_id' => 1, 'active' => 1])->andWhere(['user_id' => $user_id]);
	
		 
	     $query =  $sql->All();
		
         return $sql->count();
	   }
	   

	   
	public function counterboardmagazin($cats, $user_id)
    {    
	   $params = ['category' => $cats, 'status_id'=> 1, 'active'=>1, 'auction' => 0, 'user_id' => $user_id];
	   $query = Blog::find()->Where($params);
	   return $query->count();
	} 
	   
	   
	   
	public function counterauction($cats, $user_id)
    {    
	   $params = ['category' => $cats, 'status_id'=> 1, 'active'=>1, 'auction' => 1, 'user_id' => $user_id];
	   $query = Blog::find()->Where($params);
	   return $query->count();
	} 	   
	   
	   
	   
	   
		public function metaCatsitemagazine($cats_id, $site_name)
    {
    if($cats_id) {
	   $cat_name = Yii::$app->caches->category()[$cats_id]['name'];
	}else{
		$cat_name = '';
	}
    //Считаем колиество элементов с одинаковым названием
	$count = 0;
	

	foreach(Yii::$app->caches->category() as $res) {
	if ($res['id'] == $cats_id) {
			$parent_id = $res['parent'];
		}
		
		if ($res['name'] == $cat_name) {
	       $count++;
		}
	}

    // Проверяем, если нашли дубли названий, добавляем к ним родителя, чтобы было понятно пользователю, к какой категории относится найденая.
	
	if ($count > 1) {

		$cat = Yii::$app->caches->category()[$parent_id]['name'];
		$parent_title =  $cat.', ';
		$parent_h1 = ' - '.$cat;
	}


		if (!isset($parent_title)) {$parent_title = '';}
		if (!isset($parent_h1)) {$parent_h1 = '';}
   



	if($cats_id) {

      //Удаляем последний элемент, чтобы получить павильный путь для категории
	  $breadcru = Yii::$app->userFunctions2->breadСat($cats_id, '', '/product/auction');

	  $bread_shop = array('label' => 'Аукцион', 'url' => '/product/auction');
	  array_unshift ($breadcru, $bread_shop);
	  
	  
	  $bread_shop = array('label' => 'Товары', 'url' => '/product');
	  array_unshift ($breadcru, $bread_shop);
	  
	  
	  unset($breadcru[count($breadcru)-1]);
	  $meta['breadcrumbs'] = $breadcru;
      $meta['breadcrumbs'][] = array('label'=>$cat_name);

	  $meta['description'] = 'На сайте '. $site_name .' Вы найдете товар тематики - '.$cat_name ;
	  $meta['keywords'] =  Yii::$app->caches->category()[$cats_id]['name'].', '.$cat_name;
	  $meta['title'] = 'Аукцион / '.$parent_title.$cat_name. ' на "'. $site_name.'"';
	}else{
		
		$meta['breadcrumbs'][] = array('label' => 'Товары', 'url' => '/product');
		$meta['breadcrumbs'][] = array('label' => 'Аукцион', 'url' => '/product/auction');
		$meta['description'] = 'Аукцион на сайте '. $site_name ;
		$meta['title'] = 'Аукцион на "'. $site_name.'"';
		$meta['keywords'] =  $cat_name;
		}

     return  $meta;
    }   
	   
	   
	   
	//Функция для хлебных крошек в категориях
	public function breadСat($category, $region, $act = false)
    {	
        
		if ($region) {
		      $region_arr = Region::findOne($region->value)['url'];
		   	}
			if(!isset($region_arr)) {$region_arr = '';}
			
			if($act != 'article') {
				$arr = Yii::$app->caches->category();
			}else{
				$arr = Yii::$app->caches->artcat();
				   
			}
           return Yii::$app->userFunctions2->breadLine($arr, $category, $region_arr, $act);
	}
	
	
	
	
		public function breadLine($cat, $cats_id, $region , $act = false, $first = true )
    {
	$lin = '/product/auction';
    static $array = array();
	if($first){$array = array();}
    $value = $cat[$cats_id];
    if($value['parent'] != 0 && $value['parent'] != "")
        {
           Yii::$app->userFunctions2->breadLine($cat, $value['parent'], false, $act, false);
		}
   		$array[] = array('name' => $value['name'], 'id' => $value['id'], 'url' => $value['url']);
    if ($region) {
		if (!isset($url)) {$url = '';}
    $url .= '/'.$region.'/';
	}else{
		if (!isset($url)) {$url = '';}
	    $url .= '/';
	}
    foreach($array as $k=>$v)
        {
        $url .= $v['url'].'/';
		if (!next($array)) {$arr_url[] = array('label'=>$v['name'], 'url' => $lin.$url );}
        }
    return $arr_url;
    }
	
	
	
	
	
 function remainsparsetimestamp($t=0){
	
	$day=floor($t/86400);
	$hour=($t/3600)%24;
	$min=($t/60)%60;
	
	return array('day'=>$day,'hour'=>$hour,'min'=>$min);
}



function auctionday($t){
	
	$timesres='';
	if(time()<$t){

		$arr=$this->remainsparsetimestamp($t-time());
		if($arr['day']>0){
			$timesres.=$arr['day'].' дн. ';
		}
		if($arr['hour']>0||$timesres!=''){
			$timesres.=$arr['hour'].' ч. ';
		}
		if($arr['min']>0||$timesres!=''){
			$timesres.=$arr['min'].' мин.';
		}
	}
	return $timesres;
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

    }

}
                                                                                                                                                                                             