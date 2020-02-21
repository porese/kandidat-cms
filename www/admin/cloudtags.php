<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
$url=$_SERVER['PHP_SELF'];
$sitetitle='Настройка облака меток';
$filename=ENGINE.'cloudtagsdb.php';
@$contentcenter.= '<h3>'.$sitetitle.'</h3>';

if (isset($_REQUEST['settings'])) {
	$x=(int)$_REQUEST['x'];
	$y=(int)$_REQUEST['y'];
	$pxsize=(int)$_REQUEST['pxsize'];
	$bgcolor=trim($_REQUEST['bgcolor']);
	$color=trim($_REQUEST['color']);
	$color2=trim($_REQUEST['color2']);
	$colorh=trim($_REQUEST['colorh']);
	$speed=(int)$_REQUEST['speed'];

	$sdata='%'.$pxsize.'%'.$x.'%'.$y.'%'.$bgcolor.'%'.$color.'%'.$speed.'%'.$color2.'%'.$colorh.'%';

	if((int)$_REQUEST['maketags']==1){
		$cloud[0]=$sdata;

		$cat_st=get_articlessubdir();
		$cat_st[]='';

		$count_files=sizeof($cat_st);
		for ($i = 0; $i < $count_files; $i++){
			$dd=opendir(ARTICLES.$cat_st[$i]);
			while ($pfile = readdir($dd)){
		  		$ext=getftype($pfile);
		  		if($pfile!='.' && $pfile!='..' && ($ext=='dat') && ($pfile!='404.dat') && !is_dir(ARTICLES.$pfile)){
					$data=file_get_contents(ARTICLES.$cat_st[$i].'/'.$pfile);
					$title=articlesparam('title',$data);
					$tags=articlesparam('tags',$data);
					if(!empty($tags)){
						$tags=explode(' ',$tags);
						if(is_array($tags))foreach($tags as $point){
							$cllink=$cat_st[$i]==''?'':$cat_st[$i].'/';$cllink.=basename($pfile,'.dat');
							$cloud[]=$point.'%%'.$title.'%%'.$cllink.'%%';
						}
					}
				}
		   	}
		}

	    $news=file(ENGINE.'newsdb.php');
		$news_Glink='/news'.($news_cat=='1'?'/':'-');
	    if(($count=sizeof($news))>0){
			for($i=0;$i<$count;$i++){
				$new=unserialize($news[$i]);
				if(isset($new['tags'])){
					$tags=explode(' ',$new['tags']);
					if(is_array($tags))foreach($tags as $point){
						$cllink=$news_Glink.($i+1);
						$cloud[]=$point.'%%'.$new['head'].'%%'.$cllink.'%%';
					}
				}
			}
		}

		savearray($filename, $cloud, $rez='w+');
	}else{
		$cloud=file($filename);
		$cloud[0]=$sdata;
		savearray($filename, $cloud, $rez='w+');
	}

}else{
	if(file_exists(ENGINE.'cloudtagsdb.php')){
		$scloud=file(ENGINE.'cloudtagsdb.php');
		$sizeofcloud=sizeof($scloud);
		if($sizeofcloud>0){
			$sdata=explode('%',$scloud[0]);
			$pxsize=$sdata[1];
			$x=$sdata[2];
			$y=$sdata[3];
			$bgcolor=$sdata[4];
			$color=$sdata[5];
			$speed=$sdata[6];
			$color2=$sdata[7];
			$colorh=$sdata[8];
		}
	}
}
$contentcenter .=<<<EOT
<form action="$url" method="post" name="settings_form">
<label title="Размер шрифта (оптимально 10-12)">Размер шрифта*<br />
<input class="settings" type="text" name="pxsize" id="title" value="$pxsize"></label>
<br /><br />
<label title="Высота блока подбирается исходя из шаблона ">Высота блока*<br />
<input class="settings" type="text" name="y" id="title" value="$y"></label>
<br /><br />
<label title="Ширина блока подбирается исходя из шаблона ">Ширина блока*<br />
<input class="settings" type="text" name="x" id="title" value="$x"></label>
<br /><br />
<label title="Цвет фона (белый ffffff) ">Цвет фона*<br />
<input class="settings" type="text" name="bgcolor" id="title" value="$bgcolor"></label>
<br /><br />
<label title="Цвет шрифта (серый 333333) ">Цвет текста*<br />
<input class="settings" type="text" name="color" id="title" value="$color"></label>
<br /><br />
<label title="Цвет шрифта (серый 333333) ">Цвет текста 2*<br />
<input class="settings" type="text" name="color2" id="title" value="$color2"></label>
<br /><br />
<label title="Цвет шрифта (серый 333333) ">Цвет текста "hover"*<br />
<input class="settings" type="text" name="colorh" id="title" value="$colorh"></label>
<br /><br />
<label title="Скорость движения элементов в облаке (оптимально 115 ) ">Скорость движения элементов*<br />
<input class="settings" type="text" name="speed" id="title" value="$speed"></label>
<br /><br />
<label><input class="settings" type="checkbox" name="maketags" id="title" value="1" />Построить теги. Выполнять после добавления или удаления страниц и новостей с тегами.</label><br />
<br /><br />
<div class="submit"><input type="submit" class="submit-button" id="Submit" name="settings" value="Сохранить изменения" /></div><br />
</form>
EOT;

$contentcenter .='<br /><br /><br /><center><h3>Образец</h3><br />';
if(file_exists(ENGINE.'cloudtagsdb.php')){
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
	}
}
$contentcenter .='<script type="text/javascript" src="'.$prefflp.'/js/swfobject.js"  charset="utf8"></script>
<div id="tags">
<p>'.str_replace('><','><br /><',$tags).'</p>
<p>Облако тегов требует для просмотра <noindex><a href="http://www.adobe.com/go/getflashplayer" target="_blank" rel="nofollow">Flash Player 9</a></noindex> или выше.</p>
<script type="text/javascript">
	var rnumber = Math.floor(Math.random()*9999999);
	var widget_so = new SWFObject("'.$prefflp.'/js/tagcloud.swf?r="+rnumber, "tagcloudflash", "'.$x.'", "'.$y.'", "9", "#'.$bgcolor.'");
	widget_so.addParam("allowScriptAccess", "always");
	widget_so.addParam("wmode", "transparent");
	widget_so.addVariable("tcolor", "0x'.$color.'");
	widget_so.addVariable("tcolor2", "0x'.$color2.'");
	widget_so.addVariable("hicolor", "0x'.$colorh.'");
	widget_so.addVariable("tspeed", "'.$speed.'");
	widget_so.addVariable("distr", "false");
	widget_so.addVariable("mode", "tags");
	widget_so.addVariable("tagcloud", "'.urlencode($tags).'");
	widget_so.write("tags");</script>
</div>
</center>';
include $localpath.'/admin/admintemplate.php';
