<script>
(function(){var _id="dbefb93e8abe309cd616da9a0e6970b4";
while(document.getElementById("timer"+_id))_id=_id+"0";
document.write("<div id='timer"+_id+"' style='min-width:605px;height:86px;'></div>");
var _t=document.createElement("script");
_t.src="/../assest_all/timer/v1.js";
var _f=function(_k){
	var l=new MegaTimer(_id, {
"view": [1, 1, 1, 1],
			"type": {
				"currentType": "1",
				"params": {
					"usertime": true,
					"tz": "3",
					"utc": 1614297600000
				}
			},
		"design":{"type":"plate","params":{
			"round":"10",
			"background":"gradient",
			"background-color":["#000000","#ababab"],
			"effect":"flipchart",
			"space":"2",
			"separator-margin":"20",
			"number-font-family":{
				"family":"Comfortaa",
				"link":"<link href='//fonts.googleapis.com/css?family=Comfortaa&subset=latin,cyrillic' rel='stylesheet' type='text/css'>"},
				"number-font-size":"60",
				"number-font-color":"#ffffff",
				"padding":"12",
				"separator-on":false,
				"separator-text":":",
				"text-on":false,
				"text-font-family":{
					"family":"Comfortaa",
					"link":"<link href='//fonts.googleapis.com/css?family=Comfortaa&subset=latin,cyrillic' rel='stylesheet' type='text/css'>"},
					"text-font-size":"12","text-font-color":"red"}},
					"designId":3,
					"theme":"white",
					"width":605,
					"height":86});
	if(_k!=null)l.run();};_t.onload=_f;_t.onreadystatechange=function(){if(_t.readyState=="loaded")_f(1);};
	var _h=document.head||document.getElementsByTagName("head")[0];_h.appendChild(_t);}).call(this);

	
</script>
<!--
<script>
(function(){var _id="dbefb93e8abe309cd616da9a0e6970b4";
while(document.getElementById("timer"+_id))_id=_id+"0";
document.write("<div id='timer"+_id+"' style='min-width:605px;height:86px;'></div>");
var _t=document.createElement("script");
_t.src="/../assest_all/timer/v1.js";
var _f=function(_k){
	var l=new MegaTimer(_id, {"view":[1,1,1,1],"type":{
		"currentType":"2",
		"params":{"startByFirst":true,"days":"1","hours":"1","minutes":"1","utc":1613606400000}},
		"design":{"type":"plate","params":{
			"round":"10",
			"background":"gradient",
			"background-color":["#000000","#ababab"],
			"effect":"flipchart",
			"space":"2",
			"separator-margin":"20",
			"number-font-family":{
				"family":"Comfortaa",
				"link":"<link href='//fonts.googleapis.com/css?family=Comfortaa&subset=latin,cyrillic' rel='stylesheet' type='text/css'>"},
				"number-font-size":"60",
				"number-font-color":"#ffffff",
				"padding":"12",
				"separator-on":false,
				"separator-text":":",
				"text-on":false,
				"text-font-family":{
					"family":"Comfortaa",
					"link":"<link href='//fonts.googleapis.com/css?family=Comfortaa&subset=latin,cyrillic' rel='stylesheet' type='text/css'>"},
					"text-font-size":"12","text-font-color":"red"}},
					"designId":3,
					"theme":"white",
					"width":605,
					"height":86});
	if(_k!=null)l.run();};_t.onload=_f;_t.onreadystatechange=function(){if(_t.readyState=="loaded")_f(1);};
	var _h=document.head||document.getElementsByTagName("head")[0];_h.appendChild(_t);}).call(this);
	
	
	
</script>

-->
<script src="/../assest_all/timer/settings.min.js"></script>

<script src="/../assest_all/timer/timer.min.js"></script>


<script>
var now = new Date();
// Time to Month end
window.showDate = new Date(now.getFullYear(), now.getMonth() + 1, 1);
window.timeToEndOfMonth = showDate.getTime() - now.getTimezoneOffset() * 60000;
var initParams = { type: { currentType: 1, params: { utc: timeToEndOfMonth, usertime: true } }, design: designs[1] };
window.lptimer = new MegaTimer('Preview',initParams);
</script>
<?php




