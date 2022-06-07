<?php

/* @var $this yii\web\View */

$this->title = 'Админпанель';

$setting = explode("\n",Yii::$app->caches->setting()['yandex-grafik']);

$url = 'https://api-metrika.yandex.ru/stat/v1/data';

 if(isset($_GET['stats'])) { 
   echo "<script> var stats = "."'".$_GET['stats']."'"."</script>";$stats = $_GET['stats'];
}else{
   echo "<script> var stats = '30';</script>";	
   $stats = '30';
}



/////////////////////////////////////////////////////////
///// ПОЛУЧИМ ДАННЫЕ В JSON И ПЕРЕВЕДЁМ ИХ В МАССИВ /////
/////////////////////////////////////////////////////////







function curl_file_get_contents($url, $token)
{


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-yametrika+json', 'Authorization: OAuth' .  $token]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $obj = curl_exec($ch);
    curl_close($ch);
    return $obj;
	}
$url = 'https://api-metrika.yandex.ru/stat/v1/data';
$authToken = preg_replace('/\s+/', '',$setting[0]);

$params = array(


    'ids'         => $setting[1],
   // 'oauth_token' => $_settings['token_metr'],
    'metrics'     => 'ym:s:visits,ym:s:pageviews,ym:s:users',
    'dimensions'  => 'ym:s:date',
    'date1'       => $stats.'daysAgo',
    'date2'       => 'yesterday',
    'sort'        => 'ym:s:date',
);



$json = curl_file_get_contents( $url . '?' . http_build_query($params),$authToken);
if(isset(json_decode($json, true)['data'])) {
  $data = json_decode($json, true)['data'];

///////////////////////////////////////////////////////////////
///// ПРЕОБРАЗУЕМ ДАННЫЕ ДЛЯ ЛИНЕЙНОГО ГРАФИКА HIGHCHARTS /////
///////////////////////////////////////////////////////////////
$tmpdata = [];
if ($data) {
foreach($data as $item) {
    $tmpdata['visits'][]     = $item['metrics'][0];
    $tmpdata['pageviews'][]  = $item['metrics'][1];
    $tmpdata['users'][]      = $item['metrics'][2];
    $tmpdata['categories'][] = $item['dimensions'][0]['name'];
}
}
//////////////////////////////////////////////////////////
///// ВЕРНЁМ JSON С НУЖНОЙ СТРУКТУРОЙ ДЛЯ HIGHCHARTS /////
//////////////////////////////////////////////////////////
$categories = json_encode($tmpdata['categories'], JSON_UNESCAPED_UNICODE);
$series = json_encode([
    [ 'name' => 'Визиты',     'data' => $tmpdata['visits'] ],
    [ 'name' => 'Просмотры',  'data' => $tmpdata['pageviews'] ],
    [ 'name' => 'Посетители', 'data' => $tmpdata['users'] ]
], JSON_UNESCAPED_UNICODE);
/////////////////////////////////////////////////////////
///// ВЫВЕДЕМ HTML-КОД И ПОСТРОИМ ГРАФИК HIGHCHARTS /////
/////////////////////////////////////////////////////////
		
		
		


$params = array(
    'ids'         => $setting[1],
    'metrics'     => 'ym:s:visits,ym:s:pageviews,ym:s:users',
    'dimensions'  => 'ym:s:date',
    'date1'       => $stats.'daysAgo',
    'date2'       => 'yesterday',
    'sort'        => 'ym:s:date',
);




$json = curl_file_get_contents( $url . '?' . http_build_query($params), $authToken);
$data = json_decode($json, true);



$tmpdata = [];
if ($data['data']) {
foreach ($data['data'] as $item) {
    $tmpdata[] = [
        'name' => $item['dimensions'][0]['name'],
        'y'    => $item['metrics'][0],
    ];
}
}
$dataForSeries = json_encode($tmpdata, JSON_UNESCAPED_UNICODE);


		
		
		
		
		
		echo '
		
<div style="display: inline-flex; line-height: 30px; width: 100%; margin-bottom: 20px;">
<span>Статистика за </span>	

<select id="listsort" style="width: 100px; margin-left: 5px; margin-right: 5px;">
';?> <?php if(isset($_GET['stats'])) { echo '<option value="?stats='.$_GET['stats'].'">'.$_GET['stats'].'</span>';}else{echo '<option value="?stats=30">30</span>';}?>
<?php echo '
<option value="?stats=1">1</option>
<option value="?stats=2">2</option>
<option value="?stats=3">3</option>
<option value="?stats=5">5</option>
<option value="?stats=7">7</option>
<option value="?stats=10">10</option>
<option value="?stats=15">15</option>
<option value="?stats=20">20</option>
<option value="?stats=30">30</option>
<option value="?stats=40">40</option>
<option value="?stats=50">50</option>
</select> Дней
</div>
<div class="col-md-12 heighttt"></div>
<script>
  $("#listsort").change(function() {
    if($(this).val() != ""){
            window.location = $(this).val();
        }
      
    });
	
</script>
';

			
			























echo <<<HTML
<div style="display: table; width: 100%; background: #FFF; margin-bottom: 20px; overflow: hidden;">
<div id="containers" style='display: table; width: 100%;'></div>
</div>
<div style="display: table; width: 100%; background: #FFF; overflow: hidden;">

<div id="container1"></div>
</div>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script>
    Highcharts.chart('container1', {
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
      },
      title: {
        text: 'Источники трафика за последние ' +stats+ ' дней'
      },
      tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: true,
            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
            style: {
              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
            }
          }
        }
      },
      series: [{
        name: 'Визиты',
        colorByPoint: true,
        data: $dataForSeries
      }]
    });

    Highcharts.chart('containers', {
      chart: {
        type: "spline"
      },
      title: {
        text: "Активность посетителей за последние "+stats+" дней",
        x: -20
      },
      xAxis: {
        categories: $categories
      },
      yAxis: {
        title: {
            text: "Количество"
        }
      },
      legend: {
        layout: "vertical",
        align: "right",
        verticalAlign: "middle",
        borderWidth: 0
      },
      series: $series
    });
  </script>
