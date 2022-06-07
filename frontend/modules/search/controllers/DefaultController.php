<?php

namespace frontend\modules\search\controllers;
use frontend\models\Blog;
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
         $sql = Blog::find()->select(['blog.title', 'blog.category', 'coord.*', 'coord.text as coordtext'])->Where([ 'active' => 1, 'status_id' => 1]);
		 $sql->LeftJoin('blog_coord coord', 'coord.blog_id = blog.id');
		 $sql->andFilterWhere(['like', 'title', $text]);
		 $sql->orFilterWhere(['like', 'coord.text', $text]);
		 if ($region > 0) {
		     $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), $region);
		     $sql->andWhere(['region' => $reg_all]);
		  }
		  $sql->GroupBy('blog.id');
		  $blogs = $sql->Limit(27)->asArray()->all();



   
		  $blogi = [];
		  foreach($blogs as $blog) {
			  $regions = explode(', ',$blog['coordtext']);
			  foreach($regions as $reg) {
		        if (strpos(mb_strtolower (trim($reg)), mb_strtolower ($text)) !== false) {
			       $blogi[] = array('title' => $reg, 'category' => $blog['category']);
				   $act = true;
			    }
			  }

			   if(!isset($act)) {
			      $blogi[] = array('title' => Yii::$app->userFunctions2->textName($blog['title'], $text), 'category' => $blog['category']);
		       }
			 }
		
		 
		  
	       $blogi =  array_unique($blogi, SORT_REGULAR);
	       return $this->render('index', compact('blogi', 'region', 'text'));
		}
		 
    }
	
	
}
