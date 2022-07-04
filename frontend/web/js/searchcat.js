
if(location.pathname == '/add' || location.pathname == '/update') {
	 getmodalcat = '?modal=true';
 }
 if(location.pathname == '/auctionadd' || location.pathname == '/auctionupdate'){
	 getmodalcat = '?modal=true&auction=true';
 }
 if(location.pathname == '/expressadd' || location.pathname == '/expressupdate'){
	getmodalcat = '';
 }

$(document).ready(function() {
	poiskcat();
	startCatModal();
	catok();
	$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
		poiskcat();
		//catok();
		startCatModal();
	});
 }); 
 

 function startCatModal() {
	$('.category-click').click(function() {
		if($('.blog-category').val()) {
		cat_ajax_act_cat($('.blog-category').val(), 1);
	}else{
		cat_ajax_act_cat($(this).attr("data-id"), false);
	}
});
 }
 
 //      Функции
 //----------------------------------------------------------------//
 
 
 function poiskcat() {
	$('.blog-title').attr('autocomplete', 'off');
   $(".blog-title").bind("keyup", function() {

    	if($('.blog-category').val() == '' || location.pathname == '/expressupdate') {
			
			addcat();
		
	    } 
   });

 }


function addcat() {
	var text = $('.blog-title').val();

	if (/[a-zа-яё]/i.test(text) && text.length > 1){
	
		$.ajax({
		url:   '/searchcat'+getmodalcat, 
		type:     "get", //метод отправки
		dataType: "html", //формат данных
		data: {text:text},
		success: function(response) { //Данные отправлены успешно
		
			if(response) {
			   $(".searchcat-ajax").remove();
			}else{
				$(".searchcat-ajax").show();
			}
			 $(".catok").remove();
			 if(response) {
			    $(".cat-st").next().append('<div class="searchcat-ajax">'+response+'</div>');
				clickcat();

			 }else{
				
				if(location.pathname != '/expressadd' && location.pathname != '/expressupdate') {
				  $(".searchcat-ajax").remove();
				  $(".cat-st").next().append('<div style="color: #FFF;" class="searchcat-ajax btn btn-success category-click" data-toggle="modal" data-target="#categoryMenu">Выбрат категорию из расширенного списка</div>');
				} 
			}
			 startCatModal();
		   } 
	});
	}else{
		$(".searchcat-ajax").remove();
		$(".catok").remove();
		
	}
}

 function clickcat() {

    $('.ul-click-cat').click(function() {
	  $(".searchcat-ajax").hide();
	if(!$(this).attr('data-user')) {
        if(!$(this).attr('data-plat')) {
		
	    }else{
          alert("Категория платная, вы можете подать экспресс объявление в платную категорию только после авторизации.");
		}
	}else{
		if(!$(this).attr('data-plat')) {
			
		  }else{
			if(location.pathname == '/expressupdate' || location.pathname == '/expressadd') {
			   alert("Категория платная, стоимость "+$(this).attr('data-plat')+'р. за '+$(this).attr('data-plat')+' дней.');
			}
			
			$(".cat-st").next().append('<div class="catok">Выбрана категория: <strong>'+$(this).children('.catspan').text()+'</strong></div>');
			$('.blog-category').val($(this).attr('data-id'));  
		}
	}

		catok();
	});
 }
 function catok() {

if(location.pathname != '/expressupdate' || location.pathname != '/expressadd') {
	pjaxField($('.blog-category').attr('data-id'));
}
 $('.catok').click(function() {

	$('.blog-category').val(''); 
	addcat();
 });
}













//Категории на страницах blog_add и auction_add 

function cat_click_cat() {
	$('.category_span').click(function(){
		  var id = $(this).attr("data-id");	
		cat_ajax_act_cat(id);
   });
}

function cat_ajax_act_cat(id = 0, parent = false) {
   $.ajax({
	url:   'map/cat-parent'+getmodalcat, 
	type:     "get", //метод отправки
	dataType: "html", //формат данных
	data: {id:id, parent:parent},
	success: function(response) { //Данные отправлены успешно

	  if (!response) {
		alert(response);
	  }else{
	$('.cat_ajax').html(response);
	 cat_click_cat();
	 cat_click_back_cat();
	 catclick();
	  }
	} 
});
}


function cat_click_back_cat() {
$('.category_model_back').click(function(){	
	 cat_ajax_act_cat($(this).attr("data-back"));
});
}



function catclick() {
$('.cat-ok').click(function(){	
	// $('.region-click').attr('data-region',$(this).attr('data-id'));
	 // $('.category-click').html('Категория (<span style="font-size: 11px; font-weight: normal;">'+$(this).attr('data-text')+'</span>)');
	 $('.category-click').remove();
	 $(".cat-st").next().append('<div class="category-click" data-toggle="modal" data-target="#categoryMenu">Выбрана категория: <strong>'+$(this).attr('data-text')+'</strong></div>');
	  $('.category-click').attr('data-id', $(this).attr('data-id'));
	  $('.blog-category').val($(this).attr('data-id'));

	  $('.active_reg').removeClass('active_reg');
	  $(this).addClass('active_reg');
	  $('.category_span').remove();
	  $('.searchcat-ajax').remove();
	  $('#categoryMenu').modal('hide')
	          cat_click_cat();
			  pjaxField($(this).attr('data-id'));
});
}



function pjaxField(cat_id) {
$.pjax({
  type       : 'POST',
  container  : '#pjaxFields',
  data       : {Pjax_category:cat_id, Pjax_time:$('#blog-date_del').val(), Pjax_region:$('.region-hidden').val()},
  push       : true,
  replace    : false,
  timeout    : 10000,
  "scrollTo" : false
});
field_block();
}