<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentSystem */

$this->title = 'Create Payment System';
$this->params['breadcrumbs'][] = ['label' => 'Payment Systems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-system-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
