$(document).ready(function() {	
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
 //Первоначальная загрузка корневых категорий
$('#selectBox_cat').empty();
//$('input[name=CategorySearch\\[parent\\]]').before('<div id="selectBox_cat"></div>');
//$('input[name=BlogSearch\\[category\\]]').before('<div id="selectBox_cat"></div>');
$('.catchang').before('<div id="selectBox_cat"></div>');
if ($('.catchang').val() > 0) {selectact($('.catchang').val())}else{ getCategory(0)};
sort_cat();


});




/*-----------------------------------------------------------------Функции-----------------------------------------------------------------*/






//-----------------Действие Ajax подгрузки выбранной категории и региона -----------------//
function selectact(id) {
if ($('.catchang').val()) {
    $.ajax({
        url:   '/article/exit', //url страницы (action_ajax_form.php)
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        data: {idcategory:id,id_cat:$('.id_cat').val()},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
	   $('#selectBox_cat').html(response);

var next = $('#selectBox_cat option[selected=selected]:last').val();
 getCategory(next);
 $(".sel_cat" ).change(function() {
	  if($(this).val() == 'false') {
		 $('.catchang').val('');
		 $(".cat_ok").remove();
	  }
  });
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
     getCategory($(this).val())
	}
    });

		}
		});
		
	}

}
//--------------------------------------------------------//



	

//-----------------Функция приема регионов и категорий ajax -----------------//
function getCategory(idcategory){

 $.ajax({
  url: $('.url_all').attr('data-href')+'?id'+idcategory,
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
        $('.catchang').val('');
		 $(".cat_ok").remove();
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

   }
  }
  
  
 });
 return('Ок');
}
//--------------------------------------------------------//
function imgdel($file){	
       $('.redactor-modal-delete-btn').click(function(){
	
	
		$.ajax({
        url:   '/article/img-del', //url страницы (action_ajax_form.php)
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        data: {file:$('#redactor-image-link').attr('href'), act:'article'},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
	      alert(response);
		}
	   });
       });

}

	