<?php


/* @var $this yii\web\View */
/* @var $blogs common\models\Blog */

use yii\widgets\LinkPager;
use yii\helpers\Url;
$this->registerCssFile('/css/category.css', ['depends' => ['frontend\assets\AppAsset']]);

?>

    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=<?=(string)Yii::$app->caches->setting()['api_key_yandex']?>" type="text/javascript"></script>
 
	<script>

ymaps.ready(init);
function init () {
	
	    $(".notepad").click(function(){

		 
          $(this).addClass('active');	
		  $('#ajax_form').toggle();
		  $('.note-ang').html('<div class="alert alert-danger">Просмотр избранных объявлений. <span class="close-not">продолжить поиск</span></div>');		
		  $('.note-ang').toggle();
		  $('.drop').removeClass("open");
		ajaxnone();
	
		$(".close-not").click(function(){

			
			$('.note-ang').toggle();
			$('#ajax_form').toggle();
			$('.notepad').removeClass('active');
			ajaxnone();
		});
	
		return false;
 	});
	
    var myMap = new ymaps.Map('map', {
            center: [55.76, 37.64],
            zoom: 10,
			controls: ['zoomControl'],
        }, {
            searchControlProvider: 'yandex#search'
        }),
		
        objectManager = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: true,
			hasBalloon : false,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32,
            clusterDisableClickZoom: true,
			useMapMargin: true,
            groupByCoordinates: true,
			  clusterIcons: [
                {
					
                    href: 'map_js/img/claster.png',
                    size: [25, 25],
                    offset: [-15, -15],
					
                },
                {
                    href: 'map_js/img/claster.png',
                    size: [25, 25],
                    offset: [-15, -15],
					
				
                }],
				
				
				  clusterIconContentLayout: ymaps.templateLayoutFactory.createClass('<span style="color: #FFF; display: block; font-size: 10px; margin-top: 0px;">{{ properties.geoObjects.length }}</span>')

		
		});

   //Создаем Хеш и проверяем совпадения, если они есть, то выделяем кластер, который уже был открыт ранее
  var visitedClusters = { };
objectManager.clusters.events.add('add', function (e) {
   var cluster = objectManager.clusters.getById(e.get('objectId')),
        objects = cluster.properties.geoObjects;
		if (visitedClusters[objects[0].id]) {
	         objectManager.clusters.setClusterOptions(e.get('objectId'), 
                {iconLayout: 'default#imageWithContent', iconImageHref: 'map_js/img/href.png', iconContentOffset: [9, 8],  iconImageSize: [30,30], iconImageOffset: [-19, -19]
	         }); 
		}

});


   // Чтобы задать опции одиночным объектам и кластерам,
    // обратимся к дочерним коллекциям ObjectManager.
    objectManager.objects.options.set({
         // Опции.
            // Необходимо указать данный тип макета.
            iconLayout: 'default#image',
            // Своё изображение иконки метки.
            iconImageHref: 'map_js/img/place.png',
            // Размеры метки.
            iconImageSize: [25, 25],
      
            // Смещение левого верхнего угла иконки относительно
            // её "ножки" (точки привязки).
           iconImageOffset: [-15, -10]
			

    });


    // Чтобы задать опции одиночным объектам и кластерам,
    // обратимся к дочерним коллекциям ObjectManager.

    myMap.geoObjects.add(objectManager);

	    function getVisibleObjects() {
			
      return objectManager.objects.getAll()
      .filter(function (obj, index) {
          return ymaps.util.bounds.containsPoint(
            myMap.getBounds(),
            obj.geometry.coordinates
          )
      })
    }
 
		    myMap.events.add('boundschange', function () {
         
            var objects = getVisibleObjects();
				var  arr = [];
				var  arrs = [];
				var  arid = [];
				objects.forEach(function (obj) {
                arrs[obj.id] = obj.id;
				arid = obj.id;
				arr.push(obj.id);
				
				
				if (visitedClusters[obj.id]) {
	             objectManager.objects.setObjectOptions(obj.id,
                  {iconLayout: 'default#image', iconImageHref: 'map_js/img/href.png'}); 
		        }
				
                });
				

					
	          if(('total objects: ', objects.length > 1) || ('total objects: ', objects.length == 0)) {
				     openone_arr(myMap.getBounds()[0], myMap.getBounds()[1]); 	
		
							  
				}else{
					if('total objects: ', objects.length == 1) {
				       openone(Object.keys( arrs )[0]);
					}
				}
            });
	

    ajax();
   


formgo();
function formgo() {
   $("#btn").click(function(){
		sendAjaxForm( 'ajax_form', '/map/coord');	
		$('.drop').removeClass("open");
		return false;
   });
   

}

