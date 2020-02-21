<?php
//phpfile
#Статьи, автоматически формирует список статей в категории
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');

if(!isset($_REQUEST['spage'])) {$_REQUEST['spage']=1;
} else {$_REQUEST['spage'] = (int)$_REQUEST['spage'];}
$arfiles=array();
$arsubcat=array();

//Статей на странице
#$sperpage=5;
#$saftertitle=450;
$fullcatpage='/'.$catpage;
$fullcatpage.=($subcatpage=='')?'':'/'.$subcatpage;

if(file_exists(ARTICLES."$fullcatpage")){
	$dd=opendir(ARTICLES."$fullcatpage");
	while ($pfile = readdir ($dd))
  	{
  		$ext=getftype($pfile);

  		if($pfile!='.' && $pfile!='..' && ($ext=='dat') && ($pfile!='main.dat') && !is_dir(ARTICLES.$fullcatpage.$pfile) )
    	{
			$data=file_get_contents(ARTICLES.$fullcatpage.'/'.$pfile);
			$order=articlesparam('order',$data);

			$arfiles[$order.'_'.$pfile]= $pfile;
    	}
  		if($pfile!='.' && $pfile!='..' && is_dir(ARTICLES.$fullcatpage.'/'.$pfile))
    	{
			if(file_exists(ARTICLES.$fullcatpage.'/'.$pfile.'/main.dat')){
				$data=file_get_contents(ARTICLES.$fullcatpage.'/'.$pfile.'/main.dat');
				$order=articlesparam('order',$data);

				$arsubcat[$order.'_'.$pfile]=$pfile;
			}
    	}
  	}
  	krsort($arsubcat);
  	reset($arsubcat);

  	$arsubcat = array_values($arsubcat);

 	$countallstati = count($arsubcat);
 	if($countallstati!==0){
		echo '<div class="subcat-title"><h4>'.__('Подкаталоги').'</h4><ul>';
	 	for($i=0;$i<$countallstati;$i++){
		    if(file_exists(ARTICLES.$fullcatpage.'/'.$arsubcat[$i].'/main.dat')){
				$data=file_get_contents(ARTICLES.$fullcatpage.'/'.$arsubcat[$i].'/main.dat');
		  	   	$hs_text=articlesparam('title',$data);
		        $desk_text=articlesparam('description',$data);;
				echo '<li><a href="'.cc_link($fullcatpage.'/'.$arsubcat[$i]).'" >'.$hs_text.' - '.$desk_text.'</a></li>';

			}
		}
		echo '</ul></div>';
	}
  	krsort($arfiles);
  	reset($arfiles);

  	$arfiles = array_values($arfiles);
  	
 	$countallstati = count($arfiles);
	$j = ($countallstati-1)-(($_REQUEST['spage']-1)*$sperpage);
	$i = $j-$sperpage;
	for($j;(($i<$j)&&($j>=0));$j--)  {
   		$p = $j+1;
	    if(file_exists(ARTICLES.$fullcatpage.'/'.$arfiles[$j])){
			$aname=basename($arfiles[$j],'.dat');
			$data=file_get_contents(ARTICLES.$fullcatpage.'/'.$arfiles[$j]);
   	    	$hs_text=articlesparam('title',$data);
			$pubdata_text=articlesparam('pubdate',$data);
			if(!empty($pubdata_text))$pubdata_text=date('d.m.Y',$pubdata_text).' | ';
			echo '<div class="title"><h2><a href="'.cc_link($fullcatpage.'/'.$aname.'.html').'">'.$hs_text.'</a></h2></div>';
			if($sonlytitle!=="1"){
			if($saftertitle!==0){
				$s_text=articlesparam('content',$data);
				$pos=min(strlen($s_text),$saftertitle);
				$pos = strpos($s_text, ' ',$pos);
				if($pos) $s_text=substr($s_text,0,$pos);
				$s_text.='....';
				$s_text=close_dangling_tags($s_text);
				echo '<div class="entry">'.$s_text.'</div><br />';
			}
			$commentsfilename=ARTICLES.$fullcatpage.'/'.$aname.'.dat.comment';
			echo '<p class="links">'.$pubdata_text.'<a href="'.cc_link($fullcatpage.'/'.$aname.'.html#comment_begin').'">'.__('Комментарии').': '.getcountcomments(0,$commentsfilename,$moder_comments).'</a> | ';
			echo '<a href="'.cc_link($fullcatpage.'/'.$aname.'.html').'" class="comments">'.__('Читать полностью').'</a></p><br />';
			}
		}
	}
	if($countallstati>0){
	    echo '<div id="navigation"><b>'.__('Страницы').':</b>&nbsp;';
	    $sperpage=empty($sperpage)?20:$sperpage;
		$all = ceil($countallstati/$sperpage);
		for($i=1;$i<=$all;$i++) {
			if($_REQUEST['spage']==$i) {
			echo '&laquo;<b>'.$i.'</b>&raquo;&nbsp;';
			}
			else {
	    		  echo '<a href="'.cc_link($fullcatpage.'/spage-'.$i.'.html').'">'.$i.'</a>&nbsp;';
			}
		}
		echo '</div>';
	}
}
?>
