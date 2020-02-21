<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(2>getpermision())header('LOCATION:'.$prefflp.'index.php');
$date=date("d.m.Y");

$cat  = (isset ($_GET['cat']))? trim($_GET['cat']) : '';
$subcat  = (isset ($_GET['subcat']))? trim($_GET['subcat']) : '';
$title = filtermessage($_POST['title']);
$name_link = trim($_POST['name_link']);
$content = filterquotes($_POST['editor']);
$myinclude = isset($_POST['myinclude'])?trim($_POST['myinclude']):'main';
$description = trim($_POST['description']);
$keywords = trim($_POST['keywords']);
$for_menu = (int)$_POST['formenu'];
$for_cat = (int)$_POST['forcat'];
$templatepage = trim($_POST['templatepage']);
$commentpage = trim($_POST['commentpage']);
$pubdate = trim($_POST['pubdate']);
$tags = trim($_POST['tags']);
if($cat==''){
	$folder=ARTICLES;$addpage='';$linkinfo='/';$golink='';
}else{
	if($subcat ==''){
		$folder=ARTICLES.$cat.'/';$addpage='?cat='.$cat;$linkinfo='/'.$cat.'/';$golink='?cat='.$cat;
	}else{
		$folder=ARTICLES.$cat.'/'.$subcat.'/';$addpage='?cat='.$cat.'&subcat='.$subcat;$linkinfo='/'.$cat.'/'.$subcat.'/';$golink='?cat='.$cat.'&subcat='.$subcat;
	}
}
$url=$_SERVER['PHP_SELF'].$addpage;
$templatedrop=get_templatepage($templatepage);
$get_kan_phpfile=get_kan_phpfile($myinclude);

