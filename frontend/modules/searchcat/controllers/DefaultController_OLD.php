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
         $sql = Blog::find()->select(['title', 'category'])->Where([ 'active' => 1, 'status_id' => 1]);
		 $sql->andFilterWhere(['like', 'title', $text]);
		 
		  if ($region > 0) {
		     $reg_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->regRelative(), $region);
		     $sql->andWhere(['region' => $reg_all]);
		  }
		  
		  $blogs = $sql->Limit(7)->asArray()->all();

		  $blogi = [];
		  foreach($blogs as $blog) {
			  $blogi[] = array('title' => Yii::$app->userFunctions2->textName($blog['title'], $text), 'category' => $blog['category']);
		  }
		  
		  
	       $blogi =  array_unique($blogi, SORT_REGULAR);
	       return $this->render('index', compact('blogi', 'region', 'text'));
		}
		 
    }
	
	
}
