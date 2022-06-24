<?php
namespace backend\components;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;
use common\models\Category;
use common\models\Region;
use yii;
class BlogUrlRule extends BaseObject implements UrlRuleInterface
{
	
	
	
                                    //Правило для ссылок
/*------------------------------------------------------------------------------------------------------*/
    public function createUrl($manager, $route, $params)
    {
	   if ($route === 'blog/one') {	
	        if(!isset($reg['url'])){$reg['url'] = '';}
            return   $reg['url'].'/' .  $params['region'] . '/' .  $params['category'] . '/' . $params['url'].'_'.$params['id'].'.html';
        }
        if ($route === 'article/one') {	
            return   '/article/'. $params['category'] . '/' . $params['name'].'_'.$params['id'].'.html';
        }
		if ($route === 'shop/one') {	
	     if(!isset($reg['name'])){$reg['name'] = '';}
         return   '/shop'.$reg['name'].'/' .  $params['region'] . '/' .  $params['category'] . '/' . $params['name'].'_'.$params['id'].'.html';
        }
		
		
         return false;  // данное правило не применимо
    }




                                      //Правило для URL
/*------------------------------------------------------------------------------------------------------*/

    public function parseRequest($manager, $request)
    {
	
       return false;  // данное правило не применимо
    }
	
	
}	