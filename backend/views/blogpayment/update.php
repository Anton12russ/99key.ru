<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BlogPayment */

$this->title = 'Обновить услугу: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Платные услуги', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="blog-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
