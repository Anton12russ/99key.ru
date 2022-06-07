<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
/* @var $urls */
/* @var $host */
 	
	$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<?php if ($act == 'all') {?>
 <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 	<sitemap><loc><?=$patch_url?>/sitemap_region.xml</loc></sitemap>
 	<sitemap><loc><?=$patch_url?>/sitemap_category.xml</loc></sitemap>
 	<sitemap><loc><?=$patch_url?>/sitemap_article.xml</loc></sitemap>
 	<sitemap><loc><?=$patch_url?>/sitemap_shop.xml</loc></sitemap>
 	<sitemap><loc><?=$patch_url?>/sitemap_blog.xml</loc></sitemap>
</sitemapindex>
<?php }?>



<?php if ($act == 'region') {?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <?php foreach(Yii::$app->caches->region() as $res) {?>
 	     <url><loc><?=$patch_url.'/'.$res['url']?></loc></url>
   <?php }?>
</urlset>
<?php }?>



<?php if ($act == 'category') {?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <?php foreach(Yii::$app->caches->category() as $res) {?>
 	   <url><loc><?=$patch_url.'/'.Yii::$app->userFunctions->sitemapCetegory($res['relative'], $res['url']);?></loc></url>
   <?php }?>
</urlset>
<?php }?>



<?php if ($act == 'article') {?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <?php foreach($articles as $one) {
	   $url = Url::to(['article/one', 'category'=>$one->cats['url'], 'id'=>$one->id, 'name'=>Yii::$app->userFunctions->transliteration($one->title)]);
	   ?>
	   <url>
 	      <lastmod><?=str_replace(' ','T',$one->date_add)?>+03:00</lastmod>
		  <loc><?=$patch_url.$url?></loc>
		</url>  
   <?php }?>
</urlset>
<?php }?>



<?php if ($act == 'shop') {?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <?php foreach($shop as $one) {?>
   <? $url = Url::to(['shop/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'id'=>$one->id, 'name'=>Yii::$app->userFunctions->transliteration($one->name)]);?>
 	     <url>
		  <lastmod><?=str_replace(' ','T',$one->date_add)?>+03:00</lastmod>
		  <loc><?=$patch_url.$url?></loc>
		</url>
   <?php }?>
</urlset>
<?php }?>




<?php if ($act == 'blog' && !isset($_GET['page'])) { $count = $counter/1000;?>
 <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <?php $i = 1; while ($i <= ceil($count)) {  ?>
 	    <sitemap><loc><?=$patch_url.'/sitemap_blog.xml?page='.$i?></loc></sitemap>
   <?php $i++;}?>
 </sitemapindex>
<?php }?>



<?php if ($act == 'blog' && isset($_GET['page'])) {?>
  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <?php foreach($blogs as $one) {?>
   <? $url = Url::to(['blog/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
 	    <url>
		  <lastmod><?=str_replace(' ','T',$one->date_add)?>+03:00</lastmod>
		  <loc><?=$patch_url.$url?></loc>
		</url>
   <?php }?>
</urlset>
<?php }?>