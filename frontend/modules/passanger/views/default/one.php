<?php
/* @var $this yii\web\View */
/* @var $one common\models\one */
use yii\helpers\Url;

use yii\widgets\Pjax;
$this->registerCssFile('/css/one.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/passanger_js/css/one.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/passanger_js/js/script.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title = 'Попутчики из '.Yii::$app->userFunctions2->address($one->ot).' до '.Yii::$app->userFunctions2->address($one->kuda);
if ($one->user_id != Yii::$app->user->id) {
   $this->params['h1'] = 'Попутчик из "'.Yii::$app->userFunctions2->address($one->ot).'"';
}
$this->registerJsFile('/js/one.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerMetaTag(['name' => 'description','content' => 'попутчик из '.Yii::$app->userFunctions2->address($one->ot)]);
$this->registerMetaTag(['name' => 'keywords','content' => 'попутчик, '.Yii::$app->userFunctions2->address($one->ot)]);
$this->params['breadcrumbs'][] = array('label'=> 'Попутчики', 'url'=>Url::toRoute('/passanger'));
$this->params['breadcrumbs'][] = array('label'=> 'Попутчик из "'.Yii::$app->userFunctions2->address($one->ot).'"');
$prenad = [0 => 'Водитель',1 => 'Пассажир'];
$pol = [0 => 'Мужчина',1 => 'Женщина'];
?>


<div class="col-md-12 one-body">

 <? if (isset($_GET['save'])) {?> 
        <div class="alert-del alert alert-success">Информация опубликована.</div>
   <? }?>
	<?if ($one->user_id == Yii::$app->user->id || Yii::$app->user->can('updateBoard')) {?>
	 <div class="col-md-12">
	  <h3>Быстрый редактор:</h3>
	 <div class="edit">

	 <div class="col-md-12">
	<?php Pjax::begin([ 'id' => 'pjaxFormedit','enablePushState' => false]); ?>
	 <form action=""  method="get"  data-pjax='true'>
	 <div class="form-group">
            <label class="control-label" for="passanger-mesta">Количество мест</label>
			<div class="col-md-12 row rows-edit">
			<input id="edit-mesta"  type="hidden" name="id" value="<?=$one->id?>">
			<input id="edit-mesta" style="max-width: 100px;" type="number" class="form-control" name="mesta" value="<?=$one->mesta?>">
            <button class="btn btn-success">Сохранить</button>
			</div>
	 </div>
	 </form>
	 <?php Pjax::end(); ?>
	 </div>
	<br>
	   <div class="col-md-12"><a  class="btn btn-info" href="<?=Url::to(['/passanger/update', 'id' => $one->id])?>">Редактировать</a> </div> 
	   <div style="margin-top: 10px;" class="col-md-12"><a target="_blank" class="btn btn-danger" href="<?=Url::to(['/passanger/del', 'id' => $one->id])?>">Удалить</a></div> 
	</div> 
	</div> 
	<? } ?>
    <div <?if ($one->user_id == Yii::$app->user->id || Yii::$app->user->can('updateBoard')) {?>class="opac-lm"<? } ?>>
    <div class="col-md-12 padd-h1">
	<div class="dop-one">
	<br>
	<?if($one->price) {?>
	<div class="price-div" style="float: left;">
	   <div data-toggle="tooltip" data-placement="top" title="Цена" class="one-price"> 
	   <?if($one->price) {?>
		   <?=$one->price?> <i class="fa <?=$rat['text']?>" aria-hidden="true"></i> 
	   <? }else{ ?>
	       Цена не указана
	   <? } ?>
	   </div>
	 </div>
 <? } ?>
	   <div class="header-one">
	     <div class="ava-name-body col-md-4"><a target="_blank" class="go-user" href="<?=Url::to(['/users', 'id'=>$one->user_id])?>"> <div class="header-one-div ava-name" data-toggle="tooltip" data-html="true" data-placement="top" title="Автор <?if(iconv_strlen($one->author['username']) >= 8) {?><br><strong class='author_one'><?=$one->author['username']?><?}?></strong>"><span><i class="fa fa-solid fa-user" aria-hidden="true"></i> <? if(iconv_strlen($one->author['username']) >= 8) {echo 'Автор';}else{echo $one->author['username'];}?></span></div></a></div>
         <div class="header-one-div one-phone col-md-5" data-phone="<?php if ($one->fields->phone){?><?=$one->fields->phone?><? }else{  ?>Не указан<? }  ?>" data-toggle="tooltip" data-placement="top" title="Показать Телефон"><span><i class="fa-regular fa-phone" aria-hidden="true"></i> Телефон</span></div>    
	   </div>
	   
	   


		
	</div>
	
	

	
	<div class="row">
	    <? if(isset($one->img) && $one->img > 0) {?>
         <div class="col-md-3" style="margin-bottom: 20px;
}">
             <img src="/uploads/images/passanger/logo/<?=$one->img?>"/>
		 </div>
		
     <? } ?>	  
	 
<? if(isset($one->img) && $one->img > 0) {?>
        <div class="col-md-9">
		
  <? }else{ ?>
        <div class="col-md-12">
    <? } ?>
	

	  
	<table class="table table-bordered table-one">
      <tbody>
	 <? if($one->fields->name) {?>	
	 <tr>
		<td>Имя:</td>
        <td><?=$one->fields->name?></td>
	 </tr>
	 <? }?>	
	  <tr>
		<td>Места:</td>
        <td><?=$one->mesta?></td>
	 </tr>
	 <tr>
		<td>Пренадлежность:</td>
        <td><?=$prenad[$one->appliances]?></td>
	  </tr>
	    <tr>
		<td>Откуда:</td>
        <td><?=Yii::$app->userFunctions2->address($one->ot)?></td>
	  </tr>
	    <tr>
		<td>Куда:</td>
        <td><?=Yii::$app->userFunctions2->address($one->kuda)?></td>
	  </tr>
	  <tr>
	  <?if($one->appliances == 0) {?>
		<td>Время отправления:</td>
	  <? }else{ ?>
	    <td>Желаемое время выезда:</td>
	  <? } ?>
        <td><?=Yii::$app->userFunctions2->new_time(strtotime($one->time));?></td>
	  </tr>
	   <tr>
		<td>Пол:</td>
        <td><?=$pol[$one->pol]?></td>
	  </tr>
	  <?if($one->appliances == 0 && $one->fields->marka) {?>
	  <tr>
		<td>Марка авто:</td>
        <td><?print_r($one->fields->marka)?></td>
	  </tr>
	  <? } ?>
	</tbody>
    </table>
	</div>
	</div>
	
	
	
	

	
	
	
	
	
	  
  <div class="col-md-12 block-one">
     <div class="row">
        <h3>Описание:</h3> 
<? if(isset($one->author->online)) {?>
  <? if($one->author->online > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {?>
	      <div class="online-one">Онлайн</div>
	  <?}else{?>
	      <div class="offline-one">Был(а) в сети <?=Yii::$app->userFunctions2->new_time(strtotime($one->author->online));?></div>
	  <? } ?>	
 <? } ?>	  
	   	 <div class="text-one">
		 <span class="date_blog">Просмотров: <?=$one->fields->views?></span><br>
		 <span class="date_blog">Добавлено: <?=$one->date_add?></span><br><br>
		 <?=$one->fields->text?>
		 </div>
		 
		 <? if (!Yii::$app->user->isGuest) {?>
		    <span class="mail-send">Написать автору <i class="fa fa-regular fa-envelopes" aria-hidden="true"></i></span>
		 <? }else{?>
		    <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Авторизируйтесь, чтобы написать автору."  class="mail-send-err">Написать автору <i class="fa fa-regular fa-envelopes" aria-hidden="true"></i></span>
		 <? }?>
     </div>
     <div style="padding-top: 20px;" class="row">
       <script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
       <script src="https://yastatic.net/share2/share.js"></script>
       <div class="ya-share2" data-curtain data-limit="5" data-services="vkontakte,odnoklassniki,skype,telegram,viber,moimir,messenger"></div>
     </div>
	<div class="row maps-one">
	<!--<details>
	<summary>Посмотреть маршрут на карте <i class="fa fa-map-location-dot" aria-hidden="true"></i></summary>-->
	<h3>Маршрут</h3>
	   	 <div class="iframe-div">
		    <iframe src="/passanger/maps?ot=<?=$one->ot?>&kuda=<?=$one->kuda?>&coord_ot=<?=$one->fields->coord_ot?>&coord_kuda=<?=$one->fields->coord_kuda?>" style="width:100%; height: 100%;" frameborder="0"></iframe>
		 </div>
     </div>
     <!--</details>-->
	</div>
	 </div>
</div>
</div>

