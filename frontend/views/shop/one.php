<?php
/* @var $this yii\web\View */
/* @var $blog common\models\Blog */
use yii\helpers\Url;
$this->title = $meta['title'];
$this->registerMetaTag(['name' => 'description','content' => $meta['description']]);
$this->registerMetaTag(['name' => 'keywords','content' => $meta['keywords']]);
$this->params['breadcrumbs'] = $meta['breadcrumbs'];
$this->params['h1'] = $meta['h1'];
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('/css/shopone.css', ['depends' => ['frontend\assets\AppAsset']]);
$arr = array('Понедельник','Вторник','Среда','Четверг','Пятница','Суббота','Воскресенье');
$this->registerJsFile('/js/shop_one.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$vot = $shop->reating;
$shop_url = PROTOCOL.$shop->domen.'.'.DOMAIN;
?>


<div class="col-md-12 one-body">
 <? if ($shop->active != 1) {?><br><div class="alert alert-warning">Магазин не активирован. Активируйте его в личном кабинете.</div><? } ?>
 <? if ($shop->status != 1) {?><br><div class="alert alert-danger">Магазин не опубликован, возобновить показы можно в личном кабинете.</div><? }?>
  <div class="content col-md-7 <? if ($shop->status != 1) {?>opacityyes<? }?>" >
   <?if (isset($shop->field->stocks)) {?><div  style="background: #e51b23; color: #f6f6f6; border: #49080f;"  class="alert alert-info"><span class="h3">Внимание акция!!!</span>  <?=$shop->field->stocks?></div><?}?>
   <div class="hr_add"><i class="fa fa-phone-square" aria-hidden="true"></i> Контакты</div>
    <div><span class="h3">Телефон: </span><a href="tel:<?=str_replace(' ', '',$shop->field->phone)?>"><?=$shop->field->phone?></a> </div>
     <? if ($shop->field->phone) {?><div><span class="h3">Сайт:</span>  <?=$shop->field->site?></div><?}?>
	 <div><span class="h3">Адрес:</span>  <?=$shop->field->address?></div>
	 
	 <div class="hr_add"><i class="fa fa-file-text" aria-hidden="true"></i> Информация</div>
	 <a class="alert alert-warning shop-go alert-warning1" href="<?=$shop_url?>"><i class="fa fa-shop fa-lg" aria-hidden="true"></i> Перейти к покупкам товара</a>
	 <? if($shop->field->price) {?> <div><span class="h3">Прайс-лист:</span> <a href="/uploads/images/shop/file/<?=$shop->field->price?>" target="_blank">Скачать</a></div><?}?>
 
	 <div><span class="h3">Оплата:</span>  <?=$shop->field->payment?></div>
	 <div><span class="h3">Доставка:</span>  <?=$shop->field->delivery?></div>
     <br>
	 <div><span class="h3">Описание деятельности:</span><br>  <?=$shop->text?></div>
	 <br>
	 
  </div>

<div class="col-md-5 <? if ($shop->status != 1) {?>opacityyes<? }?>">
	<?if ($shop->user_id == Yii::$app->user->id || Yii::$app->user->can('updateShop')) {?>
	 <div class="col-md-12 edit">
	     <a target="_blank" class="update" href="<?=Url::to(['shop/update', 'id' => $shop->id])?>">Редактировать</a>
	     <a target="_blank" class="del" href="<?=Url::to(['shop/del', 'id' => $shop->id])?>">Снять с публикации</a>
	</div> 
    <? } ?>

<?if ($shop->img) {?>	
<div class="logo">
  <a href="<?=$shop_url?>"><img src="<?=Yii::getAlias('@shop_logo')?>/<?=$shop->img?>"/></a>
</div>
<? } ?>	
<div class="grafik">
<div class="hr_add graf-info"><i class="fa fa-clock" aria-hidden="true"></i> График работы</div>
 <table class="table table-bordered">
      <tbody>
<? foreach ($shop->grafik as $key => $graf) { ?>
        <tr <? if (isset($graf['vih'])) {?>class="opacity"<? } ?>>
          <td><?=$arr[$key]?></td>
          <td <? if (isset($graf['vih'])) {?>colspan="2"<? } ?>>
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

<div class="hr_add graf-info"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Оценить компанию</div>
<form id="vote" method="post"  action="">
<table class="table table-bordered">
      <tbody class="vote">
        <tr>
          <td><span>Обслуживание</span><div>
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
		       <div class="good"><span class="man"><?=$vot->obsluga_plus?></span> Довольны</div>
			   <div class="bad"><span class="man"><?=$vot->obsluga_minus?></span> Не довольны</div>
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
		       <div class="good"><span class="man"><?=$vot->cena_plus?></span> Довольны</div>
			   <div class="bad"><span class="man"><?=$vot->cena_minus?></span> Не довольны</div>
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
		       <div class="good"><span class="man"><?=$vot->kachestvo_plus?></span> Довольны</div>
			   <div class="bad"><span class="man"><?=$vot->kachestvo_minus?></span> Не довольны</div>
		       </div>
		   <? } ?>   
		  </td>
        </tr>
		
		    <tr class="td_none">
		       <td colspan="3">
			    <? if (!$vote) {?>
		          <input type="hidden" name="ShopReating[shop_id]" value="<?=$shop->id?>"/>
		          <input type="button" id="btn" class="btn btn-success vote-go" data-href="<?=Url::to(['shopreating/create', 'id' => $shop->id])?>" value="Отпавить оценку" /> 
                <?}else{?>	
                     <div class="non-vote">Уже оценивали этот магазин</div>
 	            <?}?>				
			  </td>
		    </tr>
	
      </tbody>
    </table>
</form>
</div>	
  





<div class="tab-shop col-md-12">

<!-- Nav tabs -->
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">Товары</a></li>
  <li><a href="#comment" class="comments" data-href="<?=Url::to(['shop/comment', 'id' => $shop->id])?>" data-toggle="tab">Комментарии (<?=$count_com?>)</a></li>
  <? if ($count_art > 0) {?>
  <li><a href="#article" class="article" data-href="<?=Url::to(['shop/article', 'id' => $shop->user_id])?>" data-toggle="tab">Статьи (<?=$count_art?>)</a></li>
  <? } ?>
  <? if (isset($shop->imageShop)) {?>
     <li><a href="#images" class="images" data-href="<?=Url::to(['shop/images', 'id' => $shop->id])?>" data-toggle="tab">Слайдер магазина</a></li>
  <? } ?>
  <? if (isset($shop->field->coord)) {?>
      <li><a href="#maps" class="maps" data-href="<?=Url::to(['shop/maps', 'id' => $shop->id, 'coordin' => $shop->field->coord, 'address' => $shop->field->address])?>" data-toggle="tab">На карте</a></li>
  <? } ?>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane active" id="home" >
      <? 
	     echo $this->render('blog_all', [
	    'blogs' => $blog,
		'pages' => $pages,
		'rates' => $rates,
		'price' => $price,
		'notepad' => $notepad,
		'user_id' => $shop->user_id
    ]); 
	?>
  
  </div>
  <div class="tab-pane" id="article"></div>
  <div class="tab-pane" id="comment"></div>
  <? if (isset($shop->imageShop)) {?>
  
     <div class="tab-pane" id="images"></div>
  <? } ?>
   <? if (isset($shop->field->coord)) {?> 
      <div class="tab-pane" id="maps">
	      <div class="maps-one"> 
	   	       <div class="iframe-div">
		          <iframe src="/ajax/maps?coord=<?=$shop->field->coord?>&address=<?=$shop->field->address?>" style="width:100%; height: 400px; max-height: 100%;" frameborder="0"></iframe>
		      </div>
         </div>
	  </div>
   <? } ?>
</div>
</div>
</div>