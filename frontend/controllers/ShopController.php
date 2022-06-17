<?php

namespace frontend\controllers;
use \yii\base\DynamicModel;
use Yii;
use yii\helpers\Html;
use frontend\models\Shop;
use frontend\models\User;
use common\models\Rates;
use frontend\models\Blog;
use common\models\Payment;
use common\models\ShopField;
use frontend\models\ShopComment;
use common\models\ShopImages;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Category;
use common\models\Article;
use yii\data\Pagination;
use yii\helpers\Url;
/**
 * BlogController
 */
class ShopController extends Controller
{
	
	//Страница объявления
	public function actionOne($id, $url)
    {
		
	
  // print_r($id_one);
   if ($shop = Shop::find()->with('field')->andWhere(['id'=>$id])->one()){

   if(!Yii::$app->user->can('updateShop')) {
        if ($shop->status != 1) {
	       if ($shop->user_id != Yii::$app->user->id) {
	           throw new NotFoundHttpException('Такого магазина нет или он находится на модерации');
		   }
        }
   }


	    $url_original = 'shop/'.Yii::$app->caches->region()[$shop['region']]['url'] . '/' . Yii::$app->caches->category()[$shop['category']]['url'] . '/' . Yii::$app->userFunctions->transliteration($shop['name']).'_'.$shop['id'].'.html';


	  //Проверяем, совпадает ли url с оригиналом
	   if($url_original == $url) {
		   
		//Получаем мета теги
	   $meta = Yii::$app->userFunctions->metaOne($shop->category, $shop->region, $shop->name, 'shop');


     //Достаем количество комментариев
	$query = ShopComment::find()->andWhere(['blog_id' => $id, 'status' => 1])->orderBy(['id' => SORT_DESC]);  
	$count_com = $query->count();

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
     }elseif(isset(Yii::$app->request->cookies['votes']) && Yii::$app->request->cookies['votes'] == $id){
       $vote = true;
     }else{
	   $vote = false;
     }





      //Отбираем объявления

	$query = Blog::find()->andWhere(['user_id' => $shop->user_id, 'status_id' => 1, 'active' => 1])->orderBy(['id' => SORT_DESC]);	
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$blog = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	
    foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $price = $res['id'];
	   }
	}
	$rates = Yii::$app->caches->rates();
	$notepad = Yii::$app->userFunctions->notepadArr();

    $query_art = Article::find()->andWhere(['user_id' => $shop->user_id, 'status' => 1]);
	$count_art = $query_art->count();
	
	
	
          return $this->render('one', compact('shop', 'rates', 'notepad', 'price', 'blog', 'pages', 'meta', 'fields' ,'comment', 'comment_add', 'comment_save', 'count_com', 'vote', 'count_art'));
	   }else{
		  throw new NotFoundHttpException('Страница не найдена.');
	   }
    }
	throw new NotFoundHttpException('Такого магазина нет или он находится на модерации');
}




public function actionBlogsearch($user_id)
{
	$text = Yii::$app->request->get('text');

    $query = Blog::find()->andWhere(['user_id' => $user_id, 'status_id' => 1, 'active' => 1])->andFilterWhere(['like', 'title', $text])->orderBy(['id' => SORT_DESC]);
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
	
    $user_id = $user_id;
    return $this->render('blog_all', compact('blogs', 'rates', 'notepad', 'price', 'pages',  'fields', 'user_id'));

}




