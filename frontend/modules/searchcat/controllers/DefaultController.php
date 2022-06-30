<?php

namespace frontend\modules\searchcat\controllers;
use frontend\models\Blog;
use common\models\CatServices;
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
         $sql = Blog::find()->select(['blog.title', 'blog.category'])->Where([ 'active' => 1, 'status_id' => 1]);
		 $sql->andFilterWhere(['like', 'title', $text]);
		 $sql->andWhere(['>=', 'category', '0']);
		 $blogs = $sql->orderBy('id ASC')->Limit(10)->asArray()->all();



   
		  $blogi = [];
		  foreach($blogs as $blog) {
			  $regions = explode(', ',$blog['coordtext']);
			  foreach($regions as $reg) {
		        if (strpos(mb_strtolower (trim($reg)), mb_strtolower ($text)) !== false) {
			       $blogi[] = array('title' => $reg, 'category' => $blog['category'], 'plat' => $this->platnaya($blog['category']));
				   $act = true;
			    }
			  }

			   if(!isset($act)) {
			      $blogi[] = array( 'category' => $blog['category'], 'plat' => $this->platnaya($blog['category']));
		       }
			 }
		

	       $blogi =  array_unique($blogi, SORT_REGULAR);
	       return $this->render('index', compact('blogi', 'region', 'text'));
		}

		 
    }



	protected function platnaya($cat, $reg = '1')
    {
	
		$reg = Yii::$app->userFunctions->catparent($reg, 'reg');
		$cat = Yii::$app->userFunctions->catparent($cat, 'cat');
		
		$return = CatServices::find()->Where(['cat' => $cat])->asArray()->all();
		foreach ($return as $res) {
           if (in_array($res['reg'], $reg)) {
			   //$ret = $res['price'];
			   return true;
		   }
		}		
	    return false;
    }
}
