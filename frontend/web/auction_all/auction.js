$(document).ready(function() {
	$("#bet-price").on("change keyup paste", function(){
		$('#bet-price').off();
        if($(this).val() >= $(this).attr('data-moment')) {
			alert('Вы можете купить лот по блиц-цене ');
		}
    });	
  
	
	plus();
	minus();
	pay_lot();
	auction_history();
$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {	
    plus();
	minus();
    pay_lot();
	auction_history();
	
	if (container.container == '#pjaxContent1') {
	    $.pjax.reload({container: '#pjaxContent2'}); 
	}
	
});
});

function plus() {
	$('.plus').click(function(){
	proverka();
		var value = parseFloat($('#bet-price').val()) + parseFloat($(this).attr('data-shag'));
		$('#bet-price').val(value);
		 $('#bet-price_false').val(value);
	});
}


function minus() {
	$('.minus').click(function(){
		proverka();
		var value = parseFloat($('#bet-price').val())- parseFloat($(this).attr('data-shag'));
		if(parseFloat($('#bet-price').attr('data-val')) <= value) {
		  $('#bet-price').val(value);
		}
	});
}


function proverka() {
	$('#bet-price').off();

        if($('#bet-price').val() >= $('#bet-price').attr('data-moment')) {
			alert('Вы можете купить лот по блиц-цене ');
		}
	
}
function pay_lot() {
	 
	$('.userreserv').click(function(){
		 $('.modal-body').html('');
		 $.ajax({
        url:   '/ajax/auctionreserv', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:$(this).attr('data-id')},
        success: function(response) { //Данные отправлены успешно
		    $('.modal-body').html(response);
	
		} 
		});
	 });	
		
		
		
	    $('.pay_lot').click(function(){
			$('.modal-body').html('');
		$.ajax({
        url:   '/ajax/auctionreserv', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:$(this).attr('data-id')},
        success: function(response) { //Данные отправлены успешно
		    $('.modal-body').html(response);
			 $.pjax.reload({container: '#pjaxContent2'}); 
		} 
    });
		});
	}



function auction_history() {
	$('.btn-history-auction').click(function(){
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