<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TimerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Таймер аукционов';
$this->params['breadcrumbs'][] = $this->title;
?>


<a class="btn btn-success" href="/admin/timerauction/update">Редактировать таймер</a>

<div class="timer-index">
<br>
   



    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<iframe style="border: 0px; min-width: 350px;height: 90px;" src="/admin/timerauction/views"></iframe>

    <?php Pjax::end(); ?>

</div>
