<?php

namespace frontend\modules\searchcat\controllers;
use frontend\models\Blog;
use common\models\CatServices;
use common\models\Category;
use common\models\AuctionCat;

use yii\web\Controller;
use yii;
/**
 * Default controller for the `search` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($text, $region = false)
    {		
		if($text) {
			
			
         $sql = Blog::find()->select(['blog.title', 'blog.category']);  
		 $sql->andFilterWhere(['like', 'title', $text]);
		 $sql->andWhere(['>=', 'category', '0']);
		 $blogs = $sql->orderBy('id ASC')->Limit(10)->asArray()->all();


		 $blogi = [];


         $cats = Category::find()->select(['name', 'id', 'parent']);
		 $cats->andFilterWhere(['like', 'name', $text]);
		 $category =$cats->orderBy('id ASC')->Limit(3)->all();
		 
   //Проверяем есть ли такая категория в платных
		 if(isset($_GET['auction']) && $_GET['auction']) {

		    foreach($category as $cat) {
			  if($this->catAuction($cat['id'])) {
			      $blogi[] = array('category' => $cat['id'], 
			      'day'=>Yii::$app->caches->setting()['express_add'], 
			      'plat' => $this->platnaya($cat['id']), 
			      'user_id' => Yii::$app->user->id);
			  }
	        }
		      foreach($blogs as $blog) {
				if($this->catAuction($blog['category'])) {
			      $blogi[] = array( 'category' => $blog['category'], 
				  'day'=>Yii::$app->caches->setting()['express_add'], 
				  'plat' => $this->platnaya($blog['category']), 
				  'user_id' => Yii::$app->user->id);
				}
			   }
			

		}else{

			    foreach($category as $cat) {
					$blogi[] = array('category' => $cat['id'], 
					'day'=>Yii::$app->caches->setting()['express_add'], 
					'plat' => $this->platnaya($cat['id']), 
					'user_id' => Yii::$app->user->id);
				 }
				  foreach($blogs as $blog) {
						  $blogi[] = array( 'category' => $blog['category'], 
						  'day'=>Yii::$app->caches->setting()['express_add'], 
						  'plat' => $this->platnaya($blog['category']), 
						  'user_id' => Yii::$app->user->id);
					 }

		}
	}
	       $blogi =  array_unique($blogi, SORT_REGULAR);
	       return $this->render('index', compact('blogi', 'region', 'text'));
		}

		 
 

	
	public function catAuction($cat)
    {
		/*$cat  = Category::find()->Where(['id' => $cat])->One();
		echo $cat->name.
		'<br>';*/
		Yii::$app->userFunctions2->arrayNulle();
		$arr =  Yii::$app->userFunctions2->recursive(Yii::$app->caches->category(), $cat);

		if($arr) {
           if($catAuction = AuctionCat::find()->Where(['cat' => $arr])->One()) {
               return true;
		   }
		}
		return false;
	}






	public function platnaya($cat, $reg = '1')
    {
	
		$reg = Yii::$app->userFunctions->catparent($reg, 'reg');
		$cat = Yii::$app->userFunctions->catparent($cat, 'cat');
		
		$return = CatServices::find()->Where(['cat' => $cat])->asArray()->all();
		foreach ($return as $res) {
           if (in_array($res['reg'], $reg)) {
			   //$ret = $res['price'];
			   return $res['price']*Yii::$app->caches->setting()['express_add'];
		   }
		}		
	    return false;
    }
}
