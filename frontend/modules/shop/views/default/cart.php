<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

list($x1,$x2) = explode('.',strrev($_SERVER['HTTP_HOST']));
$xdomain = strrev($x1.'.'.$x2);

$patch_url = PROTOCOL.$xdomain;
$this->registerCssFile('/css/main.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->title = 'Корзина, в магазине "'.$shop->name.'"';
$this->registerMetaTag(['name' => 'description','content' => $this->title]);
$this->registerMetaTag(['name' => 'keywords','content' => $this->title. ', магазин, '.$shop->name]);
//передаем в шаблон
if(isset($shop->img)) {$this->params['logo'] = $shop->img;}else{$this->params['logo'] = '';}
$this->params['title'] = $shop->name;
$this->params['phone'] = $shop->field->phone;
$this->params['shop'] = $shop;
$this->params['arr'] =  array('Пн.','Вт.','Ср.','Чт.','Пт.','Сб.','Вс.');
$this->params['vote'] = $vote;
$this->params['vot'] = $shop->reating;
if ($count_art > 0) {$this->params['menu_art'] = true;}else{$this->params['menu_art'] = false;}
$this->params['breadcrumbs'] = array();
$this->params['breadcrumbs'][] = ['label' => 'Корзина'];
foreach(Yii::$app->caches->rates() as $res) {
	if($res['def'] == 1) {
		$rate_def = $res;
	}
}
$this->registerJsFile('/js/cart.js',['depends' => [\yii\web\JqueryAsset::className()]]);

        
?>

<div class="one-bodys">
<div class="col-md-12"><h1><?=$this->title?></h1></div>
<?php Pjax::begin(['id' => 'pjaxCart']); ?>
   <?php if($save == true) {?>
     <div class="col-md-12"><div class="alert alert-success">Ваш заказ оформлен, ожидайте пожалуйста, с Вами свяжется продавец.</div></div> 
   <?php }else{ ?>

	 <?php if($blogs) {?>
<table class="table table-bordered table-cars">
      <thead class="hidden-xs">
        <tr>
          <th>Изображение</th>
          <th>Наименование</th>
          <th>Количество</th>
          <th>Цена</th>
        </tr>
      </thead>
      <tbody>

	  <?php foreach ($blogs as $one) {?>
	  
	      <? $price_arr = array();
				   foreach($one->blogField as $res) {
					if ($res['field'] == $price) { 
				      $price_val = $res['value'];
					  $rates_val = $res['dop'];
				    }
				   }
	                if ($price_val) { 

					    $price_one = str_replace(' ','',$cars[$one->id]['price'])*$cars[$one->id]['count']; 
                        $sum[] = $price_one;						
					}?>  
	    <? $url = Url::to(['/boardone', 'id'=>$one->id]);?>
        <tr>
          <td><img class="car-img-table" src="<? if ($one->imageBlogOne['image']) {?> <?= Yii::getAlias('@blog_image_mini').'/'.$one->imageBlogOne['image'];?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo-main.png<? } ?>"/></td>
          <td class="left-td-car hidden-xs"><a data-pjax="0"  target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($one->title, 100)?></a> <br>ID: <?=$one->id?>
		  <?if (isset($car_note[$one->id])) {?><i data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$car_note[$one->id]?>" class="fa fa-bell" aria-hidden="true"></i><? } ?>
		  </td>
          <td>
		      <a class="hidden-md visible-xs" data-pjax="0"  target="_blank" href="<?=$url?>"><?=Yii::$app->userFunctions->substr_user($one->title, 100)?></a> <div  class="hidden-md visible-xs"></div> <span class="hidden-md visible-xs" style="">ID: <?=$one->id?></span>
		  	<? if(isset($one->count) && $cars[$one->id]['count'] > $one->count) { $err = true;?>
				<div class="cart-alert alert alert-danger">Всего на складе (<?=$one->count?>) шт.</div>
			<? } ?>
		     <div class="max-input-car">
			 <div class="input-car">
		         <input data-value="<?=$cars[$one->id]['count']?>" type="number" name="count"  data-maxlength="2" class="form-control" value="<?=$cars[$one->id]['count']?>"/>
			 </div>
				 <div class="button-cart">
				   <button  data-id="<?=$one->id?>" class="btn btn-success refresh-product"><i class="fa fa-refresh" aria-hidden="true"></i></button>
				   <button data-id="<?=$one->id?>" class="btn btn-danger del-product"><i class="fa fa-times" aria-hidden="true"></i></button>
				 </div>
		     </div>
			
		  </td>	 
          <td>
		      <span style="white-space: nowrap;"><?=number_format($price_one, 0, '.', ' ')?> <i class="fa <?=$rate_def['text']?>" aria-hidden="true"></i></span>
		  </td>
        </tr>
      <?php } ?>

      </tbody>
    </table>
	
	<div class="col-md-12 itogs">Стоимость товара: <span class="itog"><?=number_format(array_sum($sum), 0, '.', ' ')?> <i class="fa <?=$rate_def['text']?>" aria-hidden="true"></i></span></div>
	<? if($shop->field->pay_delivery > 0) { $pay_delivery = $shop->field->pay_delivery;?><div class="col-md-12 itogs">Стоимость дотавки: <span class="itog"><?=$shop->field->pay_delivery?> <i class="fa <?=$rate_def['text']?>" aria-hidden="true"></i></span></div><?}else{?><?$pay_delivery = 0;?><?}?>
	
	<div class="col-md-12 itogs">Итого: <span class="itog"><?=number_format(array_sum($sum)+$pay_delivery, 0, '.', ' ')?> <i class="fa <?=$rate_def['text']?>" aria-hidden="true"></i></span></div>
	  
	<?if(!isset($err)) {?>
	
	  <!--Оформить заказ-->
	<div class="cart-act-2">
 <div class="col-md-12">
 	
 <? if($shop->field['сhoice_pay'] == 2 || $shop->field['сhoice_pay'] == 0) {?>
 
    <? if(!Yii::$app->user->id || Yii::$app->user->identity->balance < (array_sum($sum)+$pay_delivery) ) {?>
	

      <!-- <div class="alert alert-success cart-user-act">Вы можете оплатить заказ через гарант-сервис "<?=Yii::$app->caches->setting()['site_name']?>", для этого <a data-toggle="modal" data-target="#myModallogin" class="loginpop login-head-class" href="#">авторизируйтесь</a> на сайте, пополните баланс на (<?=number_format(array_sum($sum)+$pay_delivery, 0, '.', ' ')?> <i class="fa <?=$rate_def['text']?>" aria-hidden="true"></i>) в личном кабинете, обновите эту странцу и продолжите оформление заказа.
	      <br><a href="<?=Url::to(['/payment'])?>">Информация об оплате</a>
	   </div>-->
    <? }}?>
	</div>
	
	
 <?if(!Yii::$app->user->id) {?>
 
<div class="col-md-12">	
<br>
<div class="alert alert-warning cart-user-act"> Чтобы продолжить оформление заказа, 
  <a  data-toggle="modal" data-target="#myModallogin" class="loginpop login-head-class " href="#" class="alert-link">Авторизируйтесь</a> или 
  <a href="/user/signup">Зарегистрируйтесь</a>
  
</div>
</div>
	<? }else{ ?>	
	
		
<div class="add_chex add_chexs">
<div class="form-group field-carbuyer-dostavkashop required">
<label class="control-label">Доставка <span class="req_val">*</span></label>
<div id="carbuyer-dostavkashop" role="radiogroup" aria-required="true"><label>
<input type="radio" name="CarBuyer[dostavkashop]" value="0"  checked> Доставка <span style="color: #337ab7; cursor: pointer;" data-toggle="collapse" data-target="#delivery1">Подробнее</span><div id="delivery1" class="collapse collapse-dis"><?=$shop->field['delivery']?></div></label>
<label><input type="radio" name="CarBuyer[dostavkashop]" value="1"> Самовывоз</label></div>


</div>	
</div>
	
	
	
	
	
	
	<?php Pjax::begin(['id' => 'pjaxAddress']); ?> 
	<?php $form = ActiveForm::begin([
	 'options' => [
        'data-pjax' => false,
		
    ],

	
	]); ?>
	


<?php $garant = 'Для Вас доступна оплата заказа через Гарант-Сервис "'.Yii::$app->caches->setting()['site_name'].'".<br> После завершения заказа, с Вашего личного счета на сайте "'.Yii::$app->caches->setting()['site_name'].'" будет удержана сумма ('.number_format(array_sum($sum)+$pay_delivery, 0, '.', ' ').' <i class="fa '.$rate_def['text'].'" aria-hidden="true"></i>).
	   <br><a target="_blank" href="https://1tu.ru/Garant-Servis.htm">Подробнее об услуге "Гарант-Сервис"</a>';
	   
if($shop->field['сhoice_pay'] == 2) {  
   $arr_pay['0'] = 'Оплата через (Гарант-Сервис) <span style="color: #337ab7; cursor: pointer;" data-toggle="collapse" data-target="#demo">Подробнее</span><div id="demo" class="collapse collapse-dis">'.$garant.'</div>';
   $arr_pay['1'] = 'Оплата по договору с продавцом <span style="color: #337ab7; cursor: pointer;" data-toggle="collapse" data-target="#demo1">Подробнее</span><div id="demo1" class="collapse collapse-dis">'.$shop->field['private_payment'].'</div>';  
 }
 
 if($shop->field['сhoice_pay'] == 1) {   
     $arr_pay['1'] = 'Оплата по договору с продавцом <span style="color: #337ab7; cursor: pointer;" data-toggle="collapse" data-target="#demo1">Подробнее</span><div id="demo1" class="collapse collapse-dis">'.$shop->field['private_payment'].'</div>';  
$car->private_payment = 1;
 }
 if($shop->field['сhoice_pay'] == 0) {  
$car->private_payment = 0; 
    $arr_pay['0'] = 'Оплата через (Гарант-Сервис) <span style="color: #337ab7; cursor: pointer;" data-toggle="collapse" data-target="#demo">Подробнее</span><div id="demo" class="collapse collapse-dis">'.$garant.'</div>';
 } 
			   ?>
		

	   <div class="add_chex" style="float: none;">
		   <?= $form->field($car, 'private_payment')
               ->radioList($arr_pay
               ,['encode'=>FALSE]); ?>	
       </div>

	

     <div class="col-md-6">
	  <h3>Контактные данные</h3>
	  	<hr>
	
    <?= $form->field($car, 'name')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($car, 'family')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($car, 'email')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($car, 'phone')->widget(\yii\widgets\MaskedInput::className(), ['mask' => Yii::$app->caches->setting()['mask']])->textInput(['maxlength' => true]) ?>
   </div>

<?if(!isset($_GET['address'])) {  ?> 
   <div class="col-md-6 div-hidden">
    <h3>Адрес доставки</h3>
	<hr>
	<?= $form->field($car, 'country')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($car, 'region')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($car, 'city')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($car, 'address')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($car, 'postcode')->textInput(['maxlength' => true]) ?>

</div>	
 <? }?>	


		<?php if (isset($errpay)) {echo $errpay;}?>
	
	
    <div class="col-md-12 form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
 <?php Pjax::end(); ?>	
	  </div>
	<?}?>  
	  <?php }else{ ?>
	        <span class="itog hidden">0 <i class="fa <?=$rate_def['text']?>" aria-hidden="true"></i></span>
	        <div class="col-md-12"><div class="alert alert-warning">Вы не добавляли товар в корзину.</div></div> 
	  <?php } ?>
	<?php } ?> 
	

 <?php Pjax::end(); ?>	
  
 <? }?>
</div>
</div>
