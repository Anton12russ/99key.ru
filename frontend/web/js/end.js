$(document).ready(function() {
   end();
});




//      Функции
//----------------------------------------------------------------//

//выводим код календаря
function end() {
 $('.end-click').click(function(){

 $.ajax({
        url: $(this).attr('data-href'),
        type:     "POST", 
        dataType: "html",
        success: function(response) { //Данные отправлены успешно
		  $('.end-click:first').remove();
           $(".end:last").after(response);
	     
		   end();
    	},
    	error: function(response) { // Данные не отправлены
            $('#result_form').html('Ошибка. Данные не отправлены.');
    	}
 	});
 });
}


//--------------------------------------------------------//