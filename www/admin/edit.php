<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
$whatpage = preg_replace('/[^a-z0-9-_]/iu','',$_REQUEST['what']);
$cat  = (isset ($_GET['cat']))? trim($_GET['cat']) : '';
$subcat  = (isset ($_GET['subcat']))? trim($_GET['subcat']) : '';

if($cat==''){
	$folder=ARTICLES; $editpage='?what='; $linkinfo='';
}else{
	if($subcat ==''){
		$folder=ARTICLES.$cat; $editpage='?cat='.$cat.'&what='; $linkinfo='/'.$cat;
	}else{
		$folder=ARTICLES.$cat.'/'.$subcat; $editpage='?cat='.$cat.'&subcat='.$subcat.'&what='; $linkinfo='/'.$cat.'/'.$subcat;
	}
}

$errormessage='';
$myFile = $folder.'/'.$whatpage.'.dat';
if(!empty($_REQUEST['action'])){
	$title = filtermessage($_POST['title']);
	$content = filterquotes($_POST['editor']);
	$myinclude = trim($_POST['myinclude']);
	$description = trim($_POST['description']);
	$keywords = trim($_POST['keywords']);
	$templatepage = trim($_POST['templatepage']);
	$commentpage = trim($_POST['commentpage']);
	$pubdate = trim($_POST['pubdate']);
	$for_menu = (int)$_POST['formenu'];
	$tags = trim($_POST['tags']);
	$order = trim($_POST['order']);

	$somecontent = "<!-- Kan_title -->\n";
	$somecontent .= $title;
	$somecontent .= "<!-- Kan_title -->\n";
	$somecontent .= "<!-- Kan_content -->\n";
	$somecontent .= $content;
	$somecontent .= "<!-- Kan_content -->\n";
	$somecontent .= "<!-- Kan_myinclude -->\n";
	$somecontent .= $myinclude;
	$somecontent .= "<!-- Kan_myinclude -->\n";
	$somecontent .= "<!-- Kan_description -->\n";
	$somecontent .= $description;
	$somecontent .= "<!-- Kan_description -->\n";
	$somecontent .= "<!-- Kan_keywords -->\n";
	$somecontent .= $keywords;
	$somecontent .= "<!-- Kan_keywords -->\n";
	$somecontent .= "<!-- Kan_templatepage -->\n";
	$somecontent .= $templatepage;
	$somecontent .= "<!-- Kan_templatepage -->\n";
	$somecontent .= "<!-- Kan_comment -->\n";
	$somecontent .= $commentpage;
	$somecontent .= "<!-- Kan_comment -->\n";
	$somecontent .= "<!-- Kan_pubdate -->\n";
	$somecontent .= strtotime($pubdate);
	$somecontent .= "<!-- Kan_pubdate -->\n";
	$somecontent .= "<!-- Kan_tags -->\n";
	$somecontent .= $tags;
	$somecontent .= "<!-- Kan_tags -->\n";
	$somecontent .= "<!-- Kan_order -->\n";
	$somecontent .= $order;
	$somecontent .= "<!-- Kan_order -->\n";
	$somecontent=stripslashes($somecontent);

	if(!save($myFile,$somecontent,'w'))$errormessage.='<li>Невозможна запись в файл ('.$myFile.')</li>';
	if($for_menu == 1)if(!savedata(ENGINE.'menudb.php',array('page'=>($linkinfo.'/'.$whatpage.'.html'),'head'=>$title)))
         $errormessage.='<li>Невозможно записать файл меню</li>';
}else{
	if(file_exists($myFile)){
		$data = file_get_contents($myFile);
		$title=articlesparam('title',$data);
		$content = articlesparam('content',$data);
		$myinclude = articlesparam('myinclude',$data);
		$description = articlesparam('description',$data);
		$keywords = articlesparam('keywords',$data);
		$templatepage = articlesparam('templatepage',$data);
		$commentpage = articlesparam('comment',$data);
		$pubdate = articlesparam('pubdate',$data);
		if($pubdate!=='')$pubdate = date("d.m.Y",$pubdate);
		$tags = articlesparam('tags',$data);
		$order = articlesparam('order',$data);
	}else $errormessage='<li>Файл '.$myFile.' отсутствует</li>';
}


