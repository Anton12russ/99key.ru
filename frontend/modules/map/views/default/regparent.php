     <div class="reg-body">
	<div class="cat-h4"> 
 <span class="h4_region">

 <?if (isset($ids)) {?>
	 <span class="region_model_back" data-back="<?=$ids?>"><i class="fa fa-reply-all" aria-hidden="true"></i></span>
	  Искать по <a data-id="<?=$id_one?>" class="cat-ok" href="#" data-text="<?=Yii::$app->caches->Region()[$id_one]['name']?>">Искать по всему региону</a><span>
	  </span>
 <? }else{?>	
	     <a data-id="" class="cat-ok" href="#"  data-text="По всем странам">Поиск по всем странам</a><span>
	  </span>
 <? }?>	 
	    </span>
		</div>
	  <br>

	  <div class="overflow_modal">
	      <ul class="region_ajax">
		 <? foreach ($array as $res ) {?>
		     <? if ($res['children']) { ?>
			    <li><span class="region_span" <?if($idorig == $res['id']) {?>style="color: red"<? } ?> data-link="<?=$res['url']?>" data-id="<?=$res['id']?>"><?=$res['name']?></span></li>
		     <? }else{ ?>
			
                      <li><a data-id="<?=$res['id']?>" class="cat-ok <?if($idorig == $res['id']) {?> active_reg<? } ?>" data-text="<?=$res['name']?>" href="#"><?=$res['name']?></a></li>  
             		
					  
			 <? }?>
		  <? }?>
		     </ul>
	</div>		 
	</div>		 
<?php exit();?>