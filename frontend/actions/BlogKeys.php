<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use common\models\BlogKey;
use frontend\models\KeySearch;
use common\models\Blog;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use common\models\CarshopSearch;
class BlogKeys extends Action
{

    public function run()
    {
      $model = new KeySearch();
          $save = false;
      if ($model->load(Yii::$app->request->post()) && $model->validate()) {
          $save = true;
      }
	     return $this->controller->render('blogkeys', compact('model', 'save'));
	  }
	

}