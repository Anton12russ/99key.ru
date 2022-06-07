 $(document).ready(function() {
	 modalOpen();


	 $(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
		 if (container.container == '#pjaxContent') {
			  modalOpen();
		
		 }
	  });
	 });
	 
	 
	 
 
	 
	 
	 
	 
	 
function coordinates() {

 ymaps.ready(function () {	
//Скрипт подсказок от яндекс	
	if($('#suggest').val()) { var suggest = $('#suggest').val(); $('#suggest').val('');}
 var suggestView = new ymaps.SuggestView('suggest', {
      provider: {
        suggest: function (request, options) {
          return (suggestView.state.get('open') ?
            ymaps.suggest(request) : ymaps.vow.resolve([]))
            .then(function (res) {
              suggestView.events.fire('requestsuccess', {
                target: suggestView,
              })
            
              return res;
			
            })
            
        }
      }
    });

if(suggest) {
  $('#suggest').val(suggest);
}
suggestView.state.set('open', true);	 
 ymaps.geolocation.get({
    provider: 'yandex',
    autoReverseGeocode: true
}).then(function (result) {
//Город пользователя
//console.log(result.geoObjects.get(0).properties.get('metaDataProperty').GeocoderMetaData.Address.Components[4].name);
	    var city = result.geoObjects.get(0).properties.get('metaDataProperty').GeocoderMetaData.Address.Components[4].name;
		if(!city) {city = '';}
// $('#address-text').val(result.geoObjects.get(0).properties.get('metaDataProperty').GeocoderMetaData.Address.Components[2].name+', '+city);

//  var addr = result.geoObjects.get(0).properties.get('metaDataProperty').GeocoderMetaData.text;

  //$('#suggest').val(addr);
  
/* var coord = result.geoObjects.get(0).geometry._coordinates;
  
 // $('#coord-lat').val(coord[0]);
//  $('#coord-lon').val(coord[1]);
  if (!coord) {
      coord = [56.78858096013811,49.61682750000001];
  }
if(suggest) {
	coord = [$('#coord-lat').val(), $('#coord-lon').val()];
}*/
 var  coord = [55.584222181163646,37.38552449999999];

  
 
            var myMap = new ymaps.Map('YMapsIDadd', {
                    center: coord,
                    zoom: 13,
                    behaviors: ['default', 'scrollZoom'],
					controls: ['zoomControl'],
					//controls: [new CrossControl],
					//controls: [new ymaps.control.SearchControl({ noPlacemark: true }), { top: 5, left: 200 }],
				
                });

myMap.controls.add(new CrossControl);
myMap.controls.add('zoomControl');
myMap.controls.add('typeSelector', { top: 5, right: 5 });
myMap.behaviors.disable('scrollZoom');  
myMap.events.add('actiontick', function (mapEventObject){
  $('.cross-control').addClass('cross-control-maxi');
 });
 
 
 $('#YMapsID').click(function(){
    $('#suggest').removeClass('panel-false'); 
 });
  		
 
myMap.events.add('actionend', function (event){



  $('.cross-control').removeClass('cross-control-maxi');
    var coords = myMap.getCenter();
	coord = coords[0]+', '+coords[1];
   $('#suggest').attr('data-coord', coord);
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);
			//Населенный пункт
			 //console.log(firstGeoObject.getLocalities());
			
			
			//Запрещаем панели открываться
		      suggestView.state.set({open: false});
			  
			  if(!$("#suggest").hasClass("act-none")) {
		        $('#suggest').val(firstGeoObject.getAddressLine());
			  }else{
			    $("#suggest").removeClass("act-none");
			  }
			  suggestfocus();
           
			  var city = firstGeoObject.getLocalities()[0];
			  if(!city) {city = '';}
			  
			  var region = firstGeoObject.getAdministrativeAreas()[0];
	          if(!region) {region = '';}
			 /* if(region) {
			    $('#address-text').val(region+', '+city);
			  }else{
			    $('#address-text').val('');
			  }*/
	        //Разрешаем панели открываться
	        suggestView.events.once('requestsuccess', function () {
                suggestView.state.set('open', true);
            }); 
        });	
 });


suggestView.events.add('select', function (e) {
	//Запрещаем панели открываться
	suggestView.state.set({open: false});
	$('#suggest').addClass('act-none');
	suggestfocus();
	var value = $('#suggest').val();
    ymaps.geocode(value, {
        results: 1
    }).then(function (res) {
            // Выбираем первый результат геокодирования.
            var firstGeoObject = res.geoObjects.get(0);
                // Координаты геообъекта.

                coords = firstGeoObject.geometry.getCoordinates();
				coord = coords[0]+', '+coords[1];
				$('#suggest').attr('data-coord', coord);
                // Область видимости геообъекта.
                bounds = firstGeoObject.properties.get('boundedBy');
			
            myMap.setBounds(bounds, {
                // Проверяем наличие тайлов на данном масштабе.
                checkZoomRange: true
            });
			
		
			
        });
   });
 
 
 });
});

}

