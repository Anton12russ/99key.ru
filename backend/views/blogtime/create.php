<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BlogTime */

$this->title = 'Срок хранения объявления';
$this->params['breadcrumbs'][] = ['label' => 'Срок хранения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-time-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
