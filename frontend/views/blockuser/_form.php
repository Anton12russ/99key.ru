


<script src="/../assest_all/timeruser/settings.min.js"></script>

<script src="/../assest_all/timeruser/timer.min.js"></script>


<script>
var now = new Date();
// Time to Month end
window.showDate = new Date(now.getFullYear(), now.getMonth() + 1, 1);
window.timeToEndOfMonth = showDate.getTime() - now.getTimezoneOffset() * 60000;
var initParams = { type: { currentType: 1, params: { utc: timeToEndOfMonth, usertime: true } }, design: designs[1] };
window.lptimer = new MegaTimer('Preview',initParams);
</script>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;

$this->registerJsFile('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/../assest_all/timeruser/editor.min.js?v=1', ['depends' => [\yii\web\JqueryAsset::className()]]);
/* @var $this yii\web\View */
/* @var $model common\models\Block */
/* @var $form yii\widgets\ActiveForm */

$this->registerCssFile('/css/add.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerJsFile(Url::home(true).'js/category2.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<style>
#w0 {
 width: 100%;
}
</style>
<div class="block-form">

    <?php $form = ActiveForm::begin(); ?>
	
	



    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<br>
<div class="col-md-12  text-right" style="margin-bottom: 20px;">
   <span class="btn btn-primary" data-toggle="modal" data-target="#myModal">
       Вставить таймер
   </span>

</div>
<br>
    <?= $form->field($model, 'text')->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
		'formatting' => [
		'h1','h2','p','blockquote'
		],
        'plugins' => [
            'clips',
            'fullscreen',
        ],
		'imageUpload' => \yii\helpers\Url::to(['/ajax/save-redactor-img','sub'=>'article']),
		'imageDelete' => \yii\helpers\Url::to(['/ajax/save-img-del']),
        'clips' => [
            ['Красный', '<span class="label-red">Здесь вставить текст</span>'],
            ['Зеленый', '<span class="label-green">Здесь вставить текст</span>'],
            ['Голубой', '<span class="label-blue">Здесь вставить текст</span>'],
        ],
    ],
])->label(false)?>
<br>

	    <?= $form->field($model, 'position')->dropDownList([
        'top' => 'Под шапкой',
        'footer' => 'Перед подвалом',
        'right' => 'Слева',
		'add' => 'Между объявлениями',
     ],
    [
        'prompt' => 'Выбрать'
    ]); ?>

<br>

    <?//= $form->field($model, 'date_add')->textInput() ?>

<?= $form->field($model, 'date_del', ['template' => '{error}{label}{input}'])->textInput(['class' => 'form-control  datepicker'])->label('Дата выключения');?>
<br>
    <?= $form->field($model, 'action')->dropDownList([
    	'all' => 'Везде',
		'index' => 'Главная страница',
		'product' => 'Список товара (Только в рубриках)',
		'boardone' => 'Страница товара',
		'mynotepad' => 'Список товара (Избранные)',
		'delivery' => 'Страница "Доставка"',
		'payment' => 'Страница "Оплата"',
		'contact' => 'Страница "Контакты"',
		'cort' => 'Страница "Корзина"',
     ],
    [
        'prompt' => 'Выбрать'
    ]); ?>

 <br>

	<?= $form->field($model, 'header_ok', ['template' => '{error}{label}<div class="add_chex">{input}</div>'])->radioList([
		'0' => 'Не показывать', 
		'1' => 'Показывать', 
	]); ?>
<br>
<?= $form->field($model, 'category')->textInput(['class' => 'catchang','type' => 'hidden' ]) ?>
<br>
    <?= $form->field($model, 'sort')->textInput() ?>
	<br>

	
	<?= $form->field($model, 'status', ['template' => '{error}{label}<div class="add_chex">{input}</div>'])->radioList([
		'1' => 'Опубликовано', 
		'2' => 'Снято с публикации', 
	]); ?>

<br>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


















<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">

<link href="/../assest_all/timeruser/editor.min.css?2" rel="stylesheet">
    