HTML;
//end
}else{
?><div class="alert alert-warning">Ваш график не настроен, для того, чтобы его настроить, прочтите инструкцию.</div>

<p>1) Авторизирутесь на яндексе</p>

<p>2) Регистрируем Счетчик метрики https://metrika.yandex.ru/list/</p>

<p>3) Вставляем код счетчика в админпанели -> настройки -> Код счетчика типа (яндекс метрики)</p>

<p>4) Перейдите по ссылке https://oauth.yandex.ru/client/new и зарегистрировать новое приложение</p>

<p>- Название приложения*: Вписываем название сайта</p>

<p>- Платформы: Веб-сервисы</p>

<p>- Callback URI #1: Не заполняем</p>

<p>- Нажимаем на ссылку (Подставить URL для разработки)</p>

<p>- В списке, который ниже, выбираем (яндекс метрика + Получение статистики, чтение параметров своих и доверенных счётчиков)</p>

<p>5) Копируем ID</p>

<p>6) Переходим по этой сслыке https://oauth.yandex.ru/authorize?response_type=token&client_id=</p>

<p>7) В конец URL вставляем наш id</p>

<p>8) Разрешаем доступ</p>

<p>9) Копируем полученный токен и вставляем в админапель -> настройки -> График посещения на главной админпанели (Первая строка).</p>

<p>10) Заходим и копируем id счетчика https://oauth.yandex.ru/client/new, затем вставляем id счетчика в админапель -> настройки -> График посещения на главной админпанели (Вторая строка)</p>

<p>ПРИМЕЧАНИЕ: Если Счетчик создан только что, то график появится не сразу,а в течении суток. Остается только подождать.</p>
<?php
}
?>