//$this->registerJsFile('/../assest_all/timer/timer.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile('/../assest_all/timer/settings.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);


//$this->registerJsFile('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/../assest_all/timer/editor.min.js?v=1', ['depends' => [\yii\web\JqueryAsset::className()]]);
/*
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;


$this->registerJsFile('https://browser.sentry-cdn.com/5.4.0/bundle.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<< JS
Sentry.init({dsn:'https://6a631da3c58d485c87dca56fa55a8fb0@sentry.lpmtr.net/3'});
JS;
$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );


$this->registerJsFile('/assest_all/timer/timer.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('/../assest_all/timer/editor.min.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile('/assest_all/timer/settings.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);




$js = <<< JS
var now = new Date();
// Time to Month end
window.showDate = new Date(now.getFullYear(), now.getMonth() + 1, 1);
window.timeToEndOfMonth = showDate.getTime() - now.getTimezoneOffset() * 60000;
var initParams = { type: { currentType: 1, params: { utc: timeToEndOfMonth, usertime: true } }, design: designs[1] };
window.lptimer = new MegaTimer('Preview',initParams);
JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );

$this->registerJsFile('/assest_all/timer/editor.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
*/
?>






  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="/../assest_all/timer/editor.min.css?2" rel="stylesheet">
    





<div class="fixed-block">
    <div class="preview-block">
        <div class="switch-background">
            <div class="switch-background-button white"></div>
            <div class="switch-background-button black"></div>
        </div>

        <div class="switch-elements">
            <!--<div class="switch-title">??????????????:</div>-->
            <div class="">
                <label><input type="checkbox" name="view[0]" checked="checked"> ??????</label>
            </div>
            <div class="">
                <label><input type="checkbox" name="view[1]" checked="checked"> ????????</label>
            </div>
            <div class="">
                <label><input type="checkbox" name="view[2]" checked="checked"> ????????????</label>
            </div>
            <div class="">
                <label><input type="checkbox" name="view[3]" checked="checked"> ??????????????</label>
            </div>
        </div>
        <div class="timerPreviewContainer">
            <div class="timerPreviewWrapper">
                <div id="timerPreview"></div>
            </div>
        </div>
    </div>

    <ul class="tabs-title" id="main-nav">
        <div class="tabs-title-wrap">
            <li class="active" data-id="1" title="???????????????? ?????????????????????? ?????? ??????????????">1. ?????? ??????????????</li>
            <li data-id="2" title="?????????????????? ?????????????????????? ???????????? ?? ???????????? ??????????????">2. ????????????</li>
            <li data-id="3" title="?????????????????? ???????????? ???????????????? ?????? ???????????????????? ???????????? ??????????">3. ??????????????????<span class="hidden">?????????? ?????????????? ????????, ?????????? ?????????????????? ?????????????????? ????????????</span></li>
            <li data-id="4" title="???????????????????? ?????? ?????????????? ?? ???????????????? ?????? ???? ????????">4. ???????????????? ??????</li>

            <div style="clear: both"></div>
        </div>
    </ul>

    <div id="pin"></div>

</div>

