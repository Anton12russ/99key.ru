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
$this->title = 'Спор (Заказ № '.$model->id_car.')';

$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = array('label'=> 'Споры', 'url'=>Url::toRoute('disputeshop'));
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
?>

	


<div class="row">

   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">


<h1><i class="fa fa-balance-scale" aria-hidden="true"></i> <?=$this->title?></h1>

<table class="table table-bordered">
      <thead>
        <tr>
          <th>Товар</th>
          <th>Контактные данные покупателя</th>
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
								   $note = ' <i style="color: red;" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$results['note'].'" class="fa fa-bell" aria-hidden="true"></i>';
							
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
    
          <td><?=$result?></td>
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
        </tr>
        
      </tbody>
    </table>
	
<?php if($model->status != 2) { ?>		

<?php if (!$model->id) {	?>
<div class="alert alert-info">Опишите пожалуйста в деталях с чем связана Ваша претензия. В течении 2-х дней она будет рассмотрена администрацией сайта и будет вынесен вердикт в отношение Вашего спора.</div>	
<?php }	?>	

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
		'imageUpload' => \yii\helpers\Url::to(['/ajax/save-redactor-img','sub'=>'article']),
		'imageDelete' => \yii\helpers\Url::to(['/ajax/save-img-del']),
       
    ],  
])->label('Текст претензии '.$edit)?>
<? if (!$model->id) { ?>
    <?= $form->field($model, 'cashback', ['template' => '{error}{label}{input}'])->textInput(['class' => 'form-control'])->label('Сумма возврата') ?>
<? } ?>
<div class="col-md-12 form-group upcr">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success add-preloader']) ?>
</div>


<?php ActiveForm::end(); ?>	
<?php Pjax::end(); ?>
<?php }else{	?>

<div class="alert alert-info">Спор был закрыт администрацией</div>

<?php }	?>
<?php Pjax::begin([ 'id' => 'pjaxDispute']); ?>
<? if ($model->id) { ?>
<div class="col-md-12">
<? foreach($comment as $res) { ?>
<div class="col-md-12 row">
    <?if($res['user_id'] == 0) {?>
	    <span class="time_disput">Ответ от администрации сайта (<?=$res['date']?>)</span>
	<? }elseif($res['user_id'] == Yii::$app->user->id){ ?>
	     <span class="time_disput">Ваше сообщение (<?=$res['date']?>)</span>
	<? }else{ ?>
	     <span class="time_disput">Покупатель (<?=$res['date']?>)</span>
	<? } ?>
</div>	
	<div class="mess_disput col-md-12"><?=$res['text']?></div>
<? } ?>
</div>

<?= LinkPager::widget([
 'pagination' => $pages,
]); ?>  
<?}?>
<?php Pjax::end(); ?> 
</div>


</div>

</div>