<?php
  
  use yii\helpers\Url;
  use yii\helpers\Html;
  use yii\widgets\ActiveForm;
  use yii\widgets\Pjax;


$this->registerJsFile('/auction_all/auction.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$price = str_replace(' ', '', $price);

?>
<style>
.field-bet-price.has-error .help-block {
padding: 3px;
    border: 1px solid #a94442;
    border-radius: 3px;
    text-align: center;
}
</style>

<?$second = strtotime($blog->date_del)?>
	<?php Pjax::begin([ 'id' => 'pjaxContent2']); ?>
<? if($auction_act == 2) {?>
<div class="row">
<div class="col-md-12">
<div class="row">

<div class="col-md-3 price-div" style="text-align: center;padding-right: 0;">
    <div data-toggle="tooltip" data-placement="top" class="one-price" style=" margin-bottom: 0;float: none; padding-left: 5px; padding-right: 5px;"> 
        <span style="font-size: 15px;"><i style="color: green;" class="fa fa-gavel" aria-hidden="true"></i> Торги</span>
   </div>
 
</div>
<div class="col-md-9">
	 <div class="price-div" style="width: 100%; text-align: center; ">
	   <div data-toggle="tooltip" data-placement="top" title="Текущая ставка" class="one-price" style=" margin-bottom: 0;width: 100%; padding-left: 5px; padding-right: 5px;"> 
		  <span style="font-size: 15px;">Начальная ставка</span> <?=$price?> <i class="fa <?=$rates['text']?>" aria-hidden="true"></i>
	   </div>
	 </div>

</div>
 </div>
<br>
 </div>
 



<div class="col-md-12">

   <div class="btn-reserv <?  if($blog->reserv_user_id == Yii::$app->user->id) {?>userreserv<?} ?>"<?  if($blog->reserv_user_id == Yii::$app->user->id) {?>data-id="<?=$blog_id?>" data-toggle="modal" data-target="#auctionModal"<?} ?>>Зарезервировано</div>

</div>
 </div>

<?}else{ ?>

<div class="row">
<div class="col-md-12">
<div class="row">

<div class="col-md-3 price-div" style="text-align: center;padding-right: 0;">
    <div data-toggle="tooltip" data-placement="top" class="one-price" style=" margin-bottom: 0; float: none; padding-left: 5px; padding-right: 5px;"> 
        <span style="font-size: 15px;"><i style="color: green;" class="fa fa-gavel" aria-hidden="true"></i> Торги</span>
   </div>
 
</div>
<div class="col-md-9">

	 <div class="price-div" style="width: 100%; text-align: center; ">
	   <div data-toggle="tooltip" data-placement="top" title="Текущая ставка" class="one-price" style=" margin-bottom: 0;width: 100%; padding-left: 5px; padding-right: 5px;"> 
		  <span style="font-size: 15px;">Начальная ставка</span> <?=$price?> <i class="fa <?=$rates['text']?>" aria-hidden="true"></i>
	   </div>
	 </div>

</div>
 </div>
<br>
 </div>
 </div>


<div class="<?if(!$auction->price_moment){?>col-md-12<?}else{ ?>col-md-12<?} ?>">

<div class=" row"> 

	 <?  if(Yii::$app->user->id) {?>
	    <button <?if(!$auction->price_moment){?>style="width: 100%; margin-bottom: 10px;"<?} ?> style="width: 100%; margin-bottom: 10px;" class="btn-stavka btn btn-success " data-toggle="modal" data-target="#auctionModal"><i class="fa-solid fa-gavel fa-beat" style="--fa-beat-scale: 1.5;" aria-hidden="true"></i> Сделать ставку
        </button>
	 <?}else{ ?>
       <button <?if(!$auction->price_moment){?>style="width: 100%; margin-bottom: 10px;"<?} ?> style="width: 100%; margin-bottom: 10px;" class="btn-stavka btn btn-success loginpop login-head-class"  data-toggle="modal" data-target="#myModallogin"><i class="fa-solid fa-gavel fa-beat" style="--fa-beat-scale: 1.5;" aria-hidden="true"></i> Сделать ставку
        </button>
	 <?} ?>

</div>
</div>
<?} ?>


 <?  if($auction->price_moment && $auction_act != 2) {?>
 <?  if(Yii::$app->user->id) {?>
		<? if($auction->price_moment && $auction_act == 1) {?>
		  <div class="col-md-12">
		  <div class=" row">
		     <button style="width: 100%;margin-bottom: 10px;" data-id='<?=$blog_id?>'
	         <?if($blog->user_id == Yii::$app->user->id) {?>data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="Вы не можете зарезервировать собстсевнный лот"<? } ?>			 
			 class="btn btn-info 
			 <?if($blog->user_id != Yii::$app->user->id) {?>pay_lot  <? } ?>
			 "
			 <?if($blog->user_id != Yii::$app->user->id) {?> data-toggle="modal" data-target="#auctionModal" <? } ?>
			 >Купить по блиц-цене <?=$auction->price_moment?> <i class="fa <?=$rates['text']?>" aria-hidden="true"></i>
			 </button>
          </div> 
		  </div> 
		<?php  }?>
 <?}else{ ?>
          <div class="col-md-12">
		      <div class=" row">
		     <button style="width: 100%;margin-bottom: 10px;" data-id='<?=$blog_id?>' class="btn btn-info  loginpop login-head-class" data-toggle="modal" data-target="#myModallogin">Купить по блиц-цене <?=$auction->price_moment?> <i class="fa <?=$rates['text']?>" aria-hidden="true"></i></button>
          </div> 
		  </div> 
 <?} ?>
  <?} ?>

 <?php Pjax::end(); ?>
	   <!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="auctionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body row">
<?php Pjax::begin([ 'id' => 'pjaxContent1']); ?>		 

<?php
if(!$bets) { 
   $model->price = $price + $auction->shag;
  // $model->price_false = $auction->price_add + $auction->shag;
}else{
   $model->price = $price + $auction->shag;
 //  $model->price_false = $bets[0]->price + $auction->shag;
}
?>	
<?if($save_ok) {?>	
   <div class="alert alert-success"> Ваша ставка принята</div>
<?}else{ ?>
    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true,], 'enableClientValidation' => false,]);?>
