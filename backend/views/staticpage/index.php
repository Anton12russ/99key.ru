<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\StaticPageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статичные страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить страницу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'name',
              ['attribute'=>'url','format'=>'raw', 'value'=>
	           function($data) {
				  return Html::a(\yii\helpers\StringHelper::truncate($data->url,100,'...'),'/'.$data->url.'.htm',['target'=>'_blank', 'data-pjax'=>"0"]);
			   }
			],
           // 'title',
            //'text:ntext',
            //'description',
            //'keywords',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
