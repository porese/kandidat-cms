<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(2>getpermision())header('LOCATION:index.php');
$sitetitle='Добавить новость';
$url=$_SERVER['PHP_SELF'];

include(CONF.'newsconf.php');
if(!empty($_SESSION['name']))$adminname=$_SESSION['name'];
$date=date("d.m.Y");
$time=date("H:i:s");
if(isset($_REQUEST['action'])){
	$head=filtermessage($_REQUEST['header']);
	$new=filterquotes($_REQUEST['editorh']);
	$extranew=filterquotes($_REQUEST['editor']);
	$adminname=filterquotes($_REQUEST['adminname']);
	$pubdate=trim($_REQUEST['pubdate']);
	$comments=(int)$_REQUEST['comments'];
	$idmess=time();
	$description = $new['description'];
	$keywords = $new['keywords'];
	$tags = $new['tags'];
	if(trim($_REQUEST['header'])==''||$new==''){
		$contentcenter='<font size="2" color="'.$warnalertcolor.'"><b>Вы не заполнили одно из обязательных полей!<br>Поля, отмеченные звездочкой (*), должны быть заполнены!</b></font>';
	}else{
		$data=array('head'=>$head,'mess'=>$new, 'aname'=>$adminname, 'admmail'=>$admmail, 'pubdate'=>$pubdate, 'pubtime'=>$time, 'extra'=>$extranew, 'id'=>$idmess, 'comments'=>$comments,'description'=>$description,'keywords'=>$keywords,'tags'=>$tags,);
		savedata($newsdbfilename, $data, 'a+');
		$contentcenter='<font size="2" color="'.$warnalertcolor.'"><b>Новость успешно добавлена!</b></font><br /><br />';
		$contentcenter.='<a href="../admin/news.php">Обзор новостей</a><br /><br />';
		$contentcenter.='<a href="../admin/addnews.php">Добавить новость</a><br />';
		include $localpath.'admin/admintemplate.php';
		exit;
	}
}else{
	$head='Заголовок новости';
	$new='Краткий текст новости';
	$extranew='Продолжение текста новости';
}

@$contentcenter .=<<<EOT
<script type='text/JavaScript' src='/admin/js/scw.js'></script>
<form action="$url" method="post" name="my_form">
<input type="hidden" name="action" id="action" value="go" />
<h3>Добавление новости:</h3>Заполните все поля формы!<br /><br />
<label title="Введите дату">Дата новости:
<input type="text" name="pubdate" id="pubdate" size="10" maxlength="10" readonly='readonly' value=$date>
<img src='images/calendar.png' /></label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Автор новости:
<input type="text" name="adminname" id="adminname" size="15" maxlength="100" value='$adminname' /></label><br />
<label><b>Комментарии:</b></label>
<label><input name="comments" type="radio" value="0" checked="checked" /> запрещены</label>
<label><input name="comments" type="radio" value="1"  /> остановлены</label>
<label><input name="comments" type="radio" value="2"  /> разрешены</label>
<br /><br />
<input type="text" name="header" size="70" maxlength="255" value="$head" /><br/><br/>
<textarea  id="editorh" name="editorh" cols=89 rows=4>$new</textarea>
<textarea  id="editor" name="editor" cols=89 rows=10>$extranew</textarea>
<label title="Описание страницы :: Описание страницы вводится как обычный текст; он заполняет метатег meta-description создаваемой страницы.  В описании указывают краткое содержание того, что представлено на странице. Не стоит данное поле заполнять ключевыми словами. Текст описания мало влияет на степень отношения поисковика к странице сайта."><b>Описание (description):</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="description" name="description" id="description" value="$description" /></label><br /><br />
<label title="Ключевые слова :: Ключевые слова вводятся через запятую; оно заполняет метатег meta-keywords создаваемой страницы. Каждая страница продвигается в поисковых системах по одному или нескольким ключевым словам. Стоит помнить, что некоторые поисковые системы (например, Яндекс) не придают значения указываемым ключевым словам. Ключевые слова, записанные для конкретной страницы обязательно должны присутствовать в ней. Плотность ключевых слов (число ключевых слов, отнесенных к общему числу слов) в тексте не должна превышать 5-10%, иначе поисковые роботы могут счесть страницу за спам."><b>Ключевые слова:</b>
<input type="text" name="keywords" id="keywords" value="$keywords"  size="40" /></label><br /><br />
<label title="Теги :: Теги (метки) вводятся через пробел; используется в облаке тегов создаваемой страницы."><b>Теги (метки):</b>
<input type="text" name="tags" class="settings" id="keywords" value="$tags" size="40" /></label><br /><br />
<div class="submit"><input type="submit" class="submit-button" value="Добавить новость" /></div><br />
</form>
EOT;
include $localpath.'admin/admintemplate.php';
?>