$sitetitle='Добавить страницу';
$contentcenter=<<<EOT
<h3>Добавление новой страницы</h3>в каталог $linkinfo<br/><br/>
<form action="$url" method="post" name="my_form">
<input type="hidden" name="action" id="action" value="go" />
<label><b>Заголовок:</b>
<input type="text" name="title" class="settings"  id="title" size="70" maxlength="255" value="" /></label><br/><br/>
<label><b>Дата публикации:</b>
<input type="text" name="pubdate" id="pubdate" size="10" maxlength="10" readonly="readonly" value="$date" />
<img src="images/calendar.png" title="Выбор даты" /></label>
<img src="images/delete.png" title="Удалить дату" onclick="document.forms['my_form'].elements['pubdate'].value='';" />
<br/><br/>
<label title="Если поле оставить пустым, название файла будет сгенерировано автоматически."><b>Название файла страницы будет записано в системе в виде латинских букв и(или) цифр вот так: </b>
<input type="text" name="name_link" class="settings" id="title" size="10" maxlength="35" value="" /><b>.html</b></label><br><br>
<textarea name="editor" id="editor" cols=99 rows=25>$content</textarea><br />
<label title="Вводимое имя - это название файла (все символы до .php), находящегося в каталоге /mycode, в котором содержится код, который будет выводиться после основного текста создаваемой страницы. При создании страницы только с материалом указывается имя <b>main</b>, если необходимо создать страницу на которой будет выведена форма обратной связи, предваряемая текстом содержащим, например, адрес и схему проезда, необходимо вписать <b>feedback</b>. Вы можете создать и другой файл, который необходимо разместить в каталоге <b>/mycode</b> с вашим содержимым; он будет внедрен к основному тексту создаваемой страницы. Вторая строка должна содержать //phpfile"><b>ИМЯ php-кода:</b>&nbsp;&nbsp;&nbsp;
<span class="select-and-input">
$get_kan_phpfile
<input type="text" name="myinclude" id="myinclude" value="$myinclude" size=10 />
</span></label><br><br>
<label title="Описание страницы :: Описание страницы вводится как обычный текст; он заполняет метатег meta-description создаваемой страницы.  В описании указывают краткое содержание того, что представлено на странице. Не стоит данное поле заполнять ключевыми словами. Текст описания мало влияет на степень отношения поисковика к странице сайта."><b>Описание (description):</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="description" name="description" class="settings" id="description" value="" size=40 /></label><br><br />
<label title="Ключевые слова :: Ключевые слова вводятся через запятую; оно заполняет метатег meta-keywords создаваемой страницы. Каждая страница продвигается в поисковых системах по одному или нескольким ключевым словам. Стоит помнить, что некоторые поисковые системы (например, Яндекс) не придают значения указываемым ключевым словам. Ключевые слова, записанные для конкретной страницы обязательно должны присутствовать в ней. Плотность ключевых слов (число ключевых слов, отнесенных к общему числу слов) в тексте не должна превышать 5-10%, иначе поисковые роботы могут счесть страницу за спам. "><b>Ключевые слова (keywords):</b>
<input type="text" name="keywords" class="settings" id="keywords" value="" size=40 /></label><br /><br />
<label title="Теги :: Теги (метки) вводятся через пробел; используется в облаке тегов создаваемой страницы."><b>Теги (метки):</b>
<input type="text" name="tags" class="settings" id="keywords" value="" size=40 /></label><br /><br />
<label title="Шаблон для данной страницы. Позволяет сделать для каждой страницы свой шаблон. Находится в каталоге шаблона и имеет имя в виде template<...>,php"><b>Локальный шаблон:&nbsp;</b>$templatedrop</label><br /><br />
<label><b>Комментарии:</b></label>
<label><input name="commentpage" type="radio" value="0" checked="checked" /> запрещены</label>
<label><input name="commentpage" type="radio" value="1"  /> остановлены</label>
<label><input name="commentpage" type="radio" value="2"  /> разрешены</label>
<br /><br />
<label><b>Включить ссылку страницы в меню?</b></label>
<input type="radio" name="formenu" value="1" style="border: 0;" /> Да
<input checked="checked" type="radio" name="formenu" value="0" style="border: 0;" /> Нет<br /><br />
<label><b>Включить ссылку страницы в страницу категории?</b></label>
<input type="radio" name="forcat" value="1" style="border: 0;" /> Да
<input checked="checked" type="radio" name="forcat" value="0" style="border: 0;" /> Нет<br /><br />
<div class="submit"><input type="submit" class="submit-button" value="Добавить страницу" /></div>
</form>
EOT;

if(!empty($_REQUEST['action'])){
	$errormessage='';
	if (strlen($title)==0)$errormessage.='<li>введите заголовок</li>';

	if ($errormessage=='') {
		if (strlen($name_link)<1) {
	   		$np=makepermalink(translit($title));
		} else {
		 	$np=$name_link;
		}
		if ($for_menu == 1) {
			if(!savedata(ENGINE.'menudb.php',array('page'=>($linkinfo.$np.'.html'),'head'=>$title))){
		         $errormessage='<li>Невозможно записать файл меню</li>';
			 }
		}

		if ($for_cat == 1) {
			$cat_myFile = $folder.'main.dat';
			$cat_file=file($cat_myFile);
			$cat_file[2]="<!-- Kan_content -->
<li><a href=\"".$linkinfo.$np.".html\">".$title."</a></li>\r\n";
			if(!savearray($cat_myFile,$cat_file,'w','')){
		         $errormessage='<li>Невозможно записать файл '.$cat_myFile.'</li>';
			 }
		}
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

		$filename=$folder.$np.'.dat';
		if(!save($filename,$somecontent,'w')){
			$errormessage.= '<li>Файл '.$filename.' невозможно записать</li>';
		}
	}
	if(''!=$errormessage){
		@$contentcenter .=  '<h3>Ошибки</h3><ul>' . $errormessage . '</b><br /><br />'.$title.$contenet;
	}elseif($_REQUEST['action']=='go')header('Location: '.$prefflp.'/admin/index.php'.$golink);
	else header('Location: '.$prefflp.'/admin/edit.php'.$golink.'&what='.$np);
}

include $localpath.'/admin/admintemplate.php';

?>
