<ul>
<? if($result) {?>
  <? foreach($result as $res)  {?>
       <li><a href="/<?=$res['url']?>"><?=$res['name']?></a></li>
  <? } ?>
<? }else{ ?>
<li>Не найдено</li>
<? } ?>
</ul>
<? exit(); ?>