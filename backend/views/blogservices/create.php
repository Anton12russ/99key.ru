<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BlogServices */
$this->title = 'Добавить услугу';
$this->params['breadcrumbs'][] = ['label' => 'blogservices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="blog-services-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'model_update' => $model_update,
    ]) ?>

</div>
