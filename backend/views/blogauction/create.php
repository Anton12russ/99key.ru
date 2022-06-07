<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BlogAuction */

$this->title = 'Create Blog Auction';
$this->params['breadcrumbs'][] = ['label' => 'Blog Auctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-auction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
