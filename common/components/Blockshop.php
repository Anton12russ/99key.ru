<?php
 
namespace common\components;

use yii\base\Component;
use yii\helpers\Html;
use common\models\Blog;
use common\models\BlockUser;
use common\models\Passanger;
use yii;

class Blockshop extends Component { 
  function block($controller, $action, $cat, $cat_all, $reg_all, $user_id) {

	$arr_block = BlockUser::find()->where(['user_id' => $user_id])->orderBy(['sort' => SORT_ASC])->all();

	if ($action) {
	   $contr = $controller.'/'.$action;
	}else{
	   $contr = $controller;
	}
	$arr = array();
   
	
	 foreach($arr_block as $res) {

		$err = array();
		if($res['date_del']) {
		   if($res['date_del'] <= date('Y-m-d H:i:s')) {
		 	 $err[] = true;
		   }	
		} 
		 
	
		
				  
      if ($res['status'] == 1) {
		if (isset($res['region'])) {
			   $coo_reg = (string)Yii::$app->request->cookies['region'];	
			if (!in_array($coo_reg, Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), $res['region']))){$err[] = true;}else{}
		}


        if ($res['category']) {

			if (!in_array($cat, Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['category']))){$err[] = true;}else{}
		}
		
		
		if (!$err) {
		   $text = '';
		   if (strpos($res['text'], '{board_block}') !== false) {
               $text = Yii::$app->block->servicesBlock($cat_all, $reg_all);
			}else{
			   $text = $res['text'];
			}
			
			
	
			
	
			
		   if (strpos($contr, $res['action']) !== false || $res['action'] == 'all' ) {
			   //Если блок с платными объявлениями, то проверим на пустоту массива с объявлениями
			     $arr[] = array('position'=>$res['position'], 'text' => $text , 'name' => $res['name'] , 'header_ok' => $res['header_ok'], 'route' => false);
		   }
		 }
	
	 }
	 }
	 return $arr;
	}
	
	
	
	
	
		public function Shoptop() {
			 $shops = Shop::find()->Where(['>','img',0])->andWhere(['status' => 1, 'active' => 1])->orderBy(['rayting' => SORT_DESC])->Limit(3)->all();
			 $shops['shops'] = $shops;
			 $shops['shop'] = true;
			 return $shops;
		}
	
	
		public function Passanger() {
			 $region = @Yii::$app->caches->region()[Yii::$app->request->cookies['region']->value]['name'];
			 $pass = Passanger::find()->select('time')->where(['>=','time', date('Y-m-d H:i:s')]);
			 if(!empty($region)) { 
			 if($region == 'Россия') {
			     $pass->andFilterWhere(['like', 'ot',  $region]);
			 }else{
				 $pass->andFilterWhere(['like', 'ot',  $region.',']);
			 }
			 }
			 $pass = $pass->asArray()->all();

			 foreach($pass as $pa) {
				 $passan[] = date("Y-m-d", strtotime($pa['time']));
			 }
			 if(!isset($passan)) {
				 $passan = '';
			 }
			 $pass['passs'] = $passan;
			 $pass['pass'] = true;
			 return $pass;
		}
	
	
		//Функция для платных объявлений (Block)
	public function servicesBlock($cat_all, $reg_all) {
	$block_arr = Yii::$app->userFunctions->services_type('block');
	if ($block_arr) {
	$region = Yii::$app->request->cookies['region'];
		  if ($cat_all) {
	              if ($reg_all) {
                     $params_block = ['id'=>$block_arr, 'status_id'=>1, 'category'=>$cat_all, 'region' => $reg_all];
	              }else{
	                 $params_block = ['id'=>$block_arr, 'status_id'=>1, 'category'=>$cat_all];
	              }
		}else{
			    if ($reg_all) {
                     $params_block = ['id'=>$block_arr, 'status_id'=>1, 'region' => $reg_all];
	              }else{
	                 $params_block = ['id'=>$block_arr, 'status_id'=>1,  /*'region' => $region*/];
	              }
		}

	   $block_blog['blog'] = Blog::find()->andWhere($params_block)->with('blogField')->with('imageBlogOne')->with('regions')->with('categorys')->orderBy('rand()')->Limit(Yii::$app->caches->setting()['max_count_block'])->all();
	   
	   if (!$block_blog['blog']) {
		   //Если нет объявлений в категории, то показывать объявления всего региона
		       if ($reg_all) {
                     $params_block = ['id'=>$block_arr, 'status_id'=>1, 'region' => $reg_all];
	              }else{
	                 $params_block = ['id'=>$block_arr, 'status_id'=>1];
	           }
		   $block_blog['blog'] = Blog::find()->andWhere($params_block)->with('blogField')->with('imageBlogOne')->with('regions')->with('categorys')->orderBy('rand()')->Limit(Yii::$app->caches->setting()['max_count_block'])->all();
	   }
	   
	   
	    if (!$block_blog['blog']) {
		   //Если нет и в своем регионе, то ищем в других
           $params_block = ['id'=>$block_arr, 'status_id'=>1, 'active'=> 1];
		   $block_blog['blog'] = Blog::find()->andWhere($params_block)->with('blogField')->with('imageBlogOne')->with('regions')->with('categorys')->orderBy('rand()')->Limit(Yii::$app->caches->setting()['max_count_block'])->all();
	   }
	   
	}

	
	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $block_blog['price'] = $res['id'];

	   }
	}
	 $block_blog['rates'] = Yii::$app->caches->rates();
	return $block_blog;
	  }
	//<!--------------------------------->
}