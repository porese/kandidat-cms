<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
include CONF.'photoconf.php';
if(3>getpermision())header('LOCATION:index.php');

function convert_dir ($directory){
	$dir = opendir($directory);
	while(($file = readdir($dir))){
		if ( is_file ($directory."/".$file)){
			$ext=getftype($file);
			if($file=='namedb.dat'){
				if(file_exists($directory."/".$file)){
					$gbs=file($directory."/".$file);
				    $open=fopen($directory."/".$file,"w");
				    $result=0;
					for($i=0;$i<sizeof($gbs);$i++){
						$data=unserialize($gbs[$i]);
						if(!preg_match('//u', $data['opisanie']))$data['opisanie']=iconv('cp1251','utf-8',$data['opisanie']);
						fwrite($open, serialize($data)."\n");
						$result++;
					}
				    fclose($open);
				}
			}elseif($ext=='dat'){
				$txt=file_get_contents($directory."/".$file);
				if(!preg_match('//u', $txt))$txt=iconv('cp1251','utf8',$txt);
				save($directory."/".$file,$txt,'w');
				$result++;
			}elseif($ext=='comment'){
				if(file_exists($directory."/".$file)){
					$gbs=file($directory."/".$file);
				    $open=fopen($directory."/".$file,"w");
				    $result=0;
					for($i=0;$i<sizeof($gbs);$i++){
						$data=unserialize($gbs[$i]);
						if(!preg_match('//u', $data['content']))$data['content']=iconv('cp1251','utf-8',$data['content']);
						if(!preg_match('//u', $data['name']))$data['name']=iconv('cp1251','utf-8',$data['name']);
						fwrite($open, serialize($data)."\n");
						$result++;
					}
				    fclose($open);
				}
			}
		}else if( is_dir ($directory."/".$file) &&
				  ($file != ".") && ($file != "..")){
			  $result+=convert_dir ($directory."/".$file);
		}
	}
	closedir ($dir);
	return $result;
}


$sitetitle='Перекодирование данных';
@$contentcenter .='<h3>Список перекодированных данных</h3>';
$contentcenter.='<br><table cellspacing="0" width=95%>
	<thead>
    <tr>
    <td width="80%" class="line3"><b>Файл</b></td>
    <td width="20%" class="line3"><b>ОК</b></td>
    </tr></thead><tbody>';


$myFile=ENGINE.'guestbookdb.php';
if(file_exists("$myFile")){
	$gbs=file("$myFile");
    $open=fopen("$myFile","w");
    $result=0;
	for($i=0;$i<sizeof($gbs);$i++){
		$data=unserialize($gbs[$i]);
		if(!preg_match('//u', $data['name']))$data['name']=iconv('cp1251','utf-8',$data['name']);
		if(!preg_match('//u', $data['city']))$data['city']=iconv('cp1251','utf-8',$data['city']);
		if(!preg_match('//u', $data['mess']))$data['mess']=iconv('cp1251','utf-8',$data['mess']);
		if(!preg_match('//u', $data['answer']))$data['answer']=iconv('cp1251','utf-8',$data['answer']);
		fwrite($open, serialize($data)."\n");
		$result++;
	}
    fclose($open);
    $contentcenter.='<tr class="line2" ><td class="line2">'.$myFile.'</td>
  	  <td class="line2">'.$result.' записей</td></tr>';

}
$myFile=ENGINE.'newsdb.php';
if(file_exists("$myFile")){
	$gbs=file("$myFile");
    $open=fopen("$myFile","w");
    $result=0;
	for($i=0;$i<sizeof($gbs);$i++){
		$data=unserialize($gbs[$i]);
		if(!preg_match('//u', $data['head']))$data['head']=iconv('cp1251','utf-8',$data['head']);
		if(!preg_match('//u', $data['mess']))$data['mess']=iconv('cp1251','utf-8',$data['mess']);
		if(!preg_match('//u', $data['aname']))$data['aname']=iconv('cp1251','utf-8',$data['aname']);
		if(!preg_match('//u', $data['extra']))$data['extra']=iconv('cp1251','utf-8',$data['extra']);
		fwrite($open, serialize($data)."\n");
		$result++;
	}
    fclose($open);
    $contentcenter.='<tr class="line2" ><td class="line2">'.$myFile.'</td>
  	  <td class="line2">'.$result.' записей</td></tr>';
}
$myFile=ENGINE.'commentsdb.php';
if(file_exists("$myFile")){
	$gbs=file("$myFile");
    $open=fopen("$myFile","w");
    $result=0;
	for($i=0;$i<sizeof($gbs);$i++){
		$data=unserialize($gbs[$i]);
		if(!preg_match('//u', $data['content']))$data['content']=iconv('cp1251','utf-8',$data['content']);
		if(!preg_match('//u', $data['name']))$data['name']=iconv('cp1251','utf-8',$data['name']);
		fwrite($open, serialize($data)."\n");
		$result++;
	}
    fclose($open);
    $contentcenter.='<tr class="line2" ><td class="line2">'.$myFile.'</td>
  	  <td class="line2">'.$result.' записей</td></tr>';
}
$myFile=ENGINE.'menudb.php';
if(file_exists("$myFile")){
	$gbs=file("$myFile");
    $open=fopen("$myFile","w");
    $result=0;
	for($i=0;$i<sizeof($gbs);$i++){
		$data=unserialize($gbs[$i]);
		if(!preg_match('//u', $data['head']))$data['head']=iconv('cp1251','utf-8',$data['head']);
		if(!preg_match('//u', $data['title']))$data['title']=iconv('cp1251','utf-8',$data['title']);
		fwrite($open, serialize($data)."\n");
		$result++;
	}
    fclose($open);
    $contentcenter.='<tr class="line2" ><td class="line2">'.$myFile.'</td>
  	  <td class="line2">'.$result.' записей</td></tr>';
}
$result=convert_dir(ARTICLES);
$contentcenter.='<tr class="line2" ><td class="line2">'.ARTICLES.'</td>
  	  <td class="line2">'.$result.' записей</td></tr>';

$result=convert_dir(PICTURES);
$contentcenter.='<tr class="line2" ><td class="line2">'.PICTURES.'</td>
  	  <td class="line2">'.$result.' записей</td></tr>';

$contentcenter.='</tbody></table><br><br />';
include $localpath.'/admin/admintemplate.php';
?>
