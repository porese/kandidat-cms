<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(2>getpermision())header('LOCATION:index.php');
$whatpage = preg_replace('/[^a-z0-9-_]/i','',$_REQUEST['what']);
$cat  = (isset ($_GET['cat']))? trim($_GET['cat']) : '';
$subcat  = (isset ($_GET['subcat']))? trim($_GET['subcat']) : '';
@$updateornot = preg_replace('/[^a-z0-9-_]/i','',$_REQUEST['action']);
if($cat==''){
  $folder=ARTICLES;
  $delwhat='?what=';
  $linkinfo='';
}else{
    if($subcat ==''){
		$folder=ARTICLES.$cat;
		$delwhat='?cat='.$cat.'&what=';
		$linkinfo='/'.$cat;
	}else{
		$folder=ARTICLES.$cat.'/'.$subcat;
		$delwhat='?cat='.$cat.'&subcat='.$subcat.'&what=';
		$linkinfo='/'.$cat.'/'.$subcat; }}
$sitetitle='Удаление страницы';

$myFile = $folder.'/'.$whatpage.'.dat';
if ($updateornot) {
	if($updateornot=='delpage') {
		@unlink($myFile);
		@unlink($myFile.'.comment');
		$file_menu = ENGINE.'menudb.php';
		$filepp = file($file_menu);
		foreach ($filepp as $key => $val) {
			if (strpos($val,$whatpage)!==FALSE){
				array_splice($filepp,$key,1);
				savearray($file_menu,$filepp,'w','');
				$about_menu='Также была удалена и ссылка на эту страницу из меню!<br /><br />';
				break;
			}
		}
		if(empty($about_menu))$about_menu='Ссылки на эту страницу в меню не было!<br /><br />';
		$about_menu.='Проверьте требуется ли редактировать шаблон, т.к. в коде шаблона ссылка могла быть тоже прописана!<br /><br />
			Проверьте требуется ли редактировать страницу категории, в которой размещалась эта страница, т.к. ссылка могла быть тоже прописана!<br /><br />
			<a href="../admin/index.php'.$delwhat.'"><B>Вернуться в категорию</B></a><br /><br />
			<a href="../admin/index.php"><B>Вернуться в каталог</B></a>';
	   	$sitetitle='Страница удалена';
        $contentcenter= '<br /><br /><div class="message_warn_ok">Страница <strong>'.$linkinfo.'/'.$whatpage.'.html</strong> была успешно удалена!</div><br><br>' . $about_menu . '</b><br />';
		include $localpath.'admin/admintemplate.php';
	   	exit;
	}
}else{
	if(file_exists($myFile)){
		$data = file_get_contents($myFile);
		$title = articlesparam('title',$data);
		$sitetitle='Удаление страницы: '.$title;
        $contentcenter='<h3>Удаление страницы</h3><br />';
		$contentcenter.='<div class="message_quest">Вы хотите удалить страницу: <strong>'.$title.'</strong> из папки <strong>'.$linkinfo.'/</strong>?</div>';
		$contentcenter.='<br /><a href="delete.php'.$delwhat.$whatpage.'&action=delpage">ДА</a> | ';
		$contentcenter.='<a href="index.php">НET</a>';
		$contentcenter.='<br /><br /><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';
	}
}
include $localpath.'admin/admintemplate.php';
?>
