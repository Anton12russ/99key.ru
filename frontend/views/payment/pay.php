<?php
use yii\helpers\Url;


if (!isset($status)) {
   echo $return;
$this->registerJs( <<< EOT_JS_CODE
$("form").submit();
EOT_JS_CODE
);
}else{
$redireect = Url::to(['user/balance']);
$this->registerJs( <<< EOT_JS_CODE
setTimeout(
  function() 
  {
    window.location.href = "{$redireect}";
  }, 2253000);
EOT_JS_CODE
);
}
?>
<?php if (isset($status)) {?>

<?php if ($status == 1) {?>
   <div class="alert alert-success">Платеж зачислен.<br> Идет переадресация на страницу личного кабинета, ожидайте пожалуйста.</div>
<?php }?>
<?php if ($status == 2 || $status == 0) {?>
   <div class="alert alert-danger">Платеж не прошел.<br> Идет переадресация на страницу личного кабинета, ожидайте пожалуйста.</div>
<?php }?>

<?php }else{ ?>
<div class="alert alert-info">Подождите, идет переадресация на страницу оплаты</div>
<?php }?>