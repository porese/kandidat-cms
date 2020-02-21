<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');
$sitetitle='Настройки сайта';
$myFile = CONF.'/config.php';
$title=$sitename;

@$contentcenter .='<h3>Настройки сайта</h3>';

if ($_REQUEST['action']=="validate") {
	$title = trim($_POST["title"]);
	$template = trim($_POST["template"]);

	$siteoff = $_POST["siteoff"];
	$offtext = trim($_POST["offtext"]);
	$cc_url = trim($_POST["cc_url"]);
	$gzip_enable = trim($_POST["gzip_enable"]);
	$g_captcha = trim($_POST["g_captcha"]);
	$commentsperpage_comments = trim($_POST["commentsperpage_comments"]);
	$moder_comments=trim($_POST["moder_comments"]);
	$sape_user = trim($_POST["sape_user"]);
	$linkfeed_user = trim($_POST["linkfeed_user"]);
	$language = trim($_POST["language"]);
	$rss_content = trim($_POST["rss_content"]);
	$prefflp = trim($_POST["prefflp"]);
	$saftertitle = (int)$_POST["saftertitle"];
	$sperpage = (int)$_POST["sperpage"];
	$sonlytitle = trim($_POST["sonlytitle"]);
	$disableURL = (int)$_POST["disableURL"];
}
if ($siteoff=="1") $is_checked='checked="checked"';
if ($cc_url=="1") $is_checked_cc_url='checked="checked"';
if ($gzip_enable=="1") $is_checked_gzip_enable='checked="checked"';
if ($g_captcha=="1") $is_checked_g_captcha=array('checked="checked"','');else$is_checked_g_captcha=array('','checked="checked"');
if ($moder_comments=="1") $is_checked_moder_comments='checked="checked"';
if ($sonlytitle=="1") $is_sonlytitle='checked="checked"';
if ($disableURL==1) $is_checked_disableURL='checked="checked"';

$arrtmpl=array();
$d = dir('../templates');
while($entry=$d->read())
		if ($entry{0}!='.'&&is_dir(LOCALPATH.'templates/'.$entry))$arrtmpl[$entry]=$entry;
$d->close();
$templatedrop=make_droplist($arrtmpl,'template',$template);

$arrlng=array();
$d = dir('../lang');
while($entry=$d->read())
	if(getftype($entry)=='php'){
		$entry = basename($entry,'.php');
		if ($entry{0}!='.' )$arrlng[$entry]=$entry;
	}
$d->close();
$languagedrop=make_droplist($arrlng,'language',$language);

$rss_contentdrop='<select name="rss_content" id="rss_content">';
$rss_contentdrop.='<option '.(($rss_content=='1')?'selected="selected"':'').' value="1">Только новости</option>';
$rss_contentdrop.='<option '.(($rss_content=='2')?'selected="selected"':'').' value="2">Только статьи</option>';
$rss_contentdrop.='<option '.(($rss_content=='3')?'selected="selected"':'').' value="3">Все</option>';
$rss_contentdrop.='</select>';

$captchainf=__('Возможно хостинг не совместим с модулем "графическая каптча"');
$gdinfo=getgdinfo();
if(false!==$gdinfo){
	if($gdinfo>1){
		if(1==getgdinfo('FreeType Support')){
			$captchainf=__('"Графическая каптча" поддерживается хостингом');
		};
	};
}


