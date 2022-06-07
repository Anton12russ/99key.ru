<? if($model) {?>
<div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading">История ставок</div>
      <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>Имя пользователя</th>
            <th>Ставка</th>
          </tr>
        </thead>
        <tbody>
       <? foreach($model as $mod) {?>
          <tr>
            <td><?=$mod->author['username']?></td>
			<td><?=$mod->price?></td>
          </tr>
<? }?>
        </tbody>
      </table>
    </div>
<? }else{ ?>
	
	
	<div class="alert alert-warning">
      Ставок пока нет
    </div>
<? } ?>	