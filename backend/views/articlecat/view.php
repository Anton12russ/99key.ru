<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\ArticleCat;
/* @var $this yii\web\View */
/* @var $model common\models\ArticleCat */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Article Cats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="article-cat-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
                [                                         
            'label' => 'Родительская категория',
            'value'=> function ($model) {return ArticleCat::linenav($model->parent);},         
         ],
        ],
    ]) ?>

</div>
