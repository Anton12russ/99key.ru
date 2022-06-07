<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Блог';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile(Url::home(true).'js/article.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);	
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить Статью', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'title',
              ['attribute'=>'author','format'=>'raw','value'=> 
                 function($data) {
                   return '<span class="author_email">'.$data['author']['email'].'</span>';
                 },
                 'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
             ],

            ['attribute'=>'status','filter'=>\common\models\Article::STATUS_LIST,'value'=>@Status],
       
			   ['attribute'=>'cat','format'=>'raw','value'=> 
                 function($data) {
                   return $data->cats['name'];
                 },
                 'filterInputOptions' => [
                     'class' => 'form-control catchang', 
                     'id' => null,
					 'type'=>'hidden'
                  ],
             ],
           ['attribute'=>'date_add', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker',
                'id' => false,
                 'autocomplete'=>'off',
            ],
			],
			
			
			['attribute'=>'date_end', 
			  'filterInputOptions' => [
                'class' => 'form-control datepicker',
                'id' => false,
				'autocomplete'=>'off',
            ],
			],
			
			['attribute'=>'date_update', 
			  'filterInputOptions' => [
                'class' => 'form-control datepicker',
				'autocomplete'=>'off',
                'id' => false,
            ],
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<script>
$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val()) {selectact($('.catchang').val())}else{ getCategory(0)};
calendar();
</script>
    <?php Pjax::end(); ?>
</div>
