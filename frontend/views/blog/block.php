
     <?foreach($block as $res) {?>
	    <div class="col-md-12">
        <div class="mega <? if ($res['header_ok']){?> block-top-bottom <? }else{ ?><? }?>">	
       <? if ($res['header_ok']){?><h3 class="block-header"><?=$res['name']?></h3><? } ?>
           <div class="body_block"><?= $res['text']?></div>
	   </div>
	   </div>
      <? } ?>

