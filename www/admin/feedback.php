<?
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');
$sitetitle="Обратная связь";

if ($_REQUEST["action"]=="validate") {
	$myemail=trim($_POST["myemail"]);
	$emails_ta=trim($_POST["emails_ta"]);
	$emails='array("'.str_replace("\n",'","',$emails_ta).'")';
	$emails=str_replace("\r","",$emails);

	$priority = trim($_POST["priority"]);

	$allowattach = trim($_POST["allowattach"]);
	$max_file_size = trim($_POST["max_file_size"]);
	$max_file_total = trim($_POST["max_file_total"]);

	$defaultsubject = trim($_POST["defaultsubject"]);
	$use_subject_drop = (trim($_POST["use_subject_drop"])=="1")?"true":"false";
	$subjects_ta =trim($_POST["subjects_ta"]);
	$subjects='array("'.str_replace("\n",'","',$subjects_ta).'")';
	$subjects=str_replace("\r","",$subjects);

	$use_ltd_drop = (trim($_POST["use_ltd_drop"])=="1")?"true":"false";
	$ltd_ta =trim($_POST["ltd_ta"]);
	$ltds='array("'.str_replace("\n",'","',$ltd_ta).'")';
	$ltds=str_replace("\r","",$ltds);

	$use_smtp_sent = (trim($_POST["use_smtp_sent"])=="1")?"true":"false";
	$arr_for_smtp = 'array("addres"=>"'.trim($_POST["addres"]).'","port"=>'.(int)$_POST["port"].',"login"=>"'.trim($_POST["login"]).'","pwd"=>"'.trim($_POST["pwd"]).'","frommail"=>"'.trim($_POST["frommail"]).'")';

	@$somecontent .= "<?php \n";
	$somecontent  .= "\$myemail=\"".$myemail."\";\n";
	$somecontent  .= "\$emails=".$emails.";\n";
	$somecontent  .= "\$priority=\"".$priority."\";\n";
	$somecontent  .= "\$allowattach=\"".$allowattach."\";\n";
	$somecontent  .= "\$max_file_size=\"".$max_file_size."\";\n";
	$somecontent  .= "\$max_file_total=\"".$max_file_total."\";\n";

	$somecontent  .= "\$defaultsubject=\"".$defaultsubject."\";\n";
	$somecontent  .= "\$use_subject_drop=".$use_subject_drop.";\n";
	$somecontent  .= "\$subjects=".$subjects.";\n";

	$somecontent  .= "\$use_ltd_drop=".$use_ltd_drop.";\n";
	$somecontent  .= "\$ltds=".$ltds.";\n";

	$somecontent  .= "\$use_smtp_sent=".$use_smtp_sent.";\n";
	$somecontent  .= "\$arr_for_smtp=".$arr_for_smtp.";\n";
	$somecontent  .= "?>\n";

	save (CONF."feedbackconf.php",$somecontent,"w+");
	$contentcenter = "<html><head><meta http-equiv='Refresh' content='2; URL=feedback.php'></head><body><h2>Настройки формы обратной связи записаны.</h2></body></html>";
	include $localpath.'/admin/admintemplate.php';
	exit;
}

include_once CONF.'feedbackconf.php';
$addres=$arr_for_smtp['addres'];
$port=$arr_for_smtp['port'];
$login=$arr_for_smtp['login'];
$pwd=$arr_for_smtp['pwd'];
$frommail=$arr_for_smtp['frommail'];
foreach($emails as $strtmp) {
	@$emails_ta.=$strtmp."\n";
}
foreach($subjects as $strtmp) {
	@$subjects_ta.=$strtmp."\n";
}
foreach($ltds as $strtmp) {
	@$ltd_ta.=$strtmp."\n";
}
if ($use_subject_drop) $checked_use_subject_drop="checked";
if ($use_ltd_drop) $checked_use_ltd_drop="checked";
if ($use_smtp_sent) $checked_use_smtp_sent="checked";

$url=$_SERVER['PHP_SELF']."?action=validate";
$contentcenter .=<<<EOT
<h3>Параметры формы обратной связи</h3><br />
<form action="$url" method="post" name="settings_form">
Электронная почта получателя*<br />
<input class="settings" type="text" name="myemail" id="title" value="$myemail"> <img src="images/info.png"  class="Tips1" title="Электронная почта получателя сообщений" />
<br />
Дополнительные адреса получателей сообщений<br />
<textarea  class="settings" rows="6" cols="45" name="emails_ta">$emails_ta</textarea>
<br /><br />
Приоритет сообщения*<br />
<input class="settings" type="text" name="priority" id="title" value="$priority"> <img src="images/info.png"  class="Tips1" title="Приоритет сообщения в заголовке :: 3 (можно и другой) " />
<br /><br /><hr /><br />
Количество вложенных файлов*<br />
<input class="settings" type="text" name="allowattach" id="title" value="$allowattach"> <img src="images/info.png"  class="Tips1" title="Количество вложенных файлов в письме :: 1 (от 0 до 3) " />
<br />
Максимальный размер вложенного файла (Кб)*<br />
<input class="settings" type="text" name="max_file_size" id="title" value="$max_file_size"> <img src="images/info.png"  class="Tips1" title="Максимальный размер одного файла вложенного в письмо :: 1024 Кб" />
<br />
Максимальный размер всех вложенных файлов (Кб)*<br />
<input class="settings" type="text" name="max_file_total" id="title" value="$max_file_total"> <img src="images/info.png"  class="Tips1" title="Максимальный суммарный размер всех файлов вложенных в письмо :: 2048 Кб" />
<br /><br /><hr /><br />
Subject по умолчанию<br />
<input class="settings" type="text" name="defaultsubject" size="38" id="title" value="$defaultsubject">
<br /><br />
<label><input class="settings" type="checkbox" name="use_subject_drop" id="title" value="1" $checked_use_subject_drop>Использовать subject из списка ниже.</label><br />
<br />
<textarea  class="settings" rows="6" cols="45" name="subjects_ta">$subjects_ta</textarea>
<br /><br /><hr /><br />
<label><input class="settings" type="checkbox" name="use_ltd_drop" id="title" value="1" $checked_use_ltd_drop>Использовать список идентификации ниже</label><br />
<br />
<textarea  class="settings" rows="6" cols="45" name="ltd_ta">$ltd_ta</textarea>
<br /><br /><hr /><br />
<label><input class="settings" type="checkbox" name="use_smtp_sent" id="title" value="1" $checked_use_smtp_sent>Использовать сторонний smtp сервер (если на хосте отсутствует sendmail)</label><br />
<br />
Адрес сервера:<input class="settings" type="text" name="addres" id="title" value="$addres">
Порт:<input class="settings" type="text" name="port" id="title" size="4" value="$port"><br />
Аутоитентификация<br />
имя:<input class="settings" type="text" name="login" size="10" id="title" value="$login">
пароль:<input class="settings" type="text" name="pwd" size="10" id="title" value="$pwd">
<br />
Адрес с которого идет отправка:<input class="settings" type="text" name="frommail" id="title" value="$frommail"> <img src="images/info.png"  class="Tips1" title="Данный адрес должен быть зарегистрирован на сервере." />


<br />
<br />
<div class="submit"><input type="submit" class="submit-button" id="Submit" name="settings" value="Сохранить изменения" /></div><br />
</form>
EOT;
include $localpath.'/admin/admintemplate.php';
?>
