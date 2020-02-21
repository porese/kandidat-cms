<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');

if(isset($_POST['submit'])){
	$sitetitle='Обновление Kandidat CMS by it UTF-8 RC4';
	$src = 'http://kandidat-cms.googlecode.com/files/up.tgz';
   	$dst = LOCALPATH.'/media/upgrade/up.tgz';
   	@unlink($dst);
	if(copy($src,$dst)){
		$fp=fopen(LOCALPATH.'/media/upgrade/up.tgz','r');
		$buff=fread($fp,3);
		fclose($fp);
		$tstr=chr(0x1F).chr(0x8B).chr(0x08);
		if($buff===$tstr){
	    		system('tar -xf '.LOCALPATH.'/media/upgrade/up.tgz -C '.LOCALPATH);
		    	if(file_exists(LOCALPATH.'admin/install/index.php')){
    				header('LOCATION:install/index.php?upgrade=1');
        	        	exit;
    			}
    	  }else $contentcenter='Файл обновлений поврежден.<br />';
	}else $contentcenter='Файл скопировать не удалось.<br />';
	$contentcenter.='Обновление не произведено, попробуйте пойзже.';
	include LOCALPATH.'/admin/admintemplate.php';
	exit;
}
$content='
<form action="'.$_SERVER['SCRIPT_NAME'].'" method="post">
<h>Обновление системы</h><br />
<div style="text-align: left;">Обновление системы саполняется с сервера, для этого необходимо подключение к интернету.
Будет осуществлена:
<ul><li>замена каталогов /admin, /mycode /code</li>
<li>файлов /engine/menu.php</p></li></ul>
</div><br />
<a href="'.$prefflp.'/admin/index.php">Перейти в админпанель<a>
<br /><br />
<input type="submit" name="submit" value="Обновить">
</form>
';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title><?=$install; ?></title>
<style type="text/css">
<!--
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}
h2 {
	font-size: 18px;
	color: #003366;
}
#txt {
	border: thin solid #0D6D9D;
	width: 200px;
	background-color: #E9E9E9;
}
-->
</style>
</head>
<body style="background-color:#EBEBEB">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="middle"><table width="623" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><img src="images/inst_top.jpg" alt="Вас приветствует автоматическое обновление Kandidat CMS" width="623" height="165"></td>
</tr>
<tr>
<td valign="top" background="images/inst_bg.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="15">
<tr>
<td align="center" valign="top">
<? echo $content; ?>
</td>
</tr>
</table></td>
</tr>
<tr>
<td><img src="images/inst_bottom.jpg" width="623" height="24"></td>
</tr>
</table>
<p style="font-size:10px"><strong>Kandidat CMS</strong> &copy; 2007-2010<br>
<a href="http://www.kan-studio.ru" target="_blank">www.kan-studio.ru</a></p></td>
</tr>
</table>
</body>
</html>
