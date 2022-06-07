<div class="form-group field-blog-username required">
         <input placeholder="Введите адрес" data-coord="" type="text" id="suggest" class="form-control" aria-required="true">
</div>
<div id="YMapsIDadd"></div>
<br>
<? if($_GET['act'] == 'ot') {?>
	<div style="text-align: right;"><button type="button" class="btn btn-primary add-coord btn-ot-add">Выбрать</button></div>
<?}?>

<? if($_GET['act'] == 'kuda') {?>
	<div style="text-align: right;"><button type="button" class="btn btn-primary add-coord btn-kuda-add">Выбрать</button></div>
<?}?>
<?php
exit();
?>