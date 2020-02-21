<script type="text/javascript" src="<? echo $prefflp;?>/js/swfobject.js"  charset="utf-8"></script>
<div id="tags"><p>
<?php
#Облако тегов на flash. Подключается по include в удобном месте шаблона.
if(file_exists(ENGINE.'cloudtagsdb.php')){
	$arrclouds = array();
	$cloud=file(ENGINE.'cloudtagsdb.php');
	$sizeofcloud=sizeof($cloud);
	if($sizeofcloud>0){
		$sdata=explode('%',$cloud[0]);
		for($i=1;$i<$sizeofcloud;$i++){
			$data=explode('%%',$cloud[$i]);
			$arrclouds[$data[0]]=$arrclouds[$data[0]]+1;
		}
		$tags = '<tags>';
		if(is_array($arrclouds))foreach($arrclouds as $key=>$val)$tags .= '<a href="'.cc_link('/cloudtags-'.translit($key).'.html').'" style="font-size: '.($sdata[1]+$val-1).'pt">'.$key.'</a>';
		$tags .= '</tags>';
	}else exit;
	echo str_replace('><','><br /><',$tags);
}
?>
</p>
<p>Облако тегов требует для просмотра <noindex><a href="http://www.adobe.com/go/getflashplayer" target="_blank" rel="nofollow">Flash Player 9</a></noindex> или выше.</p>
<script type="text/javascript">
	var rnumber = Math.floor(Math.random()*9999999);
	var widget_so = new SWFObject("<? echo $prefflp;?>/js/tagcloud.swf?r="+rnumber, "tagcloudflash", "<? echo $sdata[2];?>", "<? echo $sdata[3];?>", "9", "#<? echo $sdata[4];?>");
	widget_so.addParam("allowScriptAccess", "always");
	widget_so.addParam("wmode", "transparent");
	widget_so.addVariable("tcolor", "0x<? echo $sdata[5];?>");
	widget_so.addVariable("tspeed", "<? echo $sdata[6];?>");
	widget_so.addVariable("tcolor2", "0x<? echo $sdata[7];?>");
	widget_so.addVariable("hicolor", "0x<? echo $sdata[8];?>");
	widget_so.addVariable("distr", "false");
	widget_so.addVariable("mode", "tags");
	widget_so.addVariable("tagcloud", "<?php echo urlencode($tags); ?>");
	widget_so.write("tags");</script>
</div>

