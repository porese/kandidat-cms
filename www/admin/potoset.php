<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');
include CONF.'photoconf.php';
$url=$_SERVER['PHP_SELF'];
$sitetitle='Настройка фото-альбома';
@$contentcenter.= '<h3>Настройка фото-альбома</h3>';

$arrpath=array();
$d = dir('../images/prettyPhoto');
while($entry=$d->read())if($entry{0}!='.')$arrpath[$entry]=$entry;
$d->close();
$htmltemplatepp=make_droplist($arrpath,'template',$templatepp);

$arrpath=array(''=>'');
$d = dir('../'.$gallerypath.'/');
while($entry=$d->read())
		if ($entry{0}!='.'&&$entry!='thumb'&&is_dir(LOCALPATH.$gallerypath.'/'.$entry))$arrpath[$entry]=$entry;
$d->close();
$photorotpathdrop=make_droplist($arrpath,'photorotpath',$photorotpath);


$checked_info_pic=($info_pic=='1')?'checked="checked"':'';
$checked_multiupload=($multiupload=='1')?'checked="checked"':'';
$contentcenter .=<<<EOT
<form action="$url" method="post" name="settings_form">
<label title="Путь к галлереи, относительно корня сайта :: pictures">Путь к галлереи, относительно корня сайта: <input type="text" name="gallerypath" value="$gallerypath" /></label>
<br />
Каталог случайного фото: $photorotpathdrop
<br />
<br />
<label title="Количество колонок :: два, три или четыре (можно и больше, но оптимально 2)">Количество картинок в строке:<select name="xtable">
EOT;
for($i=1;$i<6;$i++){
	if($i==$x)$contentcenter .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
 	else$contentcenter .='<option value="'.$i.'">'.$i.'</option>';
}
$contentcenter .=<<<EOT
</select> *</label>
<br /><br />
<label title="Количество строк :: два, три или четыре (можно и больше, но оптимально 2) ">Количество строк:<select name="ytable">
EOT;
for($i=1;$i<6;$i++){
	if($i==$y)$contentcenter .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
 	else$contentcenter .='<option value="'.$i.'">'.$i.'</option>';
}
$contentcenter .=<<<EOT
</select> *</label>
<br /><br />
<label><input class="settings" type="checkbox" name="infopic" id="title" value="1" $checked_info_pic />Показавать имя файла и размер под фотографиями.</label><br />
<br /><br />
<label title="Шаблон для всплывающих окон кртинок">Шаблон prettyPhoto: $htmltemplatepp</label>
<br /><br />
<label title="В галереи нажимаем <i>Загрузить файлы</i>, в окне выбора помечаем файлы. Далее <i>открыть</i> или <i>ок</i>. Начинают закачиваться файлы, показывая процесс и результат, потом обновляем страничку."><input class="settings" type="checkbox" name="multiupload" id="title" value="1" $checked_multiupload />Использовать многопоточный загрузчик файлов. (использует js и json)</label><br />
<br /><br />
<div class="submit"><input type="submit" class="submit-button" id="Submit" name="settings" value="Сохранить изменения" /></div><br />
</form>
<br><br><a href="../admin/photo.php"><B>Вернуться в фото-альбом</B></a>
EOT;

if (isset($_REQUEST['settings'])) {
	$xtable=(int)$_REQUEST['xtable'];
	$ytable=(int)$_REQUEST['ytable'];
	$info_pic=isset($_REQUEST['infopic'])?1:0;
	$templatepp = trim($_REQUEST['template']);
	$multiupload = (int)$_REQUEST['multiupload'];
	$gallerypath = trim($_REQUEST['gallerypath']);
	$photorotpath = trim($_REQUEST['photorotpath']);
	if ($xtable=="" || $ytable=="") {
		$contentcenter='<font size="2"><b>Вы не заполнили одно из обязательных полей!<br />Поля, отмеченные звездочкой (*), должны быть заполнены!</b></font><br /><br /><a href=\'javascript:history.back(1)\'><b>Вернуться назад</b></a>';
	} else {
		$info_conf='<?php $x='.$xtable.'; $y= '.$ytable.'; $f=$x*$y; $types=array("jpg","jpeg","png","gif"); $multiupload="'.$multiupload.'" ;$info_pic="'.$info_pic.'"; $templatepp="'.$templatepp.'"; $gallerypath="'.$gallerypath.'"; define("PICTURES",LOCALPATH.$gallerypath.\'/\');$photorotpath="'.$photorotpath.'";?>';
		save(CONF.'photoconf.php',$info_conf,'w');
		$contentcenter='<font size="2"><b>Настройки фото-альбома изменёны!</b></font><br><br><a href="../admin/photo.php"><b>Вернуться в фото-альбом</b></a>';
	}
}
include $localpath.'/admin/admintemplate.php';
?>