<div class="container">

    <div class="timer-editor">

        <!-- STEP 1 -->
        <div id="step-1" class="step">

            <div class="step-1-examples">
                <h4>??????????????:</h4>
                <a href="#" class="example-action" data-id="1">?????????? ???????????? ???????? ?? 0 ??????????, ?????????? ?????????? 24 ????????</a>

                <a href="#" id="more-examples">???????????? ??????????????</a>
                <div class="more-examples" style="display: none;">
                    <a href="#" class="example-action" data-id="2">?????????? ???????????? ???????? ?? 2 ???????? ????????, ???????????? ???? 12 ????????</a>
                    <a href="#" class="example-action" data-id="3">?????????? ?? 19 ??????????, ?????????????????? ?? 21 ??????, ???? ?????????????????? ???????? ??????????????????????</a>
                    <a href="#" class="example-action" data-id="5">???????????????? ???????????? ???? 1 ????????????</a>
                    <a href="#" class="example-action" data-id="6">???????????? ???????????????? ?? 9 ?????????? ???? 21 ????????</a>
                    <a href="#" class="example-action" data-id="7">10 ?????????? ?? 5 ??????????, ?????????????? ?? ???????????????? ??????????????</a>
                    <a href="#" id="hide-examples">????????????????</a>
                </div>
            </div>

            <div class="panels-block mt4">
                <div class="panels-wrapper clearfix">
                    <div class="panel panel-primary fl panel-success" data-id="1">
                        <div class="panel-body">
                            ???? ???????????????????????? ????????
                        </div>
                    </div>



                    <div class="panel panel-primary fr" data-id="3">
                        <div class="panel-body">
                            ??????????????????
                        </div>
                    </div>

                    <div class="panel panel-primary mha" data-id="2">
                        <div class="panel-body">
                            ???? ???????????????????? ??????????????
                        </div>
                    </div>
                </div>

                <div class="timer-type-liner clearfix">
                    <div class="block fl" data-id="1"><div class="line"></div><div class="arrow-down"></div></div>
                    <div class="block fr" data-id="3" style="visibility: hidden;"><div class="line"></div><div class="arrow-down"></div></div>
                    <div class="block mha" data-id="2" style="visibility: hidden;"><div class="line"></div><div class="arrow-down"></div></div>
                </div>

                <input type="hidden" value="1" name="types[currentType]" id="type-currentType"/>

                <div id="timer-type-wrap" class="clearfix ">
                    <div id="timer-type-1" class="fl timer-type timer-type-top design-inputs">
                        <!-- ???? ???????????????????????? ???????? -->
                        <table class="vat mt2">
                            <tr>
                                <td class="pr2">
                                    <span class="type-title">?????????? ??????????????:</span>
                                </td>
                                <td>
                                    <table class="vam design-inputs">
                                        <tr>
                                            <td><input type="text" value="" name="types[1][date]" id="type-1-date"/></td>
                                            <td style="padding: 0 5px;">??</td>
                                            <td><input type="text" value="" name="types[1][time]" id="type-1-time" placeholder="15:00" maxlength="5"/></td>
                                        </tr>
                                    </table>
                                    <div class="mt2">
                                        <label class="local-time no_sel">
                                            <input type="checkbox" checked="checked" name="types[1][usertime]" id="type-1-usertime">
                                            ???? ???????????????????? ?????????????? ????????????????????????
                                        </label>

                                        <div class="">
                                            <select name="types[1][tz]" id="type-1-tz" disabled="disabled">
                                                <option value="1">(UTC+1) ??????????</option>
                                                <option value="2">(UTC+2) ??????????????????????</option>
                                                <option value="3" selected="selected">(UTC+3) ????????????</option>
                                                <option value="4">(UTC+4) ????????????</option>
                                                <option value="5">(UTC+5) ????????????????????????</option>
                                                <option value="6">(UTC+6) ????????</option>
                                                <option value="7">(UTC+7) ????????????????????</option>
                                                <option value="8">(UTC+8) ??????????????</option>
                                                <option value="9">(UTC+9) ????????????</option>
                                                <option value="10">(UTC+10) ??????????????????????</option>
                                                <option value="11">(UTC+11) ??????????????????????????</option>
                                                <option value="12">(UTC+12) ????????????????</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div id="timer-type-3" class="timer-type fr" style="display: none;">
                        <!-- ?????????????????? -->
                        <table class="vat mt2">
                            <tr>
                                <td class="pr2"><span class="type-title">???????????? ??????????????:</span></td>
                                <td>
                                    <table class="vam">
                                        <tr>
                                            <td>
                                                <a href="#" class="weekdays">
                                                <span id="weekdays-title">???????????? ????????</span>
                                                <span class="weekdays-list">
                                                    <label>
                                                        <input type="checkbox" name="types[3][weekdays]" value="0"
                                                               checked="checked"/>
                                                        ??????????????????????
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="types[3][weekdays]" value="1"
                                                               checked="checked"/>
                                                        ??????????????
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="types[3][weekdays]" value="2"
                                                               checked="checked"/>
                                                        ??????????
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="types[3][weekdays]" value="3"
                                                               checked="checked"/>
                                                        ??????????????
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="types[3][weekdays]" value="4"
                                                               checked="checked"/>
                                                        ??????????????
                                                    </label>
                                                    <label class="weekend">
                                                        <input type="checkbox" name="types[3][weekdays]" value="5"
                                                               checked="checked"/>
                                                        ??????????????
                                                    </label>
                                                    <label class="weekend">
                                                        <input type="checkbox" name="types[3][weekdays]" value="6"
                                                               checked="checked"/>
                                                        ??????????????????????
                                                    </label>
                                                </span>
                                                </a>
                                            </td>
                                            <td style="padding: 0 5px;">??</td>
                                            <td class="design-inputs">
                                                <input type="text" value="" name="types[3][time]" id="type-3-time" class="c_text" placeholder="15:00"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="mt1 design-inputs">
                                        <label class="local-time no_sel">
                                            <input type="checkbox" checked="checked" name="types[3][usertime]" id="type-3-usertime">
                                            ???? ???????????????????? ?????????????? ????????????????????
                                        </label>

                                        <div class="">
                                            <select name="types[3][tz]" id="type-3-tz" disabled="disabled">
                                                <option value="1">(UTC+1) ??????????</option>
                                                <option value="2">(UTC+2) ??????????????????????</option>
                                                <option value="3" selected="selected">(UTC+3) ????????????</option>
                                                <option value="4">(UTC+4) ????????????</option>
                                                <option value="5">(UTC+5) ????????????????????????</option>
                                                <option value="6">(UTC+6) ????????</option>
                                                <option value="7">(UTC+7) ????????????????????</option>
                                                <option value="8">(UTC+8) ??????????????</option>
                                                <option value="9">(UTC+9) ????????????</option>
                                                <option value="10">(UTC+10) ??????????????????????</option>
                                                <option value="11">(UTC+11) ??????????????????????????</option>
                                                <option value="12">(UTC+12) ????????????????</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="pt2 pr2"><span class="type-title">????????????????????????:</span></td>
                                <td class="pt2">
                                    <table class="design-inputs tbl-duration">
                                        <tr>
                                            <td class="pr1"><input type="text" class="date-item" name="types[3][hours]" id="type-3-hours" placeholder="" value="1"></td>
                                            <td class="pr1"><input type="text" class="date-item" name="types[3][minutes]" id="type-3-minutes" placeholder="" value="1"></td>
                                        </tr>
                                        <tr>
                                            <td class="pr1 c_text"><span>??????????</span></td>
                                            <td class="pr1 c_text"><span>??????????</span></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>
                    </div>

                    <div id="timer-type-2" class="timer-type mha" style="display: none;">
                        <!-- ???? ???????????????????? ?????????????? -->
                        <table class="vat mt2">
                            <tr>
                                <td class="pr2">
                                    <span class="type-title">???????????? ??????????????:</span>
                                </td>
                                <td>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="types[2][start]" id="type-2-first_enter" value="first_enter" checked>
                                            ?? ?????????????? ?????????????????? ????????????????
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="types[2][start]" id="type-2-just_now" value="just_now">
                                            ?????????? ????????????
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="pt2 pr2">
                                    <span class="type-title">????????????????????????:</span>
                                </td>
                                <td class="pt2">
                                    <table class="design-inputs tbl-duration">
                                        <tr>
                                            <td class="pr1"><input type="text" class="date-item" name="types[2][days]" id="type-2-days" placeholder="" value="1"></td>
                                            <td class="pr1"><input type="text" class="date-item" name="types[2][hours]" id="type-2-hours" placeholder="" value="1"></td>
                                            <td class="pr1"><input type="text" class="date-item" name="types[2][minutes]" id="type-2-minutes" placeholder="" value="1" maxlength="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="pr1 c_text"><span>????????</span></td>
                                            <td class="pr1 c_text"><span>??????????</span></td>
                                            <td class="pr1 c_text"><span>??????????</span></td>
                                        </tr>
                                    </table>
                                    <!--<div class="clearfix">-->
                                        <!--<div class="date-item">-->
                                            <!---->
                                            <!---->
                                        <!--</div>-->
                                        <!--<div class="date-item">-->
                                            <!---->
                                            <!---->
                                        <!--</div>-->
                                        <!--<div class="date-item">-->
                                            <!---->
                                            <!---->
                                        <!--</div>-->
                                    <!--</div>-->
                                </td>
                            </tr>
                        </table>

                        <!--<div class="central-block timer-type-top">-->
                            <!--<div class="timer-type-block">-->

                            <!--</div>-->
                            <!--<div class="timer-type-block">-->

                            <!--</div>-->
                        <!--</div>-->

                        <!--<div class="central-block">-->
                            <!--<div class="timer-type-block">-->

                            <!--</div>-->
                            <!--<div class="timer-type-block">-->

                            <!--</div>-->
                        <!--</div>-->
                        <!--<div class="clear"></div>-->

                    </div>

                </div>
            </div>
        </div>
        <!-- END OF STEP 1 -->

        <!-- STEP 2 -->
        <div id="step-2" class="step" style="display: none;">
            <div id="design-filter">
                <label>
                    <input type="checkbox" checked="checked" data-id="text">
                    ??????????
                </label>

                <label>
                    <input type="checkbox" checked="checked" data-id="circle">
                    ????????
                </label>

                <label>
                    <input type="checkbox" checked="checked" data-id="plate">
                    ????????????????
                </label>
            </div>

            <div class="design-list clearfix">
            </div>
        </div>
        <!-- END OF STEP 2 -->

        <!-- STEP 3 -->
        <div id="step-3" class="step design-inputs" style="display: none;">

            <input type="hidden" value="text" id="design-type"/>

            <!-- ?????????? -->
            <div data-type="text" class="design-setting-group" style="display: block;">

                <!--<div class="ds-line clearfix">
                    <div class="ds-left">
                        ?????? ??????????????:
                    </div>
                    <div class="ds-right ds-design-type">
                        ??????????????????
                    </div>
                </div>-->

                <!-- ?????????? -->

                <div class="ds-line clearfix">
                    <div class="ds-left">
                        <strong>??????????</strong>
                    </div>

                </div>

                <div class="ds-line clearfix">
                    <div class="ds-left align-right">??????????:</div>
                    <div class="ds-right">
                        <select class="font-family-selector" name="design[text][number-font-family]"></select>
                        <div class="range-tooltips">
                            <input type="number" name="design[text][number-font-size]">
                            <span>
                                <input type="range" min=10 max=120  step="1" class="sync">
                            </span>
                        </div>
                        px
                        <input type="text" value="black" style="background-color: #000;" name="design[text][number-font-color]" class="color-control" data-id="1">
                    </div>
                </div>

                <div class="ds-line clearfix">
                    <div class="ds-left align-right">
                        ????????????:
                    </div>
                    <div class="ds-right">
                        <input type="range" min=0 max=100 value=20 step="1" name="design[text][separator-margin]">
                    </div>
                </div>

                <!-- ?????????????????????? -->

                <div class="ds-line clearfix">
                    <div class="ds-left">
                        <strong>??????????????????????</strong>
                    </div>
                    <div class="ds-right">
                        <div class="ui toggle checkbox">
                            <input type="checkbox" name="design[text][separator-on]">
                        </div>
                    </div>
                </div>

                <div class="ds-line clearfix">
                    <div class="ds-left align-right">
                        ??????????:
                    </div>
                    <div class="ds-right">
                        <input type="text" name="design[text][separator-text]" class="separator-text" maxlength="10">
                    </div>
                </div>

                <!-- ?????????????? -->

                <div class="ds-line clearfix">
                    <div class="ds-left">
                        <strong>??????????????</strong>
                    </div>
                    <div class="ds-right">
                        <div class="ui toggle checkbox">
                            <input type="checkbox" name="design[text][text-on]">
                        </div>
                    </div>
                </div>

                <div class="ds-line clearfix">
                    <div class="ds-left align-right">
                        ??????????
                    </div>
                    <div class="ds-right">
                        <select class="font-family-selector" name="design[text][text-font-family]"></select>
                        <div class="range-tooltips">
                            <input type="number" name="design[text][text-font-size]">
                            <span>
                                <input type="range" min=10 max=120  step="1" class="sync">
                            </span>
                        </div>
                        px
                        <input type="text" value="black" style="background-color: #000;" name="design[text][text-font-color]" class="color-control" data-id="1">
                    </div>
                </div>
            </div >

            <!-- ???????????????? -->
            <div data-type="plate" class="design-setting-group" style="display: none;">

                <!--<div class="ds-line clearfix">
                    <div class="ds-left">
                        ?????? ??????????????:
                    </div>
                    <div class="ds-right ds-design-type">????????????????</div>
                </div>-->

                <table class="vat">
                    <tr>
                        <td>

                            <!-- ???????????????? -->

                            <div class="ds-line clearfix">
                                <div class="ds-left">
                                    <strong>?????? ????????????????</strong>
                                </div>
                                <div class="ds-right">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ?????????????????????? ??????????
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=0 max=60 value=10 step="1" name="design[plate][round]">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ??????
                                </div>
                                <div class="ds-right">
                                    <select data-id="1" class="smart-color-selector" name="design[plate][background]">
                                        <option value="solid" selected="selected">????????????????</option>
                                        <option value="gradient">????????????????</option>
                                        <option value="opacity">????????????????????</option>
                                    </select>

                                    <div data-id="1" class="smart-color-selector-solid smart-color-selector-block">
                                        <input type="text" value="#000" style="background-color: #000;" name="design[plate][background-color]" class="color-control" data-id="1">
                                    </div>

                                    <div data-id="1" class="smart-color-selector-gradient smart-color-selector-block" style="display: none;">
                                        <input type="text" value="#fff" style="background-color: #fff;" name="design[plate][background-color][0]" class="color-control" data-id="1">
                                        <input type="text" value="#000" style="background-color: #000;" name="design[plate][background-color][1]" class="color-control" data-id="1">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ????????????
                                </div>
                                <div class="ds-right">
                                    <select class="" name="design[plate][effect]">
                                        <option value="none">??????</option>
                                        <option value="flipchart">????????????????????????????</option>
                                        <option value="slide">??????????????????</option>
                                    </select>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ???????????? ????????????????
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=0 max=30 value=2 step="1" name="design[plate][space]">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ????????????
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=0 max=100 value=20 step="1" name="design[plate][separator-margin]">
                                </div>
                            </div>
                        </td>
                        <td>
                            <!-- ?????????? -->

                            <div class="ds-line clearfix">
                                <div class="ds-left">
                                    <strong>??????????</strong>
                                </div>

                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">??????????:</div>
                                <div class="ds-right">
                                    <select class="font-family-selector" name="design[plate][number-font-family]"></select>
                                    <div class="range-tooltips">
                                        <input type="number" name="design[plate][number-font-size]">
                                        <span>
                                            <input type="range" min=10 max=120  step="1" class="sync">
                                        </span>
                                    </div>
                                    px
                                    <input type="text" value="black" style="background-color: #000;" name="design[plate][number-font-color]" class="color-control" data-id="1">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ????????????:
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=0 max=60 value=20 step="1" name="design[plate][padding]">
                                </div>
                            </div>

                            <!-- ?????????????????????? -->

                            <div class="ds-line clearfix ds-line-sep">
                                <div class="ds-left">
                                    <strong>??????????????????????</strong>
                                </div>
                                <div class="ds-right">
                                    <div class="ui toggle checkbox">
                                        <input type="checkbox" name="design[plate][separator-on]">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">??????????:</div>
                                <div class="ds-right">
                                    <input type="text" name="design[plate][separator-text]" class="separator-text" maxlength="10">
                                </div>
                            </div>

                            <!-- ?????????????? -->

                            <div class="ds-line clearfix ds-line-sep">
                                <div class="ds-left">
                                    <strong>??????????????</strong>
                                </div>
                                <div class="ds-right">
                                    <div class="ui toggle checkbox">
                                        <input type="checkbox" name="design[plate][text-on]">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">??????????:</div>
                                <div class="ds-right">
                                    <select class="font-family-selector" name="design[plate][text-font-family]"></select>
                                    <div class="range-tooltips">
                                        <input type="number" name="design[plate][text-font-size]">
                                        <span>
                                            <input type="range" min=10 max=120  step="1" class="sync">
                                        </span>
                                    </div>
                                    px
                                    <input type="text" value="black" style="background-color: #000;" name="design[plate][text-font-color]" class="color-control" data-id="1">
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>



            </div>

            <!-- ?????????? -->
            <div data-type="circle" class="design-setting-group" style="display: none;">

                <!--<div class="ds-line clearfix">
                    <div class="ds-left">
                        ?????? ??????????????:
                    </div>
                    <div class="ds-right ds-design-type">????????????????</div>
                </div>-->

                <table class="vat">
                    <tr>
                        <td style="width: 50%;">
                            <div class="ds-line clearfix">
                                <div class="ds-left">
                                    <strong>??????????</strong>
                                </div>
                                <div class="ds-right">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ?????????????? ??????????:
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=1 max=50 value=10 step="1" name="design[circle][width]">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ???????????? ??????????:
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=1 max=100 value=10 step="1" name="design[circle][radius]">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ???????? ??????????:
                                </div>
                                <div class="ds-right">
                                    <select data-id="2" class="smart-color-selector" name="design[circle][line]" style="width: 120px;">
                                        <option value="solid" selected="selected">????????????????</option>
                                        <option value="gradient">????????????????</option>
                                    </select>

                                    <div data-id="2" class="smart-color-selector-solid smart-color-selector-block">
                                        <input type="text" value="#000" style="background-color: #000;" name="design[circle][line-color]" class="color-control" data-id="1">
                                    </div>

                                    <div data-id="2" class="smart-color-selector-gradient smart-color-selector-block" style="display: none;">
                                        <input type="text" value="#fff" style="background-color: #fff;" name="design[circle][line-color][0]" class="color-control hide-opacity" data-id="1">
                                        <input type="text" value="#000" style="background-color: #000;" name="design[circle][line-color][1]" class="color-control hide-opacity" data-id="1">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ???????? ????????:
                                </div>
                                <div class="ds-right">

                                    <select data-id="3" class="smart-color-selector" name="design[circle][background]" style="width: 120px;">
                                        <option value="solid" selected="selected">????????????????</option>
                                        <option value="gradient">????????????????</option>
                                        <option value="opacity">????????????????????</option>
                                    </select>

                                    <div data-id="3" class="smart-color-selector-solid smart-color-selector-block">
                                        <input type="text" value="#000" style="background-color: #000;" name="design[circle][background-color]" class="color-control" data-id="1">
                                    </div>

                                    <div data-id="3" class="smart-color-selector-gradient smart-color-selector-block" style="display: none;">
                                        <input type="text" value="#fff" style="background-color: #fff;" name="design[circle][background-color][0]" class="color-control hide-opacity" data-id="1">
                                        <input type="text" value="#000" style="background-color: #000;" name="design[circle][background-color][1]" class="color-control hide-opacity" data-id="1">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ??????????????????????:
                                </div>
                                <div class="ds-right">
                                    <select class="" name="design[circle][direction]">
                                        <option value="direct">???? ?????????????? ??????????????</option>
                                        <option value="back">???????????? ?????????????? ??????????????</option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td style="width: 50%">
                            <!-- ?????????? -->

                            <div class="ds-line clearfix">
                                <div class="ds-left">
                                    <strong>??????????</strong>
                                </div>

                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">??????????:</div>
                                <div class="ds-right">
                                    <select class="font-family-selector" name="design[circle][number-font-family]"></select>
                                    <div class="range-tooltips">
                                        <input type="number" name="design[circle][number-font-size]">
                                        <span>
                                            <input type="range" min=10 max=120  step="1" class="sync">
                                        </span>
                                    </div>
                                    px
                                    <input type="text" value="black" style="background-color: #000;" name="design[circle][number-font-color]" class="color-control" data-id="1">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ????????????:
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=0 max=100 value=20 step="1" name="design[circle][separator-margin]">
                                </div>
                            </div>

                            <!-- ?????????????????????? -->

                            <div class="ds-line clearfix ds-line-sep">
                                <div class="ds-left">
                                    <strong>??????????????????????</strong>
                                </div>
                                <div class="ds-right">
                                    <div class="ui toggle checkbox">
                                        <input type="checkbox" name="design[circle][separator-on]">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ??????????:
                                </div>
                                <div class="ds-right">
                                    <input type="text" name="design[circle][separator-text]" class="separator-text" maxlength="10">
                                </div>
                            </div>

                            <!-- ?????????????? -->

                            <div class="ds-line clearfix ds-line-sep">
                                <div class="ds-left">
                                    <strong>??????????????</strong>
                                </div>
                                <div class="ds-right">
                                    <div class="ui toggle checkbox">
                                        <input type="checkbox" name="design[circle][text-on]">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    ??????????:
                                </div>
                                <div class="ds-right">
                                    <select class="font-family-selector" name="design[circle][text-font-family]"></select>
                                    <div class="range-tooltips">
                                        <input type="number" name="design[circle][text-font-size]">
                                        <span>
                                            <input type="range" min=10 max=120  step="1" class="sync">
                                        </span>
                                    </div>
                                    px
                                    <input type="text" value="black" style="background-color: #000;" name="design[circle][text-font-color]" class="color-control" data-id="1">
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
        <!-- END OF STEP 3 -->

        <!-- STEP 4 -->
        <div id="step-4" class="step design-setting-group" style="display: none;">
            <div class="output-block" id="output-block" style="display: none">
                <div>???????? ?????? ???????????????? ?? ????????, ?????? ?????????? ???????????????????? ???????? ????????????:</div>
                <!--<textarea id="output-code-0" readonly onclick="this.select();" rows="3" style="resize: none"></textarea>-->
                <div style="margin-top: 3px;"><input type="text" id="output-code-0" readonly onclick="this.select();"/></div>

                <!--<div class="mt4 email-link">-->
                    <!--<a href="#">?????????????????? ?????? ???? email</a>-->
                <!--</div>-->
            </div>

            <div class="output-block" id="email" style="display: none;">
                <span class="mr1">?????????????????? ?????? ???? email:</span>
                <input type="text" value="" placeholder="?????????????? email" id="email-field">
                <button type="button" class="button" id="send-email">??????????????????</button>
                <div class="message"></div>
            </div>


            <div class="output-block" id="code-changed" style="display: none;">
                <button type="button" class="button green" id="code-changed-create">?????????????? ?????????? ??????</button>
                <!--<button type="button" class="button grey" id="code-changed-update">???????????????? ?????????????? ??????</button>-->
            </div>

        </div>
        <!-- END OF STEP 4 -->



    </div>

    <div class="buttons">
        <button type="button" class="button" id="next-step">?????????????????? ?????? >></button>
    </div>


</div>

<!--
<script src="/../assest_all/timer/settings.min.js"></script>
<script language="JavaScript">
var now = new Date();
// Time to Month end
window.showDate = new Date(now.getFullYear(), now.getMonth() + 1, 1);
window.timeToEndOfMonth = showDate.getTime() - now.getTimezoneOffset() * 60000;
var initParams = { type: { currentType: 1, params: { utc: timeToEndOfMonth, usertime: true } }, design: designs[1] };
window.lptimer = new MegaTimer('Preview',initParams);
</script>
<!--
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="/../assest_all/timer/editor.min.js?v=1"></script>
-->



</body>
</html>

