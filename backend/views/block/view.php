<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Block */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Blocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="block-view">

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
            'text:ntext',
            'Position',
            'Status',
            'date_add',
            'date_del',
            'Action',
			'Header',
            'Registr',
            ['attribute'=>'category','value'=> function ($model) {return  Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->category(), $model->category);},],
            ['attribute'=>'region','value'=> function ($model) {return  Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->region(), $model->region);},],
            'sort',
        ],
    ]) ?>

</div>
