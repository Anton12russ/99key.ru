<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;


$this->registerCssFile('/css/article_one.css', ['depends' => ['frontend\assets\AppAsset']]);

$this->title = $model->title;

$this->registerMetaTag(['name' => 'description','content' => $model->description]);
$this->registerMetaTag(['name' => 'keywords','content' => $model->keywords]);
$this->params['breadcrumbs'][] = $model->name;
$this->params['h1'] = $model->name;
?>


	
  <div class="col-md-12 one-body">
      <div class="col-md-12">
          <?=$model->text?>
	  </div>
  </div>
<br>
