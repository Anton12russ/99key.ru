$(document).ready(function() {	
dispute_cashback();


if($('[data-toggle="tooltip"]').length) {
   $('[data-toggle="tooltip"]').tooltip('enable');
}
//-----------------Запуск функции счетчика -----------------//
blog_services();
if($(".date_services").length) {	
   date_services();
}
 $('.open_window_counter').click(function(){
  if ($(this).attr('data-act') == 'blog') {
     window.open('/admin/countercat',"MyWin", "menubar=yes,width=380,height=430"); 
   }else{
	 window.open('/admin/countercatshop',"MyWin", "menubar=yes,width=380,height=430");   
   }
 });
 $('.count_start_go').click(function(){
	 if($(this).attr('data-act') == 'shop') {
	   counter_cat_del('shop');
	 }else{
	   counter_cat_del('blog');
	 }
 });
//-----------------Действие с URL на странице категории и региона -----------------//
 $('.rigs').click(function(){
	var repl = $('#category-name').val();
   $('#category-url').val(trim(repl));
 });
 $('.trans').click(function(){
   $('#category-url').val(cyr2lat( $('#category-name').val()  ));
 });
 
 
$('.rigsstaticpage').click(function(){
	var repl = $('#staticpage-name').val();
   $('#staticpage-url').val(trim(repl));
 });
 $('.transstaticpage').click(function(){
   $('#staticpage-url').val(cyr2lat( $('#staticpage-name').val()  ));
 });
 
 
 //Отправка формы PJAX , при выборе e-mail пользователя в филтре блога
$('.author_email').click(function(){$('.author_input').val($(this).text()); $('input').change(); });
alssAct();
delstr();

//Запускаем функцию календаря
if($(".datepicker").length) {
calendar();
}
 $("#btnSettigs").click(
		function(){
			dAjaxForm('result_form', 'ajax_form', '/admin/settings/update-all');
			return false; 
		}
	);
	
	
	user_id();
	
$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {	
	user_id();
});
	

});
closedispute();

//Действие после обновления pjax
$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
	//if (container.container == '#pjaxContent') {
	//calendar();
    //}
	
	calendar();
 });

/*-----------------------------------------------------------------Функции-----------------------------------------------------------------*/
function closedispute() {

	
	
	
	$('.spor-close').click(function(){
	
		$.ajax({
        url:    $(this).attr('data-href'), //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных

        success: function(response) { //Данные отправлены успешно
        	alert(response);
			$('.close-disp').remove();
			$('#pjaxFormdis').remove();
			$('.table-background').after('<div class="alert alert-warning">Спор закрыт</div>');

	}
 	});
	});
	
	
/*	
	$('.shop_close').click(function(){
		$.ajax({
        url:    $(this).attr('data-href'), //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных

        success: function(response) { //Данные отправлены успешно
        	alert(response);
			$('.close-disp').remove();
			$('#pjaxFormdis').remove();
			$('.table-background').after('<div class="alert alert-warning">Спор закрыт</div>');

	}
 	});
	});*/
}



function blog_services() {
 $('.services-click').click(function(){
	$('#user_cont').attr('src','/admin/blogservices/create-none?blog_id='+$(this).attr('data-id'));
});
}
	
function date_services() {

const formatDate = date => ('0'+date.getHours()).slice(-2) + ':' + ('0'+date.getMinutes()).slice(-2) + ':' + ('0'+date.getSeconds()).slice(-2);
$( ".date_services").datepicker({  
onSelect: function(dateText, inst) {
	$(this).val($(this).val()+' '+formatDate(new Date()))
	}
});
}
function counter_cat_del(shop) {
	$('.count_start_go').text('Подождите. Не закрывайте окно.');
	$('.count_start_go').attr('disabled','disabled');
	if(shop == 'shop') {
		var url = '/admin/countercatshop/remove'; 
	}else{
		var url = '/admin/countercat/remove'; 
	}
   $.ajax({
        url:  url, 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        success: function() { //Данные отправлены успешно
		if(shop == 'shop') {
		    counter_cat('shop');
		}else{
		    counter_cat('blog');
		}
		}
    });		
}
function counter_cat(shop) {
	if(shop == 'shop') {
		var url = '/admin/countercatshop/count';
	}else{
		var url = '/admin/countercat/count';
	}
    $.ajax({
        url: url, //url страницы (action_ajax_form.php)
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        data: {id:$('.counter_cat').attr('data-id')},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
	    $('.counter_cat').attr('data-id', response);
		
        $('.counter_cat').attr('data-count', parseInt($('.counter_cat').attr('data-count')) + 100);
		rows =  ($('.counter_cat').attr('data-count') * 100) / parseInt($('.rows_cat').text());

		if (rows > 100) {rows = 100;}
		$('.counter_cat').css('width',rows+'%');
		$('.counter_cat').text(rows+'%');
setTimeout(
  function() 
  {
	  if (rows == 100) {
	/*	     $.ajax({
        url:   '/admin/countercat/cachedel', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        success: function() { //Данные отправлены успешно
		}
    });	
   */
	$('.count_start_go').text('Закончено, можете закрыть окно.');	  
	$('.counter_cat').removeClass('progress-bar-info');
	$('.progress').removeClass('progress-striped');
	$('.counter_cat').addClass('progress-bar-success');
    $('.counter_cat').text('Готово');
	
	  }else{
		   counter_cat();
	  }
  }, 1000);
  
  
		}
	});
}

