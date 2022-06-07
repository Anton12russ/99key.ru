<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserAlerts */

$this->title = 'Update User Alerts: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Alerts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-alerts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
