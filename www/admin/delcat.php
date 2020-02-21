<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(2>getpermision())header('LOCATION:index.php');
$cat  = (isset ($_GET['cat']))? trim($_GET['cat']) : '';
$subcat  = (isset ($_GET['subcat']))? trim($_GET['subcat']) : '';
$del_ok = (int)$_GET[ok];

if ($cat==''){
    $linkinfo='';
    $golink='';
}else{ 
    if($subcat ==''){
	$linkinfo='/'.$cat;
	$golink='';
    }else{
	$linkinfo='/'.$cat.'/'.$subcat; 
        $golink='?cat='.$cat;
    }
}
$sitetitle='Удаление категории';
if (($cat=="")&($subcat=="")){
    @$contentcenter.= '<h3>Удаление категории</h3>
    	Удалять нечего <B>$linkinfo/</B>';
} else {
    @$contentcenter.= '<h3>Удаление категории</h3>';
    if ($del_ok) {
        if($del_ok>0){
     	    full_del_dir($localpath.'/articles'.$linkinfo);
            @unlink($localpath.'/articles'.$linkinfo.'.comment');
			$file_menu = ENGINE.'menudb.php';
			$filepp = file($file_menu);
			foreach ($filepp as $key => $val) {
				if (strpos($val,$linkinfo)!==FALSE){
					array_splice($filepp,$key,1);
					savearray($file_menu,$filepp,'w','');
					$contentcenter.='Удалена и ссылка на категорию <B>'.$linkinfo.'</B> из меню!<br /><br />';
					break;
				}
			}

    	    $contentcenter.= 'Категория <B>'.$linkinfo.'</B> успешно удалена!<br /><br />
    	    	<a href="../admin/index.php'.$golink.'"><B>Вернуться в категорию</B></a><br /><br />
    	    	<a href="../admin/index.php"><B>Вернуться в корень категорий</B></a>';
    	    include $localpath.'/admin/admintemplate.php'; exit;
    	}
     } else {
		$contentcenter.= 'Вы действительнохотите физически удалить категорию <B>'.$linkinfo.'/</B>?
			<br><a title="Удалить" href="../admin/delcat.php?what=main&cat='.$cat.'&subcat='.$subcat.'&ok=1">ДА</a> | <a title="Отложить"  href=\'javascript:history.back(1)\'>НЕТ</a>
			<br><br><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';
    }
}
include $localpath.'/admin/admintemplate.php';

?>
