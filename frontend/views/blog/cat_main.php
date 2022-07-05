<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
$this->registerJsFile(Url::home(true).'/js/main.js',['depends' => [\yii\web\JqueryAsset::className()]]);

$region = @Yii::$app->caches->region()[Yii::$app->request->cookies['region']->value];
 if($region['name']) {
    $passurl = Url::to(['/passanger', 'ot' => $region['name']]);
}else{
	$passurl = Url::to(['/passanger']);
}
?>

	
<div class="row">
   <div class="tree_cat">






<div class="col-md-12 row">
  <div class="<? if ($block_right){?>col-lg-4 col-md-4 col-sm-4 col-xs-12<?}else{?>col-lg-3 col-md-3 col-sm-3 col-xs-12<?}?> cat-list maintree">
     <div class="el_body el-pop">
        <a  href="<?=$passurl?>">
		  <img class="ln-shadow" src="/passanger_js/img/pass.png" alt="попутчики">		 
          <div class="cat_title_width"><div class="cat_title">Попутчики <span class="cat_count pass_count">(<?=Yii::$app->userFunctions2->passangerreg(Yii::$app->request->cookies['region'])?>)</span></div> </div>
        </a>
		
 
      <span data-toggle="collapse" class="btn-cat-collapsed collapsed">   <span class=" icon-down-open-big"></span> </span>

  
      </div>	
  </div>
<a href="/passanger"><div class="col-md-8 banner-pass col-sm-8 hidden-xs"></div></a>
</div>




  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 cat-list maintree">
     <div class="el_body el-auc">
        <a href="<?=Url::to(['auction/index'])?>">
		  <img class="ln-shadow" src="/images_all/category/auction.png" alt="попутчики">		 
          <div class="cat_title_width"><div class="cat_title cat-auc">Аукцион <span class="cat_count count-auc">(<?=Yii::$app->userFunctions2->auctioncount(Yii::$app->request->cookies['region'])?>)</span></div> </div>
        </a>
		
 
      <span data-toggle="collapse" class="btn-cat-collapsed collapsed">   <span class=" icon-down-open-big"></span> </span>

  
      </div>	
  </div>

<?php foreach($cat_main as $cat) {?>

  <div class="<? if ($block_right){?>col-lg-4 col-md-4 col-sm-4 col-xs-12<?}else{?>col-lg-3 col-md-3 col-sm-3 col-xs-12<?}?> cat-list maintree dropdown">
     <div class="el_body">
	 
        <a class="parent_click" id="navbardrops-<?= $cat['id']?>" data-id="<?= $cat['id']?>" href="<?= $cat['url']?>">
            <?php if ($cat['image']) {?>
				<img class="ln-shadow" src="<?= $cat['image']?>" alt="<?= $cat['name']?>">		 
			<? } ?>
          <div class="cat_title_width"><div class="cat_title"><?= $cat['name']?> <span class="cat_count">(<?=$cat['count']?>)</span></div></div>
        </a>
		
 
     <!-- <span data-target="cat-id-<?= $cat['id']?>" data-toggle="collapse" class="btn-cat-collapsed collapsed">   <span class=" icon-down-open-big"></span> </span>
        <ul class="dropdown-menu cat_el " role="menu" aria-labelledby="navbardrops-<?= $cat['id']?>">
		  <div class="treug"></div>
          <div class="menuh3"> <a href="<?= $cat['url']?>"><i class="fa fa-external-link" aria-hidden="true"></i> <?= $cat['name']?></a></div>
		  <div class="cat_el_li navbardrops-<?= $cat['id']?>"></div> 
        </ul>
  -->
      </div>	
  </div>
<?php } ?>

<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 cat-list maintree">
     <div class="el_body el-auc">
        <a href="<?=Url::to(['express/index'])?>">
		    <img class="ln-shadow" src="/images_all/category/bolt.png" alt="попутчики">		 
          <div class="cat_title_width"><div class="cat_title cat-auc">Экспресс объявления <span class="cat_count count-auc">(<?=Yii::$app->userFunctions2->expresscount(Yii::$app->request->cookies['region'])?>)</span></div> </div>
        </a>
      <span data-toggle="collapse" class="btn-cat-collapsed collapsed">   <span class=" icon-down-open-big"></span> </span>
      </div>	
  </div>
   </div>
</div>
