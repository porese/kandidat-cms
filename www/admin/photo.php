<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(2>getpermision())header('LOCATION:index.php');
include CONF.'photoconf.php';
@$page = (int)$_GET[page];
@$delcat = $_GET['delcat'];
@$del = $_GET['del'];
@$del_ok = (int)$_GET[ok];
@$save = (int)$_GET[ok];
@$addpic = $_GET[addpic];
@$edit = $_GET['edit'];
$url=$_SERVER['PHP_SELF'];
$sitetitle='Фото-альбом';
$cat  = (isset ($_GET['cat']))? $_GET['cat'] : "";
if($cat =='') { $razdel=''; $link=$prefflp.'/'.$gallerypath.'/'; $piccat=PICTURES;} else { $razdel='cat='.$cat.'&'; $link=$prefflp.'/'.$gallerypath.'/'.$cat.'/'; $piccat=PICTURES.$cat.'/';}

function pages($string){
	global $f,$u,$page,$pages,$cat, $razdel;
	if($string>$f){
		if(!isset($page))$page=1;
		for($u=1;$u<=$pages;$u++)
			if($u!=$page){
				if($u==$pages)$tmpstr.= ' <a href="photo.php?'.$razdel.'page='.$u.'">'.$u.'</a>';
				else $tmpstr.= ' <a href="photo.php?'.$razdel.'page='.$u.'">'.$u.'</a>';
			}else{
				if($u==$pages)$tmpstr.= '&nbsp;'.$u;
				else $tmpstr.= '&nbsp;'.$u;
			}
	}else return 1;
	return $tmpstr;
}
function navigatepages($npr){
	global $page, $pages, $razdel;
  	$result='<div class="navigation-photo">'.__('Страницы').':&nbsp;&nbsp;';
    if($page>1)$result.='<a href="photo.php?'.$razdel.'page='.($page-1).'"><b><<</b></a>&nbsp;&nbsp;';
    $result.= pages($npr);
    if($page<$pages)$result.='&nbsp;&nbsp;<a href="photo.php?'.$razdel.'page='.($page+1).'"><b>>></b></a>';
  	$result.='</div>';
  	return $result;
}


if ($edit) {
      $myFile=$piccat.'namedb.dat';
      $info_img=getimagesize($piccat.$edit);
	  $opisanie=getopisanie($cat,$edit);
      @$contentcenter.= '<h3>Редактирование Фото</h3>';
      $contentcenter.='<br /><table align="center" cellspacing="5" border="0" width="80%">
		<tr>
		<td valign=top align=center width="98%"><table cellspacing="1" cellpadding="2" width="100%" align="center" border="0">
  			<tr><td colspan="2" align="center">Раздел: <b>'.$cat.'</b></td>
    		</tr><tr>
    		<td colspan="2" valign="middle" align="center">
	          <a href="'.$link.$edit.'" target="_blank" rel="prettyPhoto[gallery-'.$cat.']">
  		      <img src="'.$link.'thumb/t'.$edit.'" border="1"></a>
      	  	  <br />имя: '.$edit.'<br />
        	  <i>размер: '.$info_img[0].'x'.$info_img[1].'</i><br /><hr>';
	  $contentcenter.='Описание:<br />
			  <form action=?cat='.$cat.'&edit='.$edit.'&ok=1 name="uploadForm" method="post">
		  	  <textarea name="Opisanie" cols="40" row="6">'.$opisanie.'</textarea><br />
  			  <input type="submit" value="Сохранить" /><br />
			  </form></table>';

      if($save){
			$opisanie=$_POST['Opisanie'];
			$opisanie=str_replace("\n",' ',$opisanie);
			$opisanie=str_replace("\r",'',$opisanie);
			$data=array('fname'=>$cat.'/'.$edit,
								  'opisanie'=>$opisanie);
			$present=0;
			$arr=array();
			if(file_exists($myFile)){
				$arr=loaddata($myFile);
			}
			$present=false;
			if(is_array($arr))foreach ($arr as $key=>$ta){
				if (($cat.'/'.$edit)==$ta['fname']){
					$arr[$key]['opisanie']=$opisanie;
					$present=true;
					break;
				}
			}
			if(!$present){
				$arr[]=$data;
			}
			savedataarray($myFile,$arr,'w');

			header("LOCATION: $prefflp/admin/photo.php?edit=$edit&cat=$cat");
			exit;
  	  }
      $contentcenter.= '<br /><br /><a href=\'javascript:history.back(1)\'><b>Вернуться назад</b></a><tr></table>';
      include LOCALPATH.'/admin/admintemplate.php'; exit;
}


