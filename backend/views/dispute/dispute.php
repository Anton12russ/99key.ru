<?php
/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/js/user.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title = 'Спор (Заказ № '.$car->id.' - магазин "'.$car->shop['name'].'" )';
$this->params['breadcrumbs'][] = array('label'=> 'Споры', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = $this->title;
$services = Yii::$app->userFunctions->servicesBlog();
foreach($services as $res) {
	$arrserv[$res['type']] = $res;
}
		foreach(Yii::$app->caches->rates() as $rates) {
					if($rates['def'] == 1) {
						define('RAT',   $rates['text']);
					}
				}
if ($model->id) {	
   $edit = '(Ответ)';
}else{
   $edit = '';
}
$arrey = ['0'=>['На рассмотрении'],'1'=>['Отправлен'],'2'=>['Доставлен'],'3'=>['Отменен'],'4'=>['Завершен']];
?>


<table class="table table-bordered table-background">
      <thead>
        <tr>
          <th>Товар</th>
          <th>Контактные данные покупателя</th>
		  <th>Дополнительная информация</th>
        </tr>
      </thead>
      <tbody>
        <tr>
              <? $prot = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
				$product_id = explode('&',$car->id_product);
				$products = explode(',',$product_id[0]);
				$result = '';
				foreach($products as $res) {
					$prod = explode('|',$res);
					if(isset($prod[1]) && isset($prod[2])) {
						
						if($car->note) {
							foreach($car->note as $results) {
							   if($results['id_product'] == $prod[0]) {
								   $note = ' <i style="color: red;" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$results['note'].'" class="fa fa-bell-o" aria-hidden="true"></i>';
							
							   }
							}
						}else{
							  $note = '';
						}
						
					    $result .= 'ID <a pjax="0" target="_blank" href="'.$prot.$car->shop['domen'].'.'.DOMAIN.'/boardone?id='.$prod[0].'">'.$prod[0].'</a> - '.$prod[1].' шт.'.$note.'<br>за шт - '.$prod[2].' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';
					if(isset($product_id[1])) {
						$result .= 'Доставка '.$product_id[1].' <i class="fa '.RAT.'" aria-hidden="true"></i><hr style="margin: 3px;">';
					}
					}
			}?>
    
          <td><?=$result?> Итого: <span class="price-dispute"><?=$car->price?></span> <i class="fa <?=RAT?>" aria-hidden="true"></i></td>
          <td>
		   Имя:      <?=$car->bayer['name']?><br>
		   Фамилия:  <?=$car->bayer['family']?> <br>  
		   E-mail:   <?=$car->bayer['email']?><br>
		   Телефон:  <?=$car->bayer['phone']?><br>
		   Страна:   <?=$car->bayer['country']?><br>
		   Регион:   <?=$car->bayer['region']?><br>
		   Город:    <?=$car->bayer['city']?><br>
		   Адрес:    <?=$car->bayer['address']?><br>
		   Индекс:   <?=$car->bayer['postcode']?><br>
		  
		  </td>
		  <td>
		  Статус заказа: <strong><?=$arrey[$car->status][0]?></strong><br>
		  <?=$car->data_add?><br>
		  Количество споров у продавца:
		  <?=$countdispute?><br>
		  Имя продавца:
		  <?=$car->user['username']?><br>
		  E-mail продавца:
		  <?=$car->user['email']?><br>
		    Ссылка на магазин:
			<a pjax="0" target="_blank" href="<?=$prot.$car->shop['domen'].'.'.DOMAIN?>"><?=$car->shop['name']?></a>
		  <br>
		  </td>
		  
		
		  </td>
        </tr>
        
      </tbody>
    </table>
<?php if($model->status != 2) { ?>		
	
<div class="col-md-12 close-disp">
<h4>Закрыть спор и перевести средства в пользу</h4>
   <button type="submit" class="btn btn-success add-preloader spor-close bayer_close"  data-href="<?=Url::to(['closebayer', 'id'=>$model->id]);?>">Покупателя</button>	
   <button type="submit" class="btn btn-info add-preloader spor-close shop_close"  data-href="<?=Url::to(['closeshop', 'id'=>$model->id]);?>">Продавца</button>	
</div>
	
<div class="col-md-12 row">
   <p></p>
   <span>Заявленная сумма возврата</span> = <?=$model->cashback?> <i class="fa <?=RAT?>" aria-hidden="true"></i><hr style="margin: 3px;">
   <p></p>
   <p></p>
</div>

<?php Pjax::begin([ 'id' => 'pjaxFormdis']); ?>
<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'data-pjax' => true,], 'enableClientValidation' => false,]);?>

<?php $model->text = '';?>
<?= $form->field($model, 'text')->widget(Widget::className(), [
   
    'settings' => [
	
        'lang' => 'ru',
        'minHeight' => 200,
		'formatting' => [
		'h1','h2','p','blockquote'
		],
        'plugins' => [

            'fullscreen',
			'video',

        ],
		'imageUpload' => \yii\helpers\Url::to(['/site/save-redactor-img','sub'=>'article']),
		'imageDelete' => \yii\helpers\Url::to(['/site/save-img-del']),
       
    ],  
])->label('Ответ на претензию')?>

    <?= $form->field($model, 'cashback', ['template' => '{error}{label}{input}'])->textInput(['class' => 'form-control'])->label('Изменить сумму возврата') ?>

<div class="col-md-12 form-group upcr">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success add-preloader']) ?>
</div>


<?php ActiveForm::end(); ?>	
<?php Pjax::end(); ?>
<?php }else{	?>
<div class="alert alert-info">Спор был закрыт администрацией</div>
<?php }	?>

<div class="row">
<?php Pjax::begin([ 'id' => 'pjaxDispute']); ?>

<div class="col-md-12">
<? foreach($comment as $res) { ?>
<div class="col-md-12 row">
    <?if($res['user_id'] == 0) {?>
	    <span class="time_disput">Ваш ответ (<?=$res['date']?>)</span>
	<? }elseif($res['user_id'] == $model->id_bayer){ ?>
	     <span class="time_disput">Покупатель (<?=$res['date']?>)</span>
	<? }else{ ?>
	     <span class="time_disput">Продавец (<?=$res['date']?>)</span>
	<? } ?>
</div>	
	<div class="mess_disput col-md-12"><?=$res['text']?></div>
<? } ?>
</div>

<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>  


<?php Pjax::end(); ?> 
</div>
