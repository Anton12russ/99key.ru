<?php
use yii\helpers\Url;
$this->registerJsFile('/js/uslugi.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$services = Yii::$app->userFunctions->servicesBlog();
if(isset(Yii::$app->user->id)) {
$balance = Yii::$app->user->identity->balance;
}else{
$balance = 0;
}
?>

<span class="hidden balance_user" data-balance="<?=$balance?>"></span>
	    <!--Информируем пользователя об успешной регистрации-->
	    <? if (isset($registr_success) && $registr_success === true) {?> 
	        <div class="alert alert-info text-center">Вы успешно зарегистрировались.<br> Чтобы активировать аккаунт и свое объявление, перейдите по ссылке из письма, который мы отправили на e-mail. Проверьте папку "Спам", возможно письмо попало туда. </div>
     	<? } ?> 

 
	    <!--Если объявление в платной категории-->
	    <? if ($price_category) {?> 
		  <div class="alert alert-warning text-center">Объявление добавлено, но чтобы оно активировалось на (<?=$price_category['date']?> дней), его нужно оплатить.<br> Стоимость <strong><?=$price_category['sum']?></strong> <i class="fa <?=$rates_cat_val?>" aria-hidden="true"></i></div>
         <br>
		    <a class="btn btn-success" href="https://1tu.ru/user/blogs"><i class="fa fa-list-alt" aria-hidden="true"></i> Мой товар</a>
		 <br>
		 <br>
		    <a class="btn btn-success" href="https://1tu.ru/user/auction"><i class="fa fa-gavel" aria-hidden="true"></i> Мой аукцион</a>
		 <br>
		 <br>
               <a class="btn btn-success" href="/ajax/copy?id=<?=$save['id']?>"><i class="fa-solid fa-copy" aria-hidden="true"></i>Копировать объявление</a>
          <br>
          <? if ($price_category['sum'] > $balance || Yii::$app->user->isGuest) { ?>
		  <!--Показывать это условие, если это не гость-->
		  <? if (!Yii::$app->user->isGuest) { ?>
		  <div class="alert alert-danger err-balance display-block">Недостаточно средств на балансе (<? echo $price_category['sum']-$balance?> <i class="fa <?=$rates_cat_val?>" aria-hidden="true"></i>), оплатить удобным способом?</div>
		  <? } ?>	
	
		  <div class="body-logo-pay center display-block">
		      <?foreach($payment as $res) {?>
				  <div class="logo_pay">
		              <a class="sum-open" style="background-image: url(/images_all/logo_payment/yandex_money_icon.png)" href="<?=Url::to(['payment/'.$res['route'].'-form', 'id_pay' => $price_category['id_payment']])?>"></a>
				  </div>
		      <? } ?>
		   </div>
		   <? }else{ ?>
		    <div class="personal-center">
		      <a class="pay-act btn btn-success add-preloader" href="<?=Url::to(['payment/personal', 'id_pay' => $price_category['id_payment']])?>">Оплатить</a> 
		    </div>
		  <? } ?>

	    <? }else{ ?>
		 <!--Если объявление не платное-->
		 <div class="save"><div class="alert alert-success text-center">Объявление успешно добавлено! <br>Запомните код <span class="key-class"><?=$key?></span> чтобы вы в дальнейшем смогли его отредактировать.</div>
		 <br>
		    <a class="btn btn-success" href="https://1tu.ru/user/blogs"><i class="fa fa-list-alt" aria-hidden="true"></i> Мой товар</a>
		 <br>
		 <br>
		    <a class="btn btn-success" href="https://1tu.ru/user/auction"><i class="fa fa-gavel" aria-hidden="true"></i> Мой аукцион</a>
		 <br>
		 <br>
            <a class="btn btn-success" href="/ajax/copy?id=<?=$save['id']?>"><i class="fa-solid fa-copy" aria-hidden="true"></i> Копировать объявление</a>
         <br>
        
		 
		 <h2>Продать быстрее!</h2>
		 <div class="hidden day-def" data-def="<?=$save['date_del']?>"></div>
		 <div class="serv_div">
	<!--Платные услуги-->
	<? foreach($services as $res) {  $servPrice[] = $save['date_del']*$res['price']?>
		 <? if ($res['type'] == 'top') {?>
		    <div class="services top" boolean="true" data-toggle="tooltip" data-placement="right" title="<? echo 'Услуга будет действовать до  '.$save['date_del'].' дней.'?> <?=$res['text']?>" data-href="<?=$res['type']?>"> 
			   <div class="services_name"><i class="fa fa-circle-arrow-up fa-serv" aria-hidden="true"></i><div><span class="name"><span class="serv-name"><?=$res['name']?></span>:  <br><span class="serv-price"><span class="price"><?=$res['price']?></span> <i class="fa <?=$rates_cat_val?>" aria-hidden="true"></i>  / день</span></span></div></div>
			   <div class="services_text hidden-xs"> <?=Yii::$app->userFunctions->substr_user($res['text'], 70)?></div>
			</div>
		<? } ?>	
		
		 <? if ($res['type'] == 'block') {?>
		    <div class="services block" boolean="true" data-toggle="tooltip" data-placement="right" title="<? echo 'Услуга будет действовать до  '.$save['date_del'].' дней.'?> <?=$res['text']?>" data-href="<?=$res['type']?>"> 
			   <div class="services_name"><i class="fa-regular fa-fire fa-serv" aria-hidden="true"></i><div><span class="name"><span class="serv-name"><?=$res['name']?></span>: <br><span class="serv-price"><span class="price"><?=$res['price']?></span> <i class="fa <?=$rates_cat_val?>" aria-hidden="true"></i>  / день</span></span></div></div>
			   <div class="services_text hidden-xs"><?=Yii::$app->userFunctions->substr_user($res['text'], 70)?></div>
			</div>
		<? } ?>	
		
		 <? if ($res['type'] == 'bright') { ?>
		    <div class="services bright" boolean="true" data-toggle="tooltip" data-placement="right" title="<? echo 'Услуга будет действовать до  '.$save['date_del'].' дней.'?> <?=$res['text']?>"  data-href="<?=$res['type']?>"> 
			   <div class="services_name"><i class="fa fa-star-half-stroke fa-serv" aria-hidden="true"></i><div><span class="name"><span class="serv-name"><?=$res['name']?></span>:  <br><span class="serv-price"><span class="price"><?=$res['price']?></span> <i class="fa <?=$rates_cat_val?>" aria-hidden="true"></i>  / день</span></span></div></div>
			   <div class="services_text hidden-xs"><?=Yii::$app->userFunctions->substr_user($res['text'], 70)?></div>
			</div>
		<? } ?>		
	<? } ?>
		</div> 
		 <div class="serv_div padding">
		  <? if (max($servPrice) > $balance || Yii::$app->user->isGuest) { ?>
		  <!--Показывать это условие, если это не гость-->
		  <? if (!Yii::$app->user->isGuest) { ?>
		  <div class="alert alert-warning err-balance ">Недостаточно средств на балансе (<?=$balance?> <i class="fa <?=$rates_cat_val?>" aria-hidden="true"></i>), оплатить удобным способом?</div>
		  <? } ?>
 <div class="pay-none">
		  <div class="services-add"></div>
		  <div class="services-ok hidden">Оплатить услугу</div>
		  <div class="services-ok-tyme hidden"><div class="hid-days" style="display: inline-block; margin-top: 10px;">Услуга будет действовать до <br>

<div class="form-group" style=" margin-top: 10px;">
<input id="day" type="number" data-max="<?=$save['date_del']?>" value="<?=$save['date_del']?>" id="blog-count" class="form-control" name="Blog[count]" placeholder="99" style="max-width: 100px; float: left;">
<label class="control-label" for="blog-count">дней</label></div>
</div>
		  	<span class="services-bottom"></span>	  
			<div class="itog">Итого <span class="itogo"></span> руб.</div>
			
		  </div>
		  <div class="pay-disable"></div>



		  <div class="body-logo-person center">
		    <a class="pay-act btn btn-success add-preloader" data-href="<?=Url::to(['payment/personal', 'blog_id' => $save['id']])?>" href="#">Оплатить</a>
		  </div>
		    <div class="body-logo-pay center">
		      <?foreach($payment as $res) {?>
				  <div class="logo_pay">
		              <a class="sum-open" style="background-image: url(<?=$res['logo']?>)" data-href="<?=Url::to(['payment/'.$res['route'].'-form', 'blog_id' => $save['id']])?>" href="#"></a>
				  </div>
		      <? } ?>
		   </div>
		  </div>
		   <? }else{ ?>
		   
		   
		   
		   
		   
		   
		  <div class="pay-none">
		  <div class="services-add"></div>
		  <div class="services-ok hidden">Оплатить услугу</div>
		  <div class="services-ok-tyme hidden"><div class="hid-days" style="display: inline-block; margin-top: 10px;">Услуга будет действовать до <br>

<div class="form-group" style=" margin-top: 10px;">
<input id="day" type="number" data-max="<?=$save['date_del']?>" value="<?=$save['date_del']?>" id="blog-count" class="form-control" name="Blog[count]" placeholder="99" style="max-width: 100px; float: left;">
<label class="control-label" for="blog-count">дней</label></div>
</div>
		  	<span class="services-bottom"></span>	  
			<div class="itog">Итого <span class="itogo"></span> руб.</div>
			
		  </div>

		   <div class="body-logo-person center">
		       <a data-pjax="0" target="_blank"  class="pay-act btn btn-success add-preloader" data-href="<?=Url::to(['payment/personal', 'blog_id' => $save['id']])?>" href="#">Оплатить</a>
			</div>   
		  </div>
		   <? } ?>
		 </div>
		<? } ?> 
	</div>