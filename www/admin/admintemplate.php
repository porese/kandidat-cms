<?php
$admintemplate = (isset ($_POST['admintemplate']))? trim($_POST['admintemplate']) : '';
$url=$_SERVER['PHP_SELF'];
if($admintemplate!==""){
	save($localpath.'admin/templates/template.ini',	$admintemplate,'w');
	echo "<html><head><meta http-equiv='Refresh' content='0; URL=$url'></head></html>";
	exit;
}
$data=file($localpath.'admin/templates/template.ini');
$admintemplate=trim($data[0]);

$choisetemplate='<form action="index.php" method="post">';
$choisetemplate.='<select name="admintemplate" onChange="this.form.submit()">';
$d = dir($localpath.'admin/templates');
while($entry=$d->read()) {
		if ($entry != '.' && $entry != '..' && is_dir($localpath.'admin/templates/'.$entry)){
			if(trim($entry) == trim($admintemplate)){
				$choisetemplate .= '<option selected="selected" name="'.$entry.'">'.$entry.'</option>';
			}else{
				$choisetemplate .= '<option name="'.$entry.'">'.$entry.'</option>';
			}
		}
}
$choisetemplate.='</select></form>';
//EOT;
if($admintemplate=='')$admintemplate='new';
include $localpath.'/admin/templates/'.$admintemplate.'/admintemplate.php';
?>
