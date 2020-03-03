<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');
include_once CONF.'newsconf.php';
$url=$_SERVER['PHP_SELF'];
$sitetitle='Настройка новостей';
$contentcenter= '<h3>Настройка новостей</h3>';
$checked_newsmoderator='';
$checked_news_cat='';
$checked_newsmoderator=($newsmoderator=="1")?'checked="checked"':'';
$checked_news_cat=($news_cat=="1")?'checked="checked"':'';
$contentcenter .=<<<EOT
<form action="$url" method="post" name="settings_form">
<label>Имя автора новостей*<br />
<input class="settings" type="text" name="adminname" id="title" value="$adminname" /></label>
<br />
<label title="Электронная почта автора новостей">Электронная почта*<br />
<input class="settings" type="text" name="admmail" id="title" value="$admmail" /></label>
<br /><br />
<label title="Количество новостей на странице :: шесть (можно любую, зависит от шаблона) ">Количество новостей на странице*<br />
<input class="settings" type="text" name="newsperpage" id="title" value="$newsperpage" /></label>
<br /><br />
<label title="Количество комментариев на странице :: 20 (можно любую, зависит от шаблона) ">Количество комментариев на странице*<br />
<input class="settings" type="text" name="commentsperpage" id="title" value="$commentsperpage" /></label>
<br />
<label title="Через какое время возможен ввод следующего комментария :: 40 (можно любую, зависит от любителей пофлудить) ">Через какое время возможен ввод следующего комментария*<br />
<input class="settings" type="text" name="newsflood" id="title" value="$newsflood" /></label>
<br />
<label><input class="settings" type="checkbox" name="newsmoderator" id="title" value="1" $checked_newsmoderator>Обязательная модерация новых комментариев.</label><br />
<br /><br />
<label title="Количество новостей в ротаторе на главной странице :: один, два (можно и больше, зависит от шаблона) ">Количество новостей в ротаторе (на главной странице)*<br />
<input class="settings" type="text" name="newsonmainpage" id="title" value="$newsonmainpage" /></label>
<br />
<label title="Количество символов в заголовке ротатора (на главной) :: сорок (можно и больше, но оптимально 40) ">Количество символов в заголовке*<br />
<input class="settings" type="text" name="lengthhead" id="title" value="$lengthhead" /></label>
<br />
<label title="Количество символов в теле новости ротатора (на главной) :: сто (можно и больше, но оптимально 100) ">Количество символов в теле новости*<br />
<input class="settings" type="text" name="lengthnews" id="title" value="$lengthnews" /></label>
<br />
<label  title="Цвет для привлечения внимания">Цвет для внимания<br />
<input class="settings" type="text" name="warnalertcolor" id="title" value="$warnalertcolor" /></label>
<br />
<br />
<label><input name="news_cat" type="checkbox" value="1" $checked_news_cat /> Работа новостей из каталога <i>/news/</i> (иначе как файл <i>/news-</i>)</label>
<br />
<br />
<div class="submit">
<input type="submit" class="submit-button" id="Submit" name="settings" value="Сохранить изменения" /></div><br />
</form>
<br><br><a href="../admin/news.php"><B>Вернуться в новости</B></a>
EOT;

if (isset($_REQUEST['settings'])) {
$adminname=$_REQUEST['adminname'];
$admmail=trim($_REQUEST['admmail']);
$newsperpage=(int)$_REQUEST['newsperpage'];
$newsonmainpage=(int)$_REQUEST['newsonmainpage'];
$lengthhead=(int)$_REQUEST['lengthhead'];
$lengthnews=(int)$_REQUEST['lengthnews'];
$warnalertcolor=trim($_REQUEST['warnalertcolor']);
$commentsperpage=trim($_REQUEST['commentsperpage']);
$newsflood=(int)$_REQUEST['newsflood'];
$newsmoderator=isset($_REQUEST['newsmoderator'])?1:0;
$news_cat=isset($_REQUEST['news_cat'])?1:0;
	if ($adminname=="" || $admmail==""|| $newsperpage==""|| $newsonmainpage==""|| $lengthhead==""|| $lengthnews=="" || $commentsperpage=="" || $newsflood=="") {
	$contentcenter="<font size=\"2\"><b>Вы не заполнили одно из обязательных полей!<br>Поля, отмеченные звездочкой (*), должны быть заполнены!</b></font><br /><br /><a href='javascript:history.back(1)'><B>Вернуться назад</B></a>";
	} else {
	$info_conf='<?php
$back="<center><p class=\"back\"><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a></p></center>";
$sIP = getenv("REMOTE_ADDR");
$siteaddress="http://$_SERVER[HTTP_HOST]";
$newsdbfilename=ENGINE."/newsdb.php";
$newslogfilename=ENGINE."/newslogdb.php";
$commentsdbfilename=ENGINE."/commentsdb.php";
$adminname="'.$adminname.'";
$admmail="'.$admmail.'";
$newsperpage='.$newsperpage.';
$newsonmainpage='.$newsonmainpage.';
$commentsperpage='.$commentsperpage.';
$lengthhead='.$lengthhead.';
$lengthnews='.$lengthnews.';
$warnalertcolor="'.$warnalertcolor.'";
$newsflood="'.$newsflood.'";
$newsmoderator="'.$newsmoderator.'";
$news_cat="'.$news_cat.'";
?>';
	if(!save(CONF.'newsconf.php',$info_conf,"w"))die ("Не возможно записать данные в файл!");
	$contentcenter='<h2>Настройки новостей изменёны!</h2><br /><br /><a href="news.php"><b>Вернуться в новости</b></a><br /><br />';
	}
}
include $localpath.'/admin/admintemplate.php';

?>
