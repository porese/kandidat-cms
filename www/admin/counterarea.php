<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(2>getpermision())header('LOCATION:index.php');

$updateornot = @$_POST['Submit'];
$content = @$_POST['article'];

$myFile = ENGINE.'counter.php';
$sitetitle='Редактирование блока counter';

if(empty($updateornot)){
	$data='';
	if(file_exists($myFile))
		if(filesize($myFile)>0){
			$data = file_get_contents($myFile);
		}	
	$url=$_SERVER['PHP_SELF'];
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
		,syntax: "php"
		,toolbar: "search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, word_wrap, help"
		,syntax_selection_allow: "css,html,js,php,perl"
		,show_line_colors: true
	    });
	</script>
	<h3>$sitetitle</h3>
	Данный блок можно ставить в шаблон сайта, в любое удобное место<br />
	<div class="code">
		&lt;?include \$localpath."/engine/counter.php";?&gt;
	</div>
	<form action="$url" method="post" name="my_form">
	<textarea name="article" id="code" rows=30 style="width: 100%;">$data</textarea>
	<br /><br>
	<div class="submit"><input type="submit" class="submit-button" id="Submit" name="Submit" value="Сохранить изменения" /></div><br />
	</form>
EOT;
	$contentcenter = stripslashes($contentcenter);
}else{
	$content=stripslashes($content);
	save($myFile,$content,'w');
#	@chmod($myFile, 0644);
	@$contentcenter.="<div class=\"message_warn_ok\">Изменения в блоке counter сохранены!</div><br><br><br><br><a href='javascript:history.back(1)'><B>Вернуться назад</B></a>";
}

include $localpath.'/admin/admintemplate.php';

?>