function sendAjaxForm(ajax_form, url) {

	var formData = $("#"+ajax_form+" :input") .filter(function(index, element) { return $(element).val() != ""; }) .serialize();
	

    $.ajax({
        url:     url, //url страницы (action_ajax_form.php)
        type:     "GET", //метод отправки
        dataType: "html", //формат данных
        data: formData,  // Сеарилизуем объект
   }).done(function(data) {
	
     objectManager.removeAll();
     var regs = $('.regions-coord').text();
	 	 
	 //Раскомментировать, если нужно ограничить область видимости , только город, чтобы видеть объявления только города, несмотря на то, что пользователь указал коорлинаты не своего региона.
	/* if(regs) {
	  ymaps.geocode(regs, {
      
        results: 1
    }).then(function (res) {
            // Выбираем первый результат геокодирования.
            var firstGeoObject = res.geoObjects.get(0),
                // Координаты геообъекта.
                coords = firstGeoObject.geometry.getCoordinates(),
			
                // Область видимости геообъекта.
          bounds = firstGeoObject.properties.get('boundedBy');
            // Масштабируем карту на область видимости геообъекта.
            myMap.setBounds(bounds, {
                // Проверяем наличие тайлов на данном масштабе.
                checkZoomRange: true
            });
	
        });
	
	 } */
	 
     var result = JSON.parse(data);

	 if(result.features) {

		   objectManager.add(data);

	var result = JSON.parse(data);
	 if(result.features) {
		 if(result.features.length == 1) {
			 myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange:true, zoomMargin:17});
		 }else{
	       myMap.setBounds(myMap.geoObjects.getBounds());
		 }
	 }
		
		
		
		 var objects = getVisibleObjects();
				var  arr = [];
				var  arrs = [];
				var  arid = [];
				objects.forEach(function (obj) {
                arrs[obj.id] = obj.id;
				arid = obj.id;
				arr.push(obj.id);
	
				if (visitedClusters[obj.id]) {
	             objectManager.objects.setObjectOptions(obj.id,
                  {iconLayout: 'default#image', iconImageHref: 'map_js/img/href.png'}); 
		        }
				
                });
				

					
	          if(('total objects: ', objects.length > 1) || ('total objects: ', objects.length == 0)) {
				     openone_arr(myMap.getBounds()[0], myMap.getBounds()[1]);	 
				}else{
					if('total objects: ', objects.length == 1) {
				       openone(Object.keys( arrs )[0]);
					}
				}
		
		
		
		
		
		
	 }else{
		openone_arr('');
		
	 }
	

	/*	
	 objectManager.events.add('click', function (e) {
console.log(e.get('objectId'));
 objectManager.objects.setObjectOptions(e.get('objectId'),
        {iconLayout: 'default#image', iconImageHref: 'map_js/img/href.png'});
     objectManager.clusters.setClusterOptions(e.get('objectId'), 
        {iconLayout: 'default#imageWithContent', iconImageHref: 'map_js/img/href.png', iconContentOffset: [7, 6],  iconImageSize: [25,25], iconImageOffset: [-15, -15]
	  }); 
  
		var id = e.get('objectId');
		var tho = e.get('target')._overlaysById[id]._data.properties;
		//console.log(e.get('target')._overlaysById[id]);
		if(tho) { 
		var arri = [];
		$.each(tho.geoObjects,function(index,value){
               arri.push(value.id);
			 });
			 console.log(arri);
			  visitedClusters[arri[0]] = true;
			  //openone_arr(arri.join(','));
		}else{
			openone(id);
			//alert('awdawd');
		}
    });  */ 
  });



}



//-------------------------------------Функции для открытия one ------------------------//

function  openone_arr(arr1, arr2) {

	if($('.notepad').hasClass('active')) {
		var note = true;
	}
        $.ajax({
        url:    '/map/all', //url страницы (action_ajax_form.php)
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        data: {array1:arr1[0], array2:arr1[1], array3:arr2[0], array4:arr2[1], note:note},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно	
		$('.modal-left').html(response);
		$('.modal-left').addClass('modal-add');
		
			 //Функция открытия One объявления
               click_all();
			  $(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
                click_all();
              });
		} 
    });
}









