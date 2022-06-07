<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SupportSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Техническая поддержка';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="support-subject-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
                 ['attribute'=>'author','format'=>'raw','value'=> 
                 function($data) {
                   return '<span class="author_email">'.$data['author']['email'].'</span>';
                 },
                 'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
             ],

			['attribute'=>'subject','format'=>'raw','value'=> 
                 function($data) {
                   return $data->subject.'<br>'.Html::a('Ответить',['edite','id' => $data->id]);
                 },
                
             ],
            ['attribute'=>'date_add', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker',
                'id' => false,
                 'autocomplete'=>'off',
            ],
			],
            ['attribute'=>'date_update', 
			  'filterInputOptions' => [
                'class' => 'form-control  datepicker',
                'id' => false,
                 'autocomplete'=>'off',
            ],
			],
            ['attribute'=>'status','filter'=>\common\models\SupportSubject::STATUS_LIST,'value'=>@Status],
            ['attribute'=>'flag_admin','format'=>'raw','filter'=>\common\models\SupportSubject::STATUS_FLAG,'value'=>@Flag],
            ['attribute'=>'flag_user','format'=>'raw','filter'=>\common\models\SupportSubject::STATUS_FLAG,'value'=>@Flaguser],

           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
