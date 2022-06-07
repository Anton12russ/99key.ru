<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=<?=Yii::$app->caches->setting()['api_key_yandex']?>" type="text/javascript"></script>

	<script>


ymaps.ready(function () {  
    var myMap = new ymaps.Map('map', {
        center: [55.751574, 37.573856],
        zoom: 9,
        controls: []
    });
    
    // Создание маршрута.
    var multiRoute = new ymaps.multiRouter.MultiRoute({
        referencePoints: [
            [<?=$coord_ot?>],
            [<?=$coord_kuda?>]
        ]
    }, {
        // Внешний вид путевых точек.
        wayPointStartIconColor: "#FFFFFF",
        wayPointStartIconFillColor: "#B3B3B3",
        // Внешний вид линии активного маршрута.
        routeActiveStrokeWidth: 8,
        routeActiveStrokeStyle: 'solid',
        routeActiveStrokeColor: "#002233",
        // Внешний вид линий альтернативных маршрутов.
        routeStrokeStyle: 'dot',
        routeStrokeWidth: 3,
        boundsAutoApply: true
    });

    // Добавление маршрута на карту.
    myMap.geoObjects.add(multiRoute);
});   

	</script>

		<style>
        #map, body, html {
            width: 100%; height: 100%; padding: 0; margin: 0;
        }
    </style>

<div id="map"></div>
