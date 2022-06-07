$(document).ready(function() {
notepad();
sorttd();
	auction_history();
priceajax();	
uploads();
payment();
activemenu();
checkedAll();
clickpjax();
day_add();
count_product();
  $('.mess_disput img').removeAttr('height');
  $('.mess_disput img').removeAttr('style');
$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
	priceajax();
$('.notepad-act-plus').click(function(){	
  notepad($(this).attr('data-id'));
  if ($(this).children().attr('class') == 'fa fa-heart-crack') {$(this).children().attr('class', 'fa fa-heart');}else{$(this).children().attr('class', 'fa fa-heart-crack');}

});
	uploads();
	sorttd();
	auction_history();
	day_add();
    count_product();
  $('.mess_disput img').removeAttr('height');
  $('.mess_disput img').removeAttr('style');
    setTimeout(function() { $(".alert-act").hide('show'); }, 2000);
	checkedAll();
	clickpjax();
});
});



//----------------------------Функции-----------------------------------//
function payment() {
	$('#sum').keyup(function(){
		var suma = $(this).val();
		$('.sum-open').each(function(i,elem) {

		  var sum = $(this).attr('data-rout')+'?sum='+suma+'&url='+$('.body-logo-pay').attr('data-url');
		  sum = sum.replace('??','?');
		  $(this).attr('href', sum);
		});
	});
	
	if($('#sum').val() > 0){
		var suma = $('#sum').val();
		$('.sum-open').each(function(i,elem) {
		  var sum = $(this).attr('data-rout')+'?sum='+suma+'&url='+$('.body-logo-pay').attr('data-url');
		  $(this).attr('href', sum);
		});
	}
	/*$('.sum-open').click(function(){	
	var rout = $(this).attr('data-rout')+'-form';
	var sum = $('sum').val();
     	$.ajax({
        url: rout, 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {sum:sum},
        success: function(response) {  
		}
    });
	});*/
}	
function reload_page(){
	if (confirm('Вы уверены, что хотите все удалить безвозвратно?')) {
		$('.add-preloader').val('true');
	
	}
}
function activemenu() {
    var location = window.location.href;
    var cur_url = '/user/' + location.split('/').pop();

    $('.menu_user li').each(function () {
        var link = $(this).find('a').attr('href');
        if (cur_url == link) {
            $(this).addClass('active');
        }
    });
}


function checkedAll() {
	
	 $('.act span').click(function(){
        if (!$(this).hasClass('all')) {
        $('input').attr('checked','checked');
		$(this).addClass('all');
        }else{
        $('input').removeAttr('checked');
		$(this).removeClass('all');
        }
    });
  }
  
  //--------------------------------------------------------//
