<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\StaticPage */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Статичные страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="static-page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            ['attribute'=>'url','format'=>'raw', 'value'=>
	           function($data) {
				  return Html::a(\yii\helpers\StringHelper::truncate($data->url,100,'...'),'/'.$data->url.'.htm',['target'=>'_blank', 'data-pjax'=>"0"]);
			   }
			],
            'title',
            'text:html',
            'description',
            'keywords',
			'Menu',
        ],
    ]) ?>

</div>
