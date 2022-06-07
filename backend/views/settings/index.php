<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Основные настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить настройку (для разработчика)', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<div id="result_form"></div> 
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<form name="form" method="post" id="ajax_form" action="">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],
          //  'id',
          //'name',
		
         
		  ['attribute'=>'text','format'=>'raw', 'value'=>
	           function($data) {
				   return $data->text;
			   }
			],
           ['attribute'=>'value', 'format'=>'raw', 'value'=>
	           function($data) {
				   //Условия по типу полей
				   //text
				if ($data->type == 'v') {
					 return '<input placeholder="'.$data->placeholder.'"  name="'.$data->id.'" type="text" value="'.$data->value.'" class="form-control" style="width: 70%"/>'; 
				}
				    //select
				if ($data->type == 's') {
				  $return = '<select class="form-control" style="width:70%;" name="'.$data->id.'">';
			        foreach(explode("\n", $data->val_text) as $key => $res) {
					       $return .='<option value="'.$key.'"'; if(str_replace(array("\r"),"",$res) == $data->value){$return .='selected';} $return .='>'.str_replace(array("\r"),"",$res).'</option>';
			        }
					       $return .='</select>';
					       return $return; 
			    }
			   
			     //radio
			   	if ($data->type == 'r') {
			        foreach(explode("\n", $data->val_text) as $key => $res) {
						if(!isset($return)) {$return = '';}
					       $return .='<label><input type="radio"  name="'.$data->id.'" value="'.$key.'"'; if($key == $data->value){$return .='checked';} $return .='/> '; $return .= str_replace(array("\r"),"",$res).'</label>';
			        }
					       return $return; 
			    }
				
				

				//textarea
				if ($data->type == 't') {
			       return '<textarea placeholder="'.$data->placeholder.'" style="min-width: 400px;" rows="5" class="form-control" name="'.$data->id.'">'.$data->value.'</textarea>'; 
			    }

			   }
			  
            ],
        ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<button id="btnSettigs" type="button" class="btn btn-success">Сохранить</button>
</form>
<script>
 $("#btnSettigs").click(
		function(){
			dAjaxForm('result_form', 'ajax_form', '/admin/settings/update-all');
			return false; 
		}
	);
</script>
    <?php Pjax::end(); ?>

</div>
