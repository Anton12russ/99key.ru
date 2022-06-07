

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=<?=Yii::$app->caches->setting()['api_key_yandex']?>>" type="text/javascript"></script>

	<script>
	ymaps.ready(init);
function init () {
    var myMap = new ymaps.Map("map", {
            center: [<?=$coord?>],
            zoom: 15
        }, {
            searchControlProvider: 'yandex#search'
        }),
        myPlacemark = new ymaps.Placemark([<?=$coord?>], {
            // Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
            balloonContentHeader: "<?=Yii::$app->request->get()['address']?>",
  
        });

    myMap.geoObjects.add(myPlacemark);

}
	</script>

		<style>
        #map, body, html {
            width: 100%; height: 100%; padding: 0; margin: 0;
        }
    </style>

<div id="map"></div>
<?php exit();?>