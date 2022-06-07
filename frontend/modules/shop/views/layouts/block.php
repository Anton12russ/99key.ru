<? if ($block) {?>
     <?foreach($block as $res) {?>

	   <!--------------Левый блок------------->
	   <? if ($res['position'] == 'right') {?>
		 <? if ($res['route'] == 'board')  {?>
	         <?echo $this->render('services.php', ['blog' => $res['text']]);?>
	    <? }elseif($res['route'] == 'shop'){ ?>
		     <? echo $this->render('shop_top.php', ['shops' => $res['text']]);?>
		<? }elseif($res['route'] == 'passenger' && $res['text'] != ''){ ?>
		    <? echo $this->render('passenger.php', ['passenger' => $res['text']]);?>
        <? }elseif($res['route'] == 'passenger' && $res['text'] == ''){ ?>
		   <? 
		     $this->registerCssFile('/passanger_js/css/calendar_main.css');
  $this->registerJsFile('/passanger_js/js/calendar.js',['depends' => [\yii\web\JqueryAsset::className()]]);
  $this->registerJsFile('/passanger_js/js/calendar_script.js',['depends' => [\yii\web\JqueryAsset::className()]]);
  
		   ?>
		<? }else{ ?>
		 <div class="row personal-3-left block-right" style="width: auto;  display: block; margin-bottom: 0px;">
       <? if ($res['header_ok']){?><h3 class="block-header"><?=$res['name']?></h3><? } ?>
           <div class="body_block"><?=Yii::$app->userFunctions3->timer($res['text'])?></div>
		 </div>
		 <br>
	 <? } ?>
       <? } ?>
    	
		
	   <!--------------Под шапкой блок------------->
	   <? if ($res['position'] == 'top') {?>
	 
	    <div class="col-md-12">
          <div style="margin-bottom: 0px;" class="
		<?if (strpos($res['text'], 'class="iframe') === false) {?><? }else{ ?>
	        <?$res['text'] = str_replace('https://1tu.ru/ajax/timeruser','https://'.getenv("HTTP_HOST").'/timeruser',$res['text'])?>
		<? } ?>
		  <? if ($res['header_ok']){?> block-top-bottom <? }else{ ?><? }?>">	
           <? if ($res['header_ok']){?><h3 class="block-header"><?=$res['name']?></h3><? } ?>
               <div class="body_block">
			   	<? if (is_array($res['text'])) {?>
				      <div class="row">
                       <?echo $this->render('services.php', ['blog' => $res['text'], 'horisont' => 'true']);?>
					  </div>
	            <? }else{ ?> 
			         <?= Yii::$app->userFunctions3->timer($res['text'])?>
			    <? } ?>
			   </div>
	      </div>
	   </div>
       <? } ?>
		
	   <!--------------Под шапкой блок------------->
	   <? if ($res['position'] == 'add') {?>
	   
	    <div class="col-md-12">
          <div style="margin-bottom: 0px;" class="
		  	<?if (strpos($res['text'], 'class="iframe') === false) {?><? }else{ ?>
	
	        <?$res['text'] = str_replace('https://1tu.ru/ajax/timeruser','https://'.getenv("HTTP_HOST").'/timeruser',$res['text'])?>
		<? } ?>
		  <? if ($res['header_ok']){?> block-top-bottom <? }else{ ?><? }?>">	
           <? if ($res['header_ok']){?><h3 class="block-header"><?=$res['name']?></h3><? } ?>
               <div class="body_block">
			   	<? if (is_array($res['text'])) {?>
				      <div class="row">
                       <?echo $this->render('services.php', ['blog' => $res['text'], 'horisont' => 'true']);?>
					  </div>
	            <? }else{ ?> 
			         <?= Yii::$app->userFunctions3->timer($res['text'])?>
			    <? } ?>
			   </div>
	      </div>
	   </div>
       <? } ?>		
		
		
			   <!--------------Под шапкой блок------------->
	   <? if ($res['position'] == 'footer') {?>
	   <div class="col-md-12">
          <div class="
		  	<?if (strpos($res['text'], 'class="iframe') === false) {?><? }else{ ?>
	
	        <?$res['text'] = str_replace('https://1tu.ru/ajax/timeruser','https://'.getenv("HTTP_HOST").'/timeruser',$res['text'])?>
		<? } ?>
		  <? if ($res['header_ok']){?> block-top-bottom <? }else{ ?><? }?>">	
           <? if ($res['header_ok']){?><h3 class="block-header"><?=$res['name']?></h3><? } ?>
               <div class="body_block">
			   	<? if (is_array($res['text'])) {?>
				      <div class="row">
                       <?echo $this->render('services.php', ['blog' => $res['text'], 'horisont' => 'true']);?>
					  </div>
	            <? }else{ ?> 
			         <?= Yii::$app->userFunctions3->timer($res['text'])?>
			    <? } ?>
			   </div>
	      </div>
	   </div>
       <? } ?>
		
		
		
		
      <? } ?>
<? } ?>
