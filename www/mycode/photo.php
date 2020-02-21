<?php
//phpfile
#Фотогаллерея
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');
include_once CONF.'photoconf.php';
$mode= $_GET['mode'];
$page= isset($_GET['page'])?(int)$_GET['page']:1;
$image= $_GET['image'];
$img=$_GET['img'];
$cat=(isset ($_GET['cat']))? $_GET['cat'] : '';
if($cat =='') { $razdel=''; $link=$prefflp.'/'.$gallerypath.'/';} else { $razdel='-cat-'.$cat; $link=$prefflp.'/'.$gallerypath.'/'.$cat.'/';}

function pages($string){
	global $f,$u,$page,$pages,$cat, $razdel;
  	if($string>$f){
    	if(!isset($page))$page=1;
    	for($u=1;$u<=$pages;$u++)
			if($u!=$page){
				if($u==$pages)$result.= ' <a href="'.cc_link('/photo'.$razdel.'-'.$u.'.html').'">'.$u.'</a>';
          		else $result.= ' <a href="'.cc_link('/photo'.$razdel.'-'.$u.'.html').'">'.$u.'</a>';
          	}else{
        		if($u==$pages)$result.= '&nbsp;'.$u;
        		else $result.= '&nbsp;'.$u;
        	}
	}else $result=1;
  	return $result;
}
function navigatepages($npr){
	global $page, $pages, $razdel;
  	$result='<div class="navigation-photo">'.__('Страницы').':&nbsp;&nbsp;';
    if($page>1)$result.='<a href="'.cc_link('/photo'.$razdel.'-'.($page-1).'.html').'"><b><<</b></a>&nbsp;&nbsp;';
    $result.= pages($npr);
    if($page<$pages)$result.='&nbsp;&nbsp;<a href="'.cc_link('/photo'.$razdel.'-'.($page+1).'.html').'"><b>>></b></a>';
  	$result.='</div>';
  	return $result;
}

echo '<div id="navigation-photo-razdel">'.__('Разделы').':&nbsp;';
if(file_exists(PICTURES.'/info.dat'))$title_cat=loadsimple(PICTURES.'/info.dat');
else $title_cat=__('Главная');
if($cat=='')echo $title_cat.' '; else echo '<a href="'.cc_link('/photo.html').'">'.$title_cat.'</a> ';
$dir=scandir(PICTURES,1);
$count_files=sizeof($dir)-2;
for ($i = 0; $i < $count_files; $i++){
	if(is_dir(PICTURES.$dir[$i])&&($dir[$i]!=='thumb')){
	    if(file_exists(PICTURES.$dir[$i].'/info.dat'))$title_cat=loadsimple(PICTURES.$dir[$i].'/info.dat');
		else $title_cat=__('Без имени');
	    if($cat==$dir[$i]) echo ' | '.$title_cat.' '; else echo ' | <a href="'.cc_link('/photo-cat-'.$dir[$i].'.html').'">'.$title_cat.'</a> ';
	}
}
echo '</div><div>';
//$cat=urldecode($cat);
if(file_exists(PICTURES.$cat)){
		$dir=scandir(PICTURES.$cat,1);
		$count_files=sizeof($dir)-2;
		$arrpict=array();
		for($i = 0; $i < $count_files; $i++)if(in_array(getftype($dir[$i]),$types))$arrpict[]=$dir[$i];
		$pages=ceil(($npr=sizeof($arrpict))/$f);
		if(($page==0)||($page>$pages))$page=1;
		if(file_exists(PICTURES.$cat.'/info.dat'))$title_cat=loadsimple(PICTURES.$cat.'/info.dat');
		else $title_cat=__('Без имени');
		echo '</div><p>'.__('Раздел').':&nbsp;<b>'.$title_cat.'</b> ('.__('всего').': '.$npr.' '.__('фото').').</p>';
		echo navigatepages($npr);
		echo '<br /><div  class="gallery">';
		$i=$ii=1;
		$start=($page-1)*$f+1;
		$end=$page*$f;
		foreach($arrpict as $image){
			if($ii>=$start && $ii<=$end){
				if($i==1) echo '<div class="gallery-row">';
				$info_img=getimagesize(PICTURES.$cat.'/'.$image);
				$opisanie_img=getopisanie($cat,$image);
				echo '<div class="gallery-'.$x.'"><strong>-&nbsp;'.$ii.'&nbsp;-</strong><br />
					<a href="'.$link.$image.'"  title="'.__('имя: ').str_replace('.'.$end,'',$image).'<br />'.__('размер: ').$info_img[0].'x'.$info_img[1].'"	rel="prettyPhoto[gallery-'.$cat.']">
					<img src="'.$link.'thumb/t'.$image.'" alt="'.$opisanie_img.'" /></a>';
				if($info_pic=='1')echo '<br />'.__('имя: ').str_replace('.'.$end,'',$image).'<br>
					<i>'.__('размер: ').$info_img[0].'x'.$info_img[1].'</i>';
				echo '<br><i>'.$opisanie_img.'</i></div>';
				if($i==$x) { echo '</div>'; $i=1; }
				else $i++;
			}
			$ii++;
    	}
    	if($i!==1) echo '</div>';
    	echo '</div>';
    	echo navigatepages($npr);
    }else{
		echo '<p class=error_message>'.__('Во время просмотра произошли ошибки').':</p>';
		echo '<ul class=error_message>';
		echo '<li>'.__('Обзор альбома недоступен!').'</li>';
		echo '</ul>';
	}
echo '<center><p class="back"><a href="javascript:history.back(1)">'.__('Вернуться назад').'</a></p></center>';
?>