function click_all() {
	
var id = '';
var returns = '';
var img = '';
var marker = '';
 $('.main-board-one')
        .mouseover(function() {
             id = $(this).attr('data-id'); 
			
           objectManager.objects.setObjectOptions(id,
            {iconLayout: 'default#image', iconImageHref: 'map_js/img/hover.png',  iconImageSize: [35,35], iconImageOffset: [-20, -20]});			 
        
		 var arrss = objectManager.clusters.getAll();
		 $.each(arrss,function(index,value){
			  $.each(value.features,function(index,val){
				  if(index == 0) {marker = val.id}
                   if(val.id == id) { 
                   id = value.id;
  			   //console.log(marker);
				   returns = true;
				   
			
			        objectManager.clusters.setClusterOptions(id, 
                        {iconLayout: 'default#imageWithContent', iconImageHref: 'map_js/img/hover.png', iconContentOffset: [12, 12],  iconImageSize: [35,35], iconImageOffset: [-20, -20]
	                });    
					   return false;
				   }
				 
			  });
			    if(returns) {
					  
					   return false;
				   }
				
		 });
		
		
		}).mouseout(function(){   

if(returns) {
	 if(visitedClusters[marker]) {	
        img = 'map_js/img/href.png';
     }else{
        img = 'map_js/img/claster.png';
     }
}else{
	if(visitedClusters[id]) {	
        img = 'map_js/img/href.png';
     }else{
        img = 'map_js/img/place.png';
     }
}


	


         objectManager.objects.setObjectOptions(id,
           {iconLayout: 'default#image', iconImageHref: img,  iconImageSize: [25,25], iconImageOffset: [-15, -10]});
        


		   objectManager.clusters.setClusterOptions(id, 
                        {iconLayout: 'default#imageWithContent', iconImageHref: img, iconContentOffset: [7, 7],  iconImageSize: [25,25], iconImageOffset: [-15, -15]
	                }); 
returns = '';
		});
			
	
 $('.main-board-one').click(function(){
  var end = '';
  var marker = '';
  var id = $(this).attr('data-id');

	$('.padd-all').addClass('hidden');	
	 $.ajax({
        url:    '/map/one', //url страницы (action_ajax_form.php)
        type:     "GET", //метод отправки
        dataType: "html", //формат данных
        data: {id:id, close: true},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно	
        $('.one-all').html(response);
		notepad();
	
//Функция добавления просмотренной метки и кластера
  var arrss = objectManager.clusters.getAll();
		 $.each(arrss,function(index,value){
			  $.each(value.features,function(index,val){
				   if(index == 0) {marker = val.id}
                   if(val.id == id) {  
			        objectManager.clusters.setClusterOptions(value.id, 
                        {iconLayout: 'default#imageWithContent', iconImageHref: 'map_js/img/href.png', iconContentOffset: [9, 8],  iconImageSize: [30,30], iconImageOffset: [-17, -17]
	                });    
					   end = marker;
					   return false;
				   }
					if(end) {
						 return false;
					}
			  });  
		 });	
		 if(end) {
            visitedClusters[end] = true;
		 }
		 
		   visitedClusters[id] = true;
		objectManager.objects.setObjectOptions(id,
        {iconLayout: 'default#image', iconImageHref: 'map_js/img/href.png'});
		//КОнец Функция добавления просмотренной метки и кластера
		
		
		
		
          imagegallery();
		  $('.close').click(function(){
              $('.modal-add').addClass('overauto');
			  $('.padd-all').removeClass('hidden');
			  $('.one-all').html('');
			 /* objectManager.objects.setObjectOptions(id,
             {iconLayout: 'default#image', iconImageHref: 'map_js/img/place.png'});*/
			 
		  });
		} 
    });
		
	});

}








