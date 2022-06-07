<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SeoModule */

$this->title = 'Create Seo Module';
$this->params['breadcrumbs'][] = ['label' => 'Seo Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-module-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
