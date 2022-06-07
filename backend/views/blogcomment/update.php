<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BlogComment */

$this->title = 'Обновить коментарий: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Комментарии объявлений', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="blog-comment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
