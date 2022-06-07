
//выводим код календаря
function calendarmain(arr) {

var eventDates = arr.map(a => (a = new Date(a), a.setHours(0,0,0,0), a.getTime())),
    $picker = $('.calendarmain');
 minDate: new Date(),
$picker.datepicker({
dateFormat: 'yyyy-mm-dd',
	// minDate: new Date(),
    onRenderCell: function (date, cellType) {
        var currentDate = date.getTime();
        if (cellType == 'day' && eventDates.indexOf(currentDate) != -1) {
            return {
                classes: "dp-note"
            }
        }
    },
	
	  onSelect : function(dateText, el ){
   var region = $('.region_act').attr('data-regionname');
   if(region) {
		location.href = "/passanger?date="+dateText+'&ot='+region.replace('Выбрать регион',''); 
   }else{
	   location.href = "/passanger?date="+dateText; 
   }
		
    }
})






 
}
/*
function cl() {

$('.dp-note').click(function(){
		alert('awdawd');
	alert($(this).attr('data-year')+'-'+$(this).attr('data-month')+'-'+$(this).attr('data-date'));
 });
}*/