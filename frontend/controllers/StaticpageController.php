<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\StaticPage;
use yii\web\NotFoundHttpException;

class StaticpageController extends Controller
{

    public function actionIndex($url)
    {
	    $model = $this->findModel($url);

	    return $this->render('staticpage', compact('model'));
    }
	
	
	
	
	 protected function findModel($url)
    {
		$url = str_replace('.htm','',$url);
        if (($model = StaticPage::find()->Where(['url'=>$url])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }
}
