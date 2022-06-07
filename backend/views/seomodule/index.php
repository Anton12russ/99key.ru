<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SeoModuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seo Модуль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-module-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить правило', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],

           //'id',
            'title',
            'description:ntext',
            'keywords:ntext',
            'h1',
            'redirect',
            'cod_redirect',
            'url:url',


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
