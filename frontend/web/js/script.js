$(document).ready(function() {

$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
	signuploginpop();
});
signuploginpop();
menu_add();
	$('[data-toggle="tooltip"]').tooltip('enable');
	calendar();
/*Переводчик*/	
if ($(window).width() > '500'){
  $("#googlang").attr("src","//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit");	
  }
  $("#yandexone").attr("src","https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU");	
  $('.lang_mobile').addClass('lang_elite');

setTimeout(function(){ 

   $('.goog-te-menu-frame').removeAttr('style');
   $('.goog-te-menu-frame').css('height','0px');
   $('.goog-te-menu-frame').css('position','relative');
},1500);
	
function in_lang(){
$('.lng').addClass('unhide');
}
		
timer_search();
	
search();
phone_click();
$('.notepad-act-plus').click(function(){	
  notepad($(this).attr('data-id'));
  if ($(this).children().attr('class') == 'fa fa-heart-crack') {$(this).children().attr('class', 'fa fa-heart');}else{$(this).children().attr('class', 'fa fa-heart-crack');}

});

$('.region_act').click(function(){	
   region_ajax_act($(this).attr("data-region"));
});

  //  region_ajax_act($('.btn-default').attr("data-region"));
	 region_click_back();

	$('.search_form').submit(function(e){
    $(this).find('.form-control').filter(function(){
        return !$.trim(this.value).length;  // get all empty fields
    }).prop('disabled',true);    
});


//$('[data-toggle="tooltip"]').tooltip('enable');


$(".category-go").change(function(){
	 window.location.href = $(this).val();
});




});
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'ru', includedLanguages: 'de,en,ru,zh-CN', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
function menu_add() {
    

	$(".droptuggol").hover(function(){             
		var dropdownMenu = $(this).children(".dropdown-menu");
		dropdownMenu.parent().addClass("open");
   },function(){           
//	var dropdownMenu = $(this).children(".dropdown-menu");
//	dropdownMenu.parent().removeClass("open");         
   });

}
function phone_click() {
		$('.one-phone').click(function(){
		$(this).text($(this).attr('data-phone'));
    });
}
function region_click() {
		$('.region_span').click(function(){
		var id = $(this).attr("data-id");	
		region_ajax_act(id);
    });
}

function region_ajax_act(id) {

	   $.ajax({
        url:   '/ajax/reg-parent', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:id},
        success: function(response) { //Данные отправлены успешно
				
		  if (!response) {
    	    alert(response);
		  }else{
		$('.region_ajax').html(response);
		 region_text();
         region_click();
		 region_click_back();
		 
		  }
		} 
    });
}


function region_text() {
	$("#region-text").bind("change paste keyup", function() {
		     	$.ajax({
        url:   '/ajax/regsearch', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {text:$(this).val()},
        success: function(response) { //Данные отправлены успешно
		     $(".search-ajax-text").remove();
		      $(".reg-context").append('<div class="search-ajax-text">'+response+'</div>');
		} 
    });
       
    });
}	


function region_click_back() {
	$('.region_model_back').click(function(){	
     	region_ajax_act($(this).attr("data-back"));
	});
}	


function notepad(id) {

   	   $.ajax({
        url:   NOTEPAD_URL, 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:id},
        success: function(response) { //Данные отправлены успешно
		} 
    });
}

function search(id) {
	$('.sort-menu [type="checkbox"]').click(function(){
			var arrs = [];
    var idchex = $(this).parent().parent().attr('id');
		 $('#'+idchex+' input:checkbox:checked').each(function(){
           arrs.push($(this).parent().text());
         });
		 var arr_implode = arrs.join(',').replace(/\r?\n/g, '');
		 if (arr_implode) {
		$(this).parent().parent().parent().prev().text(arr_implode);
		 }else{
			$(this).parent().parent().parent().prev().text('Выбрать'); 
		 }
    });
	

}


$(document).on('click', '.field-field-cat .dropdown-menu', function(e) {
  e.stopPropagation()
})

function regminimodal() {

	ymaps.ready(function(){
        var geolocation = ymaps.geolocation;	
if (geolocation.city) {		
		$.ajax({
        url:   '/ajax/regopen', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {city:geolocation.city},
        success: function(response) { //Данные отправлены успешно
		 // alert(response);
		 $('.cont-mod-user').html(response);
		} 
    });
	$("#myModalBox").modal('show');
}		
    });
}



