<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserAlerts */

$this->title = 'Create User Alerts';
$this->params['breadcrumbs'][] = ['label' => 'User Alerts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-alerts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
