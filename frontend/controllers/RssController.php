<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;
use yii\web\Response;
use common\models\Shop;
use common\models\Blog;
use common\models\Article;
use yii\data\Pagination;
class RssController extends Controller
{

    public function actionBoard()
    {
	$blog = Blog::find()->andWhere(['status_id' => 1, 'active' => 1])->orderBy(['id' => SORT_DESC])->limit(20)->all();
	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$act = 'board';
		return $this->render('index', compact('blog', 'act'));
    }
	
	
	
    public function actionAkcii()
    {

	$cat_all = Yii::$app->userFunctions->recursСat(Yii::$app->caches->catRelative(), 164);	

	$blog = Blog::find()->andWhere(['status_id' => 1, 'active' => 1, 'category'=>$cat_all])->orderBy(['id' => SORT_DESC])->limit(20)->all();
    $article = Article::find()->andWhere(['status' => 1])->orderBy(['id' => SORT_DESC])->limit(20)->all();	

	
	Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$act = 'akcii';
		

		return $this->render('index', compact('blog', 'article', 'act'));
    }
	
	
	
	
	
    public function actionShop()
    {
	$shop = Shop::find()->andWhere(['status' => 1, 'active' => 1])->orderBy(['id' => SORT_DESC])->limit(20)->all();

	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$act = 'shop';
		
		return $this->render('index', compact('shop', 'act'));
    }
	
	
	
	 public function actionArticle()
    {
	$article = Article::find()->andWhere(['status' => 1])->orderBy(['id' => SORT_DESC])->limit(20)->all();

	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$act = 'article';
		
		return $this->render('index', compact('article', 'act'));
    }
	
}