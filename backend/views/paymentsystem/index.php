<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PaymentSystemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Платежные системы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-system-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить платежную систему (Для разработчиков)', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],
          'SmallImage:image',
            //'id',
            'name',
            'rates',
            'settings:ntext',
           // 'settings_input:ntext',
			['attribute'=>'status','filter'=>\common\models\PaymentSystem::STATUS_LIST, 'value'=>@Statusid],
              ['class' => 'yii\grid\ActionColumn',
			  'template'=>'{update}',
		    	],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
