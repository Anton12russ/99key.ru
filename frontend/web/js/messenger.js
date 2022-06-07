$(document).ready(function() {
        all_act();
		setTimeout(function(){ auto_refresh(); },20000);
		$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
		       setTimeout(function(){ auto_refresh(); },20000);	
		       all_act();
        });
}); 


function all_act() {
      tops();
      loader_content();
}


  function auto_refresh(){
       $.pjax.reload({container: '#pjaxMess'});
	     all_act();
    }


 function tops() {
	  var block = document.getElementById("top");
       block.scrollTop = 99999;
 }
 



 
  //Доп функция
  function loader_content() {
	$('.loads').click(function(){ 
       window.open($(this).attr('data-href'), "Переписка", "width=500, height=500");
   });

}
