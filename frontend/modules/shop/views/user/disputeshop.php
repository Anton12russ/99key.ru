<?php
/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/user.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title = 'Споры в отношении Вашей продукции';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = $this->title;
$services = Yii::$app->userFunctions->servicesBlog();
foreach($services as $res) {
	$arrserv[$res['type']] = $res;
}


?>

	


<div class="row">

   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">


<h1><i class="fa fa-balance-scale" aria-hidden="true"></i> <?=$this->title?></h1>

	

<?php Pjax::begin([ 'id' => 'pjaxDispute']); ?>

<div class="col-md-12">
<? if($comment){ ?>
<? foreach($comment as $res) { ?>
<div class="row">
 <span class="time_disput">Дата открытия (<?=$res['date']?>)</span>

	<div style="padding-bottom: 10px;" class="mess_disput col-md-12"><a data-pjax="0" href="<?= Url::to(['user/disputshopone', 'id'=>$res->id]);?>">Заказ  № <?=$res->car['id']?></a>
	<? if ($res['status'] == 1) { ?>
		<span style="color: red;">(Спор открыт)</span>
	<? }else{ ?>
	   <span style="color: #ccc;">(Спор закрыт)</span>
	<? } ?>   
	<br></div>
	</div>	
<? } ?>
<? }else{ ?>
<br>
<div class="alert alert-warning" role="alert"> Пока нет споров.</div>
<? } ?>
</div>

<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>  

<?php Pjax::end(); ?> 
</div>


</div>

</div>