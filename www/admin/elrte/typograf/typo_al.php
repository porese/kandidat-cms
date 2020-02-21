<?
if ($_POST[text])
{
$text = urldecode($_POST['text']);
$text = get_magic_quotes_gpc()?stripslashes($text):$text;
require_once dirname(__FILE__).'/library/remotetypograf.php';
$remoteTypograf = new RemoteTypograf ('UTF-8');
$remoteTypograf->htmlEntities();
$remoteTypograf->br (false);
$remoteTypograf->p (true);
$remoteTypograf->nobr (3);
echo $remoteTypograf->processText ($text);
}
?>
