<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');
include CONF.'gbconf.php';
$url=$_SERVER['PHP_SELF'];
$sitetitle='Настройка гостевой книги';
@$contentcenter.= '<h3>'.$sitetitle.'</h3>';

$checked_sendmail=($sendmail)?'checked="checked"':'';
$checked_catmessage=($catmessage)?'checked="checked"':'';
$checked_gbmoderator=($gbmoderator)?'checked="checked"':'';
$contentcenter .=<<<EOT
<form action="$url" method="post" name="settings_form">
<label title="Количество сообщений на странице (оптимально 5) ">Количество сообщений на странице *<br />
<input class="settings" type="text" name="pnumber" id="title" value="$pnumber" /></label>
<br /><br />
<label><input class="settings" type="checkbox" name="sendmail" id="title" value="1" $checked_sendmail />Отправлять письмо при добавлении нового сообщения.</label><br />
<br />
<label>Адрес на который следует отправлять письма *<br />
<input class="settings" type="text" name="valmail" id="title" value="$valmail" /></label>
<br /><br />
<label><input class="settings" type="checkbox" name="catmessage" id="title" value="1" $checked_catmessage />Ограничивать число сообщений в гостевой книге.</label><br />
<br />
<label>Максимальное число сообщений в гостевой книге<br />
<input class="settings" type="text" name="nummessage" id="title" value="$nummessage" /></label>
<br /><br />
<label title="Через какое время возможен набор следующего сообщения в гостевой книге (оптимально 60) ">Через какое время возможен набор следующего сообщения в гостевой книге<br />
<input class="settings" type="text" name="gbflood" id="title" value="$gbflood" /></label>
<br /><br />
<label><input class="settings" type="checkbox" name="gbmoderator" id="title" value="1" $checked_gbmoderator />Обязательная модерация новых сообщений.</label><br />
<br /><br />
<div class="submit"><input type="submit" class="submit-button" id="Submit" name="settings" value="Сохранить изменения" /></div><br />
</form>
<br><br><a href="../admin/guestbook.php"><B>Вернуться в гостевую книгу</B></a>
EOT;

if (isset($_REQUEST['settings'])) {
$pnumber=(int)$_REQUEST['pnumber'];
$sendmail=isset($_REQUEST['sendmail'])?1:0;
$valmail=$_REQUEST['valmail'];
$catmessage=(int)$_REQUEST['catmessage'];
$nummessage=(int)$_REQUEST['nummessage'];
$gbflood=(int)$_REQUEST['gbflood'];
$gbmoderator=isset($_REQUEST['gbmoderator'])?1:0;

	if (($pnumber=="")||(!preg_match('/^[-0-9a-zа-я_]+@[-0-9a-zа-я_^\.]+\.[a-zа-я]{2,4}$/iu', $valmail))) {
	$contentcenter='<font size="2"><b>Вы не заполнили одно из обязательных полей!<br />Поля, отмеченные звездочкой (*), должны быть заполнены!</b></font><br><br><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';
	} else { @chmod (CONF.'gbconf.php', 0777);
	$info_conf=<<<EOT
<?php
  \$pnumber = "$pnumber"; // Число сообщений на странице
  \$sendmail = "$sendmail"; // Отпрвлять письмо на e-mail при добавлении нового сообщения
  \$valmail = "$valmail";  // em-mail на который следует отправлять сообщение
  \$catmessage = "$catmessage"; // Ограничивать число сообщений в гостевой книге?
  \$nummessage = "$nummessage"; // Максимальное число сообщений в гостевой книге
  \$gbflood = "$gbflood"; // Через какое время возможен набор следующего сообщения
  \$gbmoderator = "$gbmoderator"; // Модерация сообщений
?>
EOT;
	save(CONF.'gbconf.php',$info_conf,'w');
	@chmod(CONF.'gbconf.php', 0644);
	$contentcenter='<font size="2"><b>Настройки гостевой книги изменёны!</b></font><br /><br /><a href="../admin/guestbook.php"><B>Вернуться в гостевую книгу</B></a>';
	}
}
include $localpath.'/admin/admintemplate.php';
?>
