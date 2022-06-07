﻿$(document).ready(function() {
   radiusmapaction();
   	filtrmain();
   delcoord();
   addEventListener('click', function () { return false; });
}); 


 function radiusmapaction() {
	 

$('#loadcoord').click(function(){

if($(this).attr('data-auction')) {
    var url = '/ajax/mapall?auction=true';
}else{
	var url = '/ajax/mapall';
}

if (!$("#YMapsIDadd").length){
		$.ajax({
        url:   url, 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        success: function(response) { //Данные отправлены успешно
		$('#radiusmap').html(response);
		$('#YMapsIDadd').addClass('preloadmap');
	setTimeout(function(){

      coordinates();

    },1000)

		} 
    });
  }	
});
 
 }
  
 
function delcoord() { 
$('.close-radius').click(function(){	
      $('#filtr-coord').val('');
      $('#filtr-radius').val('');
	   if ($("#search_form").length){
	       $(".go-search").click();
	   }else{
		   $(".top-submit-text").click();
	   }
  });

}


function filtrmain() {
$('.tab-box-new a').click(function(){
		$.ajax({
        url:   '/ajax/mainfiltr', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {act:$(this).attr('data-act'), url:window.location.href},
        success: function(response) { //Данные отправлены успешно  
		} 
        });
		return false; // подробнее про return false;
	});
}