//Список магазинов на главной
	
    public function actionIndex($category, $patch)
    {
		
	$post = Yii::$app->request->post();
	if(isset($post['style']) && $post['style'] == 'grid') {
			  Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'style_shop',
                        'value' => 'grid'
              ]));
			  $url_red = str_replace('?style=grid','',$_SERVER['REQUEST_URI']);
			  $url_red = str_replace('style=grid','',$url_red);
		  return $this->redirect([$url_red]);
	  
	}
	
		if(isset($post['style']) && $post['style'] == 'list') {
		  Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'style_shop',
                        'value' => ''
              ]));
			  $url_red = str_replace('?style=list','',$_SERVER['REQUEST_URI']);
			  $url_red = str_replace('style=list','',$url_red);
			 
		      return $this->redirect([$url_red]);
	}
	
	
	
	
	$text = Yii::$app->request->get('text');
	if ($text) {
	    $text = Html::encode($text);
	}	
		
	   $region = Yii::$app->request->cookies['region'];

		//Получаем всех родителей последней категории в url и передаем в выборку, в итоге получаем в корневой категории объявления потомков
		if ($category) {
	       $cat_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $category);
		   //Показывать в дочерних категориях
		   $pred_cat = str_replace('|','',explode(' | ',Yii::$app->caches->catRelative()[$category]));
           $cat_all =  array_merge($cat_all, $pred_cat);
		}else{
			$category = 0;
			$cat_all = '';
		}
		
		

	   //Получаем список категорий на странице выбранной категории
	   // $cat_menu = Yii::$app->userFunctions->menuCatshop($category, $patch);
		
		$query = Category::find()->where(['parent' => $category])->orderBy('sort');
	    $pages_cat = new Pagination(['totalCount' => $query->count(), 'pageSize' => 15]);
	    $cat_arr = $query->offset($pages_cat->offset)->limit($pages_cat->limit)->all();
		
		
		$id_region = Yii::$app->request->cookies['region'];
		$region = $id_region;
		/*
		if($id_region) {
		    $arr_regs =  Yii::$app->userFunctions->catCounShop(Yii::$app->caches->regRelative(), $id_region->value);
		}
		foreach($cat_arr as $res) {
           if($id_region) {
			   $count = Yii::$app->userFunctions->countershop(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id']), $arr_regs);
		   }else{
			   $count = Yii::$app->userFunctions->countershop(Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id']), '');
		   }
			$array[$res['id']] = array('id' => $res['id'], 'name' => $res['name'], 'url' =>  '/'.$patch.'/'.$res['url'], 'image' => $res['image'], 'count'=>$count, $res['id']);
		}
		if(isset($array)) {
		    $cat_menu = $array;
		}else{
			$cat_menu = '';
		}
   */

	    //Если регион не выбран, меняем параметры без региона 
	if ($region) {
	   //$reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region->value);
       $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), (int)$region->value);

       $predoc = str_replace('|','',explode(' | ',Yii::$app->caches->regRelative()[(int)$region->value]));

       $reg_all =  array_merge($reg_all,$predoc);
	   
	   
	if ($category) {
            $params = ['status'=>1, 'active'=>1, 'category'=>$cat_all, 'region' => $reg_all];
	   }else{
		    $params = ['status'=>1, 'active'=>1, 'region' => $reg_all];
	   }
	}else{
		if ($category) {
	       $params = ['status'=>1, 'active'=>1, 'category'=>$cat_all];
		}else{
		   $params = ['status'=>1, 'active'=>1];
		}
	}
	
	
       
		foreach($cat_arr as $res) {
			 $predoc_cat = str_replace('|','',explode(' | ',Yii::$app->caches->catRelative()[$res['id']]));
           if($id_region) {

			   $count = Yii::$app->userFunctions->countershop(array_merge($predoc_cat,Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id'])), $reg_all);
		   }else{
			  
			   $count = Yii::$app->userFunctions->countershop(array_merge($predoc_cat,Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), $res['id'])), '');
		   }
			$array[$res['id']] = array('id' => $res['id'], 'name' => $res['name'], 'url' =>  '/'.$patch.'/'.$res['url'], 'image' => $res['image'], 'count'=>$count, $res['id']);
		}
		if(isset($array)) {
		    $cat_menu = $array;
		}else{
			$cat_menu = '';
		}
		
	$sql = Shop::find()->with('regions')->with('categorys'); 	
	$sql->andFilterWhere(['like', 'name', $text]);
		//Пареметры для сортировки 
	 if (Yii::$app->request->get('sort')) {  
	 if (Yii::$app->request->get('sort') == 'DESC') $sort = SORT_DESC; 
	 if (Yii::$app->request->get('sort') == 'ASC') $sort = SORT_ASC; 
	 }else{
		 $sort = '';
	 }
	
		 if ($sort) {
	         $query = 
			 $sql->andWhere($params);
			      //Для модератора
			      if(Yii::$app->user->can('updateShop')) {
				      $sql->orWhere(['status'=>0]);
					  $sql->orWhere(['active'=>0]);
				  }
			 $sql->orderBy(['rayting' => $sort]);
	     }else{
             $query = $sql->andWhere($params);
			 			      //Для модератора
			      if(Yii::$app->user->can('updateShop')) {
				      $sql->orWhere(['status'=>0]);
					  $sql->orWhere(['active'=>0]);
				  }
			 $sql->orderBy(['date_add' => SORT_DESC,]);
	     }
    
	$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$shops = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();

	 
	//Записываю параметры для вида (title, keywords,description)
	if($category == '') {$category = 0;}
	$metaCat = Yii::$app->userFunctions->metaCatshop($category, $region);
	if(Yii::$app->request->cookies['style_shop'] == 'grid') {
       return $this->render('all_grid', compact('shops', 'pages_cat', 'pages', 'metaCat' , 'cat_menu',  'category', 'cat_all', 'reg_all'));
	}else{
	   return $this->render('all', compact('shops', 'pages_cat', 'pages', 'metaCat' , 'cat_menu',  'category', 'cat_all', 'reg_all'));
	}
    }






    //Страница объявления
	public function actionComment($id)
    {
		 //Добавление комментария
        $comment_add = new ShopComment();
		$comment_save = '';
		
	//Сохраняем	Комментарий ----------------------------------------------------------------------------------
     if ($comment_add->load(Yii::$app->request->post())) {
		  $comment_add->blog_id = $id;
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
          
          if($comment_add->save()){
		     $comment_save = true;
			 
			 

			    $shop = Shop::findOne($id);
			 	$user_c = User::findOne($shop->user_id);

			    if(isset($user_c->alert['comment']) && $user_c->alert['comment'] == 1) {
					$url = Url::to(['shop/one', 'region'=> $shop->regions['url'], 'category'=> $shop->categorys['url'], 'id'=> $shop->id, 'name'=>Yii::$app->userFunctions->transliteration($shop->name)]);
			         Yii::$app->functionMail->comment_shop($user_c, $url);
			    }
			 
			 
			 
			 
		  }
    }
	//Сохранили	Комментарий ----------------------------------------------------------------------------------		 
	$query = ShopComment::find()->andWhere(['blog_id' => $id, 'status' => 1])->orderBy(['id' => SORT_DESC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '15']);
	$comment = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	 $shop = true;
	 $this->layout = 'style_none';
	 return $this->render('/blog/comment', compact('comment', 'shop', 'comment_add', 'comment_save', 'pages', 'count_com', 'model_search'));
	}
	






    //Страница Со статьями
	public function actionArticle($id)
    {
	$user_id = $id;
	$text = Yii::$app->request->get('text');
	if ($text) {
	    $text = Html::encode($text);
	}	
       $sql = Article::find()->with('cats'); 
	   $sql->andFilterWhere(['like', 'title', $text]);
	   $query = $sql->andWhere(['status'=>1])->andWhere(['user_id'=>$id])->orderBy(['date_add' => SORT_DESC,]);
       $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	   $article = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
		$this->layout = 'style_none';
	    return $this->render('/shop/article', compact('article', 'pages', 'user_id'));
	}






    //Страница Изображений
	public function actionImages($id)
    {
		 //Добавление комментария
        $comment_add = new ShopComment();
		$comment_save = '';
		
     $images = ShopImages::find()->where(['shop_id' => $id])->orderBy(['sort' => SORT_ASC])->all();
	 $this->layout = 'style_none';
	 return $this->render('images', compact('images'));
	}



	public function actions()
    {
    return [
            'add' => [
                'class' => 'frontend\actions\ShopAdd',
            ],
			
			'update' => [
                'class' => 'frontend\actions\ShopUpdate',
            ],
			
			'del' => [
                'class' => 'frontend\actions\ShopDel',
            ],
    ];
}
	
	
}