    <div style="display: table; width: 100%;"> 
 <span class="h4_region">

 <?if (isset($ids)) {?>
	<span class="region_model_back" data-back="<?=$ids?>">Назад <i class="fa fa-reply-all fa-lg" aria-hidden="true"></i></span>
	  Выберите населенный пункт или <a href="/<?=$id_one?>">Искать по всему региону</a><span>
	  </span>
 <? }else{?>	
	     <a href="/ajax/region-all">Показать все объявления по России</a><span>
	  </span>
 <? }?>	 
	    </span>
		</div>
	  <br>
	  <div class="form-group reg-context">
         <input type="text" placeholder="Начните вводить название города" id="region-text" class="form-control">
     </div>


	  <br>
	  <div class="overflow_modal">
	      <ul class="region_ajax">
		 <? foreach ($array as $res ) {?>
		     <? if ($res['children']) { ?>
			    <li><span class="region_span" data-link="<?=$res['url']?>" data-id="<?=$res['id']?>"><?=$res['name']?></span></li>
		     <? }else{ ?>
			 
			   <? if ($res['id'] == (string)Yii::$app->request->cookies['region']) {?>
			          <li><span class="active_reg" ><?=$res['name']?></span></li>
				<? }else{?>
                      <li><a href="<?=$res['url']?>"><?=$res['name']?></a></li>  
                <? }?>			
					  
			 <? }?>
		  <? }?>
		     </ul>
	</div>		 
			 
<?php exit();?>