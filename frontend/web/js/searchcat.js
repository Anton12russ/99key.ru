$(document).ready(function() {
	poiskcat();
 });
 
 
 
 
 //      Функции
 //----------------------------------------------------------------//
 
 
 function poiskcat() {
	 $('#blogexpress-title').attr('autocomplete', 'off');
 /*$(document).on('click', function(e) {
   if (!$(e.target).closest(".top-search-search").length) {
	   $(".searchcat-ajax").remove();
 
	}
   e.stopPropagation();
   });*/
	 
 
			 
		 
 $("#blogexpress-title").bind("keyup", function() {
	 var text = $('#blogexpress-title').val();
	 if (/[a-zа-яё]/i.test(text) && text.length > 1){
		 $.ajax({
		 url:   '/searchcat', 
		 type:     "get", //метод отправки
		 dataType: "html", //формат данных
		 data: {text:text},
		 success: function(response) { //Данные отправлены успешно
			  $(".searchcat-ajax").remove();
			  $(".catok").remove();
			  
			  if(response) {
			    $(".field-blogexpress-title").append('<div class="searchcat-ajax">'+response+'</div>');
		        clickcat();
			  }
			} 
	 });
	 }else{
		 $(".searchcat-ajax").remove();
		 $(".catok").remove();
		 
	 }
	 });
	
 }

 function clickcat() {
    $('.ul-click-cat').click(function() {
		$(".searchcat-ajax").remove();
		$('.field-blogexpress-title').append('<div class="catok">Выбрана категория: <strong>'+$(this).text()+'</strong></div>');
       $(this).attr('data-id');
	});
 }