$(document).ready(function() {
      fileinputPrice();
	   fileinput();
	   calendar();
	   fileinputlogo();
	  // maps_id();
	   output();
	   domen();
$('.regionchang').before('<div id="selectBox_region"></div>');
$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val()) {selectact($('.catchang').val(), 'cat')}else{ getCategory(0)};
if ($('.regionchang').val()) {selectact($('.regionchang').val(), 'reg')}else{ getRegion(0)};


$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
	
	
       fileinputPrice();

	calendar()
	output();
	domen();
    fileinput();
    fileinputlogo();

	if (container.container == '#pjaxFile') {
        progress_open();
	  $('.preloader').hide();
	}
	if (container.container == '#pjaxLogo') {
        progress_open();
	  $('.preloader').hide();
	}
	if (container.container == '#pjaxPrice') {
        progress_open();
	  $('.preloader').hide();
	}
	
	if (container.container == '#pjaxContent') {
		maps_id();
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
	
	if($('#body_conteiner').hasClass("col-md-9")) {
		$('.graf-table').css('width','100%');
	}
	
	});
	
	if($('#body_conteiner').hasClass("col-md-9")) {
		$('.graf-table').css('width','100%');
	}
	
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
     getCategory($(this).val())
	}
    });

   }else{
    //Информирует пользователя если подкатегорий, для выбранной категории не существует
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

	if($(this).val() == 'false') {
	}else{
	 $('.reg_i').removeAttr('disabled');
	}

 
	 if($(this).val() == 'false') {
     var sdsf = $(this).prev().val();
 	 $('.regionchang').val(sdsf);
	 }else{

	 $('.regionchang').val($(this).val());
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

   }else{
    //Информирует пользователя если подкатегорий, для выбранной категории не существует
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


}else{
	var id_cat = $('.id_cat').val(); 
	var Box	= '#selectBox_cat';
	var chang = '.catchang';
	var sel_cat = '.sel_cat';
	//if ($(".catchang").val()){ $("#selectBox_cat").after("<div class='alert alert-success add_al cat_ok'>Выбрано</div>");}

}
    $.ajax({
        url:   '/ajax/exitcat', //url страницы (action_ajax_form.php)
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        data: {idcategory:id,id_cat:id_cat, act:act},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
	   $(Box).html(response);
	   

if (sel_cat == '.sel_reg') {
    var next = $('#selectBox_region option[selected=selected]:last').val();
    getRegion(next);	
}else{
	var next = $('#selectBox_cat option[selected=selected]:last').val();
    getCategory(next);
}

$(sel_cat).change(function() {

	if($(this).val() == 'false') {
	}else{
	 $('.reg_i').removeAttr('disabled');
	}
	 if($(this).val() == 'false') {
     var sdsf = $(this).prev().val();
 	 $(chang).val(sdsf);
	 }else{

	 $(chang).val($(this).val());
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
	url = $('#shop-dir_name').val();
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

function output() {
$('[type=checkbox]').click(function(){
	if ($(this).is(':checked')){
		$(this).parent().parent().parent().addClass('opacity');
	} else {
		$(this).parent().parent().parent().removeClass('opacity');
	}
});
}






function domen() {
	 $('#shop-domen').keyup(function(){
		  var text = $('.alert-domen').attr('data-text');
		  var domen = $('.alert-domen').attr('data-domen');
		  $('.alert-domen').html(text+' <strong>'+cyr2lat($('#shop-domen').val())+'.'+domen+'</strong>');
     });   
}











/*----------------Для логотипа--------------------------------------------------------*/

//--------------------------------------------------------//
function pjax_filelogo() {
	url = $('#shop-dir_name').val();
	  $.pjax({
        type       : 'POST',
        container  : '#pjaxLogo',
        data       : {'dir_name':url},
        push       : true,
        replace    : false,
        timeout    : 10000,
        "scrollTo" : false
    });
}

//-----------------Функция допов для загрузчика изображений -----------------//
function fileinputlogo() {
//Выключаем поле клика изображений, при условии, что изображений болше, чем в переменной
$('#input-logo').on('fileunlock', function(event) {
	pjax_filelogo();
 });
 //Отправляем выбранный файл на сервер сразу после его выбора
$('#input-logo').on('fileimagesloaded', function(event) {
    $('#input-logo').fileinput('upload');
 });	
}


//выводим код календаря
function calendar() {
if ($(".datepicker").length){
$(".datepicker").datepicker({
	dateFormat: 'yy-mm-dd',
    onSelect : function(dateText, inst){
		var dt = new Date();
		var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
		$(this).val(dateText+' '+time);
    }
});
}
}







/* Функция транслитерации	*/

function cyr2lat(str) {

    var cyr2latChars = new Array(
['а', 'a'], ['б', 'b'], ['в', 'v'], ['г', 'g'],
['д', 'd'],  ['е', 'e'], ['ё', 'yo'], ['ж', 'zh'], ['з', 'z'],
['и', 'i'], ['й', 'y'], ['к', 'k'], ['л', 'l'],
['м', 'm'],  ['н', 'n'], ['о', 'o'], ['п', 'p'],  ['р', 'r'],
['с', 's'], ['т', 't'], ['у', 'u'], ['ф', 'f'],
['х', 'h'],  ['ц', 'c'], ['ч', 'ch'],['ш', 'sh'], ['щ', 'shch'],
['ъ', ''],  ['ы', 'y'], ['ь', ''],  ['э', 'e'], ['ю', 'yu'], ['я', 'ya'],
 
['А', 'A'], ['Б', 'B'],  ['В', 'V'], ['Г', 'G'],
['Д', 'D'], ['Е', 'E'], ['Ё', 'YO'],  ['Ж', 'ZH'], ['З', 'Z'],
['И', 'I'], ['Й', 'Y'],  ['К', 'K'], ['Л', 'L'],
['М', 'M'], ['Н', 'N'], ['О', 'O'],  ['П', 'P'],  ['Р', 'R'],
['С', 'S'], ['Т', 'T'],  ['У', 'U'], ['Ф', 'F'],
['Х', 'H'], ['Ц', 'C'], ['Ч', 'CH'], ['Ш', 'SH'], ['Щ', 'SHCH'],
['Ъ', ''],  ['Ы', 'Y'],
['Ь', ''],
['Э', 'E'],
['Ю', 'YU'],
['Я', 'YA'],
 
['a', 'a'], ['b', 'b'], ['c', 'c'], ['d', 'd'], ['e', 'e'],
['f', 'f'], ['g', 'g'], ['h', 'h'], ['i', 'i'], ['j', 'j'],
['k', 'k'], ['l', 'l'], ['m', 'm'], ['n', 'n'], ['o', 'o'],
['p', 'p'], ['q', 'q'], ['r', 'r'], ['s', 's'], ['t', 't'],
['u', 'u'], ['v', 'v'], ['w', 'w'], ['x', 'x'], ['y', 'y'],
['z', 'z'],
 
['A', 'A'], ['B', 'B'], ['C', 'C'], ['D', 'D'],['E', 'E'],
['F', 'F'],['G', 'G'],['H', 'H'],['I', 'I'],['J', 'J'],['K', 'K'],
['L', 'L'], ['M', 'M'], ['N', 'N'], ['O', 'O'],['P', 'P'],
['Q', 'Q'],['R', 'R'],['S', 'S'],['T', 'T'],['U', 'U'],['V', 'V'],
['W', 'W'], ['X', 'X'], ['Y', 'Y'], ['Z', 'Z'],
 
[' ', ''],['0', '0'],['1', '1'],['2', '2'],['3', '3'],
['4', '4'],['5', '5'],['6', '6'],['7', '7'],['8', '8'],['9', '9'],
['-', '-']
 
    );
 
    var newStr = new String();
 
    for (var i = 0; i < str.length; i++) {
 
        ch = str.charAt(i);
        var newCh = '';
 
        for (var j = 0; j < cyr2latChars.length; j++) {
            if (ch == cyr2latChars[j][0]) {
                newCh = cyr2latChars[j][1];
 
            }
        }
        // Если найдено совпадение, то добавляется соответствие, если нет - пустая строка
        newStr += newCh;
 
    }
    // Удаляем повторяющие знаки - Именно на них заменяются пробелы.
    // Так же удаляем символы перевода строки, но это наверное уже лишнее
    return newStr.replace(/[-]{2,}/gim, '-').replace(/\n/gim, '');
}


























//--------------------------------------------------------//
function pjax_filePrice() {
	url = $('#shop-dir_name').val();
	  $.pjax({
        type       : 'POST',
        container  : '#pjaxPrice',
        data       : {'dir_name':url},
        push       : true,
        replace    : false,
        timeout    : 10000,
        "scrollTo" : false
    });
}

//-----------------Функция допов для загрузчика изображений -----------------//
function fileinputPrice() {
//Выключаем поле клика изображений, при условии, что изображений болше, чем в переменной
$('#input-file').on('fileunlock', function(event) {
	pjax_filePrice();
 });
 //Отправляем выбранный файл на сервер сразу после его выбора
$('#input-file').on('fileloaded', function(event) {
    $('#input-file').fileinput('upload');
 });	
}