function ajax() {
var url =  "/map/coord";

 $.ajax({
         url: url
    }).done(function(data) {
     objectManager.removeAll();
     objectManager.add(data);

	var result = JSON.parse(data);
	 if(result.features) {
		 if(result.features.length == 1) {
			 myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange:true, zoomMargin:17});
		 }else{
	       myMap.setBounds(myMap.geoObjects.getBounds());
		 }
	 }
 
	 objectManager.events.add('click', function (e) {







		 
/*	 
var preset = ymaps.option.presetStorage.get('twirl#redClusterIcons');

e.get('target').options.set('icons', preset.clusterIcons);
*/


	/*var	 placemark = e.get('coords');
	
     /*myMap.setZoom(myMap.getZoom() + 1);	 
	 myMap.panTo(placemark, {
            delay: 1
        });  */
     //Смена изображения простого маркера при клике на него
	 objectManager.objects.setObjectOptions(e.get('objectId'),
        {iconLayout: 'default#image', iconImageHref: 'map_js/img/href.png'});
     objectManager.clusters.setClusterOptions(e.get('objectId'), 
        {iconLayout: 'default#imageWithContent', iconImageHref: 'map_js/img/href.png', iconContentOffset: [7, 6],  iconImageSize: [25,25], iconImageOffset: [-15, -15]
	  }); 
	

 	
	var objects = getVisibleObjects();
		var id = e.get('objectId');
		var tho = e.get('target')._overlaysById[id]._data.properties;

		if(tho) { 
		var arri = [];
		$.each(tho.geoObjects,function(index,value){
               arri.push(value.id);
			 });
			  openone_arr(myMap.getBounds()[0], myMap.getBounds()[1]);
			  
              //Записываем в хеш id первой метки в маркере
		      visitedClusters[arri[0]] = true;
		}else{
			
			
			
  var end = '';
  var marker = '';


	$('.padd-all').addClass('hidden');	
	 $.ajax({
        url:    '/map/one', //url страницы (action_ajax_form.php)
        type:     "GET", //метод отправки
        dataType: "html", //формат данных
        data: {id:id, close: true},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно	
        $('.one-all').html(response);
		notepad();
	
//Функция добавления просмотренной метки и кластера
  var arrss = objectManager.clusters.getAll();
		 $.each(arrss,function(index,value){
			  $.each(value.features,function(index,val){
				   if(index == 0) {marker = val.id}
                   if(val.id == id) {  
			        objectManager.clusters.setClusterOptions(value.id, 
                        {iconLayout: 'default#imageWithContent', iconImageHref: 'map_js/img/href.png', iconContentOffset: [9, 8],  iconImageSize: [30,30], iconImageOffset: [-17, -17]
	                });    
					   end = marker;
					   return false;
				   }
					if(end) {
						 return false;
					}
			  });  
		 });	
		 if(end) {
            visitedClusters[end] = true;
		 }
		 
		   visitedClusters[id] = true;
		objectManager.objects.setObjectOptions(id,
        {iconLayout: 'default#image', iconImageHref: 'map_js/img/href.png'});
		//КОнец Функция добавления просмотренной метки и кластера
		
		
		
		
          imagegallery();
		  $('.close').click(function(){
              $('.modal-add').addClass('overauto');
			  $('.padd-all').removeClass('hidden');
			  $('.one-all').html('');
			 
		  });
		} 
    });
			
			
			
			  //openone(id);
			  visitedClusters[id] = true;
		}
	   
    });   
  });
}














function ajaxnone() {
if($('.notepad').hasClass('active')) {
	var url =  "/map/coord?note=true";
}else{
	var url =  "/map/coord";
}

 $.ajax({
         url: url
    }).done(function(data) {
     objectManager.removeAll();
     objectManager.add(data);
	 
	 
	 var result = JSON.parse(data);

	 if(result.features) {
		 if(result.features.length == 1) {
			 myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange:true, zoomMargin:17});
		 }else{
	       myMap.setBounds(myMap.geoObjects.getBounds());
		 }
	 }else{
		 openone_arr('');
		 
	 }
  });
}



























function  openone(id) {
	 $('.modal-left').html('');	
	 $.ajax({
        url:    '/map/one', //url страницы (action_ajax_form.php)
        type:     "GET", //метод отправки
        dataType: "html", //формат данных
        data: {id:id},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно	
	
		  $('.modal-left').removeClass('modal-add');
          $('.modal-left').html(response);	
          imagegallery();	
          notepad();
		  
		} 
    });
}


function  imagegallery() {
	$('#imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:true,
        thumbItem:5,
		    speed: 500, //ms'

           // auto: true,
            pauseOnHover: true,
            loop: true,
            slideEndAnimation: true,
            pause: 2000,
        slideMargin:0,
        enableDrag: false,
        currentPagerPosition:'left',
        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#imageGallery .lslide'
            });
        }   
    });	
}




function notepad() {
  $('.notepad-map-plus').click(function(){	
  var id = $(this).attr('data-id');
  $.ajax({
        url:   '/ajax/notepad', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:id},
        success: function(response) { //Данные отправлены успешно
			if($('.notepad').hasClass('active')) {
			     ajaxnone();
		    }
		
		} 
    });

	if($('.notepad-map-plus').hasClass('link')) {
		 $('.notepad-map-plus span').text('Добавить в избранное');
		 $('.notepad-map-plus').removeClass('link');
		 var noteint =  Number.parseInt($('.noteint').text())-1;
		 $('.noteint').text(noteint);
	}else{
		 var noteint =  Number.parseInt($('.noteint').text())+1;
		 $('.noteint').text(noteint);
		 $('.notepad-map-plus span').text('В избранном');
		 $('.notepad-map-plus').addClass('link');
	}
});
   	   
}

}

	</script>

<div class="col-md-9 map-body">
   <div id="map"></div>
</div>

<div class="modal-left col-md-3"></div>