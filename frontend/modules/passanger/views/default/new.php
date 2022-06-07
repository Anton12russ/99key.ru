<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;

?>
<?php if($models) {?>
   <?php foreach($models as $one) {?>
     <? $url = Url::to(['/passanger/one', 'id' => $one->id]);?>
    <div class="col-md-12">
	  <div class="cat-board-body">
          <div class="all_img col-md-2  col-sm-3 col-xs-3" style="background-image: url(<? if (isset($one->img) && $one->img > 0) {?> <?= '/uploads/images/passanger/logo/'.$one->img;?> <? }else{ ?><?= Yii::getAlias('@blog_image').'/'?>no-photo_all.png<? } ?>);"> <a data-pjax=0 target="_blank" class="img_a" href="<?=$url?>"></a></div>
	 <div class="all_body col-md-10 col-sm-9 col-xs-8">
	 
	   <table class="table">
        <tbody>
          <tr>
            <td class="td-name">
			<div class="hide-new">
			 <span class="tds">Откуда:</span> <span class="info"><?=Yii::$app->userFunctions2->address($one->ot);?></span>
			</div>
			</td>
          </tr>
          <tr>
            <td class="td-name">
			<div class="hide-new">
			<span class="tds">Куда:</span> <span class="info"><?=Yii::$app->userFunctions2->address($one->kuda);?></span>
			</div>
			</td>

           
          </tr>
          <tr>
            <td class="td-name">
			<span class="tds">Отправка: </span><span class="info"><?=Yii::$app->userFunctions2->new_time(strtotime($one->time));?></span>
			</td>

            
          </tr>
        </tbody>
      </table>
	   
	    </div>
	 </div>
	 <div class="col-md-12  border-bottom-pass"></div>
	</div>
   <?php }	?>
<?php }	?>
<?php exit();?>
