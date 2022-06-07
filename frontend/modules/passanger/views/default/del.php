<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */
use yii\helpers\Url;
$this->title = 'Удаление';
$this->params['h1'] = 'Удаление';
$this->registerMetaTag(['name' => 'description','content' => 'Удаление']);
$this->registerMetaTag(['name' => 'keywords','content' => 'Удаление']);
$this->params['breadcrumbs'][] = array('label'=> 'Попутчики', 'url'=>Url::toRoute('/passanger'));
$this->params['breadcrumbs'][] = array('label'=> 'Удаление');
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);

$js = <<< JS
setTimeout(function() {
	window.location.replace("/passanger");
}, 2000);
JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );
?>
<div class="col-md-12 row">
<br>
<br>
<br>
<div class="alert alert-success">Поездка удалена.</div>
</div>