if ($addpic) {
  include(LOCALPATH.'/admin/uploadFile.php');
}
if ($delcat) {
	$photoFile = PICTURES.$delcat;
	if(file_exists($photoFile) ){
		if ($del_ok>0) {
			!full_del_dir($photoFile);
			@$contentcenter.= '<h3>Удаление Категории</h3>';
			$contentcenter.= 'Категория <b>'.$delcat.'</b> успешно удалена!<br /><br /><a href="../admin/photo.php"><B>Вернуться в фото-альбом</B></a>';
		} else {
			@$contentcenter.= '<h3>Удаление Категории</h3>';
			$contentcenter.= 'Вы действительно хотите удалить <b>'.$delcat.'</b><br /><a title="Удалить" href="../admin/photo.php?delcat='.$delcat.'&ok=1">ДА</a> | <a title="Отложить" href="../admin/photo.php">НЕТ</a><br /><br /><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';
		}
	} else {
		@$contentcenter.= '<h3>Удаление Категории</h3>';
		$contentcenter.= 'Такой категории <b>'.$delcat.'</b> нет!<br /><br /><a href=\'javascript:history.back(1)\'><b>Вернуться назад</b></a>';
	}
	include LOCALPATH.'/admin/admintemplate.php';
	exit;
}
if ($del) {
	$photoFile = $piccat.$del;
	$thumb_photoFile = $piccat.'/thumb/t'.$del;
	$printcat=$cat;
	if (!$cat) $printcat='Разное';
	if(file_exists($photoFile) || file_exists($thumb_photoFile)){
		if ($del_ok>0) {
			@unlink($photoFile);
			@unlink($thumb_photoFile);
			@$contentcenter.= '<h3>Удаление Фото</h3>';
			$contentcenter.= 'Фото <B>'.$del.'</B> успешно удалено!<br /><br /><a href="../admin/photo.php?cat='.$cat.'&page='.$page.'"><B>Вернуться в фото-альбом</B></a>';
		} else {
			@$contentcenter.= '<h3>Удаление Фото</h3>';
			$contentcenter.= 'Вы действительно хотите удалить <B>'.$del.'</B> из каталога <B>'.$printcat.'</B><br /><a title="Удалить" href="../admin/photo.php?del='.$del.'&cat='.$cat.'&page='.$page.'&ok=1">ДА</a> | <a title="Отложить" href="../admin/photo.php">НЕТ</a><br /><br /><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';
		}
	} else {
		@$contentcenter.= '<h3>Удаление Фото</h3>';
		$contentcenter.= 'Такого файла <B>'.$del.'</B> нет!<br /><br /><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';
	}
	include LOCALPATH.'/admin/admintemplate.php';
	exit;
}

$contentcenter.='<h3>Фото-альбом</h3><a href="../admin/potoset.php">Настройки альбома</a> | <a href="../admin/addphotocat.php">Добавить раздел</a><br /><br />Разделы:';
if(file_exists(PICTURES.'/info.dat'))$title_cat=loadsimple(PICTURES.'/info.dat');
else $title_cat=__('Главная');
if($cat=='')$contentcenter.=$title_cat.' '; else $contentcenter.='<a href="../admin/photo.php">'.$title_cat.'</a> ';
$contentcenter.=' <a title="Редактровать" href="../admin/edithpotocat.php"><img src="images/edit.png" border="0"></a>';
$dir=scandir(PICTURES,1);
$count_files=sizeof($dir)-2;
for ($i = 0; $i < $count_files; $i++){
	if(is_dir(PICTURES.$dir[$i])&&($dir[$i]!=='thumb')){
	    if(file_exists(PICTURES.$dir[$i].'/info.dat'))$title_cat=loadsimple(PICTURES.$dir[$i].'/info.dat');
		else $title_cat=__('Без имени');
	    if($cat==$dir[$i]) $contentcenter.=' | '.$title_cat; else $contentcenter.=' | <a href="../admin/photo.php?cat='.$dir[$i].'">'.$title_cat.'</a>';
	    $contentcenter.=' <a title="Редактровать" href="../admin/edithpotocat.php?cat='.$dir[$i].'"><img src="images/edit.png" border="0"></a> <a title="Удалить" href="../admin/photo.php?delcat='.$dir[$i].'"><img alt="Удалить" src="images/delete.png" border="0"></a>';
	}
}

