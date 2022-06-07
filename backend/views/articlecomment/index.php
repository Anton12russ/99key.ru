<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\BlogCommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии к объявлениям';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('/assest_all/calendar/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="blog-comment-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        //    ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'blog_id',
             ['attribute'=>'user_email','format'=>'raw','value'=> 
                 function($data) {
					 if($data['author']['email']) {
                         return '<span class="author_email">'.$data['author']['email'].'</span>';
					 }else{
						 return $data['user_email'];
					 }
                 },
				], 
				 
            ['attribute'=>'date', 
			    'filterInputOptions' => [
                   'class' => 'form-control  datepicker',
                   'id' => false,
                ],
			],
           // 'text:ntext',
           // 'user_name',
            //'user_email:email',
            ['attribute'=>'status','filter'=>\common\models\BlogComment::STATUS_LIST,'value'=>@Status],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
