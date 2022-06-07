<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\BlogAuctionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blog Auctions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-auction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Blog Auction', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'price_add',
            'price_moment',
            'date_add',
            'date_end',
            //'blog_id',
            //'user_id',
            //'rates',
            //'shag',
  ['attribute'=>'url','format'=>'raw', 'value'=>
	           function($data) {
				if(isset(Yii::$app->caches->region()[$data->blog['region']]['url']) && isset(Yii::$app->caches->category()[$data->blog['category']]['url'])) {
				  return Html::a(\yii\helpers\StringHelper::truncate($data->blog['url'],15,'...')
				  ,'/'.Yii::$app->caches->region()[$data->blog['region']]['url']
				  .'/'.Yii::$app->caches->category()[$data->blog['category']]['url'].'/'.$data->blog['url'].'_'.$data->blog_id.'.html',['target'=>'_blank', 'data-pjax'=>"0"]);
				}
			   }
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
