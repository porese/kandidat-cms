<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
$cat  = (isset ($_GET['cat']))? $_GET['cat'] : '';
$subcat  = (isset ($_GET['subcat']))? $_GET['subcat'] : '';

$cattab= '<h3>Категории:</h3><table cellpadding="5" cellspacing="0" width="70%"><thead><td   width="45%">Имя </td><td   width="40%">Ссылка для сайта</td><td colspan="2" width="15%">Действия</td></thead><tbody>';
if($cat =='') { $kanfile=ARTICLES; $razdel='/'; $linkcat='?cat='; $linkedit='?what=main&cat='; $editpage='?what='; @$contentcenter.= 'В корневой директории находятся: <br /><br /><a href="addcat.php">Добавить категорию</a> || <a href="add.php">Добавить страницу</a>'.$cattab; } else { if($subcat =='') { $kanfile=ARTICLES.$cat; $razdel='/'.$cat.'/'; $linkcat='?cat='.$cat.'&subcat='; $linkedit='?what=main&cat='.$cat.'&subcat='; $editpage='?cat='.$cat.'&what='; @$contentcenter.= 'В папке '.$cat.' находятся: <br /><br /><a href="addcat.php?cat='.$cat.'">Добавить категорию</a> || <a href="add.php?cat='.$cat.'">Добавить страницу</a>'.$cattab; } else { $kanfile=ARTICLES.$cat.'/'.$subcat.'/'; $razdel='/'.$cat.'/'.$subcat.'/'; $linkcat=''; $linkedit='';  $editpage='?cat='.$cat.'&subcat='.$subcat.'&what='; @$contentcenter.= 'В папке /'.$cat.'/'.$subcat.'/ находятся:<br /><br /><a style="color:#F7BC5B;" href="add.php?cat='.$cat.'&subcat='.$subcat.'">Добавить страницу</a>'.$cattab; $addlink=''; } }

$sitetitle='Главная';
$dumbcount=0;
$class = 'cline' . ($dumbcount % 2);
if($cat!==''){
	$contentcenter .='<tr class="'.$class.'"><td class="line3"  colspan="4"><a title="Перейти на категорию выше" ';
	if($subcat!=='')$contentcenter .='href="index.php?cat='.$cat.'"';
	else $contentcenter .='href="index.php"';
	$contentcenter .='>. . .</a></td></tr>';
}
$artd=opendir($kanfile);
while ($artfile = readdir ($artd)){
  if($artfile!='.' && $artfile!='..' && $artfile!='...' && filetype($kanfile.'/'.$artfile)=='dir' ){
    if(file_exists($kanfile.'/'.$artfile.'/main.dat')){
		$title_text=file_get_contents($kanfile.'/'.$artfile.'/main.dat');
		$title_artcat=articlesparam('title',$title_text);
		$dumbcount++;
		$class = 'cline' . ($dumbcount % 2);
	    $contentcenter .='<tr class="'.$class.'"><td class="line3"><strong><a title="Перейти в эту категорию" href="index.php'.$linkcat.$artfile.'">'.$title_artcat.'</a></strong></td><td class="line3">'.$razdel.$artfile.'/</td><td class="line3"><a title="Редактровать" href="edit.php'.$linkedit.$artfile.'"><img alt="Редактровать" src="images/edit.png"></a></td><td class="line3"><a title="Удалить" href="delcat.php'.$linkedit.$artfile.'"><img alt="Удалить" src="images/delete.png"></a></td></tr>';
     }
   }
}
closedir($artd);
$contentcenter .='</tbody></table><br>';
$contentcenter.= '<h3>Страницы:</h3><table cellpadding="5" cellspacing="0" width="98%"><thead><td width="80">№&nbsp;&nbsp;</td><td   width="40%">Страница </td><td   width="40%">Ссылка для сайта </td><td colspan="2" width="10%">Действия</td><td colspan="2" width="8%">Коммент.</td><td colspan="1" width="2%">ПП</td></thead><tbody>';

$dir = opendir($kanfile);
$basename = basename($kanfile);
$fileArr = array();
while ($file_name = readdir($dir)){
	$ext=getftype($file_name);
	if (($ext =='dat') &&($file_name !='.') && ($file_name != '..')){
		#Дата модификации...
		$fName = $kanfile.'/'.$file_name;
		$fTime = filemtime($fName);
		$fileArr[$file_name] = $fTime;
	}
}

$dumbcount=0;
$class = 'cline'.($dumbcount % 2);
if($cat =='') { $contentcenter.='<tr class="'.$class.'" ><td class="line3">'.$dumbcount.'</td><td class="line3">Главная страница</td><td class="line3">/ </td><td class="line3"><a title="Редактровать" href="edit.php?what=main"><img src="images/edit.png"></a></td><td class="line3"><img title="Удаление запрещено" alt="Удаление запрещено" src="images/disable.png" /></td><td class="line3">0</td><td class="line3"><img title="Комментарии запрещены" alt="Комментарии запрещены" src="images/disable.png" /></td></tr>';}

arsort($fileArr);
$numberOfFiles = sizeOf($fileArr);
foreach($fileArr as $thisName=>$thisTime){
	$thisTime = date("F j, Y, g:i a", $thisTime);
	if(('dat'==getftype($thisName))&&('main.dat'!==basename($thisName))){
 		$data=file_get_contents($kanfile.'/'.$thisName);
//		$filecomments = $localpath.$linkinfo.'/'.$plink.'.dat.comment';
		@$dumbcount++;
		$class = 'cline' . ($dumbcount % 2);
		$text = articlesparam('title',$data);
		$order = articlesparam('order',$data);
		$plink=menulink($thisName);
		if ($cat=='')$linkinfo='';
		else { if($subcat =='') { $linkinfo='/'.$cat; }
		else { $linkinfo='/'.$cat.'/'.$subcat; }}
		$filecomments = ARTICLES.$linkinfo.'/'.$plink.'.dat.comment';
		$contentcenter.= '<tr class="'.$class.'"><td class="line3">'.$dumbcount . '</td><td  class="line3" width="40%">' . $text . '</td>';
		$contentcenter.= '<td class="line3">' . $linkinfo.'/'.$plink . '.html</td>';
		$contentcenter.= '<td class="line3"><a title="Редактровать" href="edit.php'.$editpage.$plink.'"><img alt="Редактровать" src="images/edit.png"></a></td>';
		$contentcenter.= '<td class="line3"><a title="Удалить" href="delete.php'.$editpage.$plink.'"><img alt="Удалить" src="images/delete.png"></a></td>';
		$contentcenter.= '<td class="line3">'.getcountcomments(0,$filecomments).'</td>';
		$contentcenter.= '<td class="line3"><a title="Комментарии" href="../admin/comments.php'.$editpage.$plink.'"><img src="images/info.png"></a></td>';
		$contentcenter.= '<td class="line3">'. $order . '</td></tr>';
 	}
}
$contentcenter .='</tbody></table>';
include $localpath.'admin/admintemplate.php';
?>
