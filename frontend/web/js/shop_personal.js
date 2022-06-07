$(document).ready(function() {
	
dickount();
loginpop();
	

$('.region_act').click(function(){	
   region_ajax_act($(this).attr("data-region"));
});

  //  region_ajax_act($('.btn-default').attr("data-region"));
 region_click_back();	
	
	
	
	
	
	
	
	
	
	
   dostavka();

   $(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
	
			$('#carbuyer1-name').val($.cookie('carbuyer-name'));
			$('#carbuyer-name').val($.cookie('carbuyer-name'));
			
			$('#carbuyer1-family').val($.cookie('carbuyer-family'));
			$('#carbuyer-family').val($.cookie('carbuyer-family'));
			
			$('#carbuyer1-email').val($.cookie('carbuyer-email'));
			$('#carbuyer-email').val($.cookie('carbuyer-email'));
			
			$('#carbuyer-phone').val($.cookie('carbuyer-phone'));
			$('#carbuyer1-phone').val($.cookie('carbuyer-phone'));
			
			
			$('#carbuyer-country').val($.cookie('carbuyer-country'));
			$('#carbuyer1-country').val($.cookie('carbuyer-country'));
			
		    $('#carbuyer-region').val($.cookie('carbuyer-region'));
			$('#carbuyer1-region').val($.cookie('carbuyer-region'));
			
		    $('#carbuyer-city').val($.cookie('carbuyer-city'));
			$('#carbuyer1-city').val($.cookie('carbuyer-city'));

            $('#carbuyer-address').val($.cookie('carbuyer-address'));
			$('#carbuyer1-address').val($.cookie('carbuyer-address'));				

            $('#carbuyer-postcode').val($.cookie('carbuyer-postcode'));
			$('#carbuyer1-postcode').val($.cookie('carbuyer-postcode'));	


			
	   dostavka();
	
	   });
}); 