//выводим код календаря
function calendar() {
if($(".datepicker").length) {	
	$( ".datepicker").datepicker({});
	}
}
 
 /*функция выбора пользователя на странице добавления оъявления*/
 function delstr() {	 
if (getUrlParameter('get') == '1' || getUrlParameter('get') == '3') {
$("table").before("<input type='hidden' id='iframe_par_id'/>");
$("table").before("<input type='hidden' id='iframe_par_name'/>");

$('body').each(function(){
$('body').append($('#p0'));
$('body').children().not('#p0').remove();
$('table tr :nth-child(4)').remove();
$('table td ').css('cursor','pointer');
});


$('tbody tr').click(function(){
	$('#iframe_par_id').val($(this).attr('data-key'));
	$('#iframe_par_name').val($(this).children('td:first-child').text());
	$('tbody tr').css('background','none');
	$(this).css('background','rgb(255, 193, 166)');
});	
}
}


/*функция выбора чебокса, открытие панели справа*/
function alssAct() {
$('.filters .fa-check-square-o').click(function(){
actals('true');
});
$('.alls').click(function(){
 if ($(this).is(':checked')) {
actals('false');
 }
});
function actals($true) {
	
	addpanel();
	
if ($true == 'true') {
$(".alls").prop("checked", !$(".alls").prop("checked"));
}
$( ".alls" ).change(function() {
addpanel()
});
$( ".all" ).click(function() {
addpanel()
});
function addpanel() {
$('.panelAct').remove();
$('.control-sidebar.control-sidebar-dark').addClass('control-sidebar-open');
$('.control-sidebar-open').prepend('<div class="panelAct"><span class="close_all label label-warning" data-toggle="control-sidebar">Закрыть</span><select class="act-select form-control" name="act_status"><option value="">Выбрать действие</option><option value="m">На модерации</option><option value="1">Опубликовано</option><option value="2">Удалено</option><option value="3">Удалить окончательно</option><option value="4">Опубликовать на 365 дней</option></select><br><button class="act_sand btn btn-success">Выполнить</button></div>');
$( ".act-select" ).change(function() {
$("#act_id").val($(".act-select").val());
});

$('.act_sand').click(function(){
dAjaxForm('result_form', 'form', '/admin/blog/act');
});
}





$('.nav-justified').hide();

}
$('[data-toggle="control-sidebar"]').click(function(){
$('.panelAct').hide();
$('.nav-justified').show();
});


}

// Отправка формы и получение результата
function AjaxSettings(result_form, ajax_form, url) {
    $.ajax({
        url:     url, //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#"+ajax_form).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
        	result = response;
var matches = [];
var rem_id = $('[name = BlogSearch\\[status_id\\]]').val();
var act_id = $('.act-select').val();
$.pjax.reload({container: '#pjaxContent'}); 
	},
    	error: function(response) { // Данные не отправлены
            $('#result_form').html('Ошибка. Данные не отправлены.');
    	}
 	});
}

//Функция передачи выбранного изображения в форму
function img_value(id, file) {
 if (id == 'w1') {
   var fileimg = file.url;
   $('.img_value').attr('src', fileimg);
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
 
[' ', '-'],['0', '0'],['1', '1'],['2', '2'],['3', '3'],
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


function trim(string) {
					//Удаляем пробел в начале строки и ненужные символы
					string = string.replace(/(^\s+)|'|"|<|>|\!|\||@|#|$|%|^|\^|\$|\\|\/|&|\*|\(\)|\|\/|;|\+|№|,|\?|:|{|}|\[|\]/g, "");
					string = string.replace(' ','_');
					return string;
			}; 
			
		
//Функция получения GET из URL
			
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

//Функция созранения основных настроек
function dAjaxForm(result_form, ajax_form, url) {
    $.ajax({
        url:     url, //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#"+ajax_form).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
		if(response == true) {
	      $('#result_form').html('<div class="alert alert-info">Настройки сохранены</div>');
		     setTimeout(function() {
	        $('.alert-info').remove()
			}, 3000);
		}else{
			$.pjax.reload({container: '#pjaxContent'}); 
		}
        	
			
			$('body,html').animate({scrollTop: 0 }, 800);
    	}
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






function dispute_cashback() {

	$('#dispute-cashback').keyup(function(){
		var summ_orig = $('.price-dispute').text();
		var summ = $('#dispute-cashback').val();

		if(Number(summ) > Number(summ_orig)) {
				alert('Не более '+$('.price-dispute').text());
				$('#dispute-cashback').val('');
		}
		
	});
}



  $("#listsort").change(function() {
    if($(this).val() != ""){
            window.location = $(this).val();
        }
    });
