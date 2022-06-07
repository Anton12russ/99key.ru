<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Region;
/* @var $this yii\web\View */
/* @var $model common\models\Region */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="region-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
		
		
	
        <?= Html::a('Добавить еще', ['create'], ['class' => 'btn btn-success']) ?>

    </p>

    <?= DetailView::widget([

        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'url',
			
           [                                         
            'label' => 'Родительская категория',
            'value'=> function ($model) {return Region::linenav($model->parent);},      
         ],
		
            'sort',
        ],
    ]) ?>

</div>
