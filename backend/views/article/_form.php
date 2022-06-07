<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;

$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile(Url::home(true).'js/article.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>
	<div class="form-group">
<label class="control-label">Пользователь</label>
<a href="#" class="user_id btn btn-info" data-toggle="modal" data-target="#myModal">
<?php if ($model->user_id) {echo $model['author']['username'];}else{echo 'Выбрать пользователя';}?>
</a>
</div>
	
    <?= $form->field($model, 'user_id')->textInput(['class' => 'form-control user_input','type' => 'hidden' ])->label('') ?>
	
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cat')->textInput(['class' => 'form-control catchang','type' => 'hidden' ]) ?>
    
    <?= $form->field($model, 'date_end')->textInput(['class' => 'form-control datepicker time', 'autocomplete'=>'off']) ?>
		
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
			'video',
			'fontcolor',
			'fontfamily',
			'fontsize',
        ],
		'imageUpload' => \yii\helpers\Url::to(['/site/save-redactor-img','sub'=>'article']),
		'imageDelete' => \yii\helpers\Url::to(['/site/save-img-del']),
        'clips' => [
            ['Красный', '<span class="label-red">Здесь вставить текст</span>'],
            ['Зеленый', '<span class="label-green">Здесь вставить текст</span>'],
            ['Голубой', '<span class="label-blue">Здесь вставить текст</span>'],
        ],
    ],
])->label('Текст')?>
	
    <?= $form->field($model, 'status')->radioList([
	      	'0' => 'На модерации', 
		    '1' => 'Опубликовано', 
			'2' => 'Удалено', 
	 ]); ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Выбрать автора объявления</h4>
      </div>
	  <span class="hidden href_user" data-href="<?= Url::home(true)?>user"></span>
      <div class="modal-body" >
   <iframe src=""  id="user_cont"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary add_user" data-dismiss="modal">Выбрать</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->