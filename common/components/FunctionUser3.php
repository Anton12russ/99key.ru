<?php
 
namespace common\components;

use yii\base\Component;
use yii\helpers\Html;
use common\models\Category;
use common\models\Subscription;
use yii\web\NotFoundHttpException;
use common\models\ExecutorBoard;
use common\models\Blog;
use common\models\Timer;
use common\models\MessageRoute;
use common\models\Message;
use common\models\Organization;
use frontend\models\Shop;
use common\models\Push;
use common\models\Passanger;
use yii;
use common\models\ShopComment;
use frontend\models\Article;
class FunctionUser3 extends Component { 
	
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
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   function timer($content) 
	   {  
         $str = $content;
         preg_match_all('#{timer_(.+?)}#is', $str, $arr);
          foreach($arr[0] as $res) {
          	$res1 = str_replace('{timer_','',$res);
			$res1 = str_replace('}','',$res1);
		    $content = str_replace($res, Yii::$app->userFunctions3->timer_add((int)$res1), $content);
		
          }
	     return $content;
	   }
	   
	   
	   
	   
	   
	   
	   

	   function timer_add($id) {
               return '<iframe allowtransparency class="iframe" id="esfrsd"  style="  overflow: hidden; height: 0px; width: 100%; border: 0px;" src="/ajax/timer?id='.$id.'"></iframe>';
	   }
	   
	  
	
	

	   
	   public function counterregion($id, $user_id)
    {    

	$regarr = Yii::$app->userFunctions->recursСat(Yii::$app->caches->Regrelative(), $id);
 
	$params = ['user_id' => $user_id, 'status_id'=> 1, 'active'=>1,'region' => $regarr];

    $query = Blog::find()->Where($params);
	return $query->count();

	} 
	
	
	
	

	
	
	
	
	
	
	public function counterboard($cats, $regions)
    {    
      	if ($regions) {
		    $params = ['category' => $cats, 'status_id'=> 1, 'active'=>1, 'region' => $regions];
		}else{
			$params = ['category' => $cats, 'status_id'=> 1, 'active'=>1];
		}
	   $query = Blog::find()->Where($params);
	   return $query->count();
	} 
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		//Для корзины покупок
	public function CoocCar($id) 
	{	
	   $arr = unserialize(Yii::$app->request->cookies['car']);
	   if(isset($arr[$id])) {
		   $count = $arr[$id]['count'];
	   }else{
		   $count = '';
	   }
	   return $count;
	}
	
	
	
		//Для корзины покупок
	public function car_price($id) 
	{	
	$cars = unserialize(Yii::$app->request->cookies['car']);

	if(!$cars) {
		return 0;
	}
	if($cars) {
        foreach($cars as $key => $car) {
		   $sum[] = $car['count']*str_replace(' ','',$car['price']);
	    }
	}

	
	if(isset($sum)) {
	     return array_sum($sum);
	}else{
		return 0;
	}
	
	}
	
	
	
	//Для корзины покупок
	public function blogShop($user_id) 
	{	
	   $shop = Shop::find()->where(['user_id' => $user_id])->one();
	   if(!$shop) {
		   return false;
	   }
	   

	    return $shop;
	}
	
	
	
	
	
