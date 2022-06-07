<?php
  $cod = json_decode($model->cod)->code;		   
  $background ='';
  preg_match_all('#"designId":(.+?),"theme#is', $cod, $arr);
  if($arr[1][0] == 7 || $arr[1][0] == 8 || $arr[1][0] == 9) {
      $background = 'background: #2b303a; padding: 20px; border-radius: 10px;';
  }	 
    $a = '';
	$a_end = '';
//Если есть ссылка  
preg_match_all('#"href":"(.+?)"#is', $cod, $href);
if(isset($href[1][0])) {
	$a = '<a target="_blank" href="'.$href[1][0].'">';
	$a_end = '</a>';
}

//Если есть адаптация  
preg_match_all('#"adapt":"(.+?)"#is', $cod, $adapt);
$adapta = '';
if(isset($adapt[1][0])) {
	$adapta = 'adapt';
}
 echo '
'.$a.'<div style="padding-top: 10px;" margin-top: -10px; class="col-md-12" margin-bottom: -20px;>
<script>
(function() {
    var _id = "'.$model->id_block.'";
    while (document.getElementById("timer" + _id)) _id = _id + "0";
    document.write(\'<div class="timeradapt '.$adapta.' timers-'.$arr[1][0].'"  id="timer\' + _id + \'"   style="min-width:390px;height:72px; '.$background.' "></div>\');
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
					
</script></div>'.$a_end.'' ;
			   ?>