<div class="fixed-block">
    <div class="preview-block">
        <div class="switch-background">
            <div class="switch-background-button white"></div>
            <div class="switch-background-button black"></div>
        </div>

        <div class="switch-elements">
            <!--<div class="switch-title">Подписи:</div>-->
            <div class="">
                <label><input type="checkbox" name="view[0]" checked="checked"> дни</label>
            </div>
            <div class="">
                <label><input type="checkbox" name="view[1]" checked="checked"> часы</label>
            </div>
            <div class="">
                <label><input type="checkbox" name="view[2]" checked="checked"> минуты</label>
            </div>
            <div class="">
                <label><input type="checkbox" name="view[3]" checked="checked"> секунды</label>
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
            <li class="active" data-id="1" title="Выберите необходимый тип таймера">1. Тип таймера</li>
            <li data-id="2" title="Подберите необходимый формат и дизайн таймера">2. Дизайн</li>
            <li data-id="3" title="Настройте дизайн счетчика под оформление вашего сайта">3. Настройки<span class="hidden">Далее нажмите сюда, чтобы настроить выбранный дизайн</span></li>
 
            <div style="clear: both"></div>
        </div>
    </ul>

    <div id="pin"></div>

</div>

<div class="container1">

    <div class="timer-editor">

        <!-- STEP 1 -->
        <div id="step-1" class="step">

            <div class="step-1-examples">
                <h4>Примеры:</h4>
                <a href="#" class="example-action" data-id="1">старт каждый день в 0 часов, сброс через 24 часа</a>

                <a href="#" id="more-examples">другие примеры</a>
                <div class="more-examples" style="display: none;">
                    <a href="#" class="example-action" data-id="2">старт каждый день в 2 часа ночи, длится до 12 ночи</a>
                    <a href="#" class="example-action" data-id="3">старт в 19 часов, окончание в 21 час, на следующий день повторяется</a>
                    <a href="#" class="example-action" data-id="5">обратный отсчет до 1 января</a>
                    <a href="#" class="example-action" data-id="6">каждые выходные с 9 часов до 21 часа</a>
                    <a href="#" class="example-action" data-id="7">10 часов и 5 минут, начиная с текущего момента</a>
                    <a href="#" id="hide-examples">свернуть</a>
                </div>
            </div>

            <div class="panels-block mt4">
                <div class="panels-wrapper clearfix">
                    <div class="panel panel-primary fl panel-success" data-id="1">
                        <div class="panel-body">
                            До определенной даты
                        </div>
                    </div>



                    <div class="panel panel-primary fr" data-id="3">
                        <div class="panel-body">
                            Цикличный
                        </div>
                    </div>

                    <div class="panel panel-primary mha" data-id="2">
                        <div class="panel-body">
                            На промежуток времени
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
                        <!-- До определенной даты -->
                        <table class="vat mt2">
                            <tr>
                                <td class="pr2">
                                    <span class="type-title">Конец отсчета:</span>
                                </td>
                                <td>
                                    <table class="vam design-inputs">
                                        <tr>
                                            <td><input type="text" value="" name="types[1][date]" id="type-1-date"/></td>
                                            <td style="padding: 0 5px;">в</td>
                                            <td><input type="text" value="" name="types[1][time]" id="type-1-time" placeholder="15:00" maxlength="5"/></td>
                                        </tr>
                                    </table>
                                    <div class="mt2">
                                        <label class="local-time no_sel">
                                            <input type="checkbox" checked="checked" name="types[1][usertime]" id="type-1-usertime">
                                            По локальному времени пользователя
                                        </label>

                                        <div class="">
                                            <select name="types[1][tz]" id="type-1-tz" disabled="disabled">
                                                <option value="1">(UTC+1) Париж</option>
                                                <option value="2">(UTC+2) Калининград</option>
                                                <option value="3" selected="selected">(UTC+3) Москва</option>
                                                <option value="4">(UTC+4) Самара</option>
                                                <option value="5">(UTC+5) Екатеринбург</option>
                                                <option value="6">(UTC+6) Омск</option>
                                                <option value="7">(UTC+7) Красноярск</option>
                                                <option value="8">(UTC+8) Иркутск</option>
                                                <option value="9">(UTC+9) Якутск</option>
                                                <option value="10">(UTC+10) Владивосток</option>
                                                <option value="11">(UTC+11) Среднеколымск</option>
                                                <option value="12">(UTC+12) Камчатка</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div id="timer-type-3" class="timer-type fr" style="display: none;">
                        <!-- Цикличный -->
                        <table class="vat mt2">
                            <tr>
                                <td class="pr2"><span class="type-title">Начало отсчета:</span></td>
                                <td>
                                    <table class="vam">
                                        <tr>
                                            <td>
                                                <a href="#" class="weekdays">
                                                <span id="weekdays-title">каждый день</span>
                                                <span class="weekdays-list">
                                                    <label>
                                                        <input type="checkbox" name="types[3][weekdays]" value="0"
                                                               checked="checked"/>
                                                        Понедельник
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="types[3][weekdays]" value="1"
                                                               checked="checked"/>
                                                        Вторник
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="types[3][weekdays]" value="2"
                                                               checked="checked"/>
                                                        Среда
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="types[3][weekdays]" value="3"
                                                               checked="checked"/>
                                                        Четверг
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="types[3][weekdays]" value="4"
                                                               checked="checked"/>
                                                        Пятница
                                                    </label>
                                                    <label class="weekend">
                                                        <input type="checkbox" name="types[3][weekdays]" value="5"
                                                               checked="checked"/>
                                                        Суббота
                                                    </label>
                                                    <label class="weekend">
                                                        <input type="checkbox" name="types[3][weekdays]" value="6"
                                                               checked="checked"/>
                                                        Воскресенье
                                                    </label>
                                                </span>
                                                </a>
                                            </td>
                                            <td style="padding: 0 5px;">в</td>
                                            <td class="design-inputs">
                                                <input type="text" value="" name="types[3][time]" id="type-3-time" class="c_text" placeholder="15:00"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="mt1 design-inputs">
                                        <label class="local-time no_sel">
                                            <input type="checkbox" checked="checked" name="types[3][usertime]" id="type-3-usertime">
                                            по локальному времени посетителя
                                        </label>

                                        <div class="">
                                            <select name="types[3][tz]" id="type-3-tz" disabled="disabled">
                                                <option value="1">(UTC+1) Париж</option>
                                                <option value="2">(UTC+2) Калининград</option>
                                                <option value="3" selected="selected">(UTC+3) Москва</option>
                                                <option value="4">(UTC+4) Самара</option>
                                                <option value="5">(UTC+5) Екатеринбург</option>
                                                <option value="6">(UTC+6) Омск</option>
                                                <option value="7">(UTC+7) Красноярск</option>
                                                <option value="8">(UTC+8) Иркутск</option>
                                                <option value="9">(UTC+9) Якутск</option>
                                                <option value="10">(UTC+10) Владивосток</option>
                                                <option value="11">(UTC+11) Среднеколымск</option>
                                                <option value="12">(UTC+12) Камчатка</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="pt2 pr2"><span class="type-title">Длительность:</span></td>
                                <td class="pt2">
                                    <table class="design-inputs tbl-duration">
                                        <tr>
                                            <td class="pr1"><input type="text" class="date-item" name="types[3][hours]" id="type-3-hours" placeholder="" value="1"></td>
                                            <td class="pr1"><input type="text" class="date-item" name="types[3][minutes]" id="type-3-minutes" placeholder="" value="1"></td>
                                        </tr>
                                        <tr>
                                            <td class="pr1 c_text"><span>часов</span></td>
                                            <td class="pr1 c_text"><span>минут</span></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>
                    </div>

                    <div id="timer-type-2" class="timer-type mha" style="display: none;">
                        <!-- На промежуток времени -->
                        <table class="vat mt2">
                            <tr>
                                <td class="pr2">
                                    <span class="type-title">Начало отсчета:</span>
                                </td>
                                <td>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="types[2][start]" id="type-2-first_enter" value="first_enter" checked>
                                            с первого посещения клиентом
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="types[2][start]" id="type-2-just_now" value="just_now">
                                            прямо сейчас
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="pt2 pr2">
                                    <span class="type-title">Длительность:</span>
                                </td>
                                <td class="pt2">
                                    <table class="design-inputs tbl-duration">
                                        <tr>
                                            <td class="pr1"><input type="text" class="date-item" name="types[2][days]" id="type-2-days" placeholder="" value="1"></td>
                                            <td class="pr1"><input type="text" class="date-item" name="types[2][hours]" id="type-2-hours" placeholder="" value="1"></td>
                                            <td class="pr1"><input type="text" class="date-item" name="types[2][minutes]" id="type-2-minutes" placeholder="" value="1" maxlength="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="pr1 c_text"><span>дней</span></td>
                                            <td class="pr1 c_text"><span>часов</span></td>
                                            <td class="pr1 c_text"><span>минут</span></td>
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
                    текст
                </label>

                <label>
                    <input type="checkbox" checked="checked" data-id="circle">
                    круг
                </label>

                <label>
                    <input type="checkbox" checked="checked" data-id="plate">
                    табличка
                </label>
            </div>

            <div class="design-list clearfix">
            </div>
        </div>
        <!-- END OF STEP 2 -->

        <!-- STEP 3 -->
        <div id="step-3" class="step design-inputs" style="display: none;">

               <div class="ds-line clearfix">
                    <div class="ds-left">
                        <strong>Ссылка</strong>
                    </div>
                </div>
				   <input type="text" value="" class="link-href" style="min-width: 300px;text-align: left;" name="types[link]" id="type-1-date"/>
				    <!-- Адаптивность -->

                <div class="ds-line clearfix" style="height: 40px;">
                    <!--<div class="ds-left">
                        <strong>Адаптивность</strong>
                    </div>
                    <div class="ds-right">
                        <div class="ui toggle checkbox">
                            <input type="checkbox" checked class="adapt" name="design[adapt]">
                        </div>
                    </div>-->
                </div>


            <input type="hidden" value="text" id="design-type"/>

            <!-- Текст -->
            <div data-type="text" class="design-setting-group" style="display: block;">

                <!--<div class="ds-line clearfix">
                    <div class="ds-left">
                        Тип таймера:
                    </div>
                    <div class="ds-right ds-design-type">
                        текстовый
                    </div>
                </div>-->

                <!-- Ссылка -->
				
             
                <div class="ds-line clearfix">
                    <div class="ds-left">
                        <strong>Цифры</strong>
                    </div>

                </div>

                <div class="ds-line clearfix">
                    <div class="ds-left align-right">шрифт:</div>
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
                        отступ:
                    </div>
                    <div class="ds-right">
                        <input type="range" min=0 max=100 value=20 step="1" name="design[text][separator-margin]">
                    </div>
                </div>

                <!-- Разделитель -->

                <div class="ds-line clearfix">
                    <div class="ds-left">
                        <strong>Разделитель</strong>
                    </div>
                    <div class="ds-right">
                        <div class="ui toggle checkbox">
                            <input type="checkbox" name="design[text][separator-on]">
                        </div>
                    </div>
                </div>

                <div class="ds-line clearfix">
                    <div class="ds-left align-right">
                        текст:
                    </div>
                    <div class="ds-right">
                        <input type="text" name="design[text][separator-text]" class="separator-text" maxlength="10">
                    </div>
                </div>

                <!-- Подписи -->

                <div class="ds-line clearfix">
                    <div class="ds-left">
                        <strong>Подписи</strong>
                    </div>
                    <div class="ds-right">
                        <div class="ui toggle checkbox">
                            <input type="checkbox" name="design[text][text-on]">
                        </div>
                    </div>
                </div>

                <div class="ds-line clearfix">
                    <div class="ds-left align-right">
                        шрифт
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

            <!-- Таблички -->
            <div data-type="plate" class="design-setting-group" style="display: none;">

                <!--<div class="ds-line clearfix">
                    <div class="ds-left">
                        Тип таймера:
                    </div>
                    <div class="ds-right ds-design-type">таблички</div>
                </div>-->

                <table class="vat">
                    <tr>
                        <td>

                            <!-- Таблички -->

                            <div class="ds-line clearfix">
                                <div class="ds-left">
                                    <strong>Вид табличек</strong>
                                </div>
                                <div class="ds-right">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    закругление углов
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=0 max=60 value=10 step="1" name="design[plate][round]">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    фон
                                </div>
                                <div class="ds-right">
                                    <select data-id="1" class="smart-color-selector" name="design[plate][background]">
                                        <option value="solid" selected="selected">сплошной</option>
                                        <option value="gradient">градиент</option>
                                        <option value="opacity">прозрачный</option>
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
                                    эффект
                                </div>
                                <div class="ds-right">
                                    <select class="" name="design[plate][effect]">
                                        <option value="none">нет</option>
                                        <option value="flipchart">перелистывание</option>
                                        <option value="slide">перемотка</option>
                                    </select>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    отступ табличек
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=0 max=30 value=2 step="1" name="design[plate][space]">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    отступ
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=0 max=100 value=20 step="1" name="design[plate][separator-margin]">
                                </div>
                            </div>
                        </td>
                        <td>
                            <!-- Цифры -->

                            <div class="ds-line clearfix">
                                <div class="ds-left">
                                    <strong>Цифры</strong>
                                </div>

                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">шрифт:</div>
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
                                    отступ:
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=0 max=60 value=20 step="1" name="design[plate][padding]">
                                </div>
                            </div>

                            <!-- Разделитель -->

                            <div class="ds-line clearfix ds-line-sep">
                                <div class="ds-left">
                                    <strong>Разделитель</strong>
                                </div>
                                <div class="ds-right">
                                    <div class="ui toggle checkbox">
                                        <input type="checkbox" name="design[plate][separator-on]">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">текст:</div>
                                <div class="ds-right">
                                    <input type="text" name="design[plate][separator-text]" class="separator-text" maxlength="10">
                                </div>
                            </div>

                            <!-- Подписи -->

                            <div class="ds-line clearfix ds-line-sep">
                                <div class="ds-left">
                                    <strong>Подписи</strong>
                                </div>
                                <div class="ds-right">
                                    <div class="ui toggle checkbox">
                                        <input type="checkbox" name="design[plate][text-on]">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">шрифт:</div>
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

            <!-- Круги -->
            <div data-type="circle" class="design-setting-group" style="display: none;">

                <!--<div class="ds-line clearfix">
                    <div class="ds-left">
                        Тип таймера:
                    </div>
                    <div class="ds-right ds-design-type">круговой</div>
                </div>-->

                <table class="vat">
                    <tr>
                        <td style="width: 50%;">
                            <div class="ds-line clearfix">
                                <div class="ds-left">
                                    <strong>Круги</strong>
                                </div>
                                <div class="ds-right">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    толщина линии:
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=1 max=50 value=10 step="1" name="design[circle][width]">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    радиус круга:
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=1 max=100 value=10 step="1" name="design[circle][radius]">
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    цвет линии:
                                </div>
                                <div class="ds-right">
                                    <select data-id="2" class="smart-color-selector" name="design[circle][line]" style="width: 120px;">
                                        <option value="solid" selected="selected">сплошной</option>
                                        <option value="gradient">градиент</option>
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
                                    цвет фона:
                                </div>
                                <div class="ds-right">

                                    <select data-id="3" class="smart-color-selector" name="design[circle][background]" style="width: 120px;">
                                        <option value="solid" selected="selected">сплошной</option>
                                        <option value="gradient">градиент</option>
                                        <option value="opacity">прозрачный</option>
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
                                    направление:
                                </div>
                                <div class="ds-right">
                                    <select class="" name="design[circle][direction]">
                                        <option value="direct">по часовой стрелке</option>
                                        <option value="back">против часовой стрелки</option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td style="width: 50%">
                            <!-- Цифры -->

                            <div class="ds-line clearfix">
                                <div class="ds-left">
                                    <strong>Цифры</strong>
                                </div>

                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">шрифт:</div>
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
                                    отступ:
                                </div>
                                <div class="ds-right">
                                    <input type="range" min=0 max=100 value=20 step="1" name="design[circle][separator-margin]">
                                </div>
                            </div>

                            <!-- Разделитель -->

                            <div class="ds-line clearfix ds-line-sep">
                                <div class="ds-left">
                                    <strong>Разделитель</strong>
                                </div>
                                <div class="ds-right">
                                    <div class="ui toggle checkbox">
                                        <input type="checkbox" name="design[circle][separator-on]">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    текст:
                                </div>
                                <div class="ds-right">
                                    <input type="text" name="design[circle][separator-text]" class="separator-text" maxlength="10">
                                </div>
                            </div>

                            <!-- Подписи -->

                            <div class="ds-line clearfix ds-line-sep">
                                <div class="ds-left">
                                    <strong>Подписи</strong>
                                </div>
                                <div class="ds-right">
                                    <div class="ui toggle checkbox">
                                        <input type="checkbox" name="design[circle][text-on]">
                                    </div>
                                </div>
                            </div>

                            <div class="ds-line clearfix">
                                <div class="ds-left align-right">
                                    шрифт:
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
                <div>Этот код вставьте в блок, где нужно отобразить этот таймер:</div>
                <!--<textarea id="output-code-0" readonly onclick="this.select();" rows="3" style="resize: none"></textarea>-->
                <div style="margin-top: 3px;"><input type="text" id="output-code-0" readonly onclick="this.select();"/></div>

                <!--<div class="mt4 email-link">-->
                    <!--<a href="#">Отправить код на email</a>-->
                <!--</div>-->
            </div>

            <div class="output-block" id="email" style="display: none;">
                <span class="mr1">Отправить код на email:</span>
                <input type="text" value="" placeholder="Введите email" id="email-field">
                <button type="button" class="button" id="send-email">Отправить</button>
                <div class="message"></div>
            </div>


            <div class="output-block" id="code-changed" style="display: none;">
                <button type="button" class="button green" id="code-changed-create">Создать новый код</button>
                <!--<button type="button" class="button grey" id="code-changed-update">Обновить текущий код</button>-->
            </div>

        </div>


    </div>

</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success closesave" data-dismiss="modal">Сохранить</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