<div class="col-md-12 row">
	<div class="col-md-2 col-xs-2" style="line-height: 80px;"><span class="minus btn btn-danger" data-shag="<?=$auction->shag?>"><i class="fa fa-minus" aria-hidden="true"></i></span></div>
	<div class="col-md-8 col-xs-8">
	<?/*= $form->field($model, 'price_false', ['template' => '{error}{label}{input}'])->textInput(['disabled' => 'disabled'])->label('Укажите ставку <span class="req_val">*</span>');*/?>
	<?= $form->field($model, 'price', ['template' => '{label}{input}{error}'])->textInput(['data-moment'=> $blog->auctions->price_moment,'data-val' => $price+$auction->shag])->label('Укажите ставку <span class="req_val">*</span>');?></div>
    <div class="col-md-2 col-xs-2" style="line-height: 80px;"><span class="plus btn btn-success" data-shag="<?=$auction->shag?>"><i class="fa fa-plus" aria-hidden="true"></i></span></div>
</div>
	 <div class="form-group upcr col-md-6 col-xs-6" style="padding-left: 0px;">
	 <?if(!$bets) {?> 
        <?= Html::submitButton('Сделать ставку', ['class' => 'btn btn-success add-preloader']) ?>
	<?}else{ ?>
	    <?= Html::submitButton('Повысить ставку', ['class' => 'btn btn-success add-preloader']) ?>
	<?} ?>
     </div>
			 
	<?php ActiveForm::end(); ?>		 
		<?} ?>		


<?php if(isset($bets[0]) && $bets[0]->user_id == Yii::$app->user->id) {	?>

     <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true,], 'enableClientValidation' => false,]);?>
			<?= $form->field($model, 'del', [ 'options' => ['tag' => false], 'template' => '{input}'])->hiddenInput()->label(false);?>
		<div class="form-group upcr col-md-6  col-xs-6">
		<?if($save_ok || $save_del) {?>	
<?= Html::submitButton('Отменить ставку', ['class' => 'btn btn-danger add-preloader']) ?>
	<?}else{ ?>	
<?= Html::submitButton('Отменить ставку', ['class' => 'btn btn-danger add-preloader']) ?>
	<?} ?>
        </div>
	 <?php ActiveForm::end(); ?>	

	<?} ?>
	
<? if($save_del) {?>	
     <div class="alert alert-success col-md-12" style="margin-bottom: 10px;"> Ваша ставка отменена</div>
<?php  }?>
 		<?php Pjax::end(); ?>
		
		
		
		
		
		
		
		
		
		<div class="col-md-12" style="color: #6f7177; font-size: 13px;">В случае, если до окончания торгов никто не предложит ставку выше, Вы выиграете лот по этой цене.</div>
		

	  </div>	
	 

			 </div> 	  
	   </div>	 

  </div>




<?if($bets) {?>
<div class="col-md-12" style="margin-bottom: 10px;">
<div class="row">
 <?if($auction->price_moment){?><? } ?>
       <button style="width: 100%;" class="btn btn-warning btn-history-auction" data-toggle="modal" data-target="#auctionModalstavka" data-id="<?=$blog_id?>">
        Ставки
      </button>
</div>
</div>
<? }?>	  
	  
	   <!-- Modal -->
<div class="modal fade" id="auctionModalstavka" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body modal-body2 row">	

	  </div>
	      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>

      </div>
	</div>
  </div>
</div>