$(document).ready(function() {
     setTimeout(function(){ auto_refresh(); },60000);
		$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
		    setTimeout(function(){ auto_refresh(); },60000);	
      });
}); 

  function auto_refresh(){
       $.pjax.reload({container: '#pjaxContentroute'});
	     all_act();
    }