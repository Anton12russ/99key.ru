$(document).ready(function() {
   fileinput();
 //Первоначальная загрузка корневых категорий
$('#selectBox_cat').empty();
$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val()) {selectact($('.catchang').val())}else{ getCategory(0)};
//Действия после получения pjax -> отправка формы или изменения дополнительныхполей.
$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
	if (container.container == '#pjaxFields') {
	    add_maps();
    }
	if (container.container == '#pjaxFile') {
	fileinput();
    progress_open();
	  $('.preloader').hide();
	}
	if (container.container == '#pjaxContent') {
$('.preloader').hide();		
   fileinput();		
   user_id();
$('body,html').animate({scrollTop: 0 }, 800);
$('.regionchang').before('<div id="selectBox_region"></div>');
$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val()) {selectact($('.catchang').val())}else{ getCategory(0)};
if ($('.regionchang').val()) {selectactreg($('.regionchang').val())}else{ getRegion(0)};

  alssAct();
//add_maps();

$('.catchang').before('<div id="selectBox_cat"></div>'); }});
  user_id();
$('.add-preloader').click(function() {
$('.preloader').show();
});
});




/*-----------------------------------------------------------------Функции-----------------------------------------------------------------*/
function progress_open() {
	$('.file-thumb-progress').css('display','block');
	$('.file-thumb-progress .progress .progress-bar').attr('id-data','progressbar');
	$('[id-data]').removeClass('class');
	$('[id-data]').attr('class','progress-bar bg-success progress-bar-success');
	$('[id-data]').text('Файл загружен');
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

//-----------------Функция выбора пользователя -----------------//
function user_id() {
$('.user_id').click(function() {
$('#user_cont').attr('src',$('.href_user').attr('data-href')+'?get=1');

});	
$('.add_user').click(function() {
$('.user_input').val($("#user_cont").contents().find("#iframe_par_id").val());
$('.user_id').text($("#user_cont").contents().find("#iframe_par_name").val());
});	
}

//-----------------Действие Ajax подгрузки выбранной категории и региона -----------------//
function selectact(id) {

if ($('.catchang').val()) {
    $.ajax({
        url:   '/admin/category/exit', //url страницы (action_ajax_form.php)
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        data: {idcategory:id,id_cat:$('.id_cat').val()},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
	   $('#selectBox_cat').html(response);

var next = $('#selectBox_cat option[selected=selected]:last').val();
 getCategory(next);
$(".sel_cat" ).change(function() {
	if($(this).val() == 'false') {
	}else{
	 $('.reg_i').removeAttr('disabled');
	}
	 if($(this).val() == 'false') {
     var sdsf = $(this).prev().val();
 	 $('.catchang').val(sdsf);
	 }else{

	 $('.catchang').val($(this).val());
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
	 var devtrue = true;
     getCategory($(this).val(), devtrue)
	}
    });

		}
		});
		
	}

}
//--------------------------------------------------------//





//-----------------Функция приема регионов и категорий ajax -----------------//
	



function getCategory(idcategory, vdefault){
	
 $.ajax({
 url:"/admin/category/catall?id="+getUrlParameter('id'),
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

  
	 /*if($(this).val() == 'false') {
     var sdsf = $(this).prev().val();
 	 $('.catchang').val(sdsf);
	 }else{
	 $('.catchang').val($(this).val());
	 }*/
	 
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

   }else{
    //Информирует пользователя если подкатегорий, для выбранной категории не существует

$('.catchang').val($(".sel_cat option:selected").last().val());


if (vdefault) {
	  $.pjax({
        type       : 'POST',
        container  : '#pjaxFields',
        data       : {'Pjax_category':$('.catchang').val()},
        push       : true,
        replace    : false,
        timeout    : 10000,
        "scrollTo" : false
    });

}


     add_maps();
     }
  }
  
  
 });
 return('Ок');
}

function pjax_file() {
	  $.pjax({
        type       : 'POST',
        container  : '#pjaxFile',
        data       : {'dir_name':$('#blog-dir_name').val()},
        push       : true,
        replace    : false,
        timeout    : 10000,
        "scrollTo" : false
    });
	
}	
function add_maps() {
	    if($(".coordin").length) {
		
	        if($(".map_s").length) {}else{
	           $('head').append('<script class="map_s" src="https://api-maps.yandex.ru/2.0/?load=package.standard&amp;lang=ru-RU&amp;apikey=f7828065-35c0-4191-a8f1-65db58321100" type="text/javascript"></script>');		
	         }
		if(!$(".map_id").length) { 
		  setTimeout(function(){
		         $('head').append('<script class="map_id" src="/admin/js/maps.js"></script>');
				 maps_id();
                 },1000)
			}else{
				maps_id();
			}
			 
         }
}
//--------------------------------------------------------//
	
	
	