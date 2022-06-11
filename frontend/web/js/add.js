$(document).ready(function() {
	   fileinput();
	   field_block();
	   payadd();
	   calendar12();
	   addauction();
$('.regionchang').before('<div id="selectBox_region"></div>');
$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val()) {selectact($('.catchang').val(), 'cat')}else{ getCategory(0)};
if ($('.regionchang').val()) {selectact($('.regionchang').val(), 'reg')}else{ getRegion(0)};


$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
	calendar12();
	
	if (container.container == '#pjaxFields') {
		addauction();
		price_category();
    }

	if (container.container == '#pjaxFile') {
	    fileinput();
        progress_open();
	  $('.preloader').hide();
	}
	if (container.container == '#pjaxContent') {
	    addauction();
		field_block();
		payadd();
		price_category();
        scroll('.has-error');
        progress_open();
		fileinput();
	    $('.regionchang').before('<div id="selectBox_region"></div>');
        $('.catchang').before('<div id="selectBox_cat"></div>');
        if ($('.catchang').val()) {selectact($('.catchang').val(), 'cat')}else{ getCategory(0)};
        if ($('.regionchang').val()) {selectact($('.regionchang').val(), 'reg')}else{ getRegion(0)};
        $('[data-toggle="tooltip"]').tooltip('enable');
		if ($(".balance_user").length){
			$('#pjaxContent').attr('id','content');
		}
		
    }
	});
});




//      Функции
//----------------------------------------------------------------//




//-----------------Функция приема регионов и категорий ajax -----------------//
function getCategory(idcategory, vdefault){
	
 $.ajax({
 url:"/ajax/catall?id="+idcategory,
  cache:false,
  data:{idcategory:idcategory},
  success:function(data){
   //Создание объекта формата JSON
var optionsObj = eval( '('+data+')' );

   //Проверка на непустой объект
   if (!$.isEmptyObject(optionsObj)){
if (idcategory == 'false') {}else{
    //Создание элемента select
   $("#selectBox_cat").append("<select class='form-control sel_cat'><option value='false'>Не выбрано</option></select>");

   //Ссылка на созданный select
    var select=$("#selectBox_cat select:last");

    //Итерация объекта optionsObj, с последующим наполнение элемента select опциями 
    $.each(optionsObj, function(i, val) {
     select.append("<option value='"+ val.value +"'>"+ val.caption +"</option>");
    });

}

    // События для созданного select
    select.change(function(){
	if($(this).val() == 'false') {
	}else{
	 $('.reg_i').removeAttr('disabled');
	}

 
	 if($(this).val() == 'false') {
     var sdsf = $(this).prev().val();
 	 //$('.catchang').val(sdsf);
	 }else{

	// $('.catchang').val($(this).val());
	 }
	 
     //Удаление последующих категорий
     $(this).nextAll().remove();
	 var $obj = $('#selectBox_cat option:selected').map(function() {return this.text;}).get().join(' / ');
	 if ($obj) {
		var $objects =  $obj.replace('/ Не выбрано','');
	//$('#change_region').text($objects); $('.parent_true').text($objects); $('.text-parent').text($objects);
	 }
if($(this).val() == 'roditel') {
$('.catchang').val('');
}else{ 
     //Зацикливание
     getCategory($(this).val(), true)
	}
    });
          $(".cat_ok").remove();
		  $('.catchang').val('');
   }else{
	   
          $('.catchang').val($("#selectBox_cat .sel_cat option:selected").last().val());
		  $(".cat_ok").remove();
		  $("#selectBox_cat").after("<div class='alert alert-success add_al cat_ok'>Выбрано</div>");
		  
		  
	if (vdefault) {
   	field_block();
	  $.pjax({
        type       : 'POST',
        container  : '#pjaxFields',
        data       : {Pjax_category:$('.catchang').val(), Pjax_time:$('#blog-date_del').val(), Pjax_region:$('#blog-region').val()},
        push       : true,
        replace    : false,
        timeout    : 10000,
        "scrollTo" : false
    });
}
   }
  }
  
  
 });
 return('Ок');
}
//--------------------------------------------------------//



