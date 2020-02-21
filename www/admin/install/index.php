<?php
// Kandidat CMS version RC3 by It
$localpath=str_replace("\\","/",dirname(__FILE__));
$localpath=preg_replace("/admin\/install$/u","",$localpath);
include $localpath.'code/functions.php';

$install=__("Вас приветствует автоматическая установка Kandidat CMS");

if(isset($_GET['upgrade'])){
	full_chmod_dir(CONF,0755,0666);
	full_chmod_dir(LOCALPATH.'admin/templates',0755,0644);
	full_chmod_dir(ENGINE,0755,0644);
	full_chmod_dir(MYCODE,0755,0644);
	full_chmod_dir(LOCALPATH.'/admin/install',0777,0666);
	@unlink(MYCODE.'Kedage-b.ttf');
	@unlink(MYCODE.'captcha.php');
	@unlink(MYCODE.'bbParcer.php');
	@unlink(LOCALPATH.'/admin/install/index.php');
    header('LOCATION: '.$prfflp.'/admin/login.php');
}

if(isset($_POST['install'])){
	full_chmod_dir(CONF,0777,0666);
	full_chmod_dir($localpath.'admin/templates',0777,0644);
	full_chmod_dir(ARTICLES,0755,0644);
	full_chmod_dir(ENGINE,0755,0644);
	full_chmod_dir($localpath.'media',0755,0644);
	full_chmod_dir(MYCODE,0755,0644);
	full_chmod_dir($localpath.'pictures',0755,0644);
	full_chmod_dir($localpath.'templates',0755,0644);

	$sitename = trim($_POST["sitename"]);

	$template = trim($_POST["template"]);

	$language = trim($_POST["language"]);

	$siteoff="";
	$offtext="Реконструкция сайта. Зайдите не много позже!";

	$cc_url = trim($_POST["cc_url"]);
	$gzip_enable = trim($_POST["gzip_enable"]);

	$commentsperpage_comments=20;
	$moder_comments=0;
	$sperpage=20;
	$saftertitle=450;

	$sape_user="";

	$stupid="$" . "fudge";
	$somecontent = "<?php\n";
	$somecontent .= "\$sitename=\"$sitename\";\n";
	$somecontent .= "\$template=\"$template\";\n";
	$somecontent .= "\$siteoff=\"$siteoff\";\n";
	$somecontent .= "\$offtext=\"$offtext\";\n";
	$somecontent .= "\$cc_url=\"$cc_url\";\n";
	$somecontent .= "\$gzip_enable=\"$gzip_enable\";\n";
	$somecontent .= "\$commentsperpage_comments=".(int)$commentsperpage_comments.";\n";
	$somecontent .= "\$moder_comments=\"$moder_comments\";\n";
	$somecontent .= "\$sape_user=\"$sape_user\";\n";
	$somecontent .= "\$language=\"$language\";\n";
	$somecontent .= "\$rss_content=\"3\";\n";
	$somecontent .= "\$sperpage=$sperpage;\n";
	$somecontent .= "\$saftertitle=$saftertitle;\n";
	$somecontent .="?>";

	full_chmod_dir(CONF,0755,0644);
	full_chmod_dir(ARTICLES,0755,0644);
	full_chmod_dir(ENGINE,0755,0644);
	full_chmod_dir($localpath."pictures",0755,0644);

	$filename = CONF.'config.php';
	@chmod(CONF.'config.php', 0666);
	$fh=fopen($filename, 'w') or die("can't open file");
	fwrite($fh,$somecontent);
   	fclose($fh);

	$line=$_POST['login'].'::'.md5($_POST['login'].$_POST['password']).'::3::'.$_POST['admin_email'].'::::::::::::'.time()."::\n";
	@chmod(CONF.'users.php', 0666);
	save(CONF.'users.php',$line,'w');

	@$content.=<<<EOT
	<h2>Установка успешно завершена</h2>
	<div style="text-align:left;font-size:12px">
	<ul>
	<li>Файл конфигурации - записан</li>
	<li>Имя пользователя и пароль - записан</li>
	<li>Права на каталоги и файлы - установлены</li>
	<li>Каталог install - удален</li>
	</ul>
	</div>
	<h3>CMS полностью готова к работе</h3>
	<div style="text-align:left;font-size:12px">
	<ul>
	<li><a href="../index.php">Перейти в админ панель.</a></li>
	<li><a href="../../index.php">Перейти на главную страницу сайта.</a></li>
	</ul>
	</div>
EOT;

	@chmod($localpath.'admin/install/index.php', 0777);
	@unlink($localpath.'admin/install/index.php');

}elseif(isset($_POST['lang'])){
	$language=trim($_POST['language']);
	if(file_exists($localpath.'admin/install/'.$language.'.php')){
		include ($localpath.'admin/install/'.$language.'.php');
	}

	$d = dir("../../templates");
	@$templatedrop.="<select name=\"template\" id=\"template\">";

	while($entry=$d->read()) {
				if ($entry != "." && $entry != ".." && trim($entry) != trim($template)){
				$templatedrop .= "<option name=\"$entry\">$entry</option>";
				}
	}
	$templatedrop.="</select>";
	$d->close();

	$content='<h2>'.__('Добро пожаловать!').'</h2>
	<p style="font-size:12px">'.__('Вы находитесь в одном шаге от того момента когда Ваш сайт на Kandidat CMS').'<br>
	'.__('будет готов к полноценной работе. Спасибо за то, что вы выбрали нашу систему!').'</p>
	<form name="form1" method="post" action="index.php">
	<table width="80%" border="0" cellpadding="4" cellspacing="0" style="font-size:12px">
	<tr>
	<td width="46%">'.__('Название Вашего сайта:').'</td>
	<td width="54%" align="center"><input name="sitename" type="text" value="Мой сайт" /></td>
	</tr>
	<tr>
	<td>'.__('E-mail адрес администратора:').'</td>
	<td align="center"><input name="admin_email" type="text" value="admin@empty.ru" /></td>
	</tr>
	<tr>
	<td>'.__('Использовать ЧПУ:').'
	<td align="center" valign="top">
	<label><input name="cc_url" type="radio" value="0" checked="checked" /> '.__('Да').'</label>
	<label><input name="cc_url" type="radio" value="1" /> '.__('Нет').'</label>
	</td>
	</tr>
	<tr>
	<td>'.__('Использовать gzip:').'
	<td align="center" valign="top">
	<label><input name="gzip_enable" type="radio" value="1" /> '.__('Да').'</label>
	<label><input name="gzip_enable" type="radio" value="0" checked="checked" /> '.__('Нет').'</label>
	</td>
	</tr>
	<tr>
	<td>'.__('Шаблон:').'</td>
	<td align="center">'.$templatedrop.'</td>
	</tr>
	<tr>
	<td>'.__('Имя администратора:').'</td>
	<td align="center"><input name="login" type="text" value="admin" /></td>
	</tr>
	<tr>
	<td>'.__('Пароль администратора:').'</td>
	<td align="center"><input name="password" type="text" value="nimda" /></td>
	</tr>
	<tr>
	</table>
	<p style="font-size:10px; margin:0px; margin-top:8px"><a href="http://www.kan-studio.ru/kandidat_cms_RC3/how_install.html" target="_blank">'.__('Инструкция по установке на сайте системы').'</a> &rarr;</p>
	<p style="font-size:10px; margin:0px; margin-top:8px">
	<input type="hidden" name="language" value="'.$language.'">
	<input type="submit" name="back" value="<<< '.__('Назад').'">
	<input type="submit" name="install" value="'.__('Установить систему').' >>>">
	</p>
	</form>';
}else{
	$d = dir("../../lang");
	@$languagedrop.="<select name=\"language\" id=\"language\">";

	while($entry=$d->read()) {
			$ext=explode('.',$entry);
			$ext=strtolower(end($ext));
			if($ext=='php'){
				$entry = basename($entry,'.php');
				if ($entry != "." && $entry != ".." && trim($entry) != trim($language)){
				$languagedrop .= "<option name=\"$entry\">$entry</option>";
				}
			}
	}
	$languagedrop.="</select>";
	$d->close();

	$content='<h2>Hi!</h2>
	<p style="font-size:12px">Choice Your Langrange!</p>
	<form name="form1" method="post" action="index.php">
	<table width="80%" border="0" cellpadding="4" cellspacing="0" style="font-size:12px">
	<tr>
	<td>Language: </td>
	<td align="center">'.$languagedrop.'</td>
	</tr>
	</table>
	<br />
	<input type="submit" name="lang" value="Next >>>">
	</p>
	</form>';
}
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
<td><img src="../images/inst_top.jpg" alt="Вас приветствует автоматическая установка Kandidat CMS" width="623" height="165"></td>
</tr>
<tr>
<td valign="top" background="../images/inst_bg.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="15">
<tr>
<td align="center" valign="top">
<? echo $content; ?>
</td>
</tr>
</table></td>
</tr>
<tr>
<td><img src="../images/inst_bottom.jpg" width="623" height="24"></td>
</tr>
</table>
<p style="font-size:10px"><strong>Kandidat CMS</strong> &copy; 2007-2010<br>
<a href="http://www.kan-studio.ru" target="_blank">www.kan-studio.ru</a></p></td>
</tr>
</table>
</body>
</html>
