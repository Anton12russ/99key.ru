<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BlogServices */


?>
<div class="blog-services-create blog-services_none">

    <h1><?= Html::encode($this->title) ?></h1>
	<?if(!isset($model_ok)){$model_ok = '';}?>
    <?= $model_ok?>
    <?= $this->render('_form', [
        'model' => $model,
		'model_update' => $model_update,
    ]) ?>

</div>
