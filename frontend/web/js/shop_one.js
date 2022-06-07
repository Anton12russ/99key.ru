$(document).ready(function() {
   href_open();
  $('.vote-go').click(function(){
   dAjaxForm();
   return false; 
  });
  comment();
  images();
  article();
  $(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
    $('.notepad-act-plus').click(function(){	
      notepad($(this).attr('data-id'));
	   href_open();
      if ($(this).children().attr('class') == 'fa fa-heart-crack') {$(this).children().attr('class', 'fa fa-heart');}else{$(this).children().attr('class', 'fa fa-heart-crack');}
    });
  });
}); 
  
  




 function comment() {
		$('.comments').click(function(){
		  if(!$('#comment').html()) {
	         $('#comment').load($(this).attr('data-href'));
		  }
    });
 }
  function images() {
		$('.images').click(function(){
			if(!$('#images').html()) {
	           $('#images').load($(this).attr('data-href'));
			}
    });
 }
 
  function article() {
		$('.article').click(function(){
			if(!$('#article').html()) {
	           $('#article').load($(this).attr('data-href'));
			}
    });
	
	$('.passanger').click(function(){
	if(!$('#passanger').html()) {
	   $('#passanger').load($(this).attr('data-href'));
	}
    });
 }
 
function dAjaxForm() {
    $.ajax({
        url:   $('.vote-go').attr('data-href'), //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#vote").serialize(),  // Сеарилизуем объект
         success: function(response) { //Данные отправлены успешно
		    $('.td_none').remove();
        	alert(response);
    	},
    	error: function(response) { // Данные не отправлены
            $('#result_form').html('Ошибка. Данные не отправлены.');
    	}
 	});
}

function href_open() {

}	