	//Для модуля магазина 
	public function Shoppersonal() 
	{	
	    $subdomen = explode('.', $_SERVER['HTTP_HOST']);
		
		
		
		
		        if (($shop = Shop::find()->with('field')->andWhere(['domen'=>$subdomen, 'status' => 1, 'active' => 1])->one()) !== null) {
			
			
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		         //  Мета теги для категорий
	//------------------------------------------------------//
	
	public function metaCat($cats_id, $region)
    {
	
		if($cats_id) {
	$cat_name = Yii::$app->caches->category()[$cats_id]['name'];
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
	
	
	$site_name = Yii::$app->caches->setting()['site_name'];
		if ($region) {
		   $reg = Yii::$app->caches->regionCase()[$region->value]['name'];
		   $region_key =   ', '.$reg;
	       $regions = ' в ' .$reg;
		}
		if (!isset($parent_title)) {$parent_title = '';}
		if (!isset($parent_h1)) {$parent_h1 = '';}
		if(!isset($regions)) {$regions = '';}
		if(!isset($region_key)) {$region_key = '';}
    $meta['title'] = 'Аукционы - '.$parent_title.$cat_name. $regions. ' на '. $site_name;
	$meta['description'] = 'На сайте '. $site_name .' Вы найдете все бесплатные объявления тематики - '.$cat_name.$regions ;
	$meta['keywords'] =  Yii::$app->caches->category()[$cats_id]['name'].$region_key.',  объявления';
	$meta['h1'] = $cat_name.$parent_h1. ' ' .$regions ;
		//Удаляем последний элемент, чтобы получить павильный путь для категории
	$breadcru = Yii::$app->userFunctions3->breadСat($cats_id, $region);

	if(!isset($reg_breadcru)) {$reg_breadcru = '';} 
	$bread_shop = array('label' => 'Аукцион', 'url' => '/auction/'.$reg_breadcru);
	array_unshift ($breadcru, $bread_shop);

	unset($breadcru[count($breadcru)-1]);
	$meta['breadcrumbs'] = $breadcru;
    $meta['breadcrumbs'][] = array('label'=>$cat_name);
    return  $meta;
	
	
	
	
	
		}else{
			
		$site_name = Yii::$app->caches->setting()['site_name'];
		if ($region) {
		   $reg = Yii::$app->caches->regionCase()[$region->value]['name'];
		   $region_key =   ', '.$reg;
	       $regions = ' в ' .$reg;
		}
		if (!isset($parent_title)) {$parent_title = '';}
		if (!isset($parent_h1)) {$parent_h1 = '';}
		if(!isset($regions)) {$regions = '';}
		if(!isset($region_key)) {$region_key = '';}
    $meta['title'] = 'Аукционы - '.$parent_title. $regions. ' на '. $site_name;
	$meta['description'] = 'На сайте '. $site_name .' Вы найдете все бесплатные объявления тематики - '.$regions ;
	$meta['keywords'] =  $region_key.',  аукционы';
	$meta['h1'] = $parent_h1. ' ' .$regions ;
		//Удаляем последний элемент, чтобы получить павильный путь для категории



    $meta['breadcrumbs'][] = array('label'=> 'Аукцион');
	
	
    return  $meta;
			
		}
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
		public function breadСat($category, $region, $act = false)
    {	
        
		if ($region) {
		      $region_arr = Region::findOne($region->value)['url'];
		   	}
			if(!isset($region_arr)) {$region_arr = '';}
			
			
				$arr = Yii::$app->caches->category();
		
           return Yii::$app->userFunctions3->breadLine($arr, $category, $region_arr, 'auction');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		public function breadLine($cat, $cats_id, $region , $act = false, $first = true )
    {
	
		if($act == 'auction') {
			$lin = '/auction';
		}elseif($act == 'shop'){
			$lin = '/shop';
		}elseif($act == 'product'){
			$lin = '/product';
		}else{
			$lin = '';
		}
    static $array = array();
	if($first){$array = array();}
    $value = $cat[$cats_id];
    if($value['parent'] != 0 && $value['parent'] != "")
        {
           Yii::$app->userFunctions3->breadLine($cat, $value['parent'], false, $act, false);
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		public function counterboard2($cats, $regions)
    {    
      	if ($regions) {
		    $params = ['category' => $cats, 'status_id'=> 1, 'active'=>1, 'region' => $regions];
		}else{
			$params = ['category' => $cats, 'status_id'=> 1, 'active'=>1];
		}
	   $query = Blog::find()->Where($params)->andWhere(['!=','blog.auction', '0']);;
	   return $query->count();
	} 
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function pushonline()
    {  

	    $push = Push::find()->Where(['user_id' => Yii::$app->user->id, 'flag' => '1'])->One();
		if($push) {
		  $push_del = $push;
		  $push->flag = 0;
		  $push->update(false);
	      return $push_del;
		}
		
	}
	
	
	
	
	
	public function push($user_id, $text, $url = false)
    {  
	    $push = new Push();
	    $push->user_id = $user_id;
        $push->text = $text;
		$push->href = $url;
		$push->flag = 1;
		$push->save();
		
	}
}
                                                                                                                                                                                             