<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Block */

$this->title = 'Редактирование блока: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->blocks['right'] = '';
?>

<div class="row">

   <div class="col-md-3">
      <?= $this->render('/user/user_menu.php') ?>
   </div>
<div class="col-md-9 block-update">
 <div class="add-left-body">
    <h1><?= Html::encode($this->title) ?></h1>
<br>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
