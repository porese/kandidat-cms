<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
$sitetitle='Информация о настройках PHP';
@$contentcenter.= '<h3>'.$sitetitle.'</h3>';
$contentcenter .= '<style type="text/css">
.center {text-align: center;}
.center table {width: 90%; margin-left: auto; margin-right: auto; text-align: left; border-collapse: collapse;}
.center td { border: 1px solid #000000; font-size: 100%; vertical-align: baseline;}
.center th { border: 1px solid #000000; font-size: 100%; vertical-align: baseline; text-align: center !important;}
.center h1 {font-size: 150%;}
.center h2 {font-size: 125%;}
.center .p {text-align: left;}
.center .e {background-color: #ccccff; font-weight: bold; color: #000000;}
.center .h {background-color: #9999cc; font-weight: bold; color: #000000;}
.center .v {background-color: #cccccc; color: #000000;}
.center .vr {background-color: #cccccc; text-align: right; color: #000000;}
.center img {float: right; border: 0px;}
.center hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000;}
</style>';
$text = command2var('phpinfo();');
$text = explode('body>',$text);
$contentcenter .= str_replace('</--deletethis',"\n",trim($text[1]).'--deletethis');
include $localpath.'/admin/admintemplate.php';
?>