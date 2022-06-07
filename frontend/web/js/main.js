$(document).ready(function() {
	filtrmain();

    $('.parent_click').click(function(){
      var id_parents = $(this).attr("id");
	   $.ajax({
        url:   '/ajax/cat-parent', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:$(this).attr("data-id")},
        success: function(response) { //Данные отправлены успешно
		 $('.'+id_parents).html(response);
		}
    });
    });

});


function filtrmain() {
$('.tab-box-new a').click(function(){
	
		$.ajax({
        url:   '/ajax/mainfiltr', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {act:$(this).attr('data-act'), url:window.location.href},
        success: function(response) { //Данные отправлены успешно  
		   //$.pjax.reload({container: "#pjaxFiltr"});
		   
		} 
        });
		return false; // подробнее про return false;
	});
}
