<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CatServices */

$this->title = 'Обновить запись: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Обновить запись', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cat-services-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
	    'reates' => $reates,
        'model' => $model,
    ]) ?>

</div>
