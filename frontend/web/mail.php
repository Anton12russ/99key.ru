<?php
// отправка нескольким адресатам
$to  = 'saittronik@mail.ru' . ', '; // кому отправляем
$to .= 'elite-board@mail.ru' . ', '; // Внимание! Так пишем второй и тд адреса
// не забываем запятую. Даже в последнем контакте лишней не будет
// Для начинающих! $to .= точка в этом случае для Дописывания в переменную 

// содержание письма
$subject = "Тема сообщения";
$message = '
<html>
    <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Тема страницы</title>
    </head>
    <body>
        <p>А здесь ваше сообщение</p>
    </body>
</html>';

// устанавливаем тип сообщения Content-type, если хотим
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= "Content-type: text/html; charset=utf-8 \r\n";

// дополнительные данные
$headers .= "From: Тест письма <admin@1tu.ru>\r\n"; // от кого

mail($to, $subject, $message, $headers);
?>