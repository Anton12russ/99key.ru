 $(document).ready(function() {

	if ($(".alert-del").length > 0) {
	 setTimeout(function(){
	    $(".alert-del").remove();
	},3000);

}
 fileinputlogo();
 appliances();
 appliancesval();
	 calendar();
	    $(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
			 	if (container.container == '#pjaxFormedit') {
					 alert('Сохранено');
				}else{
		          fileinputlogo();
			      appliances();
			      appliancesval();
			      calendar();
				}
	     });
	 });



function appliances() {
	$('input[name="Passanger[appliances]"]').click(function(){
		 appliancesval();
	});

}
function appliancesval() {
  var value = $('input[name="Passanger[appliances]"]:checked').val();
		   if(value == 1) {
			   $('.close-div').css('display','none');
		   }
		   
		   if(value == 0) {
			   $('.close-div').css('display','block');
		   }
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


//--------------------------------------------------------//
function pjax_filelogo() {
	url = $('#passanger-dir_name').val();
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
	
//выводим код календаря
function calendar() {
 

 $(".calendarmain1").datepicker({
	 dateFormat: 'yyyy-mm-dd',
 minDate: new Date(),
 	  onSelect : function(dateText, el ){

		   
		   
		   
		   
		   var formData = $("#formall :input") .filter(function(index, element) { return $(element).val() != ""; }) .serialize();
		var url = $('#url').attr('data-url');
        $.ajax({
        url:    url, //url страницы (action_ajax_form.php)
        type:     "GET", //метод отправки
        dataType: "html", //формат данных
        data: formData,  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно	
          $('#btn span').text('('+response+')');		
		} 
    });
		   
		   
		   
		   
		   
    }
 
 });
}	 

