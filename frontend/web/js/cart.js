$(document).ready(function() {
   add_cart();
   edit_cart();
   del_cart();
   $('.input-car input').keyup(validateMaxLength);
   $(document).on('pjax:success' , function(selector, xhr, status, selector, container) {
	     // add_cart();
          edit_cart();
		  del_cart();
	     $('.input-car input').keyup(validateMaxLength);	  
		 if($('.itog').html()) {
		    $('.car-top').html($('.itog').html());
		 }


   });
   
});


function validateMaxLength()
{
        var text = $(this).val();
        var maxlength = $(this).data('maxlength');

        if(maxlength > 0)  
        {
                $(this).val(text.substr(0, maxlength)); 
        }
}

//      Функции
//----------------------------------------------------------------//

//выводим код календаря
function add_cart() {
	

//alert($('.car-add').attr('data-price'));
  $('.button-korz input').keydown(function(e) {
    if(e.keyCode === 13) {
if($('.button-korz input').val() > 0) {
if($('.button-korz input').val() <= Number($('.sklad').attr('data-counter'))) {
$('.button-korz input').attr('data-count',$('.button-korz input').val());
var acts = $('.car-add').attr('data-act');
 var counters =  Number($('.count-sklad').text());	


 $.ajax({
        url: '/caradd',
        type:     "get", 
		data: {id:$('.car-add').attr('data-id'), act:acts, price:$('.car-add').attr('data-price'), note:$('.note_add').val(), count:$('.button-korz input').attr('data-count')},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно
	    	   // $('.car_count').text(Number($('.car_count').text())+1);
				$('.count-sklad').text(Number($('.sklad').attr('data-counter'))-$('.button-korz input').val());
				$('.cart-alert').removeClass('hidden');
				$('#fa-car').removeClass();
				$('#fa-car').addClass('fa fa-refresh');
				if(Number($('.button-korz input').attr('data-count')) > 0) {
					 $('.count-product').removeAttr('style');
					 $('.car-minus').removeAttr('style');
				}
		
		$.pjax.reload({container: '#pjaxCar'});
		}
 	});

			
	}else{
      alert('Нет такого кол-ва на склае!');
	  
	            $('.cart-alert').addClass('hidden');
				$('.alert-none').css('display','block');
	}
    }
	}
  });

	
	
	
	
	
	
	
	
	
	
	
	
 $('.car-add').click(function(){
if($('.button-korz input').val() > 0) {
if($('.button-korz input').val() <= Number($('.sklad').attr('data-counter'))) {
$('.button-korz input').attr('data-count',$('.button-korz input').val());
var acts = $(this).attr('data-act');
 var counters =  Number($('.count-sklad').text());	


 $.ajax({
        url: '/caradd',
        type:     "get", 
		data: {id:$(this).attr('data-id'), act:acts, price:$(this).attr('data-price'), note:$('.note_add').val(), count:$('.button-korz input').attr('data-count')},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно
	    	   // $('.car_count').text(Number($('.car_count').text())+1);
				$('.count-sklad').text(Number($('.sklad').attr('data-counter'))-$('.button-korz input').val());
				$('.cart-alert').removeClass('hidden');
				$('#fa-car').removeClass();
				$('#fa-car').addClass('fa fa-refresh');
				if(Number($('.button-korz input').attr('data-count')) > 0) {
					 $('.count-product').removeAttr('style');
					 $('.car-minus').removeAttr('style');
				}
		
		$.pjax.reload({container: '#pjaxCar'});
		}
 	});

			
	}else{
      alert('Нет такого кол-ва на склае!');
	  
	            $('.cart-alert').addClass('hidden');
				$('.alert-none').css('display','block');
	}
}	
 });
 
 
 
$('.car-minus').click(function(){
var acts = $(this).attr('data-act');
 $.ajax({
        url: '/caradd',
        type:     "get", 
		data: {id:$(this).attr('data-id'), act:acts, price:$(this).attr('data-price'), count:$('.button-korz input').attr('data-count')},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно
		
	    var counters =  Number($('.count-sklad').text());

			if(Number($('.button-korz input').attr('data-count')) > 0) {

			 // $('.car_count').text(Number($('.car_count').text())-$('.button-korz input').attr('data-count'));
			  $('.count-sklad').text(Number($('.count-sklad').text())+Number($('.button-korz input').attr('data-count')));
			}
			//if(Number($('.button-korz input').attr('data-count')) == 0) {
				$('.cart-alert').addClass('hidden');
				     $('.count-product').css('display', 'none');
					 $('.car-minus').css('display', 'none');
					 $('.note_add').val('');
				$('.button-korz input').val('');
				$('#fa-car').removeClass();
				$('#fa-car').addClass('fa fa-shop');
				//}
		
	
		
		$.pjax.reload({container: '#pjaxCar'});
		}
 	});
 });
}



function edit_cart() {
  var err = '';
  

    $(".input-car input").change(function() {
       err = true;
    });

  
  $('.input-car input').keydown(function(e) {
    if(e.keyCode === 13) {
	var	id = $(this).parent().next().find(':first-child').attr('data-id');
    var count = $(this).val();
	$.ajax({
        url: '/caredite',
        type:     "get", 
		data: {id:id, count:count},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно

			$.pjax.reload({container: '#pjaxCart'});
			
			
		}
 	});
	
	err = '';
	}		
});	
 $('.refresh-product').click(function(){

    var count = $(this).parent().prev().children().val();
	 $.ajax({
        url: '/caredite',
        type:     "get", 
		data: {id:$(this).attr('data-id'), count:count},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно

			$.pjax.reload({container: '#pjaxCart'});
			
			
		}
 	});
	err = '';
});
$(document).on('click', function(e) {
  if (!$(e.target).closest(".input-car input").length) {
     if(err) {
	   alert('вы изменили количество товара, не забудьте обновить количество');
	   err = '';
     }
  }
  e.stopPropagation();
});
}




function del_cart() {
 $('.del-product').click(function(){

    var count = $(this).parent().prev().children().val();
	 $.ajax({
        url: '/cardel',
        type:     "get", 
		data: {id:$(this).attr('data-id')},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно

			$.pjax.reload({container: '#pjaxCart'});
			
			
		}
 	});
});
}

//--------------------------------------------------------//