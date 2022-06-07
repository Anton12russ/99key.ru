$(document).ready(function() {
	
too();
$(document).on( 'pjax:success' , function(selector, xhr, status, selector, container) {
	too();
	});
});


function too() {
    $('.too').click(function(){
        $('.input-too').val($(this).attr('data-id'));
		$('.textarea-too').val('Ответить: '+$(this).attr('data-name')+' \n');
		$('.textarea-too').focus();
		
    });
	

	$('.textarea-too').keyup(function(){
		if ($('.textarea-too').val().indexOf('Ответить:') > -1) {}else{
			$('.input-too').val('');
		}
	});
		
}