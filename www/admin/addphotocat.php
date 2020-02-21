<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(2>getpermision())header('LOCATION:index.php');
$url=$_SERVER['PHP_SELF'];
$sitetitle='Добавление раздела фото-альбома';
include_once(CONF.'photoconf.php');
if (isset($_REQUEST['newrazdel'])) {
	$newtitle=filtermessage($_REQUEST['title']);
	$newfolder=trim($_REQUEST['folder']);
	if ($newtitle==''||$newfolder=='') {
		$contentcenter='<font size="2"><b>Вы не заполнили одно из обязательных полей!<br>Поля, отмеченные звездочкой (*), должны быть заполнены!</b></font>';
	} else {
		mkdir(PICTURES.$newfolder, 0755);
		save(PICTURES.$newfolder.'/info.dat',$newtitle,'w');
		chmod(PICTURES.$newfolder.'/info.dat', 0644);
		save(PICTURES.$newfolder.'/index.php','<?php header("Location: ../"); exit(); ?>;','w');
		chmod(PICTURES.$newfolder.'/index.php', 0644);
		save(PICTURES.$newfolder.'/namedb.dat','','w');
		chmod(PICTURES.$newfolder.'/namedb.dat', 0644);
		mkdir(PICTURES.$newfolder.'/thumb', 0755);
		save(PICTURES.$newfolder.'/thumb/index.php','<?php header("Location: ../"); exit(); ?>;','w');
		chmod(PICTURES.$newfolder.'/thumb/index.php', 0644);
		$contentcenter="<font size=\"2\"><b>Раздел успешно добавлен!</b></font><br>";
	}
}
@$contentcenter.= '<h3>Добавление раздела</h3>';
$contentcenter .=<<<EOT
<form action="$url" method="post" name="my_razdel_form">
Название раздела фото-альбома*<br />
<input class="settings" type="text" name="title" id="title" value="Название раздела"> <img src="images/info.png"  class="Tips1" title="Название раздела :: Постарайтесь кратко обозначить тематику раздела для фото, размещенных в этом разделе. " /><br /><br />
Имя папки раздела (латинскими)*<br />
<input class="settings" type="text" name="folder" id="folder" value="Nature"> <img src="images/info.png"  class="Tips1" title="Имя папки раздела :: Задайте имя паки латинскими буквами, в которой будут сохранены все фото этого раздела. Убедитесь, что такой папки у вас ещё нет, т.к. скрипт пока не выполняет такую проверку." />
<br /><br />
<div class="submit"><input type="submit" class="submit-button" id="Submit" name="newrazdel" value="Добавить раздел" /></div><br />
</form>
<br><br><a href="../admin/photo.php"><B>Вернуться в фото-альбом</B></a>
EOT;
include $localpath.'admin/admintemplate.php';

?>
