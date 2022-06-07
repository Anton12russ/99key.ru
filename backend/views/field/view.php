<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Field;
/* @var $this yii\web\View */
/* @var $model common\models\Field */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Field', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fields-view">

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
             [                                         
            'label' => 'Категория',
           'value'=> function ($model) {return  Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->category(), $model->cat);},           
         ],
            'name',
            'values:ntext',
            'max',
            'typeField',
            'typeStringField',
            'requiredField',
            'hidenField',
            'blockField',
            'sort',
        ],
    ]) ?>

</div>
