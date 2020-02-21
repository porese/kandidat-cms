<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
$url=$_SERVER['PHP_SELF'];
$sitetitle='Настройка раздела фото-альбома';
$cat  = (isset ($_GET['cat']))? $_GET['cat'] : '';
include_once(CONF.'photoconf.php');
@$contentcenter.= '<h3>Настройка раздела</h3>';
$myFile=PICTURES.$cat.'/info.dat';
if(file_exists($myFile)){
	$title_cat=loadsimple($myFile);
	if($title_cat===false)$title_cat='Без имени';
}else $title_cat='Без имени';

$contentcenter .=<<<EOT
<form action="$url" method="post" name="razdel_form">
<label title="Название раздела :: Постарайтесь кратко обозначить тематику раздела для фото, размещенных в этом разделе.">Название раздела фото-альбома*<br />
<input class="settings" type="text" name="titlecat" id="title" value="$title_cat"></label>
<input type="hidden" name="cat" value="$cat">
<br /><br />
<div class="submit"><input type="submit" class="submit-button" id="Submit" name="title" value="Сохранить изменения" /></div><br />
</form>
<br><br><a href="../admin/photo.php"><b>Вернуться в фото-альбом</b></a>
EOT;

if (isset($_REQUEST['title'])) {
	$ntitle=filtermessage($_REQUEST['titlecat']);
	$ncat=trim($_REQUEST['cat']);
	if ($ntitle==''){
		$contentcenter='<font size="2"><b>Вы не заполнили одно из обязательных полей!<br />Поля, отмеченные звездочкой (*), должны быть заполнены!</b></font><br><br><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';
	} else {
		save(PICTURES.$ncat.'/info.dat',$ntitle,'w');
		$contentcenter='<font size="2"><b>Раздел успешно изменён!</b></font><br><br><a href="../admin/photo.php"><b>Вернуться в фото-альбом</b>';
	}
}
include $localpath.'admin/admintemplate.php';

?>
