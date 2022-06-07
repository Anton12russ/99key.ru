<? 
foreach($model->blogField as $field) {
    if($field['field'] == '475') {
	    $phone = $field['value'];
    }
}
  $address = $model->coord['text'];
  $email = $model->author['email'];
  $name = $model->author['username'];
?>
<div class="alert alert-success"> Вы зарезервировали товар.</div>

<br>
<div class="bs-example">
    <div class="panel panel-default">

      <div class="panel-heading">Контакты продавца</div>

      <table class="table">

        <tbody>
          <tr>
            <td><strong>Имя:</strong></td>
            <td><?=$name?></td>

          </tr>
          <tr>
            <td><strong>Телефон:</strong></td>
            <td><?=$phone?></td>
     
          </tr>
          <tr>
            <td><strong>Email</strong></td>
            <td><?=$email?></td>
          </tr>
		  
		  <tr>
            <td><strong>Адрес сделки</strong></td>
            <td><?=$address?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
