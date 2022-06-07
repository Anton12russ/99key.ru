<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Fields */

$this->title = 'Доавить поле';
$this->params['breadcrumbs'][] = ['label' => 'Fields', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fields-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
