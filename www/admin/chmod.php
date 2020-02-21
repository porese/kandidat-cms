<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');
include CONF.'photoconf.php';
$inst = (isset($_GET['inst'])) ? (int)$_GET['inst'] : 0;
$perm_file=0666;
$perm_dir=0777;

function perm_file ($directory,$isperm){
	global $contentcenter,$dumbcount;
	$allok=0;
	$ok=0;
	$per=substr(sprintf('%o',fileperms($directory)),-4);
	$perm=substr(sprintf('%o',$isperm),-4);
	if($per==$perm)$ok=1;else $allok++;
	@$dumbcount++;
	$class = 'cline' . ($dumbcount % 2);
	if ( is_dir ($directory)){
		$contentcenter.='<tr class="'.$class.'" ><td class="line3"><b>'.$directory.'</b></td>
		<td class="line3"><b>'.$per.'</b></td>
		<td class="line3"><b>'.$perm.'</b></td>';
		if($ok)$contentcenter.='<td class="line3"><img src="images/ok.png" /></td></tr>';
		else $contentcenter.='<td class="line3"><img src="images/error.png" /></td></tr>';
	}else{
		$contentcenter.='<tr class="'.$class.'" ><td class="line3">'.$directory.'</td>
		<td class="line3">'.$per.'</td>
		<td class="line3">'.$perm.'</td>';
		if($ok)$contentcenter.='<td class="line3"><img src="images/ok.png" /></td></tr>';
		else $contentcenter.='<td class="line3"><img src="images/error.png" /></td></tr>';
	}
	return $allok;
}

function full_perm_dir ($directory,$permdir,$permfile){
	$allok=0;
	if ( is_dir ($directory))$allok+=perm_file($directory,$permdir);
	$dir = opendir($directory);
	while(($file = readdir($dir))){
		if ( is_file ($directory.'/'.$file)){
	  		if($file=='.htaccess')continue;
	  		$allok+=perm_file ($directory.'/'.$file,$permfile);
		}elseif(is_dir($directory.'/'.$file)&&($file != '.') && ($file != '..')){
	  		full_perm_dir ($directory.'/'.$file,$permdir,$permfile);
		}
	}
	closedir ($dir);
	return $allok;
}

if($inst){
	full_chmod_dir(CONF,$perm_dir,$perm_file);
	full_chmod_dir(ARTICLES,$perm_dir,$perm_file);
	full_chmod_dir(ENGINE,$perm_dir,$perm_file);
#	full_chmod_dir(MYCODE,$perm_dir,$perm_file);
	full_chmod_dir(PICTURES,$perm_dir,$perm_file);
	full_chmod_dir($localpath.'admin/templates',$perm_dir,$perm_file);
	full_chmod_dir($localpath.'templates',$perm_dir,$perm_file);
	header('LOCATION: chmod.php');
}
@$allok=0;
@$contentcenter.= '<h3>Установка прав CMS Кандидат</h3>';
$contentcenter.='<br /><table cellpadding="2" cellspacing="0" width="90%">
	<thead>
    <td width="70%"><b>Файл</b></td>
    <td width="10%"><b>Права</b></td>
    <td width="10%"><b>Надо</b></td>
    <td width="10%"><b>ОК</b></td>
    </thead><tbody>';

$allok+=full_perm_dir(CONF,$perm_dir,$perm_file);
$allok+=full_perm_dir(ARTICLES,$perm_dir,$perm_file);
$allok+=full_perm_dir(ENGINE,$perm_dir,$perm_file);
#$allok+=full_perm_dir(MYCODE,$perm_dir,$perm_file);
$allok+=full_perm_dir(PICTURES,$perm_dir,$perm_file);
$allok+=full_perm_dir($localpath.'admin/templates',$perm_dir,$perm_file);
$allok+=full_perm_dir($localpath.'templates',$perm_dir,$perm_file);
$contentcenter.='</tbody></table><br /><br />';

if($allok)$contentcenter.='<span style="color: rgb(255, 0, 51);">Права не соответствуют требуемым. Требуется установка</span>&nbsp;
	<a title="Начать установку" href="../admin/chmod.php?inst=1">Установить права</a>';
else $contentcenter.='Права соответствуют требуемым';
include $localpath.'/admin/admintemplate.php';
?>
