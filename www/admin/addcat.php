<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(2>getpermision())header('LOCATION:index.php');
$date=date("d.m.Y");

$cat = (isset ($_GET['cat']))? $_GET['cat'] : "";
$title = filtermessage($_POST['title']);
$name_folder= trim($_POST['name_folder']);
$content = filterquotes($_POST['editor']);
$myinclude = isset($_POST['myinclude'])?trim($_POST['myinclude']):'main';
$description = trim($_POST['description']);
$keywords = trim($_POST['keywords']);
$for_menu = (int)$_POST['formenu'];
$for_shet = (int)$_POST['forshet'];
$razdel=trim($_REQUEST['razdel']);
$templatepage = trim($_POST['templatepage']);
$pubdate = trim($_POST['pubdate']);
$tags = trim($_POST['tags']);
$sitetitle='Добавить категорию';
if($cat==''){
    $hinfo='в корневую директорию сайта';
}else{
    $hinfo='в каталог /'.$cat.'/';
}
if($razdel==''){
    $golink='';
}else{
    $golink='?cat='.$razdel;
}

$url=$_SERVER['PHP_SELF'];

$myinclude='main';
@$contentcenter .='<h3>Добавление новой категории '.$hinfo.'</h3>
<form action="'.$url.'" method="post" name="my_form">
<input type="hidden" name="action" id="action" value="go" />
<label><b>Заголовок категории:</b>
<input type="text" name="title" class="settings"  id="title" size="70" maxlength="255" value="" /></label><br/><br/>
<label title="Выбор даты"><b>Дата публикации:</b>
<input type="text" name="pubdate" id="pubdate" size="10" maxlength="10" readonly="readonly" value='.$date.' />
<img src="images/calendar.png" /></label>
<img src="images/delete.png" title="Удалить дату" onclick="document.forms[\'my_form\'].elements[\'pubdate\'].value=\'\';" />
<br/><br/>
<label title="Если поле оставить пустым, название папки категории будет сгенерировано автоматически (рекомендуется)."><b>Можно не заполнять название папки категории в виде латинских букв и(или) цифр</b>
<input type="text" name="name_folder" class="settings" id="title" size="10" maxlength="80" value="" /></label><br><br>
<textarea name="editor" id="editor" cols=99 rows=25>'.$content.'</textarea><br />
<label title="Вводимое имя - это название файла (все символы до .php), находящегося в каталоге /mycode, в котором содержится код, который будет выводиться после основного текста создаваемой страницы. При создании страницы только с материалом указывается имя <b>main</b>, если необходимо создать страницу на которой будет выведена форма обратной связи, предваряемая текстом содержащим, например, адрес и схему проезда, необходимо вписать <b>feedback</b>. Вы можете создать и другой файл, который необходимо разместить в каталоге <b>/mycode</b> с вашим содержимым; он будет внедрен к основному тексту создаваемой страницы. Вторая строка должна содержать //phpfile"><b>ИМЯ php-кода:</b>&nbsp;&nbsp;&nbsp;
<span class="select-and-input">
'.get_kan_phpfile($myinclude).'
<input type="myinclude" name="myinclude" id="myinclude" value="'.$myinclude.'" size=10 />
</span></label><br><br>
<label title="Описание страницы :: Описание страницы вводится как обычный текст; он заполняет метатег meta-description создаваемой страницы.  В описании указывают краткое содержание того, что представлено на странице. Не стоит данное поле заполнять ключевыми словами. Текст описания мало влияет на степень отношения поисковика к странице сайта."><b>Описание (description):</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="description" name="description" class="settings" id="description" value="" size=40 /></label><br><br />
<label title="Ключевые слова :: Ключевые слова вводятся через запятую; оно заполняет метатег meta-keywords создаваемой страницы. Каждая страница продвигается в поисковых системах по одному или нескольким ключевым словам. Стоит помнить, что некоторые поисковые системы (например, Яндекс) не придают значения указываемым ключевым словам. Ключевые слова, записанные для конкретной страницы обязательно должны присутствовать в ней. Плотность ключевых слов (число ключевых слов, отнесенных к общему числу слов) в тексте не должна превышать 5-10%, иначе поисковые роботы могут счесть страницу за спам."><b>Ключевые слова (keywords):</b>
<input type="text" name="keywords" class="settings" id="keywords" value="" size=40 /></label><br /><br />
<label title="Теги :: Теги (метки) вводятся через пробел; используется в облаке тегов создаваемой страницы."><b>Теги (метки):</b>
<input type="text" name="tags" class="settings" id="keywords" value="" size=40 /></label><br /><br />
<label title="Шаблон для данной страницы. Позволяет сделать для каждой страницы свой шаблон. Находится в каталоге шаблона и имеет имя в виде template<...>,php"><b>Локальный шаблон:&nbsp;</b> '.get_templatepage($templatepage).'</label><br /><br />
<label><b>Включить ссылку страницы в меню?</b></label>
<input type="radio" name="formenu" value="1" style="border: 0;"> Да
<input checked="checked" type="radio" name="formenu" value="0" style="border: 0;" /> Нет<br /><br /><br />
<label><b>Включить ссылку страницы в страницу родитель?</b></label>
<input type="radio" name="forshet" value="1" style="border: 0;"> Да
<input checked="checked" type="radio" name="forshet" value="0" style="border: 0;"> Нет<br /><br /><br />
<div class="submit"><input type="hidden" name="razdel" value="'.$cat.'"><input type="submit" class="submit-button" value="Добавить категорию" /></div>
</form>';