$url=$_SERVER['PHP_SELF'];
$contentcenter .=<<<EOT
<form action="$url?action=validate" method="post" name="my_form">
	<label title="Название сайта :: Данный текст будет выводиться и вверху браузера и на страницах сайта, если такое предусмотрено в коде шаблона." >Название сайта<br />
	<input class="settings" size="50" type="text" name="title" id="title" value="$title" /></label>
	<br /><br />
	<label>Шаблон&nbsp;&nbsp;$templatedrop</label>
	<br /><br />
	<label>Язык&nbsp;&nbsp;$languagedrop</label>
	<br /><br />
	<label title="Префикс сайта :: Если нестандартные настройки хостинга, может потребоваться указать префикс ручками. Если не из каталога, оставляем пустым.">Префикс сайта (каталог из которого работает)
	<input class="settings" size="10" type="text" name="prefflp" id="prefflp" value="$prefflp" /></label>
	<br /><br />
	<label><input class="settings" type="checkbox" name="siteoff"  $is_checked />Сайт на реконструкции</label><br />
	<input class="settings" size="50" type="text" name="offtext" value="$offtext" title="Текст, который будет выводиться на странице реконструкции. " />
	<br /><br />
	<label><input class="settings" type="checkbox" name="cc_url" value="1" $is_checked_cc_url />Не использовать ЧЧУРЛЫ (без mod_rewrite)</label><br />
	<br />
	<label><input class="settings" type="checkbox" name="gzip_enable" value="1" $is_checked_gzip_enable />Использоваить gzip сжатие при передаче контента сервером клиенту</label><br />
	<br />
	Каптча. $captchainf.<br />
	<label><input name="g_captcha" type="radio" value="1" $is_checked_g_captcha[0] /> графическая</label>
	<label><input name="g_captcha" type="radio" value="0" $is_checked_g_captcha[1] /> текстовая</label>
	<br /><br />
	<label title="Количество комментариев на странице в статьях...:: 20 (можно любую, зависит от шаблона) ">Количество комментариев на странице*
	<input class="settings" type="text" size="10" name="commentsperpage_comments" id="commentsperpage_comments" value="$commentsperpage_comments" /></label>
	<br />
	<label><input class="settings" type="checkbox" name="moder_comments" value="1" $is_checked_moder_comments />Модерация комментариев</label>
	<br /><br />
	<label><input class="settings" type="checkbox" name="disableURL" value="1" $is_checked_disableURL />Запрещать вставлять URL в комментарях и гостевой книге</label><br />
	<br /><br />
	<label>Контент выводимый RSS&nbsp;&nbsp;$rss_contentdrop</label>
	<br /><br />
	<label title="Количество статей на странице в плагине статьи...:: 20 (можно любую, зависит от шаблона) ">Количество статей на странице*
	<input class="settings" type="text" size="10" name="sperpage" id="sperpage" value="$sperpage" /></label>
	<br />
	<label title="Количество символов из статьи следом за заголовком на странице в плагине статьи...:: 450 (можно любую, зависит от шаблона). Если 0 тогда показываются только заголовки.">Количество символов из статьи*
	<input class="settings" type="text" size="10" name="saftertitle" id="saftertitle" value="$saftertitle" /></label>
	<br />
	<label><input class="settings" type="checkbox" name="sonlytitle" id="sonlytitle" value="1" $is_sonlytitle />Показывать с статьях только заголовки</label>
	<br /><br />
	<label  title="Имя выданное SAPE, в корне должен быть каталог с таким имененм. В любом месте шаблона/дизайна вставляем функцию sape_link(), которая будет отображать ссылки">Имя выданное SAPE<br />
	<input class="settings" size="50" type="text" name="sape_user" id="sape_user" value="$sape_user" /></label>
	<br /><br />
	<label title="Имя выданное LinkFeed, в корне должен быть каталог с таким имененм. В любом месте шаблона/дизайна вставляем функцию linkfeed_link(), которая будет отображать ссылки">Имя выданное LinkFeed<br />
	<input class="settings" size="50" type="text" name="linkfeed_user" id="linkfeed_user" value="$linkfeed_user" /></label>
	<br /><br />
	<div class="submit"><input type="submit" id="submit" name="submit" value="Сохранить изменения" /></div><br />
</form>
EOT;

$contentcenter = stripslashes($contentcenter);

if ($_REQUEST['action']=='validate') {
	$errormessage='';

	if (empty($title)) {
		$errormessage='<li>Введите название сайта</li>';
	}

	if (''==$errormessage) {
		$somecontent = "<?php\n";
		$somecontent .= "\$sitename=\"$title\";\n";
		$somecontent .= "\$template=\"$template\";\n";
		$somecontent .= "\$siteoff=\"$siteoff\";\n";
		$somecontent .= "\$offtext=\"$offtext\";\n";
		$somecontent .= "\$cc_url=\"$cc_url\";\n";
		$somecontent .= "\$gzip_enable=\"$gzip_enable\";\n";
		$somecontent .= "\$g_captcha=\"$g_captcha\";\n";
		$somecontent .= "\$commentsperpage_comments=".(int)$commentsperpage_comments.";\n";
		$somecontent .= "\$moder_comments=\"$moder_comments\";\n";
		$somecontent .= "\$sape_user=\"$sape_user\";\n";
		$somecontent .= "\$language=\"$language\";\n";
		$somecontent .= "\$rss_content=\"$rss_content\";\n";
		$somecontent .= "\$prefflp=\"$prefflp\";\n";
		$somecontent .= "\$sperpage=$sperpage;\n";
		$somecontent .= "\$saftertitle=$saftertitle;\n";
		$somecontent .= "\$sonlytitle=\"$sonlytitle\";\n";
		$somecontent .= "\$disableURL=$disableURL;\n";
		$somecontent .="?>";

		@unlink($myFile);
		if(save($myFile,$somecontent,'a')===false){
			$errormessage='<li>Невозможно записать файл '.$myFile.'</li>';
		}
		Header('Location: index.php');
	}

	if (''!=$errormessage) {
		$contentcenter.='<br /><ul class="message_warn_error">'.$errormessage.'</ul>';
	}
}
include $localpath.'/admin/admintemplate.php';

?>
