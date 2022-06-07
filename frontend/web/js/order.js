$(document).ready(function(){
      $('.order_ok').click(function(){
        $.ajax({
        url: '/order',
        type:     "get", 
		data: {id:$(this).attr('data-board')},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно
	     $('.form_order').html(response);
		$.pjax.reload({container: '#pjaxCar'});
		}
 	});
      });
});