if(!empty($_REQUEST['action'])){

	$errormessage='';
	if (strlen($title)==0) {
		$errormessage.='<li>Введите заголовок!</li>';
	}

	if ($errormessage=="") {
		if (strlen($name_folder)<1) {
			$newfolder=makepermalink(translit($title));
		} else {
			$newfolder=$name_folder;
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
		$somecontent .= "<!-- Kan_pubdate -->\n";
		$somecontent .= strtotime($pubdate);
		$somecontent .= "<!-- Kan_pubdate -->\n";
		$somecontent .= "<!-- Kan_tags -->\n";
		$somecontent .= $tags;
		$somecontent .= "<!-- Kan_tags -->\n";

		$folder=$localpath.'/articles/'.$razdel.'/';
		if(mkdir($folder.$newfolder, 0755)){

			$filename=$folder.$newfolder.'/main.dat';
			$somecontent=stripslashes($somecontent);

			if(!save($filename,$somecontent,'w')){
		    	$errormessage.= '<li>Этот '.$filename.' невозможно записать</li>';
			}
			@chmod($filename, 0644);

			if(!save($folder.$newfolder.'/index.php',
				'<?php header("Location: ../"); exit(); ?>;','w')){
		    	$errormessage.= '<li>Этот '.$folder.$newfolder.'/index.php невозможно записать</li>';
			}
			@chmod ($folder.$newfolder.'/index.php', 0644);

			if(!save($folder.$newfolder.'/.htaccess',
				"order deny,allow\ndeny from all\n",'w')){
		    	$errormessage.= '<li>Этот '.$folder.$newfolder.'/.htaccess невозможно записать</li>';
			}
			@chmod ($folder.$newfolder.'/.htaccess', 0644);


			if ($for_menu == 1) {
				$page=($razdel=='')?'/'.$newfolder.'/':'/'.$razdel.'/'.$newfolder.'/';
				if(!savedata(ENGINE.'menudb.php',array('page'=>$page,'head'=>$title)))$errormessage.= '<li>Невозможно записать меню файл меню</li>';
			}

			if(($for_shet==1)&&($razdel!=='')){
				$cat_myFile = $folder.'main.dat';
				$cat_file=file($cat_myFile);
				$cat_file[2]="<!-- Kan_content -->
<li><a href=\"/".$razdel.'/'.$newfolder."/\">".$title."</a></li>\r\n";
				if(!savearray($cat_myFile,$cat_file,'w',''))$errormessage='<li>Невозможно записать файл '.$cat_myFile.'</li>';
			}
		}else $errormessage.='<li>Невозможно создать каталог '.$folder.$newfolder.'</li>';
	}

	if(''!=$errormessage){
		@$contentcenter .=  '<h3>Ошибки</h3><ul>' . $errormessage . '</b><br /><br />'.$title.$contenet;
	}elseif($_REQUEST['action']=='go')header('Location: '.$prefflp.'/admin/index.php'.$golink);
	else header('Location: '.$prefflp.'/admin/edit.php'.$golink.'&what=main');
}
include $localpath.'admin/admintemplate.php';
?>
