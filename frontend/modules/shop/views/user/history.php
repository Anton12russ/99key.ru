<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->registerJsFile('/js/personal.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/css/personal.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->title = 'Платежная история';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/user.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->blocks['right'] = '';
?>

<div class="row">

  
     <div class="person-body">
    <h1><i class="fa fa-money-check-dollar-pen" aria-hidden="true"></i> <?=$this->title?></h1>
<?php Pjax::begin(); ?>
   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		
		
        'columns' => [
		       ['attribute'=>'price','format'=>'raw', 'value'=>
	           function($data) {
					 $return = $data->price .' '. $data->currency;
				     return $return;
			   }
			],
            

            ['attribute'=>'blog_id','format'=>'raw', 'value'=>
	           function($data) {
				   if ($data->blog_id) {
					    if ($data->blog['id']) {
				             return Html::a(\yii\helpers\StringHelper::truncate(
				           	 $data->blog['url'],25,'...'),'/'.
                             Yii::$app->caches->region()[$data->blog['region']]['url'].'/'.
				        	 Yii::$app->caches->category()[$data->blog['category']]['url'].'/'.
					         $data->blog['url'].'_'.$data->blog['id'].'.html',['target'=>'_blank', 'data-pjax'=>"0"]);
				        }else{
							return 'Объявление удалено';
						}
				   }
			   }
			],


			'system',
			['attribute'=>'services', 'filter'=>\common\models\Payment::ACT_LIST, 'value'=>@servic],
 
			['attribute'=>'status','filter'=>\common\models\Payment::STATUS_LIST ,'format'=>'raw', 'value'=>@statusid],
             ['attribute'=>'time', 
			  'filterInputOptions' => [
			  'autocomplete' => 'off',
                'class' => 'form-control  datepicker',
                'id' => false,

            ],
			],
        ],
    ]); ?>
 <?php Pjax::end(); ?>
   </div>

</div>