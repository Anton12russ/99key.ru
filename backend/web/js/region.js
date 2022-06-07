$(document).ready(function() {	

//-----------------Действие с URL на странице категории и региона -----------------//
 $('.rigs').click(function(){
	var repl = $('#region-name').val();
   $('#region-url').val(trim(repl));
 });
 $('.trans').click(function(){
   $('#region-url').val(cyr2lat( $('#region-name').val()  ));
 });
 

 //Первоначальная загрузка корневых категорий


$('#selectBox_region').empty();
//$('input[name=RegionSearch\\[parent\\]]').before('<div id="selectBox_region"></div>');
//$('input[name=BlogSearch\\[region\\]]').before('<div id="selectBox_region"></div>');
$('.regionchang').before('<div id="selectBox_region"></div>');

if ($('.regionchang').val()) {selectactreg($('.regionchang').val())}else{getRegion(0);};
sort_reg();
});




/*-----------------------------------------------------------------Функции-----------------------------------------------------------------*/

//-----------------Функция изменение сортировки ajax -----------------//
function sort_reg() {
$(".sort_reg" ).change(function() {

$.ajax({
          type: 'GET',
          url: '/admin/region/sort',
          data: 'id='+$(this).attr('data-id')+'&sort='+$(this).val(),
          success: function(data) {
			$.pjax.reload({container: '#pjaxContent'});  
          },
        
	  
        
        }); 

});
}











//-----------------Действие Ajax подгрузки выбранной категории и региона -----------------//
function selectactreg(id) {

if ($('.regionchang').val()) {
    $.ajax({
        url:   '/admin/region/exit', //url страницы (action_ajax_form.php)
        type:     "get", //метод отправки
        dataType: "html", //формат данных
        data: {idcategory:id,id_cat:$('.id_reg').val()},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
	   $('#selectBox_region').html(response);

var next = $('#selectBox_region option[selected=selected]:last').val();
 getRegion(next);
$(".sel_reg" ).change(function() {

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

		}
		});
		
	}

}
//--------------------------------------------------------//





//-----------------Функция приема регионов и категорий ajax -----------------//
	



function getRegion(idcategory){

 $.ajax({
 url:"/admin/region/catall?id="+getUrlParameter('id'),
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
	
	
	