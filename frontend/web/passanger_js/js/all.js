 $(document).ready(function() {
	 $('[data-toggle="tooltip"]').tooltip('enable');
     searchOt();
	 searchKuda();
	 $("#formall").change(function(){
		 form_change();
     });
	 
	 $(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
	  $('[data-toggle="tooltip"]').tooltip('enable');
	                   searchOt();
					   searchKuda();
			 	 $("#formall").change(function(){
		               form_change();
					
                  });
	
    
	 });
 });
function form_change() {
		var formData = $("#formall :input") .filter(function(index, element) { return $(element).val() != ""; }) .serialize();
		var url = $('#url').attr('data-url');
        $.ajax({
        url:    url, //url страницы (action_ajax_form.php)
        type:     "GET", //метод отправки
        dataType: "html", //формат данных
        data: formData,  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно	
          $('#btn span').text('('+response+')');		
		} 
    });
}










function searchKuda() {
	$('#kuda').attr('autocomplete', 'off');

	
$("#kuda").bind("keyup", function() {

	var text = $('#kuda').val();
	if (/[a-zа-яё]/i.test(text) && text.length > 1){
     	$.ajax({
        url:   '/passanger/searchkuda', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {text:text},
        success: function(response) { //Данные отправлены успешно
		     $(".search-ajax_kuda").remove();
		     $("#kuda").after('<div class="search-ajax_kuda">'+response+'</div>');
			 value_kuda();
			 
		} 
    });
	}else{
		$(".search-ajax_kuda").remove();
	}
	});

}




function searchOt() {
	$('#ot').attr('autocomplete', 'off');
$(document).on('click', function(e) {
  if (!$(e.target).closest(".top-search-search").length) {
	  $(".search-ajax_ot").remove();

   }
  e.stopPropagation();
  });
	

			
		
$("#ot").bind("keyup", function() {

	var text = $('#ot').val();
	if (/[a-zа-яё]/i.test(text) && text.length > 1){
     	$.ajax({
        url:   '/passanger/searchot', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {text:text},
        success: function(response) { //Данные отправлены успешно
		     $(".search-ajax_ot").remove();
		     $("#ot").after('<div class="search-ajax_ot">'+response+'</div>');
			 value_ot();
			 
		} 
    });
	}else{
		$(".search-ajax_ot").remove();
	}
	});

}


function value_ot() {
  $('.search-ajax_ot li').click(function(){
       $('#ot').val($(this).text());
	   form_change();
	   $(".search-ajax_ot").remove();
 });
}

function value_kuda() {
  $('.search-ajax_kuda li').click(function(){
       $('#kuda').val($(this).text());
	    form_change();
	   $(".search-ajax_kuda").remove();
 });
}
