<?php
//phpfile
#Блогоподобная структура-список категорий->страниц. Попадают только страницы с заполненной датой
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');

if(!isset($_REQUEST['page'])) {$_REQUEST['page']=1;
} else {$_REQUEST['page'] = (int)$_REQUEST['page'];}
$arfiles=array();
$url=$whatpage;if($url=='')$url='lenta';
//Статей на странице
$sperpage=4;
//
$cat_st=get_articlessubdir();
$cat_st[]="";

$count_files=sizeof($cat_st);
for ($i = 0; $i < $count_files; $i++){
	$dd=opendir(ARTICLES.$cat_st[$i]);
	while ($pfile = readdir ($dd))
  	{
  		$ext=getftype($pfile);

  		if($pfile!='.' && $pfile!='..' && ($ext=='dat') && ($pfile!='404.dat') && !is_dir(ARTICLES.$pfile) )
    	{
			$data=file_get_contents(ARTICLES.$cat_st[$i]."/".$pfile);
			$pubdate=articlesparam('pubdate',$data);
			if($pubdate!==""){
			    if($cat_st[$i]=="")$thelink=$pfile;else $thelink=$cat_st[$i].'/'.$pfile;
			    $arfiles[$thelink]=$pubdate;
			}
    	}
  	}
}
arsort($arfiles);
reset($arfiles);
$index_arr=0;

$countallstati = count($arfiles);
$j = (($_REQUEST['page']-1)*$sperpage);

foreach($arfiles as $key=>$val){
	if(($index_arr>=$j) && ($index_arr<$j+$sperpage)){
	if(file_exists(ARTICLES.$key)){
		$data = file_get_contents(ARTICLES.$key);
		$aname=str_replace('.dat%','',$key.'%');

   	   	$hs_text=articlesparam('title',$data);
		$s_text=articlesparam('content',$data);
		$pubdata_text=articlesparam('pubdate',$data);
		if(!empty($pubdata_text))$pubdata_text=date('d.m.Y',$pubdata_text).' | ';

		$pos = @fstrpos($s_text, ' ',1000);
		if($pos) $s_text=fsubstr($s_text,0,$pos);
		$s_text.="....";
		$s_text=close_dangling_tags($s_text);
		$commentsfilename=ARTICLES.$aname.'.dat.comment';
		if(substr($aname,-4)=='main')$aname=fsubstr($aname,0,fstrlen($aname)-4);else $aname.='.html';
		echo '<div class="title"><h2>'.art_catalog($key,'->').'<a href="'.cc_link('/'.$aname).'" name="'.str_replace('/','--',$aname).'">'.$hs_text.'</a></h2></div><div class="entry">'.$s_text.'</div><br />';
		echo '<p class="links">'.$pubdata_text.'<a href="'.cc_link('/'.$aname.'#comment_begin').'">'.__('Комментарии').': '.getcountcomments(0,$commentsfilename,$moder_comments).'</a> | <a href="'.cc_link('/'.$aname).'" class="comments">'.__('Читать полностью').'</a></p><br /><br />';
	}
	}
	$index_arr++;
}
    echo '<div id="navigation"><b>'.__('Страницы').':</b>&nbsp;';
	$all = ceil($countallstati/$sperpage);
	for($i=1;$i<=$all;$i++) {
		if($_REQUEST['page']==$i) {
		echo '&laquo;<b>'.$i.'</b>&raquo;&nbsp;';
		}
		else {
    		  echo '<a href="'.cc_link('/'.$url.'-'.$i.'.html').'">'.$i.'</a>&nbsp;';
		}
	}
	echo '</div>';
?>
