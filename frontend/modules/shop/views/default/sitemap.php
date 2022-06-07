<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
/* @var $urls */
/* @var $host */
 	
	$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<?php if (isset($act) && $act == 'all') {?>
 <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  	<sitemap><loc><?=$patch_url?>/sitemap?act=static</loc></sitemap>
 	<sitemap><loc><?=$patch_url?>/sitemap?act=article</loc></sitemap>
 	<sitemap><loc><?=$patch_url?>/sitemap?act=blog</loc></sitemap>
</sitemapindex>
<?php }?>




<?php if (isset($act) && $act == 'static') {?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
      <url>
 	      <lastmod><?=str_replace(' ','T',$shop->date_add)?>+03:00</lastmod>
		  <loc><?=$patch_url.'/'?></loc>
	  </url>  
	 
	    <url>
 	      <lastmod><?=str_replace(' ','T',$shop->date_add)?>+03:00</lastmod>
		  <loc><?=$patch_url.'/delivery'?></loc>
	  </url> 
	  <url>
 	      <lastmod><?=str_replace(' ','T',$shop->date_add)?>+03:00</lastmod>
		  <loc><?=$patch_url.'/payment'?></loc>
	  </url> 
	  <url>
 	      <lastmod><?=str_replace(' ','T',$shop->date_add)?>+03:00</lastmod>
		  <loc><?=$patch_url.'/contact'?></loc>
	  </url> 
	 
</urlset>
<?php }?>





<?php if (isset($act) && $act == 'article') {?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <?php foreach($articles as $one) {
	  $url = Url::to(['/articleone', 'id'=>$one->id]);
	   ?>
	   <url>
 	      <lastmod><?=str_replace(' ','T',$one->date_add)?>+03:00</lastmod>
		  <loc><?=$patch_url.$url?></loc>
		</url>  
   <?php }?>
</urlset>
<?php }?>


<?php if (!isset($act) && !isset($_GET['page'])) { $count = $counter/1000;?>
 <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <?php $i = 1; while ($i <= ceil($count)) {  ?>
 	    <sitemap><loc><?=$patch_url.'/sitemap?page='.$i?></loc></sitemap>
   <?php $i++;}?>
 </sitemapindex>
<?php }?>



<?php if (!isset($act) && isset($_GET['page'])) {?>
  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <?php foreach($blogs as $one) {?>
   <? $url = Url::to(['/boardone', 'id'=>$one->id]);?>
 	    <url>
		  <lastmod><?=str_replace(' ','T',$one->date_add)?>+03:00</lastmod>
		  <loc><?=$patch_url.$url?></loc>
		</url>
   <?php }?>
</urlset>
<?php }?>