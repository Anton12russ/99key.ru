$(document).ready(function() {
	$('#btn').click(function(){
	   $('#bs-example-navbar-collapse-9').removeClass('in');
	});
  search();
  filtr(0);
	 $("#ajax_form").change(function(){
		 form_change();
     });
    

  $('.category-click').click(function(){

	  if($(this).attr("data-id") > 0) {
           region_ajax_act_cat($(this).attr("data-id"), 1);
	  }else{
		  region_ajax_act_cat($(this).attr("data-id"), false);
	  }
  });

  
  $('.region-click').click(function(){
	  if($(this).attr("data-region") > 0) {
         region_ajax_act_reg($(this).attr("data-region"), 1);
	  }else{
		 region_ajax_act_reg($(this).attr("data-region"), false);
	  }
  });
  
  

  region_click_back_reg();
  $(document).on("click.bs.dropdown.data-api", ".noclose", function (e) { e.stopPropagation() });


  
});



 function filtr(id) {
   $.ajax({
        url:   'map/filtr', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:id},
        success: function(response) { //Данные отправлены успешно	
		  if (!response) {
    	     alert(response);
		  }else{
		     $('.response-filtr').html(response);
			 search();
			 
			$('.sort-chex').click(function(){
	           $(this).parent().toggleClass("opendrop");
			   
			    $(document).mouseup(function (e){ // событие клика по веб-документу
		           var div = $('.sortmenu'); // тут указываем ID элемента
		             if (!div.is(e.target) // если клик был не по нашему блоку
		                 && div.has(e.target).length === 0) { // и не по его дочерним элементам
			             $('.sortmenu').parent().removeClass("opendrop");
						 
		             }
	             });
            });
	 
		  }
		} 
    });
 }

//Регионы


   function region_click_reg() {
		$('.region_span').click(function(){
          	var id = $(this).attr("data-id");	
		    region_ajax_act_reg(id);
       });
   }




function region_ajax_act_reg(id = 0, parent = false) {

	   $.ajax({
        url:   'map/reg-parent', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:id, parent:parent},
        success: function(response) { //Данные отправлены успешно
				
		  if (!response) {
    	    alert(response);
		  }else{
		$('.region_ajax').html(response);
         region_click_reg();
		 region_click_back_reg();
		 regionclick();
		  }
		} 
    });
}


function region_click_back_reg() {
	$('.region_model_back').click(function(){	
     	region_ajax_act_reg($(this).attr("data-back"));
	});
}



function regionclick() {
	$('.cat-ok').click(function(){	
     	$('.region-click').attr('data-region',$(this).attr('data-id'));
		$('.region-click').html('Регион (<span style="font-size: 11px; font-weight: normal;">'+$(this).attr('data-text')+'</span>)');
		$('.region-click').attr('data-id', $(this).attr('data-id'));
		$('#region').val($(this).attr('data-id'));
		$('.open').removeClass('open');
				form_change();
	});
}




//Категории

   function region_click_cat() {
		$('.category_span').click(function(){
          	var id = $(this).attr("data-id");	
		    region_ajax_act_cat(id);
       });
   }

function region_ajax_act_cat(id = 0, parent = false) {

	   $.ajax({
        url:   'map/cat-parent', 
        type:     "get", //метод отправки
        dataType: "html", //формат данных
		data: {id:id, parent:parent},
        success: function(response) { //Данные отправлены успешно
				
		  if (!response) {
    	    alert(response);
		  }else{
		$('.cat_ajax').html(response);
         region_click_cat();
		 region_click_back_cat();
		 catclick();
		  }
		} 
    });
}


function region_click_back_cat() {
	$('.category_model_back').click(function(){	
     	region_ajax_act_cat($(this).attr("data-back"));
	});
}



function catclick() {
	$('.cat-ok').click(function(){	
	    // $('.region-click').attr('data-region',$(this).attr('data-id'));
		  $('.category-click').html('Категория (<span style="font-size: 11px; font-weight: normal;">'+$(this).attr('data-text')+'</span>)');
          $('.category-click').attr('data-id', $(this).attr('data-id'));
		  $('#category').val($(this).attr('data-id'));
		  filtr($(this).attr('data-id'));
		  $('.open').removeClass('open');
		  		form_change();
	});
}


function search(id) {
	$('.sortmenu [type="checkbox"]').click(function(){
			var arrs = [];
    var idchex = $(this).parent().parent().attr('id');
		 $('#'+idchex+' input:checkbox:checked').each(function(){
           arrs.push($(this).parent().text());
         });
		 var arr_implode = arrs.join(',').replace(/\r?\n/g, '');
		 if (arr_implode) {
		$(this).parent().parent().parent().prev().text(arr_implode);
		 }else{
			$(this).parent().parent().parent().prev().text('Выбрать'); 
		 }
    });
	

}

function form_change() {
		var formData = $("#ajax_form :input") .filter(function(index, element) { return $(element).val() != ""; }) .serialize();
        $.ajax({
        url:    '/map/coord?count=true', //url страницы (action_ajax_form.php)
        type:     "GET", //метод отправки
        dataType: "html", //формат данных
        data: formData,  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно	
          $('#btn span').text('('+response+')');		
		} 
    });
}









