<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;

$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);

$this->params['note']['id'] = $blog->id;
$this->params['note']['pad'] = $notepad;
foreach ($fields as $res) {
  if ($res['type'] == 'y') {if ($res['hide'] == 1) {if(!Yii::$app->user->isGuest) {$youtube = $res['value'];}else{$youtube = 'hide';}}else{$youtube = $res['value'];}}
  if ($res['type_string'] == 't') {if ($res['hide'] == 1) {if(!Yii::$app->user->isGuest) {$phone = $res['value'];}else{$phone = 'авторизируйтесь';}}else{$phone = $res['value'];}}
} 
sort($price); 
?>



<div class="col-md-12 one-body-map">
<div class="over-auto">
<div class="over-auto-body">
<div class="div-gallery">
	  <?php if ($blog->imageBlog) { ?>
	    <ul id="imageGallery">
              <?php foreach ($blog->imageBlog as $images) { ?>
						 <li data-thumb="<?= '/uploads/images/board/mini/'.$images['image']?>" data-src="<?= '/uploads/images/board/maxi/'.$images['image']?>"> 
						     <div style="background-image: url(<?= '/uploads/images/board/maxi/'.$images['image']?>);" class="false_window"></div>
                         </li> 
              <?php } ?>
		</ul>
       <?php } ?>	
</div>

	   <div class="price-div">
	   <div class="one-price"> 
		<? if (isset($price[0]['value'])) {?>
		<? if (isset($price[0]['hide']) && $price[0]['hide'] > 0) {?>
	       <?if (!Yii::$app->user->isGuest) { echo $price[0]['value'];?> <i class="fa <?=$price[0]['rates']['text']?>" aria-hidden="true"></i><? foreach($fields as $res) { if ($res['type_string'] == 'q') {?><span class="torg">торг</span><?}}?><?}else{?>После авторизации<?}?>
		<?}else{?>	
		  <?=$price[0]['value']?> <i class="fa <?=$price[0]['rates']['text']?>" aria-hidden="true"></i><? foreach($fields as $res) { if ($res['type_string'] == 'q') {?> <span class="torg">торг</span><?}}?>
		<?}?>
		<?}else{?>
		     Безценно
		<?}?>
	    </div>
	 </div>


    <div class="col-md-12 notepad-map-plus <?if (isset($notepad[$blog->id])) {?>link<? } ?>"   data-id="<?=$blog->id?>"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить в избранное" >
		 <span><?if (isset($notepad[$blog->id])) {?>В избранном<?}else{?>Добавить в избранное<? } ?></span>
	</div>



		   <div class="header-one-div" data-phone="<?php if (isset($phone)){?><? }else{  ?>Не указан<? }  ?>"><span class="name-one">Автор:</span> <?=$blog->author['username']?><br> <span class="name-one">Телефон:</span> <?=$phone?></div>
		   <!--  <div class="header-one-div one-mass" data-toggle="tooltip" data-placement="top" title="Написать автору"><span><i class="fa fa-regular fa-envelopes" aria-hidden="true"></i> Написать</span></div>-->

   
   	<table class="table table-bordered table-one padding-no">
      <tbody>
  	    <? if ($coord) { 
		foreach($coord as $key => $res) {?>
	         <tr>
                <td><?=$res['name']?></td>
                <td> <?php if ($res['hide']) {if(!Yii::$app->user->isGuest) {?><?if ($res['address']) {echo  $res['address'].'<br>';}?> <?=$res['value']?><?}else{echo 'После авторизации';} }else{ ?><?if ($res['address']) {echo  $res['address'].'<br>';}?> <?=$res['value']?><? }?></td>
             </tr>
         <? } }?> 
	  	<? if ($price) { 
		foreach($price as $key => $res) {?>
		   <? if ($key) {?>
	         <tr>
                <td><?=$res['name']?></td>
                <td> <?php if ($res['hide']) {if(!Yii::$app->user->isGuest) {?><?=$res['value']?>  (<?=$res['rates']['name']?>)<?}else{echo 'После авторизации';} }else{ ?><?=$res['value']?>  (<?=$res['rates']['name']?>) <? }?></td>
             </tr>
		   <? } ?>  
         <? } }?> 
	
	<?php 
	$you_key = 0;
	$phone_key = 0;
	foreach ($fields as $res) {?>
	   <!--телефон-->
	   <?php 
	   if ($res['type_string'] == 't' ) { $phone_key++; if ($phone_key >1) {?>
	      <tr>
             <td><?=$res['name']?></td>
             <td> <?php if ($res['hide']) {if(!Yii::$app->user->isGuest) {echo $res['value'];}else{echo 'После авторизации';} }else{ ?><?=$res['value']?> <? }?></td>
          </tr>
	  <? } }
	   if ($res['type'] == 'y' ) { $you_key++; if ($you_key >1) {?>
	      <tr>
              <td><?=$res['name']?></td>
              <td> <?php if ($res['hide']) {if(!Yii::$app->user->isGuest) {echo $res['value'];}else{echo 'После авторизации';} }else{ ?><?=$res['value']?> <? }?></td>
          </tr>
	  <? } }
	  if ($res['type_string'] != 't' && $res['type'] != 'y') {
	  ?>
	  	
	      <tr>
             <td><?=$res['name']?></td>
             <td> <?php if ($res['hide']) {if(!Yii::$app->user->isGuest) {echo $res['value'];}else{echo 'После авторизации';} }else{ ?><?=$res['value']?> <? }?></td>
          </tr>
	 <?} }?>
	
	</tbody>
    </table>
  </div>
</div> 
</div>

   <div class="modal-h"><div class="modal-h1"><div class="modal-h1-div"><?=$blog->title ?></div></div> <?if(Yii::$app->request->get('close')) {?><span class="close"><? } ?></span></div>



