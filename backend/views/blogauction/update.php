<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BlogAuction */

$this->title = 'Update Blog Auction: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Blog Auctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="blog-auction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
