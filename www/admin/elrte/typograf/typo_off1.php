<?php
//header('Content-Type: text/html; charset=utf-8');
if (empty($_POST['text']))
{
	$out_txt = '';
}
else
{
require_once(dirname(__FILE__).'/library/typographus.php');
$typo = new typographus('UTF-8');

$in_txt = urldecode($_POST['text']);
$in_txt = get_magic_quotes_gpc()?stripslashes($in_txt):$in_txt;
$out_txt = $typo->process($in_txt);
}
echo $out_txt;
?>