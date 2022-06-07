    function day_add(){
    $('#day').keyup(function(){
       if(Number.parseInt($(this).val()) > $(this).attr('data-max')) {
		  $(this).val($(this).attr('data-max'));
	   }

	    $('.itogo').text(Number.parseInt($('.services-add').find('.price').text()) * Number.parseInt($(this).val()));
	  var gets = $('.services-bottom').attr('data-text');
	  var day = $('#day').val();

$('.pay-act').attr('href', $('.pay-act').attr('data-href')+'&services='+gets+'&day='+day);	
$('.sum-open').each(function (index, value) { 
     var hrefs = $(this).attr('data-href')+'&services='+gets+'&day='+day;

     $(this).attr('href', hrefs);
 });
		
    });
	
 $('#day').change(function(){
       if(Number.parseInt($(this).val()) > $(this).attr('data-max')) {
		  $(this).val($(this).attr('data-max'));
	   }

	    $('.itogo').text(Number.parseInt($('.services-add').find('.price').text()) * Number.parseInt($(this).val()));
	  var gets = $('.services-bottom').attr('data-text');
	  var day = $('#day').val();

$('.pay-act').attr('href', $('.pay-act').attr('data-href')+'&services='+gets+'&day='+day);	
$('.sum-open').each(function (index, value) { 
     var hrefs = $(this).attr('data-href')+'&services='+gets+'&day='+day;

     $(this).attr('href', hrefs);
 });
		
    });
	
	
	
	
	
	
	
	
	
	
	    $('#day').click(function(){
       if(Number.parseInt($(this).val()) > $(this).attr('data-max')) {
		  $(this).val($(this).attr('data-max'));
	   }
	   
	  var gets = $('.services-bottom').attr('data-text');
	  var day = $('#day').val();

$('.pay-act').attr('href', $('.pay-act').attr('data-href')+'&services='+gets+'&day='+day);	
$('.sum-open').each(function (index, value) { 
     var hrefs = $(this).attr('data-href')+'&services='+gets+'&day='+day;

     $(this).attr('href', hrefs);
 });
    });
}