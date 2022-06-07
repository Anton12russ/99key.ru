<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Block */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$this->blocks['right'] = '';
?>
<div class="block-view">

   <div class="col-md-2">
      <?= $this->render('/user/user_menu.php') ?>
   </div>
   <div class="col-md-10">
    <div class="person-body">

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
            'text:raw',
            'Position',
            'Status',
            'date_add',
            'date_del',
            'Action',
			'Header',

            ['attribute'=>'category','value'=> function ($model) {return  Yii::$app->userFunctions->linenav_cat(Yii::$app->caches->category(), $model->category);},],
                     'sort',
        ],
    ]) ?>
	</div>
</div>
</div>