function getRegion(idcategory){
 $.ajax({
  url:"/ajax/catall?region=true&id="+idcategory,
  cache:false,
  data:{idcategory:idcategory},
  success:function(data){
   //Создание объекта формата JSON
var optionsObj = eval( '('+data+')' );
   //Проверка на непустой объект
   if (!$.isEmptyObject(optionsObj)){
if (idcategory == 'false') {}else{
    //Создание элемента select
   $("#selectBox_region").append("<select class='form-control sel_reg'><option value='false'>Не выбрано</option></select>");

   //Ссылка на созданный select
    var select=$("#selectBox_region select:last");

    //Итерация объекта optionsObj, с последующим наполнение элемента select опциями 
    $.each(optionsObj, function(i, val) {
     select.append("<option value='"+ val.value +"'>"+ val.caption +"</option>");
    });

}

    // События для созданного select
    select.change(function(){
/*
	if($(this).val() == 'false') {
	}else{
	 $('.reg_i').removeAttr('disabled');
	}

 
	 if($(this).val() == 'false') {
     var sdsf = $(this).prev().val();
 	// $('.regionchang').val(sdsf);
	 }else{

	 //$('.regionchang').val($(this).val());
	 }*/
	 
	 	 //Замена
	 if($(this).val() == 'false') {
        $('.regionchang').val('');
		$(".reg_ok").remove();
	 }
	 
     //Удаление последующих категорий
     $(this).nextAll().remove();
	 var $obj = $('#selectBox_region option:selected').map(function() {return this.text;}).get().join(' / ');
	 if ($obj) {
		var $objects =  $obj.replace('/ Не выбрано','');
	//$('#change_region').text($objects); $('.parent_true').text($objects); $('.text-parent').text($objects);
	 }
if($(this).val() == 'roditel') {
$('.regionchang').val('');
}else{ 
     //Зацикливание
     getRegion($(this).val())
	}
    });
	
        
		  $(".reg_ok").remove();
		  $('.regionchang').val('');
   }else{
        $('.regionchang').val($("#selectBox_region .sel_reg option:selected").last().val());
		$(".reg_ok").remove();
	    $("#selectBox_region").after("<div class='alert alert-success add_al reg_ok'>Выбрано</div>");
		
		
if ($('.catchang').val()) {
	  $.pjax({
        type       : 'POST',
        container  : '#pjaxFields',
        data       : {Pjax_category:$('.catchang').val(), Pjax_time:$('#blog-date_del').val(), Pjax_region:$('#blog-region').val()},
        push       : true,
        replace    : false,
        timeout    : 10000,
        "scrollTo" : false
    });
		

}
   }
  }
  
  
 });
 return('Ок');
}
//--------------------------------------------------------//





//-----------------Действие Ajax подгрузки выбранной категории и региона -----------------//
function selectact(id, act) {

if (act == 'reg') {
	
	var id_cat = $('.id_reg').val();
    var Box	= '#selectBox_region';
	var chang = '.regionchang';
    var sel_cat = '.sel_reg';

if ($(".regionchang").val()){ $("#selectBox_region").after("<div class='alert alert-success add_al reg_ok'>Выбрано</div>");}
}else{
	var id_cat = $('.id_cat').val(); 
	var Box	= '#selectBox_cat';
	var chang = '.catchang';
	var sel_cat = '.sel_cat';
	if ($(".catchang").val()){ $("#selectBox_cat").after("<div class='alert alert-success add_al cat_ok'>Выбрано</div>");}

}
    $.ajax({
        url:   '/ajax/exitcat', //url страницы (action_ajax_form.php)
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        data: {idcategory:id,id_cat:id_cat, act:act},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
	   $(Box).html(response);

$(sel_cat).change(function() {
/*
	if($(this).val() == 'false') {
	}else{
	 $('.reg_i').removeAttr('disabled');
	}
	 if($(this).val() == 'false') {
     var sdsf = $(this).prev().val();
 	 $(chang).val(sdsf);
	 }else{

	 $(chang).val($(this).val());
	 }*/

	 if (act == 'reg') {
			  if($(this).val() == 'false') {
               $('.regionchang').val('');
		       $(".reg_ok").remove();
		     }
	     }else{
               if($(this).val() == 'false') {
                 $('.catchang').val('');
		         $(".cat_ok").remove();
			   }
		 }
	 
     //Удаление последующих категорий
     $(this).nextAll().remove();
	 var $obj = $(Box+' option:selected').map(function() {return this.text;}).get().join(' / ');
	 if ($obj) {
		var $objects =  $obj.replace('/ Не выбрано','');
	//$('#change_region').text($objects); $('.parent_true').text($objects); $('.text-parent').text($objects);
	 }
if($(this).val() == 'roditel') {
$(chang).val('');
}else{ 
     //Зацикливание
	 var devtrue = true;
	 if (act == 'reg') {
        getRegion($(this).val());
		
	 }else{
		getCategory($(this).val(), devtrue) 
	 }
	}
    });

		}
		});
		

}














//-----------------Функция допов для загрузчика изображений -----------------//
function fileinput() {
//Запускаем анимированный блок	
//$('#input-id').on('change', function(event) {
//$('.preloader').show();
//});	
	
//Выключаем поле клика изображений, при условии, что изображений болше, чем в переменной
$('#input-id').on('fileunlock', function(event) {
	pjax_file();
 });
 
$('#input-id').on('filesorted', function(event, params) {
 $.ajax({
        url:  params_sort(),
        type:     "POST", 
        dataType: "html",
        data: {arr:params.stack},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
		//$('.result').html(response);
		pjax_file();
    	},
    	error: function(response) { // Данные не отправлены
            $('#result_form').html('Ошибка. Данные не отправлены.');
    	}
 	});

});

 //Отправляем выбранный файл на сервер сразу после его выбора
