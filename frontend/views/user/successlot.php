<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\DisputeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCssFile('/assest_all/calendar2/jquery-ui.css');
$this->registerJsFile('/assest_all/calendar2/jquery-ui.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]);
		
		
		$this->registerJsFile('/js/user.js',['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = 'Выигрышные лоты';
$this->params['breadcrumbs'][] = array('label'=> 'Личный кабинет', 'url'=>Url::toRoute('index'));
$this->params['breadcrumbs'][] = $this->title;

	define("RATEST",  $ratest);

?>

<div class="row">
<div class="">
   <div class="col-md-3">
      <?= $this->render('user_menu.php') ?>
   </div>
   <div class="col-md-9">
   <div class="person-body">
    <h1><i class="fa fa-gavel" aria-hidden="true"></i> <?=$this->title?></h1>


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
              ['attribute'=>'title','format'=>'raw','value'=> 
                 function($data) {
					


				$url = Url::to(['blog/one', 'region'=>$data->regions['url'], 'category'=> $data->categorys['url'], 'url'=>$data->url, 'id'=>$data->id]);
                   return '<a href="'.$url.'" target="_blank" data-pjax=0>'.$data->title.'</a>';
                 },
                 'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
             ],

         ['attribute'=>'pricepay','format'=>'raw','value'=> 
                 function($data) {
foreach($data->blogField as $field) {
    if($field['field'] == '481') {
	    $price = $field['value'];
		$dop = $field['dop'];
    }
}	 
					 
                   return  $price.' <i class="fa '.RATEST[$dop].' aria-hidden="true"></i>';
                 },
				 
				    'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
            
             ],
			 
			 
			   ['attribute'=>'userreservauthor','format'=>'raw','value'=> 
                 function($data) {
foreach($data->blogField as $field) {
    if($field['field'] == '475') {
	    $phone = $field['value'];
    }
}
  $address = $data->coord['text'];
                   return '
				   	   <div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                Контакты продавца
              </a>
            </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse">
      <div class="panel-body">
    <table class="table">

        <tbody>
          <tr>
            <td><strong>Имя:</strong></td>
            <td>'.$data->author['username'].'</td>

          </tr>
          <tr>
            <td><strong>Email</strong></td>
               <td>'.$data->author['email'].'</td>
          </tr>
		  
		  <tr>
		       <td><strong>Телефон</strong></td>
               <td>'.$phone.'</td>
          </tr>
		  
		  <tr>
		       <td><strong>Адрес</strong></td>
               <td>'.$address.'</td>
          </tr>

        </tbody>
      </table>
 
  </div>      
	 </div>
    </div>
  </div>
  </div>
</div>
				   ';
                 },
				 
				    'filterInputOptions' => [
                     'class' => 'form-control author_input', 
                     'id' => null,
                  ],
            
             ],
			
		

		
			
	
			   

           

 
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
   </div>
</div>
</div>
