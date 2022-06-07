<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CatServices */

$this->title = 'Добавить запись';
$this->params['breadcrumbs'][] = ['label' => 'Добавить запись', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cat-services-create">

    <h1><?= Html::encode($this->title)  ?></h1>


    <?= $this->render('_form', [
	    'reates' => $reates,
        'model' => $model,
    ]) ?>

</div>