$('#input-id').on('fileimagesloaded', function(event) {
    $('#input-id').fileinput('upload');
 });	
}


//--------------------------------------------------------//
function progress_open() {
	$('.file-thumb-progress').css('display','block');
	$('.file-thumb-progress .progress .progress-bar').attr('id-data','progressbar');
	$('[id-data]').removeClass('class');
	$('[id-data]').attr('class','progress-bar bg-success progress-bar-success');
	$('[id-data]').text('Файл загружен');
}



//--------------------------------------------------------//
function pjax_file() {
	url = $('#blog-dir_name').val();
	if(!url) {
		url = $('#blogexpress-dir_name').val();
	}
	  $.pjax({
        type       : 'POST',
        container  : '#pjaxFile',
        data       : {'dir_name':url},
        push       : true,
        replace    : false,
        timeout    : 10000,
        "scrollTo" : false
    });

}	

//--------------------------------------------------------//
function scroll($id) {  
       if($($id).offset()) {
           $('html, body').animate({
	           scrollTop: $($id).offset().top-50
            }, 1000);
	   }
}
//--------------------------------------------------------//
function price_category() {
	if ($(".sum-info").length){
		  if ($('#blog-region').val()) {
             $('#blog-date_del').on('change', function () {
				   var price_category = $('.sum-info').attr('data-sum')*$(this).val();
		           $('.price_category').text(price_category);
				   
				   $('.days').text($(this).val());
             });
	   } 
	}

}

//--------------------------------------------------------//


function payadd() {
	
	//Если это страница успешной подачи, то, прокручиваем до блока вверх
	      if ($(".alert").length){
             $('html, body').animate({
	             scrollTop: $('.alert').offset().top-50
             }, 1000);
	     }
	    
	
$('.services').click(function(){
	  var get = $(this).attr('data-href');
	  var day = $('#day').val();
	  
	  $('.services-bottom').attr('data-text',get);
	  //Ставим ссылку при выборе платно	 услуги на кнопку оплаты из баланса лк
      $('.pay-act').attr('href', $('.pay-act').attr('data-href')+'&services='+get+'&day='+day);
 
 $('.sum-open').each(function (index, value) { 
     var day = $('#day').val();
     var href = $(this).attr('data-href')+'&services='+get+'&day='+day;
     $(this).attr('href', href);
 });
 day_add();
 $('.pay-disable').remove();
 
 //Условие, если не хватает средств

 if (Number.parseInt($(this).find('.name').children('.serv-price').children('.price').text()) > Number.parseInt($('.balance_user').attr('data-balance'))) {

    $('.body-logo-pay').css('display','table');
	$('.body-logo-person').css('display','none'); 
	$('.err-balance').css('display','table'); 
 }else{
	 	
	 $('.err-balance').css('display','none'); 
	$('.body-logo-person').css('display','table'); 
	$('.body-logo-pay').css('display','none');
 }
 

 $('.services-add').html($('.services-ok').text() + ' (' + $(this).find('.name').html().replace('<br>', '')+ ')' + '<br>' + $('.services-ok-tyme').html());
 $('.itogo').text(Number.parseInt($('.day-def').attr('data-def')) * Number.parseInt($(this).find('.name').find('.price').html()));
 
 
 $('.services-add').css('color','green');
 day_add();
});

}










function field_block() {
	if($('.catchang').val() > 0 && $('#blog-region').val()) {
        $('.field-none').css('display','block');
	}else{
        $('.field-none').css('display','none');
	}
}





















function calendar12() {
if ($(".datepicker12").length){
$.datepicker.regional['ru'] = {
	closeText: 'Закрыть',
	prevText: 'Предыдущий',
	nextText: 'Следующий',
	currentText: 'Сегодня',
	monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
	monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
	dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
	dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
	dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
	weekHeader: 'Не',
	dateFormat: 'dd.mm.yy',
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['ru']);
$(".datepicker12").datepicker({
	
	dateFormat: 'yy-mm-dd',
    onSelect : function(dateText, inst){
		var dt = new Date();
		var sec = (dt.getSeconds() < 10 ? '0' : '') + dt.getSeconds();
        var min = (dt.getMinutes() < 10 ? '0' : '') + dt.getMinutes();
		var time = dt.getHours() + ":" + min + ":" + sec;
		$(this).val(dateText+' '+time);
    }
});
}
}

//--------------------------------------------------------//



function addauction() {

    $('.collapseThree').click(function(){
		$('#blog-auction').val('0');
		$('#dynamicmodel-f_481').val('');
	});
	
	$('.collapseOne').click(function(){
		$('#dynamicmodel-f_481').val('0');
		$('#blog-auction').val('1');
	});
}