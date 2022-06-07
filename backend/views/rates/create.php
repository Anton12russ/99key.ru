<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Rates */

$this->title = 'Create Rates';
$this->params['breadcrumbs'][] = ['label' => 'Rates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rates-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