function online($url) {
	onlineSave($url);
	var timerId = setInterval(function() {
        onlineSave($url);
    }, 60000);	
}
function onlineSave($url) {
		$.ajax({
        url: $url, 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        success: function(response) { //Данные отправлены успешно
		
	arrs = JSON.parse(response);


if(arrs.push) {
  pushauction(arrs.push.text, arrs.push.url);
}

//Для чата
	var chat = arrs.chat;
	if(chat.count > 0) {
	   $('.mess-online').addClass('vid-mess');	
	if($.cookie('chat_push') != chat.count) {
          sendNotification('Уведомление 1tu.ru', 'https://1tu.ru/user/message', {
               body: 'Вам пришло новое сообщение',
               icon: 'https://1tu.ru/img/logo.png',
               dir: 'auto'
          });
		  $.cookie('chat_push', chat.count, { expires: 7, path: '/' });
	}		
		}else{
			$('.mess-online').removeClass('vid-mess');
		}
		} 
    });
}

function chat() {
$('.click_chat').click(function(){
		$(this).parents('.chat').toggleClass("active").find('.chat-body').slideToggle();
		if($('.chat-body iframe').attr('src')) {
			$('.chat').attr('id','');
			$('.move_chat').css('display','none');
			$('.chat').removeAttr('style');
			$('.click_chat').css('width','100%');
			$('#draggable').draggable("disable");
			$('.chat-body iframe').attr('src','');
		}else{
			$('.mess-online').removeClass('vid-mess');
			$('.click_chat').css('width','auto');
		    $('.chat-body iframe').attr('src',$(this).attr('data-href'));
			$('.chat').attr('id','draggable');
			$('.move_chat').css('display','block');
			$('#draggable').draggable();
		}
	})
}

function timer_search() {

$('.iframe').on('load', function(){

	   $(this).height($(this).contents().find('html').height());
	   var test = $(this).contents().find('.timeradapt').text().replace(/[^0-9]/g, '');
	   test = test.replace(/[8]/g, '');
	 console.log(test);  
if(test > 0) {

	//$(this).parent().parent().parent().removeClass('hiddenleft');
	//$(this).parent().parent().removeClass('hiddenleft');
	$(this).height($(this).contents().find('html').height());
	
}else{
	//$(this).before("<div class='alert alert-danger'>Выключен</div>");
	//$(this).parent().parent().parent().remove();
}
});

}





//выводим код календаря
function calendar() {
if ($(".datepicker").length){
$.datepicker.regional['ru'] = {
	closeText: 'Закрыть',
	prevText: 'Предыдущий',
	nextText: 'Следующий',
	currentText: 'Сегодня',
	monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
	monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
	dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
	dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
	dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
	weekHeader: 'Не',
	dateFormat: 'dd.mm.yy',
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['ru']);
$(".datepicker").datepicker({
	
	dateFormat: 'yy-mm-dd',
    onSelect : function(dateText, inst){
		var dt = new Date();
		var sec = (dt.getSeconds() < 10 ? '0' : '') + dt.getSeconds();
        var min = (dt.getMinutes() < 10 ? '0' : '') + dt.getMinutes();
		var time = dt.getHours() + ":" + min + ":" + sec;
		$(this).val(dateText+' '+time);
    }
});
}
}


















function signuploginpop() {
$('.signuppop').click(function(){
		
/*$('.signuppop').click(function(){
$('.modal-title').text('Регистрация');

			$('#bodylogin').html('<iframe src="/signuppop" style="width: 100%; height: 100%; border: 0;"></iframe>');
             $('#bodylogin').css('height','500px');
	});*/
		
		
$('.modal-title').text('Регистрация');
	   $.ajax({
        url: '/ajax/signuppop?redirect='+window.location.href,
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        success: function(response) { //Данные отправлены успешно
		  if(response) {
			$('#bodylogin').html(response);

		   }
		} 
    });
	});
	$('.loginpop').click(function(){
       $('.modal-title').text('Авторизация');
	   $.ajax({
        url: '/ajax/loginpop?redirect='+window.location.href,
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        success: function(response) { //Данные отправлены успешно
		  if(response) {
			$('#bodylogin').html(response);
			signuploginpop();
		   }
		} 
    });
	});

}

function pushauction(text, url) {

          sendNotification('Уведомление 1tu.ru', url, {
               body: text,
               icon: 'https://1tu.ru/img/logo.png',
               dir: 'auto'
          });
}	