<?php

$js = <<< JS

	var iframe_width = $('html').width();
  $('#wid').text($(document).width());
	//alert($(document).width());
	//$('html').css('zoom','0.6');

JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );


  $cod = json_decode($model->cod)->code;		   
  $background ='';
  preg_match_all('#"designId":(.+?),"theme#is', $cod, $arr);
  if($arr[1][0] == 7 || $arr[1][0] == 8 || $arr[1][0] == 9) {
      $background = 'background: #2b303a; padding: 20px; border-radius: 10px;';
  }	 

  
  
  
    $a = '';
	$a_end = '';
//Если есть ссылка  
preg_match_all('#"href":"(.+?)",#is', $cod, $href);

if($href[1][0] != '#') {
	$a = '<a class="atimer" target="_blank" href="'.$href[1][0].'">';
	$a_end = '</a>';
}

//Если есть адаптация  
preg_match_all('#"adapt":"(.+?)"#is', $cod, $adapt);
$adapta = '';
if(isset($adapt[1][0])) {
	$adapta = 'adapt';
}


//Получаем ширину 
preg_match_all('#"width_cont":(.+?),"#is', $cod, $width);
preg_match_all('#"height_cont":(.+?)}#is', $cod, $height);
$height = $height[1][0]+5;
 echo '
<style>
body {
	    background: none;
}
</style>
'.$a.'
<script>
(function() {
    var _id = "'.$model->id_block.'";

    while (document.getElementById("timer" + _id)) _id = _id + "0";
    document.write(\'<div class="timeradapt  timers-'.$arr[1][0].' '.$adapta.'"  id="timer\' + _id + \'"   style="min-width:'.$width[1][0].'px; height:'.$height.'px; '.$background.' "></div>\');
    var _t = document.createElement("script");
    _t.src = "/../assest_all/timer/v1.js";
    var _f = function(_k) {
        var l = new MegaTimer(_id,
            '. $cod.' );
        if (_k != null) l.run();
    };
    _t.onload = _f;
    _t.onreadystatechange = function() {
        if (_t.readyState == "loaded") _f(1);
    };
    var _h = document.head || document.getElementsByTagName("head")[0];
    _h.appendChild(_t);
}).call(this);
		
		
</script>

'.$a_end;
			   
			   
	   echo '
	   <script language="javascript">

            // прикрепляем обработчик к событию onload
            if ( window.addEventListener ) 
            { 
                window.addEventListener( "load", doLoad, false );
            }
            else if ( window.attachEvent ) 
            { 
                window.attachEvent( "onload", doLoad );
            }
            else if ( window.onLoad ) 
            {
                window.onload = doLoad;
            }
 
            // функция вызывается при загрузке страницы
            function doLoad() 
            {                
                var devicewidth = (window.innerWidth > 0) ? window.innerWidth : screen.width; // ширина устройства в px
                var sourcewidth = document.getElementById("timer'.$model->id_block.'").clientWidth;    
				sourcewidth = sourcewidth+30;
				// текущая ширина масштабируемого элемента
                var maxwidth = devicewidth - getOffsetLeft(document.getElementById("timer'.$model->id_block.'"))*2; // максимальная ширина масштабируемого элемента

                // масштабирование элемента 
                if (devicewidth < '.$width[1][0].' && sourcewidth > maxwidth)
                {   
var wed = maxwidth / sourcewidth;			
$("#timer'.$model->id_block.'").css("transform","scale("+wed+")");
$("#timer'.$model->id_block.'").css("transform-origin","left top");
$("#timer'.$model->id_block.'").css("width","calc(100% / "+wed+")");
$("#timer'.$model->id_block.'").css("overflow","hidden");


var hei = $("#timer'.$model->id_block.'")[0].getBoundingClientRect().height;
$("html").css("height", hei+"px");
$("html").css("overflow","hidden");
              /*   document.getElementById("timer'.$model->id_block.'").style.transform ="scale("+wed+")";
				 document.getElementById("timer'.$model->id_block.'").style.width = "calc(100% / "+wed+")";
				 document.getElementById("timer'.$model->id_block.'").style.overflow = "hidden";
				 document.getElementById("timer'.$model->id_block.'").style.transformOrigin = "left top";*/
                }       
            }
 
            // смещение элемента слева относительно страницы
            function getOffsetLeft( elem )
            {
                var offsetLeft = 0;
                do {
                  if ( !isNaN( elem.offsetLeft ) )
                  {
                      offsetLeft += elem.offsetLeft;
                  }
                } while( elem = elem.offsetParent );
                return offsetLeft;
            }

</script>

	   ';
			  