<?php
use yii\helpers\Url;

?>
<div class="col-md-3">
<div class="col-md-3 hello hidden-xs"><span class="name-shop"><?=$this->params['title']?> </span></div>
<div class="row personal-3-left hidden-xs">
<div class="grafik">
<div class="hr_add graf-info" style="margin-top: 20px;"><i class="fa fa-clock" aria-hidden="true"></i> График работы</div>
 <table class="table table-bordered">
      <tbody>
<? foreach ($shop->grafik as $key => $graf) { ?>
        <tr <? if (isset($graf['vih'])) {?>class="opacity"<? } ?>>
          <td><?=$arr[$key]?></td>
          <td style="white-space: nowrap;" <? if (isset($graf['vih'])) {?>colspan="2"<? } ?>>
		  <? if (isset($graf['vih'])) {?>Выходной<?}else{?>
		       <?=$graf['time']?>
		  <? } ?>
		  </td>
		  <? if (!isset($graf['vih'])) {?>
              <td><?if (isset($graf['obed']) && $graf['obed']) {?><div>Обед:</div><?=$graf['obed']?><?}else{?><div>Без обеда</div><? } ?></td>
		  <? } ?>
        </tr>
<? } ?>	
      </tbody>
 </table>
</div>
</div>
<br>
<? if(isset($blocks_right)) {?>
<?= $this->render('/layouts/block.php', ['block' => $blocks_right] ) ?>
<?}?>
<div class="row personal-3-left">
<div class="hr_add graf-info"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Оценить компанию</div>
<form id="vote" method="post"  action="">
<table class="table table-bordered">
      <tbody class="vote">
        <tr>
          <td><span>Обслуга</span><div>
		  <div class="form-group field-blog-status_id">
		    <section id="smile">
               <div class="checkbox-smile">
                  <input <?if(isset($vot->obsluga_plus) && $vot->obsluga_plus > $vot->obsluga_minus) {?>checked<?}?> type="checkbox" <?if($vote){?>disabled<?}?> name="ShopReating[services]" value="1">
                  <label></label>
               </div>
            </section>
		   </div>
		   <?if ($vot) {?>
		   	   <div class="ocen">
		       <div class="good"><span class="man"><?=$vot->obsluga_plus?></span> <i class="fa fa-thumbs-up" aria-hidden="true"></i></div>
			   <div class="bad"><span class="man"><?=$vot->obsluga_minus?></span> <i class="fa fa-thumbs-down" aria-hidden="true"></i></div>
		       </div>
		   <? } ?> 
	      </td>
          <td><span>Цена</span>
		  	<div class="form-group field-blog-status_id">
		    <section id="smile">
               <div class="checkbox-smile">
                  <input <?if(isset($vot->cena_plus) && $vot->cena_plus > $vot->cena_minus) {?>checked<?}?> type="checkbox" <?if($vote){?>disabled<?}?> name="ShopReating[price]" value="1">
                  <label></label>
               </div>
            </section>
		   </div>
		    <?if ($vot) {?>
		   	   <div class="ocen">
		       <div class="good"><span class="man"><?=$vot->cena_plus?></span> <i class="fa fa-thumbs-up" aria-hidden="true"></i></div>
			   <div class="bad"><span class="man"><?=$vot->cena_minus?></span> <i class="fa fa-thumbs-down" aria-hidden="true"></i></div>
		       </div>
		   <? } ?> 
		  </td>
          <td><span>Качество</span>
		  	<div class="form-group field-blog-status_id">
		    <section id="smile">
               <div class="checkbox-smile">
                  <input <?if(isset($vot->kachestvo_plus) && $vot->kachestvo_plus > $vot->kachestvo_minus) {?>checked<?}?> type="checkbox" <?if($vote){?>disabled<?}?> name="ShopReating[quality]" value="1">
                  <label></label>
               </div>
            </section>
		   </div>
		   <?if ($vot) {?>
		   	   <div class="ocen">
		       <div class="good"><span class="man"><?=$vot->kachestvo_plus?></span> <i class="fa fa-thumbs-up" aria-hidden="true"></i></div>
			   <div class="bad"><span class="man"><?=$vot->kachestvo_minus?></span> <i class="fa fa-thumbs-down" aria-hidden="true"></i></div>
		       </div>
		   <? } ?>   
		  </td>
        </tr>
		
		    <tr class="td_none">
		       <td colspan="3">
			    <? if (!$vote) {?>
		          <input type="hidden" name="ShopReating[shop_id]" value="<?=$shop->id?>"/>
		          <input type="button" id="btn" class="btn btn-success vote-go" data-href="<?=Url::to(['/reating', 'id' => $shop->id])?>" value="Отправить оценку" /> 
                <?}else{?>	
                     <div class="non-vote">Уже оценивали этот магазин</div>
 	            <?}?>				
			  </td>
		    </tr>
	
      </tbody>
    </table>
</form>
</div>
</div>