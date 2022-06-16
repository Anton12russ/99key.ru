<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\Blog;
/* @var $this yii\web\View */
/* @var $searchModel common\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(Yii::$app->controller->action->id == 'express') {
$this->title = 'Экспресс объявления';
}else{
  $this->title = 'Объявления';
}
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile(Url::home(true).'js/category.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Url::home(true).'js/region.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<div class="blog-index">
   <h1><?= Html::encode($this->title) ?></h1>



<?php Pjax::begin([ 'id' => 'pjaxContent']); ?>
	<form class="forms" name="form" id="form" method="post">
	<input name="act_id" id="act_id" type="hidden"/>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget(
	[
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
		['attribute'=>'','format'=>'raw', 'filter'=>'<i class="all fa fa-check-square-o" aria-hidden="true"></i>', 'value'=>
	           function($data) {
				   return '<input id="check'.$data->id.'" class="alls" type="checkbox" name="check['.$data->id.']" value = "'.$data->status_id.','.$data->region.','.$data->category.'"/>';
			   }
			],
			
		
           // ['class' => 'yii\grid\SerialColumn'],
           ['attribute'=>'image','format'=>'raw', 'value'=>
	           function($model) {
				   if(isset($model->imageBlog[0]['image'])) {
				      $icon = "<img src='/uploads/images/board/mini/".$model->imageBlog[0]['image']."'/>";
				   }else{
					  $icon = "<img src='/uploads/images/no-photo.png'/>";   
				   }
				    return $icon;
			   }
			],
          
            /*['attribute'=>'title','format'=>'raw', 'value'=>
	           function($data) {
				   return Html::a(\yii\helpers\StringHelper::truncate($data->title,100),'/board/'.$data->id.'_'.$data->url,['target'=>'_blank', 'data-pjax'=>"0"]);
			   }
			],*/
			['attribute'=>'title', 'format'=>'raw',
			   'value'=> function ($model) {
			   if($model['services']) {$style = 'style="color: red;"';}else{$style = '';}
			   return  $model->title.'<br><span data-toggle="tooltip" data-placement="top" title="Платная услуга для объявления"><i data-toggle="modal" data-target="#servModal" data-id="'.$model->id.'" class="fa fa-money services-click"  '.$style.' aria-hidden="true"></i></span>';},
            ],
		
			 'id',
			['attribute'=>'category', 
			   'value'=> function ($model) {return  Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->category(), $model->category);},
			  'filterInputOptions' => [
                'class' => 'form-control catchang', 
                'id' => null,
				'type'=>'hidden'
            ],
			],
            ['attribute'=>'region', 
		  'value'=> function ($model) {return  Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->region(), $model->region);},
			  'filterInputOptions' => [
                'class' => 'form-control regionchang', 
                'id' => null,
				'type'=>'hidden'
            ],
			],
            ['attribute'=>'date_add', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker',
                'id' => false,

            ],
			],
			
			['attribute'=>'date_del', 
			  'filterInputOptions' => [
                'class' => 'form-control datepicker',
                'id' => false,
            ],
			],
			
			
			
            ['attribute'=>'url','format'=>'raw', 'value'=>
	           function($data) {
				  return Html::a(\yii\helpers\StringHelper::truncate($data->url,5,'...'),'/'.Yii::$app->caches->region()[$data->region]['url'].'/'.Yii::$app->caches->category()[$data->category]['url'].'/'.$data->url.'_'.$data->id.'.html',['target'=>'_blank', 'data-pjax'=>"0"]);
			   }
			],
		 
            ['attribute'=>'status_id','filter'=>\common\models\Blog::STATUS_LIST,'value'=>@Status],
			['attribute'=>'active','filter'=>\common\models\Blog::STATUS_ACTIVE,'value'=>@Active],
            ['attribute'=>'author','format'=>'raw','value'=> 
                 function($data) {
                   return '<span class="author_email">'.$data['author']['email'].'</span>';
                 },
                 'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
             ],

  
		
      [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view} {update} {delete}',
        'buttons' => [
            'update' => function ($url,$model) {
              if(Yii::$app->controller->action->id == 'express') {
                $url = str_replace('update','updateexpress', $url);
              }
                return Html::a(
                '<span class="glyphicon glyphicon-pencil"></span>', 
                $url);
            },
          
        ],
    ],
        ],
    ]); ?>	
</form>
<script>
$('.author_email').click(function(){$('.author_input').val($(this).text()); $('input').change(); });
$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val()) {selectact($('.catchang').val())}else{ getCategory(0)};
$('.regionchang').before('<div id="selectBox_region"></div>');
if ($('.regionchang').val()) {selectactreg($('.regionchang').val())}else{ getRegion(0)};
$('.author_email').click(function(){pjaxBlog();});
alssAct();
calendar();
blog_services();
$('[data-toggle="tooltip"]').tooltip('enable');
</script>
    <?php Pjax::end(); ?>
</div>	




<!-- Modal -->
<div class="modal fade" id="servModal" tabindex="-1" role="dialog" aria-labelledby="servModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Платные услуги для объявления</h4>
      </div>
	  <span class="hidden href_user" data-href="<?= Url::home(true)?>user"></span>
      <div class="modal-body">
<iframe style="height: 400px;" src=""  id="user_cont"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->