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
class SitemapController extends Controller
{

    public function actionIndex()
    {
	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$act = 'all';
		return $this->render('index', compact('act'));
    }
	
	
    public function actionRegion()
    {
	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$act = 'region';
		return $this->render('index', compact('act'));
    }


    public function actionCategory()
    {
	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$act = 'category';
		return $this->render('index', compact('act'));
    }
    
    public function actionArticle()
    {
	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$articles = Article::find()->Where(['status' => 1])->orderBy(['id' => SORT_DESC])->all();
		$act = 'article';
		return $this->render('index', compact('act', 'articles'));
    }	
	
	
	public function actionShop()
    {
	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
		$shop = Shop::find()->Where(['status' => 1])->andWhere(['active' => 1])->all();

		$act = 'shop';
		return $this->render('index', compact('act', 'shop'));
    }	
	
	
	
	
	public function actionBlog()
    {
	 Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
		$this->layout = false;
    	$query = Blog::find()->andWhere([ 'status_id' => 1, 'active' => 1])->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 1000]);
		$counter = $query->count();
	    $blogs = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

		$act = 'blog';
		return $this->render('index', compact('act', 'blogs', 'pages', 'counter'));
    }	
}