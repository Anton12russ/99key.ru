$(document).ready(function() {
   $('.text-art img').removeAttr('height');
   $('.text-art img').removeAttr('style');
  /* var is = 0;
	$('.text-art img').each(function(i,elem) {
		is++;
      $(this).before("<a class='class"+is+"' href='"+$(this).attr('src')+"'></a>");
	  $(this).clone().appendTo(".class"+is);
	  $(this).remove();
	  
   }); 

   */
   
    like();

	$(".text-art img").click(function(){	// Событие клика на маленькое изображение
	  	var img = $(this);	// Получаем изображение, на которое кликнули
		var src = img.attr('src'); // Достаем из этого изображения путь до картинки
		$("body").append("<div class='popup'>"+ //Добавляем в тело документа разметку всплывающего окна
						 "<div class='popup_bg'></div>"+ // Блок, который будет служить фоном затемненным
						 "<img src="+src+" class='popup_img' />"+ // Само увеличенное фото
						 "<span class='close-s'><i class='fa fa-window-close-o' aria-hidden='true'></i></span></div>"); 
		$(".popup").fadeIn(400); // Медленно выводим изображение
		$(".popup_bg").click(function(){	// Событие клика на затемненный фон	   
			$(".popup").fadeOut(400);	// Медленно убираем всплывающее окно
			setTimeout(function() {	// Выставляем таймер
			  $(".popup").remove(); // Удаляем разметку высплывающего окна
			}, 400);
		});
		$(".close-s").click(function(){
				$(".popup").fadeOut(400);	// Медленно убираем всплывающее окно
			setTimeout(function() {	// Выставляем таймер
			  $(".popup").remove(); // Удаляем разметку высплывающего окна
			}, 400);
		});
	});

});

function like() {
	$(".like").click(function(){
	   $.ajax({
        url:  $(this).attr('data-href'), 
        type:     "post", //метод отправки
        dataType: "html", //формат данных
        success: function(response) { //Данные отправлены успешно
		  if (response == 1) {
    	    $("#like").attr('class','fa fa-heart');
			var man = Number($(".manlike").text())+1;
		  }else{
			 $("#like").attr('class','fa fa-heart-crack');
			 var man = Number($(".manlike").text())-1;
		  }
		  $(".manlike").text(man);
		} 
     });
	});
}

