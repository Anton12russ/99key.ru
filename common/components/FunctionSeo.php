<?php
 
namespace common\components;

use yii\base\Component;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii;
use common\models\Region;
use common\models\RegionCase;
class FunctionSeo extends Component { 	
	public function meta($id) {
		$arr = Yii::$app->caches->seomodule();
		$array = array();
		//Ловим протокол и сверяем
		
		$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		foreach($arr as $res) {
			
			if (strpos($res['url'], '{region}') !== false) // именно через жесткое сравнение
               {
                 	if(Yii::$app->request->cookies['region']) {
						  $case = Region::findOne(Yii::$app->request->cookies['region']->value);
			              $res['url'] =  str_replace('{region}',$case['url'].'/',$res['url']);
						  $region = true;
			        }
               }
			
		
			if($res['url'] == $patch_url.$id) {
				//Если есть редирект, переправлять на страницу редиректа
			   if ($res['redirect']) {
				   if($res['cod_redirect'] == 0) {$res['cod_redirect'] = 301;}
				   return Yii::$app->getResponse()->redirect($res['redirect'], $res['cod_redirect']);
			   }
				
			if(isset($region)) {
				
			
			//Меняем мета теги
			 $reg_name = $case['name'];
			 $reg_case = RegionCase::findOne(Yii::$app->request->cookies['region']->value)['name'];
			 
			 
			 $meta['title'] = str_replace('{region}', $reg_name, $res['title']);
			 $meta['title'] = str_replace('{region_case}', $reg_case, $meta['title']);
			 
			 
			 $meta['h1'] = str_replace('{region}', $reg_name, $res['h1']);
			 $meta['h1'] = str_replace('{region_case}', $reg_case, $meta['h1']);
			 
			 
			 $meta['description'] = str_replace('{region}', $reg_name, $res['description']);
			 $meta['description'] = str_replace('{region_case}', $reg_case, $meta['description']);
			 
			 
			 $meta['keywords'] = str_replace('{region}', $reg_name, $res['keywords']);
			 $meta['keywords'] = str_replace('{region_case}', $reg_case, $meta['keywords']);
			 
		     }else{

			//Меняем мета теги
			 $meta['title'] = $res['title'];
			 $meta['h1'] = $res['h1'];
			 $meta['description'] = $res['description'];
			 $meta['keywords'] = $res['keywords'];
				
			}
			 
			 
			 return $meta;
			 
			 
			 
			 
		    }
		}
	    return '';
	}
}