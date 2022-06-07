<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SupportSubject */

$this->title = 'Create Support Subject';
$this->params['breadcrumbs'][] = ['label' => 'Support Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="support-subject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
