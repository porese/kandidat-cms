<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
$sitetitle="Управление комментариями";

$whatpage = preg_replace("/[^a-z0-9-_]/i","",$_REQUEST['what']);
$cat  = (isset ($_GET['cat']))? trim($_GET['cat']) : "";
$subcat  = (isset ($_GET['subcat']))? trim($_GET['subcat']) : "";

$edit = (isset($_GET['edit'])) ? (int)$_GET['edit'] : 0;
$del = (isset($_GET['del'])) ? (int)$_GET['del'] : 0;
$moder = (isset($_GET['moder'])) ? (int)$_GET['moder'] : -1;
$commentsid = (isset($_GET['commentsid'])) ? (int)$_GET['commentsid'] : 0;

if ($cat=="") {
	$folder=ARTICLES; $editpage="?what=".$whatpage; $linkinfo="";
}else{
	if($subcat =="") {
		$folder=ARTICLES."$cat"; $editpage="?cat=".$cat."&what=".$whatpage; $linkinfo="/".$cat."";
	} else {
		$folder=ARTICLES."$cat/$subcat"; $editpage="?cat=".$cat."&subcat=".$subcat."&what=".$whatpage; $linkifo="/".$cat."/".$subcat."";
	}
}
$commentFile=$folder."/".$whatpage.".dat.comment";
@$contentcenter.= $commentFile;
if(file_exists($commentFile)){
	if (trim($_REQUEST['message'])!==""){
	//Запись
	}elseif($commentsid>0){
		if($del>0){
			dellcomments(0, $commentsid,  $commentFile);
			header('LOCATION: ../admin/comments.php'.$editpage);
		}
		if($moder>-1){
			modercomments(0, $commentsid,  $commentFile, $moder);
			header('LOCATION: ../admin/comments.php'.$editpage);
		}
		if($edit>0){
		}
	}

	@$contentcenter.="<table cellpadding=\"2\" cellspacing=\"0\" width=\"90%\"><h3>Комментарии к новости</h3><br /><br />";
	$arrcomments=getcomments(0, $commentFile);
	for($i=0;$i<count($arrcomments);$i++){
			$currentcomment=$arrcomments[$i];
			$contentcenter.= '<tr><td  width="50%" colspan="1"><b>Автор:</b> '.$currentcomment['name'].'  <b>ip:</b>[<a href="../admin/banip.php?add='.$currentcomment['ip'].'" title="Бан по ip адресу">'.$currentcomment['ip'].'</a>]<br/>';
			$contentcenter.= '<b>E-mail:</b>  <a href="mailto:'.$currentcomment['email'].'">'.$currentcomment['email'].'</a></td>';
			$contentcenter.= '<td  width="30%" colspan="1">'.date ("r",$currentcomment['id_comment']).'</td>';
			$contentcenter.= '<td width="15%" colspan="1">Модерация: <a title="Модерация" href="../admin/comments.php'.$editpage.'&commentsid=' . $currentcomment['id_comment'];
			if($currentcomment['moderator']==1){
				$contentcenter.= '&moder=0"><img src="images/cb_y.png" /></a></td>';
			}else{
				$contentcenter.= '&moder=1"><img src="images/cb_e.png" /></a></td>';
			}
			$contentcenter.= '<td width="5%" colspan="1"><a title="Удалить комментарий" href="../admin/comments.php'.$editpage.'&commentsid=' . $currentcomment['id_comment'] . '&del=1"><img src="images/delete.png" /></a></td></tr>';
			$contentcenter.= '<tr><td class="line3" colspan="4">'.$currentcomment['content']."</td></tr>";
	}
}
$contentcenter.="<tr><td><center><a href='javascript:history.back(1)'><B>Вернуться назад</B></a></center></td></tr>";
$contentcenter .="</table>";

include $localpath.'/admin/admintemplate.php';
?>
