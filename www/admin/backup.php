<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');
$perm_file=0644;
$perm_dir=0755;

if(isset($_POST['backup'])){
	if(!file_exists($localpath.'media/upgrade'))mkdir($localpath.'media/upgrade');
	$dst = $localpath.'media/upgrade/'.$_POST['bfile'];
	$src = 'conf articles engine media/image';
	system('cd '.$localpath.'; tar -czf '.$dst.' '.$src);
	$contentcenter.='Файл архива <b>'.$_POST['bfile'].'</b> создан успешно,<br />
	перейти в <a href="elfinder.php">файловый менеджер</a>.';
	include $localpath.'/admin/admintemplate.php';
	exit;
}

if(isset($_POST['restore'])){
	$src = $localpath.'media/upgrade/'.$_POST['rfile'];
	if(file_exists($src)){
		if($_POST['deldir']=='1'){
			full_del_dir(CONF);
			full_del_dir(ARTICLES);
			full_del_dir(ENGINE);
		}
		system('cd '.$localpath.'; tar -xzf '.$src);
		full_chmod_dir(CONF,$perm_dir,$perm_file);
		full_chmod_dir(ARTICLES,$perm_dir,$perm_file);
		full_chmod_dir(ENGINE,$perm_dir,$perm_file);
		$contentcenter.='Файл архива <b>'.$_POST['rfile'].'</b> распакован.<br />
		Данные заменены.';
	}else $contentcenter.='Файл архива <b>'.$_POST['rfile'].'</b> отсутствует на сервере.';
	include $localpath.'/admin/admintemplate.php';
	exit;
}

$drop='<select name="rfile">';
$drop.= '<option value="">--choice--</option>';
$directory=$localpath.'media/upgrade';
if(file_exists($directory)){
    $dir = scandir($directory,1);
    $matches=array();
    for($i=0;$i<count($dir)-2;$i++){
		if(preg_match('/bkan-([0-9-_]+)\.tgz/u',$dir[$i])){
			$drop .= '<option value="'.$dir[$i].'">'.$dir[$i].'</option>';
		}
    }
}
$drop.='</select>';

$bfile='bkan-'.date('d-m-Y_H-i').'.tgz';
$sitetitle='Архивирование пользовательских данных';
$contentcenter='<h3>'.$sitetitle.'</h3>';
$contentcenter.='<form action="'.$_SERVER['SCRIPT_NAME'].'" method="post">
<p>Архивируются данные из каталогов /articles, /engine, /conf, /media/image.</p>
<p>Архивный фай <b>'.$bfile.'</b> будет размещен в каталоге /media/upgrade, операции с ним<br />
можно будет проделать с помощью <a href="elfinder.php">файлового менеджера</a></p><br />
<input type="hidden" name="bfile" value="'.$bfile.'" />
<input type="submit" name="backup" value="Архивировать" /><br /><br />
<hr /><br />
Восстановить из архива '.$drop.'  ВНИМАНИЕ ВСЕ ДАННЫЕ ПОЛЬЗОВАТЕЛЯ БУДУТ ЗАМЕНЕНЫ<br /><br />
<label><input type="checkbox" name="deldir" value="1" />Предварительно удалить каталоги пользователя (очистить базу)</label><br />
<br /><br />
<input type="submit" name="restore" value="Восстановить" />
</form>';
include $localpath.'/admin/admintemplate.php';
?>
