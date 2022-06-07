<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

$this->title = 'Техподдержка';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
<div class="">
   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">
    <h1><i class="fa fa-life-ring" aria-hidden="true"></i> <?=$this->title?></h1>

	   <a class="btn btn-success" href="<?= Url::to(['user/supportadd']);?>">Новый запрос</a>

           <?php Pjax::begin([ 'id' => 'pjaxDispute']); ?>

<div class="col-md-12">
	   <br>
<? foreach($route as $res) { ?>
<div class="row">
 <span class="time_disput"><?=$res['date_add']?></span>
	<div style="padding-bottom: 10px;" class="mess_disput col-md-12"><a data-pjax="0" href="<?= Url::to(['user/supportone', 'id'=>$res->id]);?>">Тема (<?=$res->subject?>)</a>
	<? if ($res['flag_user'] == 1) { ?> <span style="color: green; font-size: 11px;">(Новое сообщение)</span><? } ?>   
    <? if ($res['flag_admin'] == 1) { ?> <span style="color: #999; font-size: 11px;">(На рассмотрении)</span><? } ?> 
	<br>
	</div>
	</div>	
<? } ?>
</div>

<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>  

<?php Pjax::end(); ?> 
	
   </div>
</div>
</div>
</div>