<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
/* @var $urls */
/* @var $host */
 	
	$patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
?>
<atom:link href="<?=$patch_url?>/rss_board.xml" rel="self" type="application/rss+xml" />

<channel>

<title><?=Yii::$app->caches->setting()['title-site']?></title>
<link><?=$patch_url?></link>
<description><?=Yii::$app->caches->setting()['description-site']?></description>
<pubDate>Thu, 31 Oct 2019 19:57:15 +0300</pubDate>
<lastBuildDate>Thu, 31 Oct 2019 19:57:15 +0300</lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator><?=Yii::$app->caches->setting()['site_name']?></generator>
<!--<copyright><?=Yii::$app->caches->setting()['copyrait']?></copyright>
<managingEditor><?=Yii::$app->caches->setting()['email']?></managingEditor>
<webMaster><?=Yii::$app->caches->setting()['email']?></webMaster>
<language>russian</language>-->
<?php if($act == 'board') {?>
   <?php foreach($blog as $one) {?>
   <? $url = Url::to(['blog/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
 	    <item>
           <title><?=$one->title?></title>
           <description><![CDATA[<? if(isset($one->imageBlog[0]->image)) {?><a href="<?=$patch_url.$url?>" target="_blank"> <img src="<?= $patch_url.Yii::getAlias('@blog_image_mini').'/'.$one->imageBlog[0]->image;?>" align="left"> </a><?}?><?=$one->text?>]]></description>
           <link><?=$patch_url.$url?> </link>
           <guid isPermaLink="true"><?=$patch_url.$url?></guid>
           <pubDate><?=date(DATE_RSS, strtotime($one->date_add))?></pubDate>
        </item>
   <?php }?>
<?php }?>  



<?php if($act == 'akcii') {?>
   <?php foreach($blog as $one) {?>
   <? $url = Url::to(['blog/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'url'=>$one->url, 'id'=>$one->id]);?>
 	    <item>
           <title><?=$one->title?></title>
           <description><![CDATA[<? if(isset($one->imageBlog[0]->image)) {?><a href="<?=$patch_url.$url?>" target="_blank"> <img src="<?= $patch_url.Yii::getAlias('@blog_image_mini').'/'.$one->imageBlog[0]->image;?>" align="left"> </a><?}?><?=$one->text?>]]></description>
           <link><?=$patch_url.$url?> </link>
           <guid isPermaLink="true"><?=$patch_url.$url?></guid>
           <pubDate><?=date(DATE_RSS, strtotime($one->date_add))?></pubDate>
        </item>
   <?php }?>
   
   
   
   <?php foreach($article as $one) {?>
   <? $url = Url::to(['article/one', 'category'=>$one->cats['url'], 'id'=>$one->id, 'name'=>Yii::$app->userFunctions->transliteration($one->title)]);?>
 	    <item>
           <title><?=$one->title?></title>
           <description><![CDATA[<? if(isset($one->imageShop[0]->image)) {?><a href="<?=$patch_url.$url?>" target="_blank"> <img src="<?= $patch_url.Yii::getAlias('@blog_image_mini').'/'.$one->imageShop[0]->image;?>" align="left"> </a><?}?><?=$one->text?>]]></description>
           <link><?=$patch_url.$url?> </link>
           <guid isPermaLink="true"><?=$patch_url.$url?></guid>
           <pubDate><?=date(DATE_RSS, strtotime($one->date_add))?></pubDate>
        </item>
   <?php }?>
<?php }?>


<?php if($act == 'shop') {?>
   <?php foreach($shop as $one) {?>
 <? $url = Url::to(['shop/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'id'=>$one->id, 'name'=>Yii::$app->userFunctions->transliteration($one->name)]);?>
 	    <item>
           <title><?=$one->name?></title>
           <description><![CDATA[<? if(isset($one->imageShop[0]->image)) {?><a href="<?=$patch_url.$url?>" target="_blank"> <img src="<?= $patch_url.Yii::getAlias('@blog_image_mini').'/'.$one->imageShop[0]->image;?>" align="left"> </a><?}?><?=$one->text?>]]></description>
           <link><?=$patch_url.$url?> </link>
           <guid isPermaLink="true"><?=$patch_url.$url?></guid>
          <pubDate><?=date(DATE_RSS, strtotime($one->date_add))?></pubDate>
        </item>
   <?php }?>
<?php }?> 




<?php if($act == 'article') {?>
   <?php foreach($article as $one) {?>
 <? $url = Url::to(['article/one', 'category'=>$one->cats['url'], 'id'=>$one->id, 'name'=>Yii::$app->userFunctions->transliteration($one->title)]);?>
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