function suggestfocus() {
  $('#suggest').focus();
  //$('#suggest').selectionStart = input.value.length;
}



function modalOpen() {
     $('.btn-ot').click(function(){
$('#map').load('/passanger/mapot?act=ot', function() {
	$('#YMapsIDadd').addClass('preloadmap');
	 coordinates();
	 
	 	$('#suggest').val('');
	    $('#coord').modal('toggle');
		coordadd('ot');

    });
		 	
		 
	

	 });
	
	 $('.btn-kuda').click(function(){
		 $('#map').load('/passanger/mapot?act=kuda', function() {
			 coordinates();
			 
	     $('#YMapsIDadd').addClass('preloadmap');
		 $('#suggest').val('');
        $('#coord').modal('toggle');
		coordadd();
		});
	 });
	 

	 
}

function coordadd(ot = false) {

if(ot)
{
	
	//Если выбрано откуда
	$('#suggest').unbind("keydown");
$('#suggest').keydown(function(e) {
    if(e.keyCode === 13) {
        if($('#suggest').attr('data-coord')) {
		   $('#passanger-coord_ot').val($('#suggest').attr('data-coord'));
		   $('#passanger-ot').val(mass($('#suggest').val()));
           $('#coord').modal('toggle');
		  }else{
			  alert('Укажите точный адрес');
		  }
    }
  });
  
  
}else{
	
$('#suggest').unbind("keydown");
$('#suggest').keydown(function(e) {
    if(e.keyCode === 13) {	
if($('#suggest').attr('data-coord')) {
		$('#passanger-coord_kuda').val($('#suggest').attr('data-coord'));
		$('#passanger-kuda').val(mass($('#suggest').val()));
        $('#coord').modal('toggle');
		 }else{
			  alert('Укажите точный адрес');
		  }
    }
  });		  
}

	
	
$('.btn-ot-add').unbind("click");
	  $('.btn-ot-add').click(function(){
		  if($('#suggest').attr('data-coord')) {
		   $('#passanger-coord_ot').val($('#suggest').attr('data-coord'));
		   $('#passanger-ot').val(mass($('#suggest').val()));
           $('#coord').modal('toggle');
		  }else{
			  alert('Укажите точный адрес');
		  }
	 });
$('.btn-kuda-add').unbind("click");
	    $('.btn-kuda-add').click(function(){
		if($('#suggest').attr('data-coord')) {
		$('#passanger-coord_kuda').val($('#suggest').attr('data-coord'));
		$('#passanger-kuda').val(mass($('#suggest').val()));
        $('#coord').modal('toggle');
		 }else{
			  alert('Укажите точный адрес');
		  }
	 });
}


function mass(arr) {
		var ot = arr.split(', ');
		ot = ot.reverse();
	    ot = ot.join(', ');
		ot = ot.replace(" ,", ",");
		var temp = "Строка<br>Строка<br>Строка";

		return ot;
}