function dostavka() {

$('.add_chexs input').click(function(){
	


	
	if($(this).val() == '1') { 
		
$.cookie('carbuyer-name', $('#carbuyer-name').val());
$.cookie('carbuyer-family', $('#carbuyer-family').val());
$.cookie('carbuyer-email', $('#carbuyer-email').val());
$.cookie('carbuyer-phone', $('#carbuyer-phone').val());
$.cookie('carbuyer-country', $('#carbuyer-country').val());	
$.cookie('carbuyer-region', $('#carbuyer-region').val());	
$.cookie('carbuyer-city', $('#carbuyer-city').val());	
$.cookie('carbuyer-address', $('#carbuyer-address').val());
$.cookie('carbuyer-postcode', $('#carbuyer-postcode').val());
       $.pjax({
        type       : 'GET',
        container  : '#pjaxAddress',
		url       : '/cart?address=false',
        push       : false,
        replace    : false,
        timeout    : 10000,
        "scrollTo" : false
    });
	}
	if($(this).val() == '0') { 
		

       $.pjax({
        type       : 'GET',
        container  : '#pjaxAddress',
		url       : '/cart',
        push       : false,
        replace    : false,
        timeout    : 10000,
        "scrollTo" : false
    });
	}
 });
}

  
function onlinego($url) {

	onlinepoddomen($url);
	var timerId = setInterval(function() {
        onlinepoddomen($url);
    }, 60000);	
}
function onlinepoddomen($url) {
	
		$.ajax({
        url: $url, 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        success: function(response) { //Данные отправлены успешно
		
	arrs = JSON.parse(response);



//Для чата
	var chat = arrs.chat;
	if(chat.count > 0) {
	   $('.mess-online').addClass('vid-mess');	
	if($.cookie('chat_push') != chat.count) {
          sendNotification('Уведомление '+window.location.href, window.location.href, {
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


	
$('.iframes').on('load', function(){
	
	   $(this).height($(this).contents().find('html').height());
	   var test = $(this).contents().find('.timeradapt').text().replace(/[^0-9]/g, '');
	   console.log( $(this).contents().text());
	   test = test.replace(/[8]/g, '');
	 
if(test > 0) {
	//$(this).parent().parent().removeClass('hidden');
	$(this).height($(this).contents().find('html').height());
}else{
	//$(this).before("<div class='alert alert-danger'>Выключен</div>");
	//$(this).parent().parent().remove();
}
});
}



















function region_click() {
		$('.region_span').click(function(){
		var id = $(this).attr("data-id");	
		region_ajax_act(id);
    });
}



function region_ajax_act(id = false) {

	   $.ajax({
        url:   '/reg-parent', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:id, user_id:$('.region_act').attr('data-user')},
        success: function(response) { //Данные отправлены успешно
		  if (!response) {
    	    alert(response);
		  }else{
		
		$('.region_ajax').html(response);
		cooces_click();
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
        url:   '/regsearch', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {text:$(this).val()},
        success: function(response) { //Данные отправлены успешно
		     $(".search-ajax-text").remove();
		     // $(".reg-context").append('<div class="search-ajax-text">'+response+'</div>');
		} 
    });
       
    });
}










function region_click_back() {
	$('.region_model_back').click(function(){	
     	region_ajax_act($(this).attr("data-back"));
		
	});
}	




function cooces_click() {
	
	$('.regionall').click(function(){	
	        $.cookie('region', '');
			location.reload();
	});
		$('.data-click').click(function(){	
			$.cookie('region', $(this).attr('data-id'));
			location.reload();
		});
	
}






function dickount(){
	var functions = false;	
	 $('.checkbox-other input').on("click",function(){  

	   var price = $('.count').attr('data-dickount');
	  var dickount = $('.count').attr('data-price');
	 
	 
    if($(this).prop("checked")) {  
	

	  
      $('.count').each(function () {
		   $(this).stop();
	       var chis = $(this).text();
		  
           $(this).prop('Counter', chis.replace(' ','')).animate({
           Counter: $(this).attr('data-dickount')
             }, {
                duration: 1500,
                easing: 'swing',
                step: function (now) {
                $(this).text(Math.ceil(now));
            }
          });
        });
		$('.car-add').attr('data-price',price*$('.car-add').attr('data-current'));
		   
        } else {
          
		     
               $('.count').each(function () {
				   $(this).stop();
	               var chis3 = $(this).attr('data-price');
	               var chis2 =  $(this).attr('data-dickount');

                   $(this).prop('Counter', chis2.replace(' ','')).animate({
                          Counter: chis3.replace(' ','')
                   }, {
                      duration: 1500,
                      easing: 'swing',
                      step: function (now) {
                              $(this).text(Math.ceil(now));
                         }
                      });
                  });
				
				  $('.car-add').attr('data-price', dickount.replace(' ','')*$('.car-add').attr('data-current'));
        }

		
		
    });

}














function loginpop() {
	
	if ($('.cart-user-act').length > 0) {

	$('#myModallogin').modal('show');
		   $.ajax({
        url: '/loginpop?redirect='+window.location.pathname, 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        success: function(response) { //Данные отправлены успешно
		  if(response) {
			$('#bodylogin').html(response);
		   }
		} 
    });
	
	}
	
	
	
	$('.signuppop').click(function(){
		
/*$('.signuppop').click(function(){
$('.modal-title').text('Регистрация');

			$('#bodylogin').html('<iframe src="/signuppop" style="width: 100%; height: 100%; border: 0;"></iframe>');
             $('#bodylogin').css('height','500px');
	});*/
		
		
$('.modal-title').text('Регистрация');
	   $.ajax({
        url: '/signuppop?redirect='+window.location.href,
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
        url: '/loginpop?redirect='+window.location.href,
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        success: function(response) { //Данные отправлены успешно
		  if(response) {
			$('#bodylogin').html(response);
		   }
		} 
    });
	});
}



