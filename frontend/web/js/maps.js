function maps_id() {
var myMap;
var search_result = [];
var myPlacemark;

ymaps.ready(function () {
    myMap = new ymaps.Map("myMap", {
        center: [55.747515,37.620868],
        zoom: 17,
        behaviors: ['default', 'scrollZoom']
    });
    myMap.controls.add('zoomControl');

    //Определяем метку и добавляем ее на карту
    myPlacemark = new ymaps.Placemark([55.747515,37.620868],{}, {preset: "twirl#redIcon", draggable: true});
    myMap.geoObjects.add(myPlacemark);

    // var search_line = $('#findAllregions').val();
    // findByGeocode(search_line);
    if ($(".coordin").val() != '') {
      var longlat = $(".coordin").val().split(",");
      var new_coords = [longlat[0], longlat[1]];

      var myReverseGeocoder = ymaps.geocode(new_coords);
      myReverseGeocoder.then(
          function (res) {
            // Выбираем первый результат геокодирования.
            var firstGeoObject = res.geoObjects.get(0),
            // Координаты геообъекта.
            coords = firstGeoObject.geometry.getCoordinates(),
            // Область видимости геообъекта.
            bounds = firstGeoObject.properties.get('boundedBy');

            myPlacemark.getOverlay().getData().geometry.setCoordinates(new_coords);
            // localStorage.setItem("coords", coords);
            myMap.setCenter([longlat[0], longlat[1]], 17);
            // myMap.setBounds(bounds, {
            //   // Проверяем наличие тайлов на данном масштабе.
            //   checkZoomRange: true
            // });

            $(".coordin").val(longlat[0] + ', '+longlat[1]);
            console.log('done');
          },
          function (err) {
              // обработка ошибки
          }
      );
    };

    //Отслеживаем событие перемещения метки
    myPlacemark.events.add("dragend", function (e) {
      coords = this.geometry.getCoordinates();
      savecoordinats();
      getAddress(coords);
    }, myPlacemark);

    //Отслеживаем событие щелчка по карте
    myMap.events.add('click', function (e) {
      coords = e.get('coordPosition');
      savecoordinats();
      getAddress(coords);
    });
});

// Определяем адрес по координатам (обратное геокодирование).
//function getAddress(coords) {
   // myPlacemark.properties.set('iconCaption', 'поиск...');
   // ymaps.geocode(coords).then(function (res) {
     //   var firstGeoObject = res.geoObjects.get(0);
     //   var ert = firstGeoObject.properties.get('name');
     //   $("#Address").val(ert);
    //});
//}

//Функция для передачи полученных значений в форму
function savecoordinats (){
    var new_coords = [coords[0].toFixed(6), coords[1].toFixed(6)];
    myPlacemark.getOverlay().getData().geometry.setCoordinates(new_coords);
    // localStorage.setItem("coords", new_coords);

    $(".coordin").val(new_coords);
    var center = myMap.getCenter();
    var new_center = [center[0].toFixed(6), center[1].toFixed(6)];
}

function findByGeocode(search_query) {
  if(search_query == '') return false;
  //массив, в который будем записывать результаты поиска
  var myGeocoder = ymaps.geocode(search_query, {results: 1});
  myGeocoder.then(
      function (res) {
        // Выбираем первый результат геокодирования.
        var firstGeoObject = res.geoObjects.get(0),
        // Координаты геообъекта.
        coords = firstGeoObject.geometry.getCoordinates(),
        // Область видимости геообъекта.
        bounds = firstGeoObject.properties.get('boundedBy');

        myPlacemark.getOverlay().getData().geometry.setCoordinates(coords);
        // localStorage.setItem("coords", coords);
        // console.log(localStorage.coords);
        myMap.setBounds(bounds, {
          // Проверяем наличие тайлов на данном масштабе.
          checkZoomRange: true
        });

        $(".coordin").val(coords[0] + ', '+coords[1]);
      },
      function (err) {
          // обработка ошибки
      }
  );
}

function findGeo(search_line) {
  $.getJSON('https://geocode-maps.yandex.ru/1.x/?format=json&callback=?&geocode='+search_line, function(data) {
      search_result = [];
      if (data.response !== undefined) {
        for(var i = 0; i < data.response.GeoObjectCollection.featureMember.length; i++) {
            //записываем в массив результаты, которые возвращает нам геокодер
            search_result.push({
                label: data.response.GeoObjectCollection.featureMember[i].GeoObject.description+' - '+data.response.GeoObjectCollection.featureMember[i].GeoObject.name,
                value:data.response.GeoObjectCollection.featureMember[i].GeoObject.description+' - '+data.response.GeoObjectCollection.featureMember[i].GeoObject.name,
                longlat:data.response.GeoObjectCollection.featureMember[i].GeoObject.Point.pos});
        }
        var longlat = search_result[0].longlat.split(" ");
        var new_coords = [longlat[1], longlat[0]];

        var newCoord = longlat[1]+", "+longlat[0];
        $(".coordin").val(newCoord);

        myPlacemark.getOverlay().getData().geometry.setCoordinates(new_coords);
        // localStorage.setItem("coords", new_coords);
        myMap.setCenter([longlat[1], longlat[0]], 8);
      }
  });
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
$(document).ready(function(){
    $(".Address").keyup(function(){
        //по мере ввода фразы, событие будет срабатывать всякий раз
        // console.log($(this).val());
        var region = $('#findAllregions').val();
        if ($(this).val() < 3) return false;
        var search_query = region + ', ' + $(this).val();

        findByGeocode(search_query);
    });
    






});

}