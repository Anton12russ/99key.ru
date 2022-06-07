 <div class="cat-body">
 <div class="cat-h4"> 
 <span class="h4_region">

 <?if (isset($ids)) {?>
	<span class="category_model_back" data-back="<?=$ids?>"><i class="fa fa-reply-all" aria-hidden="true"></i></span>
	  Искать по <a data-id="<?=$id_one?>" class="cat-ok" href="#"  data-text="<?=Yii::$app->caches->Category()[$id_one]['name']?>">Искать по всей категории <span class="span-name-reg">(<?=Yii::$app->caches->Category()[$id_one]['name']?>)</span></a><span>
	  </span>
 <? }else{?>	
	     <a data-id="" class="cat-ok" href="#" data-text="Все категории">Поиск по всем категориям</a><span>
	  </span>
 <? }?>	 
	    </span>
		</div>

	  <br>
	  <div class="overflow_modal">
	      <ul class="category_ajax">
		 <? foreach ($array as $res ) {?>
		     <? if ($res['children']) { ?>
			    <li ><?if($res['img']) {?><img src="<?=$res['img']?>"><?}?><span  <?if($idorig == $res['id']) {?>style="color: red"<? } ?> class="category_span" data-id="<?=$res['id']?>"><?=$res['name']?></span></li>
		     <? }else{ ?>
			 
			  
                      <li><a data-id="<?=$res['id']?>" class="cat-ok <?if($idorig == $res['id']) {?> active_reg<? } ?>" href="#" data-text="<?=$res['name']?>"><?=$res['name']?></a></li>  
        		
					  
			 <? }?>
		  <? }?>
		     </ul>
	</div>		 
</div>				 
<?php exit();?>