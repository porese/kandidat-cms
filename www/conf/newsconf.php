<?php
$back="<center><p class=\"back\"><a href='javascript:history.back(1)'><B>Вернуться назад</B></a></p></center>";
$sIP = getenv("REMOTE_ADDR");
$siteaddress="http://$_SERVER[HTTP_HOST]";
$newsdbfilename=ENGINE."/newsdb.php";
$newslogfilename=ENGINE."/newslogdb.php";
$commentsdbfilename=ENGINE."/commentsdb.php";
$adminname="Aдмин";
$admmail="mail@mail.ru";
$newsperpage=4;
$newsonmainpage=2;
$commentsperpage=4;
$lengthhead=40;
$lengthnews=100;
$warnalertcolor="red";
$newsflood="60";
$newsmoderator="0";
$news_cat="0";
?>