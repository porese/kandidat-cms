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
	$color=$_REQUEST['color'];
	$outlinecolor=$_REQUEST['outlinecolor'];
	$pxsize=(int)$_REQUEST['pxsize'];

	$sdata='%'.$pxsize.'%'.$x.'%'.$y.'%'.$color.'%'.$outlinecolor.'%';

	if(isset($_REQUEST['maketags'])&&(int)$_REQUEST['maketags']==1){
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

		file_put_contents($filename, serialize($cloud));
	}else{
		$cloud = unserialize(file_get_contents($filename));
		$cloud[0]=$sdata;
		file_put_contents($filename, serialize($cloud));
	}

}else{
	if(file_exists(ENGINE.'cloudtagsdb.php')){
		$scloud = unserialize(file_get_contents($filename));
		$sizeofcloud=sizeof($scloud);
		if($sizeofcloud>0){
			$sdata=explode('%',$scloud[0]);
			$pxsize=$sdata[1];
			$x=$sdata[2];
			$y=$sdata[3];
			$color=$sdata[4];
			$outlinecolor=$sdata[5];
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
<label title="Цвет шрифта (красный ff0000) ">Цвет текста*
<input class="settings" type="color" name="color" id="title" value="$color">$color</label>
<br /><br />
<label title="Цвет выделения (серый 333333, ff00ff) ">Цвет выделения*
<input class="settings" type="color" name="outlinecolor" id="title" value="$outlinecolor">$outlinecolor</label>
<br /><br />
<label><input class="settings" type="checkbox" name="maketags" id="title" value="1" />Построить теги. Выполнять после добавления или удаления страниц и новостей с тегами.</label><br />
<br /><br />
<div class="submit"><input type="submit" class="submit-button" id="Submit" name="settings" value="Сохранить изменения" /></div><br />
</form>
EOT;

$contentcenter .='<br /><br /><br /><center><h3>Образец</h3><br />';
if(file_exists(ENGINE.'cloudtagsdb.php')){
	$cloud=unserialize(file_get_contents($filename));
	$sizeofcloud=sizeof($cloud);
	if($sizeofcloud>0){
		$sdata=explode('%',$cloud[0]);
		for($i=1;$i<$sizeofcloud;$i++){
			$data=explode('%%',$cloud[$i]);
			@$arrclouds[$data[0]]=$arrclouds[$data[0]]+1;
		}
		$tags = '<ul>';
		if(is_array($arrclouds))foreach($arrclouds as $key=>$val)$tags .= '<li><a href="'.cc_link('/cloudtags-'.translit($key).'.html').'" style="font-size: '.($sdata[1]+$val-1).'pt">'.$key.'</a></li>';
		$tags .= '</ul>';
	}
}

$contentcenter .='<script src="'.$prefflp.'/js/jquery.tagcanvas.min.js" type="text/javascript"></script>
<div id="myCanvasContainer">
<canvas width="'.$x.'" height="'.$y.'" id=\'myCanvas\'>
<p>Anything in here will be replaced on browsers that support the canvas element</p>
</canvas>
</div>
<div id="tags">
	'.$tags.'
</div>
<script type="text/javascript">
  window.onload = function() {
    try {
      TagCanvas.Start("myCanvas","tags",{
            textColour: "'.$color.'",
            outlineColour: "'.$outlinecolor.'",
            reverse: true,
            depth: 0.8,
            maxSpeed: 0.05
          });
    } catch(e) {
      // something went wrong, hide the canvas container
      document.getElementById(\'myCanvasContainer\').style.display = \'none\';
    }
  };
</script>';

include $localpath.'/admin/admintemplate.php';
