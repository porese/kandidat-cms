<?php
//phpfile
#Облако тегов - результаты. Флеш модуль в cloudtags.php.
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');
include_once(CONF .'newsconf.php');
$news_Glink='/news'.($news_cat=='1'?'/':'-');
if(isset($_GET['tags'])){
	$thetags=trim($_GET['tags']);
	if(file_exists(ENGINE.'cloudtagsdb.php')){
		$cloud=file(ENGINE.'cloudtagsdb.php');
		$sizeofcloud=sizeof($cloud);
		if($sizeofcloud>0){
			$sdata=explode('%',$cloud[0]);
			for($i=1;$i<$sizeofcloud;$i++){
				$data=explode('%%',$cloud[$i]);
				if(translit($data[0])===$thetags){
					if(substr($data[2],0,5)=='/news'){
					    $news=file(ENGINE.'newsdb.php');
					    $newnum=(int)substr($data[2],6);
					    if($newnum!==0){
							$new=unserialize($news[$newnum-1]);
		    				$head = $new['head'];
							$startnews = $new['mess'];
							$pubdata_text = $new['pubdate'];
							echo '<div class="title"><h2><a href="'.cc_link($news_Glink.$newnum.'.html').'">'.$head.'</a></h2></div><div class="entry">'.$startnews.'</div><br />';
							echo '<p class="links">'.$pubdata_text.'<a href="'.cc_link($news_Glink.$newnum.'.html#comment_begin').'"> | '.__('Комментарии').': '.getcountcomments($new['id'],$commentsdbfilename,$moder_comments).'</a> | <a href="'.cc_link($news_Glink.$newnum.'.html').'" class="comments">'.__('Читать полностью').'</a></p><br /><br />';
						}
					}elseif(file_exists(ARTICLES.$data[2].'.dat')){
						$artdata = file_get_contents(ARTICLES.$data[2].'.dat');
				  	   	$hs_text=articlesparam('title',$artdata);
						$s_text=articlesparam('content',$artdata);
						$pubdata_text=articlesparam('pubdate',$artdata);
						if(!empty($pubdata_text))$pubdata_text=date('d.m.Y',$pubdata_text).' | ';
						$pos = strpos($s_text, ' ',min(strlen($s_text),$saftertitle));
						if($pos)$s_text=substr($s_text,0,$pos);
						$s_text.='....';
						$s_text=close_dangling_tags($s_text);
						if(substr($data[2],-4)=='main')$aname=fsubstr($data[2],0,-4);else $aname=$data[2].'.html';
						echo '<div class="title"><h2>'.art_catalog($data[2],'->').'<a href="'.cc_link($aname).'" name="'.str_replace('/','--',$aname).'">'.$hs_text.'</a></h2></div><div class="entry">'.$s_text.'</div><br />';
						echo '<p class="links">'.$pubdata_text.'<a href="'.cc_link('/'.$aname.'#comment_begin').'">'.__('Комментарии').': '.getcountcomments(0,ARTICLES.$data[2].'.dat.comment',$moder_comments).'</a> | <a href="'.cc_link('/'.$aname).'" class="comments">'.__('Читать полностью').'</a></p><br /><br />';
					}
				$arrclouds[$data[0]]=$arrclouds[$data[0]]+1;
				}
			}
		}
	}
}
?>
