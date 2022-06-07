$(document).ready(function() {

	dickount();
$('.mail-send').click(function(){
		$('.chat-body').slideToggle();
	
			$('.mess-online').removeClass('vid-mess');
			$('.click_chat').css('width','auto');
		    $('.chat-body iframe').attr('src',$('.click_chat').attr('data-href'));
			$('.chat').attr('id','draggable');
			$('.move_chat').css('display','block');
			$('#draggable').draggable();
	
	})
	
	$('.one-phone').click(function(){
		var tel = $(this).attr('data-phone');
		tel= tel.replace(")", "");
		tel= tel.replace("(", "");
		tel= tel.replace("-", "");
		tel= tel.replace("-", "");
		tel= tel.replace(" ", "");
		tel= tel.replace(" ", "");
    $('.one-phone').wrap('<a href="tel:'+tel+'"></a>');
			//alert($(this).attr('data-phone'));

	       
	});
}); 
  
function dickount(){
	 $('.checkbox-other input').on('click', function(){     
	   var price = $('.count').attr('data-dickount');
	  var dickount = $('.count').attr('data-price');
	 
	 
    if($(this).prop("checked")) {  
	

	  
      $('.count').each(function () {
		  $(this).stop();
	       var chis = $(this).text();
		  
           $(this).prop('Counter', chis.replace(' ','')).animate({
           Counter: $(this).attr('data-dickount')
             }, {
                duration: 1500,
                easing: 'swing',
                step: function (now) {
                $(this).text(Math.ceil(now));
            }
          });
        });
		$('.car-add').attr('data-price',price);
		   
        } else {
          
		
               $('.count').each(function () {
				   $(this).stop();
	               var chis3 = $(this).attr('data-price');
	               var chis2 =  $(this).attr('data-dickount');

                   $(this).prop('Counter', chis2.replace(' ','')).animate({
                          Counter: chis3.replace(' ','')
                   }, {
                      duration: 1500,
                      easing: 'swing',
                      step: function (now) {
                              $(this).text(Math.ceil(now));
                         }
                      });
                  });
				  $('.car-add').attr('data-price', dickount);
        }
		
		add_cart();
    });
}  


