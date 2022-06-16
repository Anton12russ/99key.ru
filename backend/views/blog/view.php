<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Blog;
/* @var $this yii\web\View */
/* @var $model common\models\Blog */
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);



//Подключаем слайдер

$this->registerCssFile('/assest_all/slider/css/lightslider.css');
$this->registerCssFile('/assest_all/slider/css/lightgallery.css');
$this->registerJsFile('/assest_all/slider/js/lightslider.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/slider/js/script.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);	
$this->registerJsFile('/assest_all/slider/js/lightgallery.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/slider/js/lightgallery-all.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<? 
//Подгоняем массивы доп.полей для того чтобы можно было их соеденить
$arr_blogField = $model->blogField;
$arr_field = $model->field;
  foreach ($arr_field as $res) {
      $ar_F[] = $res['attributes'];
  }
  foreach ($arr_blogField as $res) {
      $ar_bF[$res['attributes']['field']] = $res['attributes'];
  }
?>

<div class="blog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
     	<?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success'])?>
        <?if($model->express == '1') {?>
            <?= Html::a('Редактировать', ['updateexpress', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?}else{?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?}?>
       <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])?> 
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
		    ['attribute'=>'image','format'=>'raw', 'value'=>
	           function($model) {
				   if (isset($model->imageBlog[0]['image'])) {
				      return "<img src='/uploads/images/board/mini/".$model->imageBlog[0]['image']."'/>";
				   }else{
					  return "<img style='width: 50px;' src='/uploads/images/no-photo.png'/>";
				   }
			   }
			],
            'title',
            ['attribute'=>'url','format'=>'raw', 'value'=>
	           function($data) {
				  return Html::a(\yii\helpers\StringHelper::truncate($data->url,100,'...'),'/'.Yii::$app->caches->region()[$data->region]['url'].'/'.Yii::$app->caches->category()[$data->category]['url'].'/'.$data->url.'_'.$data->id.'.html',['target'=>'_blank', 'data-pjax'=>"0"]);
			   }
			],
            'Status',
			'Active',
			'date_add',
			'date_update',
           		['attribute'=>'category', 
			   'value'=> function ($model) {return Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->category(), $model->category);},
			  'filterInputOptions' => [
                'class' => 'form-control catchang', 
                'id' => null,
				'type'=>'hidden'
            ],
			],
		    ['attribute'=>'region','value'=> function ($model) {return Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->region(), $model->region);},],
			'author.username',
			
        ],
    ]) ?>

<br>
<h3>Дополнительные характеристики</h3>
<!--Выводим доп.поля-->
<table class="table table-hover">
 <tbody>
<?php foreach ($ar_F as $res) {?>
  <tr>
    <td><?php echo $res['name'];?></td>
	<td><?php 
	   if(!isset($ar_bF[$res['id']]['value'])) {
	    $value =  explode("\n", $res['values'])[$ar_bF[$res['id']]['value']];
       }
	    if (isset($value)) {
		     echo $value;
		}else{
	         
			 //Выводим значение валюты
			 if($res['type'] == 'p') {
				 echo $ar_bF[$res['id']]['value'] / $rates[$ar_bF[$res['id']]['dop']]['value'];
				 echo ' <i class="fa '.$rates[$ar_bF[$res['id']]['dop']]['name'].'" aria-hidden="true"></i> ';
				
				 }else{
					 echo $ar_bF[$res['id']]['value'];
				 }
		}?>
    </td>
  </tr>
<?php } ?>
 </tbody>
</table>






<?php if ($model->imageBlog) { ?>
<br>
<h3>Изображения</h3>
<br>
<div class="row">
<div class="slidercontainer col-md-6">
	<ul id="imageGallery">
	  <?php if ($model->imageBlog) { ?>
              <?php foreach ($model->imageBlog as $images) { ?>
						 <li data-thumb="<?= '/uploads/images/board/mini/'.$images['image']?>" data-src="<?= '/uploads/images/board/maxi/'.$images['image']?>"> 
                             <div  style="background-image: url(<?= '/uploads/images/board/maxi/'.$images['image']?>);" class="false_window"></div>
                         </li> 
              <?php } ?>
       <?php } ?>	 
    </ul>
</div>


<?php } ?>
</div>
</div>