function imgdel($file){	
       $('.redactor-modal-delete-btn').click(function(){
	
	
		$.ajax({
        url:   '/article/img-del', //url страницы (action_ajax_form.php)
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        data: {file:$('#redactor-image-link').attr('href'), act:'article'},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
	      alert(response);
		}
	   });
       });

}
  //Открытие ссылок по верх окна pjax
  function clickpjax() {
  $('.blank').click(function(){
	  
	 // window.open($(this).attr('data-href'), '_blank'); 
    });
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
      function day_add(){
           $('.day').keyup(function(){
             if(Number.parseInt($(this).val()) > $(this).attr('data-max')) {
		       $(this).val($(this).attr('data-max'));
	         }
	       $(this).next().find('.itog').text(Number.parseInt($(this).attr('data-price')) * Number.parseInt($(this).val()));
	        var gets = $('.services-bottom').attr('data-text');
	       $(this).parent().next().attr('href',$(this).parent().next().attr('data-hrefs')+'&day='+$(this).val() )
         });
		 
		 
		$('.day').click(function(){
             if(Number.parseInt($(this).val()) > $(this).attr('data-max')) {
		       $(this).val($(this).attr('data-max'));
	         }
	       $(this).next().find('.itog').text(Number.parseInt($(this).attr('data-price')) * Number.parseInt($(this).val()));
	        var gets = $('.services-bottom').attr('data-text');
	       $(this).parent().next().attr('href',$(this).parent().next().attr('data-hrefs')+'&day='+$(this).val() )
         });
		  
	  }
	  
	  
	  
	  function count_product(){ 
	  $('.count-input').change(function() {
		  var id = $(this).attr('data-id');
		  $.ajax({
          url:   '/ajax/count', //url страницы (action_ajax_form.php)
          type:     "get", //метод отправки
          dataType: "html", //формат данных
          data: {id:$(this).attr('data-id'), count: $(this).val()},  // Сеарилизуем объект
          success: function(response) { //Данные отправлены успешно
	      $('.con-'+id).text('('+response+')');
	      count_product();

		}
	     });
               
      });
	
	     /* $.ajax({
          url:   '/article/img-del', //url страницы (action_ajax_form.php)
          type:     "get", //метод отправки
          dataType: "html", //формат данных
          data: {file:$('#redactor-image-link').attr('href'), act:'article'},  // Сеарилизуем объект
          success: function(response) { //Данные отправлены успешно
	      
		}
	     });*/
	  }
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	   function uploads(){  
	  
	  $('.delet-slider').click(function () {
		$.ajax({
          url:   '/user/sliderdel', //url страницы (action_ajax_form.php)
          type:     "get", //метод отправки
          dataType: "html", //формат данных
          data: {id:$(this).attr('data-id')},  // Сеарилизуем объект
          success: function(response) { //Данные отправлены успешно
          $.pjax.reload({container: "#sliders"});
		}
	     });
	  });  
		  
		  
	 /*  $('.gouploads').click(function () {
		   $('#img-preview').attr('src', '/uploads/images/preloader.gif');
		   $('#img-preview').css('width', '70px');
	    });*/
$("#w1").on("afterValidate", function (event, messages) {  
var err = '';
if(messages['uploadform-url'][0]) {
   err = 'url';
}	
if(messages['uploadform-imagefile'][0] || messages['uploadform-url'][0]) {}else{
	     $('#img-preview').attr('src', '/uploads/images/preloader.gif');
		 $('#img-preview').css('width', '70px'); 
}
});

	     $('#uploadform-imagefile').change(function () {
        var input = $(this)[0];
        if (input.files && input.files[0]) {
            if (input.files[0].type.match('image.*')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                console.log('ошибка');
            }
        } else {
            console.log('хьюстон у нас проблема');
        }
    });
 
    $('#reset-img-preview').click(function() {
        $('#img').val('');
        $('#img-preview').attr('src', 'default-preview.jpg');
    });
 
    $('#form').bind('reset', function () {
        $('#img-preview').attr('src', 'default-preview.jpg');
    });
	   }
	   
	   
	   
  function sorttd(){ 
  
  
  sliderurl();
  
   $( "#sortable" ).sortable();
   $( "#sortable" ).disableSelection();
   $( "#sortable" ).sortable({
	  handle: '.shows',
      stop: function( event, ui ) { 


// Массив для сохранения значений
var personsIdsArray = [];
 
// Проход по всем элементам с классом daily-person-id
// с помощью jQuery each.
$("#sortable tr").each(function (index, el){
    // Для каждого элемента сохраняем значение в personsIdsArray,
    // если значение есть.
    var v  = $(el).attr('data-id');
    if (v) personsIdsArray.push(v);
    
});
	


	 
	 
	$.ajax({
          url:   '/ajax/slider-sort', //url страницы (action_ajax_form.php)
          type:     "get", //метод отправки
          dataType: "html", //формат данных
          data: {array:JSON.stringify(personsIdsArray)},  // Сеарилизуем объект
          success: function(response) { //Данные отправлены успешно
      
		}
	     }); 
	 
	 
	 
	  }
   });

  }
  
  
  
  
   function sliderurl (){ 
   
    $(".urlupdate").blur(function(){
		
     	$.ajax({
          url:   '/ajax/slider-url', //url страницы (action_ajax_form.php)
          type:     "get", //метод отправки
          dataType: "html", //формат данных
          data: {id:$(this).attr('data-id'), url:$(this).val()},  // Сеарилизуем объект
          success: function(response) { //Данные отправлены успешно
            if(response) {
				alert('В ссылке отсуствет https://');
			}
		 }
	     });
	});
   }
  
  
  
  
  
  
  
  
  
  
  
  
  
  function auction_history() {

	$('.btn-history-auction').click(function(){

	$('.modal-title').text($(this).parent().parent().parent().prev().text());
		$.ajax({
        url:   '/ajax/auctionhistory', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:$(this).attr('data-id')},
        success: function(response) { //Данные отправлены успешно
		    $('.modal-body2').html(response);

		} 
    });
		});
}





  function priceajax() {
	$('.ajax-price').change(function() {
		 $('.fa-check').remove();
         $('.fa-times').remove();
		  var id = $(this).attr('data-id');
		  if($(this).val() > 0) {
		  $.ajax({
          url:   '/ajax/priceajax', //url страницы (action_ajax_form.php)
          type:     "get", //метод отправки
          dataType: "html", //формат данных
          data: {id:$(this).attr('data-id'), price: $(this).val()},  // Сеарилизуем объект
          success: function(response) { //Данные отправлены успешно
          $('input.ajax-price[data-id="'+id+'"]').after('<i style="position: absolute; margin-left: -20px;" class="fa fa-check" aria-hidden="true"></i>');
         //$('.con-'+id).text('('+response+')');
    
		}
	     });
		  }else{
			  $('input.ajax-price[data-id="'+id+'"]').after('<i style="position: absolute; margin-left: -20px; color: red;line-height: 30px;" class="fa fa-times" aria-hidden="true"></i>');
		  }
		  
	
               
      });
	  }