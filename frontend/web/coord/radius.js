$(document).ready(function() {
	
   if ($(".search_form").length){
   	$('.btn-coord').click(function(){

		 $('#filtr-coord').val($('#suggest').attr('data-coord'));
		 $('#filtr-radius').val($('#suggest').attr('data-radius'));
		 $(".go-search").click();
	});
   }else{
	 $('.btn-coord').click(function(){

		 $('#filtr-coord').val($('#suggest').attr('data-coord'));
		 $('#filtr-radius').val($('#suggest').attr('data-radius'));
		 $(".top-submit-text").click();
	});
   }
	 
	   if( $('#filtr-coord').val()) {
		   $('#suggest').attr('data-coord', $('#filtr-coord').val());
		   $('#suggest').attr('data-radius', $('#filtr-radius').val());
	   }

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

  
var coord = $('#suggest').attr('data-coord').split(',');

if(suggest) {
	coord = [$('#coord-lat').val(), $('#coord-lon').val()];
}
  if($('#filtr-coord').val()) {
		 coord = $('#filtr-coord').val().split(',');

	}

            var myMap = new ymaps.Map('YMapsIDadd', {
                    center: coord,
                    zoom: 9,
                    behaviors: ['default', 'scrollZoom'],
					controls: ['zoomControl'],
					//controls: [new CrossControl],
					//controls: [new ymaps.control.SearchControl({ noPlacemark: true }), { top: 5, left: 200 }],
				
                });

myMap.controls.add(new CrossControl);
myMap.controls.add('zoomControl');
myMap.controls.add('typeSelector', { top: 5, right: 5 });
myMap.behaviors.disable('scrollZoom');  
myMap.controls.add('geolocationControl');
//Обозначаем переменные как пустые

 var circle = '';
 var coords = '';
 var actionend = '';
 var radius = 80;
 var coordinates = '';
 var radius = '';
 var formData = ''
 
setTimeout(function(){
     getyes();
}, 500);
function getyes() {
	if($('#filtr-radius').val()) {
		$('[data-radius]').each(function(i, obj) {
             if($(obj).attr('data-radius') == $('#filtr-radius').val()) {
				  	$(obj).attr('checked', 'checked');
			  }
         });
		circlefunction($('#filtr-coord').val().split(','), $('#filtr-radius').val()*1000);
		  actionend = true;
		myMap.setBounds(myMap.geoObjects.getBounds());
	      actionend = '';
	
	}
}


myMap.events.add('actiontick', function (mapEventObject){
   $('.cross-control').addClass('cross-control-maxi');

   if(!actionend) {
         myMap.geoObjects.removeAll();
    }
	

	
 });
 





 $('.radius input').click(function(){
	$('#suggest').attr('data-radius', $(this).attr('data-radius'));
	counter();
	actionend = true;
    radius = Number.parseInt($(this).attr('data-radius')*1000);
	
    myMap.geoObjects.remove(circle);
	if(coords) {coord = coords}
    circlefunction(coord,  radius);
	myMap.setBounds(myMap.geoObjects.getBounds(),{checkZoomRange:false, zoomMargin:1});
    actionend = '';
 });




	


myMap.events.add('actionend', function (event){

if(!actionend) {

  $('.cross-control').removeClass('cross-control-maxi');
   coords = myMap.getCenter();
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

              ajaxcoord();
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

 
	
		    circlefunction(coords,radius);
            $('#suggest').attr('data-coord', coords);
         
			counter();
        });	
		
		}else{

       $('.cross-control').removeClass('cross-control-maxi');

}

 });






 
 
function circlefunction(coord,radiuss){
	if($('#suggest').attr('data-radius') > 1) {
         radiuss = $('#suggest').attr('data-radius')*1000;
	}
	
	circle = new ymaps.Circle([coord, radiuss], {}, {
      geodesic: true,
      opacity: 0.5,

   });
   //Создаем окружность
   myMap.geoObjects.add(circle);


 }
 
 
 
 
suggestView.events.add('select', function (e) {
	//Запрещаем панели открываться
	suggestView.state.set({open: false});
	$('#suggest').addClass('act-none');

	var value = $('#suggest').val();
    ymaps.geocode(value, {
        results: 1
    }).then(function (res) {
            // Выбираем первый результат геокодирования.
                var firstGeoObject = res.geoObjects.get(0);
                // Координаты геообъекта.
                coords = firstGeoObject.geometry.getCoordinates();
				 $('#coord-lat').val(coords[0]);
				 $('#coord-lon').val(coords[1]);
                // Область видимости геообъекта.
                bounds = firstGeoObject.properties.get('boundedBy');
			
            myMap.setBounds(bounds, {
                // Проверяем наличие тайлов на данном масштабе.
                checkZoomRange: true
            });
        });
   });
 
 




});





function ajaxcoord() {
		$.ajax({
        url:   '/ajax/coordadd', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {region:$('#suggest').val()},
        success: function(response) { //Данные отправлены успешно
		if(response) {
		  $('#blog-region').val(response);
		}else{
			alert('Укажите верный регион');
		}
		 
		} 
    });
}




/*
function counter() {
		var coordinates = $('#filtr-coord').val(coords);
		var radius = $('#filtr-radius').val($('#suggest').attr('data-radius'));
		var formData = $("form :input") .filter(function(index, element) { return $(element).val() != ""; }) .serialize();
        $.ajax({
        url:    '/ajax/coord-counter?coord='+coordinates+'&radius='+radius, //url страницы (action_ajax_form.php)
        type:     "GET", //метод отправки
        dataType: "html", //формат данных
        data: formData,  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
	alert(response);	
	
		} 
    });
}*/

}

$('#loadcoord').click(function(){	
    counter();
});

function counter() {
	      coordinates = $('#suggest').attr('data-coord');
		  radius = $('#suggest').attr('data-radius');
		

        if ($(".search_form").length){
		   formData = $("#formall :input").filter(function(index, element) { return $(element).val() != "" && $(element).attr('id') != "filtr-coord" && $(element).attr('id') != "filtr-radius";  }).serialize();
        }else{  
           formData = $("#formtop :input").filter(function(index, element) { return $(element).val() != "" && $(element).attr('id') != "filtr-coord" && $(element).attr('id') != "filtr-radius";  }).serialize();
		}   

		if($('.search_form').attr('data-cat')) {
			var cat = $('.search_form').attr('data-cat');
		}else{
			var cat = '';
		}
if ($(".auction").length){
	var url = '/ajax/coord-counter?auction=true&coord='+coordinates+'&radius='+radius+'&category='+cat;
}else{
    var url = '/ajax/coord-counter?coord='+coordinates+'&radius='+radius+'&category='+cat;
}		 $.ajax({
       url: url   , //url страницы (action_ajax_form.php)
        type:     "GET", //метод отправки
        dataType: "html", //формат данных
        data: formData,  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
        $('.coord-count').text(' ('+response+')');	
  		 $('.btn-coord').css('display','block');
		} 
    });



	
}