$contentcenter.= '<br /><br /><table cellspacing=5 border=0 width=100%><tr><td valign=top align=center width=96%>';
$cat=urldecode($cat);
if(file_exists(PICTURES.$cat)){
	$dir=scandir(PICTURES.$cat,1);
	$count_files=sizeof($dir)-2;
	$arrpict=array();
	for($i = 0; $i < $count_files; $i++)if(in_array(getftype($dir[$i]),$types))$arrpict[]=$dir[$i];
	$pages=ceil(($npr=sizeof($arrpict))/$f);
	if(($page==0)||($page>$pages))$page=1;
	if(file_exists(PICTURES.$cat.'/info.dat'))$title_cat=loadsimple(PICTURES.$cat.'/info.dat');
	else $title_cat=__('Без имени');
    $contentcenter.='<table cellspacing=1 cellpadding=2 width="100%" align=center border=0>';
	$contentcenter.='</div><p>'.__('Раздел').':&nbsp;<b>'.$title_cat.'</b> ('.__('всего').': '.$npr.' '.__('фото').').</p>';
	$contentcenter.=navigatepages($npr);
	$contentcenter.='<br />';
	$i=$ii=1;
	$start=($page-1)*$f+1;
	$end=$page*$f;
	foreach($arrpict as $image){
      	$ext=getftype($image);
      	if($image!='.' && $image!='..' && in_array($ext,$types) && filetype($piccat.$image)=='file'){
        	if($ii>=$start && $ii<=$end){
          		if($i==1) $contentcenter.='<tr>';
          		$info_img=getimagesize($piccat.$image);
          		$contentcenter.='<td class=alt2 valign=middle align=center><strong>- '.$ii.' -</strong><br />
          			<a href="'.$link.$image.'" target="_blank" rel="prettyPhoto[gallery-'.$cat.']">
          			<img src="'.$link.'thumb/t'.$image.'" border=1 /></a>
          			<br />имя: '.str_replace(".$end","",$image).'<br />
          			<i>размер: '.$info_img[0].'x'.$info_img[1].'</i><br />
          			<i>описание: '.getopisanie($cat,$image).'</i><br />
          			<a href="'.$prefflp.'/admin/photo.php?edit='.$image.'&cat='.$cat.'&page='.$page.'"><img title="Редактирование" src="images/edit.png" border="0" /></a>
          			<a href="'.$prefflp.'/admin/photo.php?del='.$image.'&cat='.$cat.'&page='.$page.'"><img title="Удалить" src="images/delete.png" border="0" /></a></td>';
          		if($i==$x) { $contentcenter.='</tr>'; $i=1;}else $i++;
          	}
        	$ii++;
       }
	}
    $contentcenter.='<tr><td colspan='.$x.' valign=middle align=center>';
	$contentcenter.=navigatepages($npr);
    $contentcenter.='</td></tr></table>';
}else{
   	$contentcenter.='<table cellspacing=1 cellpadding=6 width="100%" align=center border=0>
     	<tr><td>Ошибка!</td></tr>
      	<tr><td align=middle>Обзор альбома недоступен!</td></tr>
   </table>';
}
$contentcenter.='</td></tr>
<tr><td><table cellspacing=1 cellpadding=6 width="100%" align=center border=0>
<tr><td align=center><span><center><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a> | <a href="photo.php?'.$razdel.'page='.($page).'"><B>Обновить в фото-альбом</B></a></span></td></tr>
</table></td></tr></table>
<div align="center">';

//<!--Загружаем фото -->
if($multiupload=='1'){
	$contentcenter.=<<<EOT
		<div id="file-uploader">
			<noscript>
				<iframe id="hiddenframe" name="hiddenframe" style="width:0px; height:0px; border:0px"></iframe>
				<form action=?addpic=1&cat=$cat&page=$page name="uploadForm" method="post" enctype="multipart/form-data"
					target="hiddenframe" onsubmit="document.getElementById('res').innerHTML='Подождите, идёт загрузка...<br /><img src=images/loader.gif />';return true;">
				    <input type="file" name="Filedata" />
				    <input type="submit" value="Загрузить" /><br />
				</form>
				<div id="res"></div>
				<p>Мультипоточный загрузчик недоступен в связи с отсутствием поддержки js в Вашем браузере.</p>
			</noscript>
		</div>

	    <script src="$prefflp/admin/js/fileuploader.js" type="text/javascript" charset=utf-8></script>
			<style type="text/css"><!--
				@import "$prefflp/admin/css/fileuploader.css";
			--></style>
	    <script>
	        function createUploader(){
	            var uploader = new qq.FileUploader({
	                element: document.getElementById('file-uploader'),
	                action: 'uploadFiles.php',
	                params:{
						cat: '$cat',
						page:'$page'
					},
	                allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
			        template: '<div class="qq-uploader">' +
			                '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
			                '<div class="qq-upload-button">Загрузить файлы</div>' +
			                '<ul class="qq-upload-list"></ul>' +
			             '</div>',
	           	});
	        }
	        window.onload = createUploader;
	    </script>
EOT;
}else{
	$contentcenter.='<iframe id="hiddenframe" name="hiddenframe" style="width:0px; height:0px; border:0px"></iframe>
	<form action=?addpic=1&cat='.$cat.'&page='.$page.' name="uploadForm" method="post" enctype="multipart/form-data"
		target="hiddenframe" onsubmit="document.getElementById(\'res\').innerHTML=\'Подождите, идёт загрузка...<br /><img src=images/loader.gif />\';return true;">
	    <input type="file" name="Filedata" />
	    <input type="submit" value="Загрузить" /><br />
	</form>
	<div id="res"></div>';
}
$contentcenter.='</div>';
include LOCALPATH.'/admin/admintemplate.php';
?>
