<?php
error_reporting(E_ALL^E_NOTICE);
$path=str_replace('\\','/',dirname(__FILE__));
include $path.'/code/functions.php';

$directory=LOCALPATH.'/media/file';//Это путь где хранятся файлы

if(!$_GET['file']) die('Missing parameter!');
if($_GET['file']{0}=='.') die('Wrong file!');

@$fname =$_GET['file'];
$fname = preg_replace('/..\//i','',$fname);
$fullPath=$directory.'/'.$fname;
if(file_exists($fullPath)){
	/* If the visitor is not a search engine, count the downoad: */
	if(!is_bot())
		inc_count($fname);
	if ($fd = fopen ($fullPath, "r")) {
		//следующая часть выводит файл
		$fsize = filesize($fullPath);
		$path_parts = pathinfo($fullPath);
		header("Content-type: application/octet-stream");
		header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
		header("Content-length: $fsize");
		header("Cache-control: private");
		//используется для прямого открытия файла
		while(!feof($fd)) {
			$buffer = fread($fd, 2048);
			echo $buffer;
		}
	}
	fclose ($fd);
	exit;
}else die("This file does not exist!");


function is_bot(){
	/* This function will check whether the visitor is a search engine robot */
	$botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi",
	"looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
	"Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
	"crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
	"msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
	"Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
	"Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot",
	"Butterfly","Twitturls","Me.dium","Twiceler");
	foreach($botlist as $bot){
		if(strpos($_SERVER['HTTP_USER_AGENT'],$bot)!==false)
		return true;	// Is a bot
	}
	return false;	// Not a bot
}

function inc_count($fname){
	$myFile=ENGINE.'logdb.php';
	@$count="";
	$present=0;
	if(is_readable($myFile)){
		$log=file($myFile);
		foreach ($log as $key => $val) {
		  if (strpos($val,"$fname")!==FALSE) { $present = $key+1; break;}
		}
		if($present!=0){
		  @$data=unserialize($log[$present-1]);
		  @$count=$data['count'];
		}
	}
	$count++;
	$data=serialize(array("fname"=>"$fname",
	    			    "count"=>$count))."\n";
	if($present!=0){
		$open=fopen($myFile,"w");
		for($i=0;$i<count($log);$i++){
      		if($i!=($present-1)){fwrite($open,$log[$i]);
			}else{fwrite($open,$data);}
  		}
		fclose($open);
	}else{
		$open=fopen($myFile,"a");
		fwrite($open, $data);
		fclose($open);
	}
}
?>
