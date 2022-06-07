$(document).ready(function() {
reservdel();
reservuserdel();
calendars();
carshopstatus();
status_bayer();
dispute_cashback();
//Действие после обновления pjax
$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
    reservdel();
	calendars();
	status_bayer();
	reservuserdel();
	if (container.container == '#pjaxContent') {
	   calendars();
	   carshopstatus();
    }
	
	if (container.container == '#pjaxFormdis') {
		$.pjax.reload({container: '#pjaxDispute'});

		
	}
 });
});




//      Функции
//----------------------------------------------------------------//


function dispute_cashback() {

	$('#dispute-cashback').keyup(function(){
		var summ_orig = $('.cachback-info').attr('data-cach');
		var summ = $('#dispute-cashback').val();

		if(Number(summ) > Number(summ_orig)) {
				alert('Не более '+$('.cachback-info').attr('data-cach'));
				$('#dispute-cashback').val('');
		}
		
	});
}

function status_bayer() {
$(".status-edit").click(function() {
var conf = confirm("Вы уверены?");
if (conf ==true)
{
 $.ajax({
        url: '/user/statusbayer',
        type:     "get", 
		data: {id:$(this).attr('data-id')},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно
           $.pjax.reload({container: '#pjaxContent'});
		}
 	 });
	 
	   }
  });
}

function carshopstatus() {
$(".car-shop-user").change(function() {
var conf = confirm("Вы хотите изменить статус на ("+$(this).find('option:selected').text()+')');
if (conf ==true)
{
 $.ajax({
        url: '/user/statuscar',
        type:     "get", 
		data: {id:$(this).attr('data-id'),status:$(this).val()},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно
		}
 	 });
	 
	   }
  });
}


//выводим код календаря
function calendars() {

if($(".datepicker2").length) {	

	$( ".datepicker2").datepicker({
		 dateFormat: 'yy-mm-dd',
	});
	}
}

function  reservdel() {
	$(".reserv-del").click(function() {
        var conf = confirm("Вы уверены?");
        if (conf ==true)
        {
	   $.ajax({
        url: '/ajax/reservdel',
        type:     "get", 
		data: {id:$(this).attr('data-id')},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно
		   alert('Готово');
		   $.pjax.reload({container: '#p0'}); 
		}
 	 });
        }
    });
}



function  reservuserdel() {
	
	$(".reserv-del-user").click(function() {
        var conf = confirm("Вы уверены?");
        if (conf ==true)
        {
	   $.ajax({
        url: '/ajax/reservdeluser',
        type:     "get", 
		data: {id:$(this).attr('data-id')},
        dataType: "html",
        success: function(response) { //Данные отправлены успешно
		   alert('Готово');
		   $.pjax.reload({container: '#p0'}); 
		}
 	 });
        }
    });
	
}
//--------------------------------------------------------//