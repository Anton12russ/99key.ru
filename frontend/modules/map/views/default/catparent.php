<div class="cat-body">
<? if (!$_GET['modal']) {?>
   <div class="cat-h4"> 
   <span class="h4_region">

 <? if (isset($ids)) {?>
	<span class="category_model_back" data-back="<?=$ids?>"><i class="fa fa-reply-all" aria-hidden="true"></i></span>
	  Искать по <a data-id="<?=$id_one?>" class="cat-ok" href="#"  data-text="<?=Yii::$app->caches->Category()[$id_one]['name']?>">
	  Искать по всей категории <span class="span-name-reg">(<?=Yii::$app->caches->Category()[$id_one]['name']?>)</span></a><span>
	</span>
	  <!--Это условие для проверки , если вызвано окно со страницы blog_add или auction_add-->
 <? }else{?>	
	     <a data-id="" class="cat-ok" href="#" data-text="Все категории">Поиск по всем категориям</a><span>
	  </span>
 <? }?>	 
	</span>
	</div>
	  <br>
<? }else{?>
	<? if (isset($ids)) {?>
	    <span class="category_model_back" data-back="<?=$ids?>"><i class="fa fa-reply-all" aria-hidden="true"></i></span>
	<? }?>
<? }?>
	  <div class="overflow_modal">
	      <ul class="category_ajax">
		 <? foreach ($array as $res ) {?>
		     <? if ($res['children']) { ?>
			           <li <?if($res['img']) {?>style="min-height: 45px;	<? } ?> "><?if($res['img']) {?><img src="<?=$res['img']?>"><?}?>
					   <span  style="
					   <?if($idorig == $res['id']) {?>color: red; <? } ?> 
					   <?if($res['img']) {?>
					       padding-left: 40px;  display: block;
                           margin-top: -35px;
						<? } ?> 
						   " class="category_span" data-plat="<?=$res['plat']?>" data-id="<?=$res['id']?>"><?=$res['name']?></span></li>
		     <? }else{ ?>
                      <li><span data-id="<?=$res['id']?>" class="cat-ok <?if($idorig == $res['id']) {?> active_reg<? } ?>" data-plat="<?=$res['plat']?>" data-text="<?=$res['name']?>"><?=$res['name']?></span></li>  
			 <? }?>
		  <? }?>
		     </ul>
	</div>		 
</div>				 
<?php exit();?>