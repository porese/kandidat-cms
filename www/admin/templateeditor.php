<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');

$updateornot = @$_POST["Submit"];
$content = @$_POST["article"];
$content=get_magic_quotes_gpc()?stripslashes($content):$content;

@$fedit = (isset($_GET["file"]))?$_GET["file"]:"template.php";
@$fedit = preg_replace("/..\//i","",$fedit);
$sitetitle="Редактирование текущего шаблона сайта $fedit";

if (empty($updateornot)) {
	$myFile = $localpath."/templates/$template/".$fedit;
	$fh = fopen($myFile, 'r');
	$data = fread($fh, filesize($myFile));
	fclose($fh);

	if(!preg_match('//u', $data)){
		if(stripos( $data,"charset=windows-1251")!==false){
			$data=iconv('windows-1251','UTF-8',$data);
			$data=str_ireplace("charset=windows-1251","charset=UTF-8",$data);
		}
	}

	$directory="$localpath/templates/$template";
	$dir = opendir($directory);
	while(($file = readdir($dir)))
	  	if ( is_file ($directory."/".$file))
	  	  if($file!=$fedit)
			@$all_files .="|&nbsp;&nbsp;<a href=\"?file=$file\"> $file</a>&nbsp;&nbsp;|";
	closedir($dir);
	$url=$_SERVER['PHP_SELF'];
	$ext=getftype($fedit);
	$ext=(preg_match("/php|html|htm|css|js/i",$ext)?$ext:"php");
	@$contentcenter .=<<<EOT
	<script language="Javascript" type="text/javascript" src="edit_area/edit_area_full.js"></script>
	<script language="Javascript" type="text/javascript">
	    // initialisation
	    editAreaLoader.init({
		id: "code"// id of the textarea to transform
		,start_highlight: true// if start with highlight
		,allow_resize: "both"
		,allow_toggle: true
		,word_wrap: true
		,language: "ru"
		,syntax: "$ext"
		,toolbar: "search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, word_wrap, help"
		,syntax_selection_allow: "css,html,js,php,perl"
		,show_line_colors: true
	    });
	</script>

	<form action="$url?file=$fedit" method="post" name="my_form">
	<h3>$sitetitle</h3>Текущий шаблон: <div class="template"><b>$template</b></div>
	Файлы в шаблоне: $all_files<br>
	<textarea name="article" id="code" rows=30 style="width: 100%;">$data</textarea>
	<br /><br>
	<div class="submit"><input type="submit" class="submit-button" id="Submit" name="Submit" value="Сохранить изменения" /></div><br />
	</form>
EOT;
	$contentcenter = stripslashes($contentcenter);
}else{
	$myFile = "../templates/$template/$fedit";

	unlink($myFile);

	@chmod("../templates/$template/$fedit", 0777);
	$ourFileName = "../templates/$template/$fedit";
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
	fclose($ourFileHandle);

	$filename=$ourFileName;
	if (is_writable($filename)) {

		@$somecontent=stripslashes($content);
		if (!$handle = fopen($filename, 'a')) {
			echo "Невозможно открыть ($filename)";
			exit;
		}

		if (fwrite($handle, $content) === FALSE) {
			echo "Невозможна запись в ($filename)";
			exit;
		}

		fclose($handle);
		@chmod("../templates/$template/$fedit", 0644);
		@$contentcenter.="<div class=\"message_warn_ok\">Изменения в шаблоне сохранены!</div><br><br><br><br><a href='javascript:history.back(1)'><B>Вернуться назад</B></a>";

	}

}

include $localpath.'/admin/admintemplate.php';

?>
