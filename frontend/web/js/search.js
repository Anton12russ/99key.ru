$(document).ready(function() {
   poisk();
});




//      Функции
//----------------------------------------------------------------//


function poisk() {
	$('.top-input-text').attr('autocomplete', 'off');
$(document).on('click', function(e) {
  if (!$(e.target).closest(".top-search-search").length) {
	  $(".search-ajax").remove();

   }
  e.stopPropagation();
  });
	

			
		
$(".top-input-text").bind("keyup", function() {
	var region = $('.region_act').attr('data-region');
	var text = $('.top-input-text').val();
	if (/[a-zа-яё]/i.test(text) && text.length > 1){
     	$.ajax({
        url:   '/search-ajax', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {text:text, region:region},
        success: function(response) { //Данные отправлены успешно
		     $(".search-ajax").remove();
		     $(".top-search-search").append('<div class="search-ajax">'+response+'</div>');
		} 
    });
	}else{
		$(".search-ajax").remove();
	}
	});
	


	   
	   
	   
	   

}