if(''!=$errormessage){
	$contentcenter =  '<h3>Ошибки</h3><ul>' . $errormessage . '</b><br /><br />';
}elseif($_REQUEST['action']=='go'){
	$url=$_SERVER['PHP_SELF'] . $editpage.$whatpage;
	@$contentcenter .=  '<h3>Успешно изменена</h3> Страница: <b>'.$title.'</b><br /><br />
	Ссылка для сайта: <b>'.$linkinfo.'/'.$whatpage.'.html</b><br />
	Если изменяли заголовок страницы, то отредактируйте также меню ссылок!</b><br /><br />
	<a href="'.$url.'">Возвратиться к редактированию страницы</a><br /><br />
	<a href="index.php'.$editpage.'">Вернуться в категорию страницы</a><br /><br />
	<a href="menueditor.php">Перейти в раздел Редактирование меню</a>';
}else{
	$is_checked_commentpage=array_fill(0, 2, '');
	$is_checked_commentpage[(int)$commentpage]='checked="checked"';

	$url=$_SERVER['PHP_SELF'] . $editpage.$whatpage;

	$templatedrop=get_templatepage($templatepage);
	$get_kan_phpfile=get_kan_phpfile($myinclude);

	if($whatpage=='main')$disabled_comment='disabled="disabled"';else $disabled_comment='';
	$sitetitle='Редакция страницы:  '.admlinkator($cat, $subcat).$title;
	$contentcenter .=<<<EOT
	<h3>Редактирование страницы</h3>$myFile
	<form action="$url" method="post" name="my_form">
		<input type="hidden" name="action" id="action" value="go" />
		<br /><label><b>Заголовок:</b>
		<input type="text" name="title" id="title" value="$title" size=40 /></label><br /><br />
		<label><b>Дата публикации:</b>
		<input type="text" name="pubdate" id="pubdate" size="10" maxlength="10" readonly="readonly" value="$pubdate" />
		<img src="images/calendar.png" title="Выбор даты" /></label>
		<img src="images/delete.png" title="Удалить дату" onclick="document.forms['my_form'].elements['pubdate'].value='';" /><br /><br />
		<textarea name="editor" id="editor" cols=99 rows=25>$content</textarea><br />
		<label  title="Вводимое имя - это название файла (все символы до .php), находящегося в каталоге /mycode, в котором содержится код, который будет выводиться после основного текста создаваемой страницы. При создании страницы только с материалом указывается имя <b>main</b>, если необходимо создать страницу на которой будет выведена форма обратной связи, предваряемая текстом содержащим, например, адрес и схему проезда, необходимо вписать <b>feedback</b>. Вы можете создать и другой файл, который необходимо разместить в каталоге <b>/mycode</b> с вашим содержимым; он будет внедрен к основному тексту создаваемой страницы. Вторая строка должна содержать //phpfile"><b>ИМЯ php-кода:</b>&nbsp;&nbsp;&nbsp;
		<span class="select-and-input">
		$get_kan_phpfile
		<input type="myinclude" name="myinclude" id="myinclude" value="$myinclude" size=10 />
		</span></label><br><br>
		<label title="Описание страницы :: Описание страницы вводится как обычный текст; он заполняет метатег meta-description создаваемой страницы.  В описании указывают краткое содержание того, что представлено на странице. Не стоит данное поле заполнять ключевыми словами. Текст описания мало влияет на степень отношения поисковика к странице сайта."><b>Описание (description):</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
		<input type="description" name="description" id="description" value="$description" /></label><br /><br />
		<label title="Ключевые слова :: Ключевые слова вводятся через запятую; оно заполняет метатег meta-keywords создаваемой страницы. Каждая страница продвигается в поисковых системах по одному или нескольким ключевым словам. Стоит помнить, что некоторые поисковые системы (например, Яндекс) не придают значения указываемым ключевым словам. Ключевые слова, записанные для конкретной страницы обязательно должны присутствовать в ней. Плотность ключевых слов (число ключевых слов, отнесенных к общему числу слов) в тексте не должна превышать 5-10%, иначе поисковые роботы могут счесть страницу за спам."><b>Ключевые слова (keywords):</b>
		<input type="text" name="keywords" id="keywords" value="$keywords"  size="40" /></label><br /><br />
		<label title="Теги :: Теги (метки) вводятся через точку с запятой ; используется в облаке тегов создаваемой страницы."><b>Теги (метки):</b>
		<input type="text" name="tags" class="settings" id="tags" value="$tags" size="40" /></label><br /><br />
		<label title="Порядковый номер в списке."><b>Порядковый номер:</b>
		<input type="text" name="order" class="settings" id="order" value="$order" size="10" /></label><br /><br />
		<label title="Шаблон для данной страницы. Позволяет сделать для каждой страницы свой шаблон. Находится в каталоге шаблона и имеет имя в виде template<...>,php"><b>Локальный шаблон:&nbsp</b> $templatedrop</label><br /><br />
		<label><b>Комментарии:</b></label>
		<label><input name="commentpage" type="radio" value="0" $is_checked_commentpage[0] /> запрещены</label>
		<label><input name="commentpage" type="radio" value="1" $is_checked_commentpage[1] /> остановлены</label>
		<label><input name="commentpage" type="radio" value="2" $is_checked_commentpage[2] /> разрешены</label>
		<br /><br />
		<label><b>Включить ссылку страницы в меню?</b></label>
		<input type="radio" name="formenu" value="1" style="border: 0;" /> Да
		<input checked type="radio" name="formenu" value="0" style="border: 0;" /> Нет<br /><br />
		<br /><br />
		<div class="submit"><input type="submit" class="submit-button" value="Изменить" /></div><br />
	</form>
EOT;
}
include $localpath.'admin/admintemplate.php';
?>
