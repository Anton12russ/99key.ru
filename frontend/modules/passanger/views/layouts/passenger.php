<?php
  use yii\helpers\Url;
  $this->registerCssFile('/passanger_js/css/calendar_main.css');
  $this->registerJsFile('/passanger_js/js/calendar.js',['depends' => [\yii\web\JqueryAsset::className()]]);
  $this->registerJsFile('/passanger_js/js/calendar_script.js',['depends' => [\yii\web\JqueryAsset::className()]]);
  $passenger = json_encode($passenger);
  $script = <<< JS
  calendarmain($passenger);
  
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="col-md-12 shop-top" style="background: #FFF;padding-bottom:15px;">
<h3 class="block-header"><i class="fa fa-car" aria-hidden="true"></i><a href="/passanger" style="text-decoration: none;"> Попутчики</a></h3>
<div class="calendarmain"></div>
</div>









