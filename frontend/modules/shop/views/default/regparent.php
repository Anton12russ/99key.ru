    <div style="display: table; width: 100%;"> 
 <span class="h4_region">

 <?if (isset($ids)) {?>
	<span class="region_model_back" data-back="<?=$ids?>">Назад <i class="fa fa-reply-all fa-lg" aria-hidden="true"></i></span>
	  Выберите населенный пункт или <a class="data-click" href="#" data-id="<?=$id_one?>">Искать по всему региону</a><span>
	  </span>
 <? }else{?>	
	     <a href="#" class="regionall">Показать все объявления по России</a><span>
	  </span>
 <? }?>	 
	    </span>
		</div>
	  <br>


	  <br>
	  <div class="overflow_modal">
	      <ul class="region_ajax">
		 <? foreach ($array as $res ) {?>
		   <?if($count = Yii::$app->userFunctions3->counterregion($res['id'], Yii::$app->request->get('user_id'))) {?>
		     <? if ($res['children']) { ?>
			    <li><span class="region_span" data-link="<?=$res['url']?>" data-id="<?=$res['id']?>"><?=$res['name']?></span> (<?=$count?>)</li>
		     <? }else{ ?>
			 
			   <? if ($res['id'] == (string)Yii::$app->request->cookies['region']) {?>
			          <li><span class="active_reg" ><?=$res['name']?></span></li>
				<? }else{?>
                      <li><a class="data-click" href="#" data-id="<?=$res['id']?>"><?=$res['name']?></a> (<?=$count?>)</li>  
                <? }?>			
					  
			 <? }?>
		  <? }?>
		 <? }?>
		     </ul>
	</div>		 
			 
<?php exit();?>