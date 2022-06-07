<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

Yii::$app->log->targets['debug'] = null;
?>

<div class="center_counter_cat">

Всего опубликованных магазинов (<span class="rows_cat"><?= $rows?></span>)
<div class="count_start">
   <button style="width: 100%;position: relative; top: 20px; " type="button" class="btn btn-warning count_start_go" data-act="shop"> Запустить пересчет магазинов</button>
</div>
<div class="progress progress-striped">
  <div class="progress-bar progress-bar-info counter_cat" data-id="1" data-count="0" role="progressbar"  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 1%">
  </div>
</div>

<div class="result"></div>
</div>

<?php
//exit();
?>


