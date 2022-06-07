<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TimerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Timers';
$this->params['breadcrumbs'][] = $this->title;
?>

<a class="btn btn-success" href="/admin/timer/saveindex">Добавить таймер</a>


<div class="timer-index">
<br>
   



    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

 
		    ['attribute'=>'id','format'=>'raw', 'value'=>
	           function($model) {
				    return  'Для вставки <strong>{timer_'.$model->id.'}</strong>';
			   }
			],
           ['attribute'=>'cod','format'=>'raw', 'value'=>
	           function($model) {
				 
	
				   
				    return  '<iframe style="border: 0px; min-width: 350px;height: 90px;" src="/admin/timer/views?id='.$model->id.'"></iframe>';
			   }
			],
			
			
			  ['attribute'=>'href','format'=>'raw', 'value'=>
	           function($model) {
				 
	                $cod = json_decode($model->cod)->code; 
				     preg_match_all('#"href":"(.+?)",#is', $cod, $href);
				    if($href[1][0] != '#') {
	                  $a = '<a target="_blank" href="'.$href[1][0].'">'.$href[1][0].'</a>';
					  return $a;
                    }else{
						 return '';
					}
			   }
			],
            'id_block',
            'tyme',

             ['class' => 'yii\grid\ActionColumn',
'template' => '{update}{delete}',
]
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
