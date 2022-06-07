<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\BlogPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Платные услуги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
	
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'type',
            'price',
            'name',

           ['class' => 'yii\grid\ActionColumn',
			'template'=>'{update}',
			],
		
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
