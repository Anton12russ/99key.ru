<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
/* @var $urls */
/* @var $host */
 	
	$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];

?>

<rss version="2.0">
<channel>
<title>Магазин "<?=$shop->name?>"</title>
<link><?=$patch_url?></link>
<description><?=$shop->text?></description>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator><?=$patch_url?></generator>
<!--
<pubDate><?=$shop->date_add?></pubDate>
<lastBuildDate><?=$shop->date_add?> +0300</lastBuildDate>
<copyright><?=$shop->name?></copyright>
<managingEditor><?=$shop->name?></managingEditor>
<webMaster><?=$shop->name?></webMaster>
<language>russian</language>-->
<?php if($act == 'board') {?>
   <?php foreach($blog as $one) {?>
    <? $url = Url::to(['/boardone', 'id'=>$one->id]);?>
 	    <item>
           <title><?=$one->title?></title>
           <description><![CDATA[<? if(isset($one->imageBlog[0]->image)) {?><a href="<?=$patch_url.$url?>" target="_blank"> <img src="<?= $patch_url.Yii::getAlias('@blog_image_mini').'/'.$one->imageBlog[0]->image;?>" align="left"> </a><?}?><?=$one->text?>]]></description>
           <link><?=$patch_url.$url?> </link>
           <guid isPermaLink="true"><?=$patch_url.$url?></guid>
           <pubDate><?=date(DATE_RSS, strtotime($one->date_add))?></pubDate>
        </item>
   <?php }?>
<?php }?>  


<?php if($act == 'article') {?>
   <?php foreach($article as $one) {?>
 <?  $url = Url::to(['/articleone', 'id'=>$one->id]);?>
 	    <item>
           <title><?=$one->title?></title>
           <description><![CDATA[<? if(isset($one->imageShop[0]->image)) {?><a href="<?=$patch_url.$url?>" target="_blank"> <img src="<?= $patch_url.Yii::getAlias('@blog_image_mini').'/'.$one->imageShop[0]->image;?>" align="left"> </a><?}?><?=$one->text?>]]></description>
           <link><?=$patch_url.$url?> </link>
           <guid isPermaLink="true"><?=$patch_url.$url?></guid>
           <pubDate><?=date(DATE_RSS, strtotime($one->date_add))?></pubDate>
        </item>
   <?php }?>
<?php }?>  
</channel>
</rss>
