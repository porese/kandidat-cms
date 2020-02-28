<?php
// Не изменять, функции можно добавлять
$localpath=substr(str_replace('\\','/',dirname(__FILE__)),0,-4);
$document_root=str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']);
if(empty($pefflp))if(strpos($localpath,$document_root)!==false){
	/* В данном случае не MAS или другая железка которая виртуалы делает по своему,             */
	/* выдираем префикс из разницы, в ином случае если $prefflp не пустое берем его из конфига  */
	if(($prefflp=str_replace($document_root,'',$localpath))!==''){
  			$prefflp=(substr($prefflp,0,1)=='/')?$prefflp:'/'.$prefflp;
			$prefflp=(substr($prefflp,-1)=='/')?substr($prefflp,0,-1):$prefflp;
	}
}
//каталоги
define ('LOCALPATH', $localpath);
define ('ARTICLES', $localpath.'articles/');
define ('ENGINE', $localpath.'engine/');
define ('MYCODE', $localpath.'mycode/');
define ('LANG', $localpath.'lang/');
define ('CONF', $localpath.'conf/');
define ('CODE', $localpath.'code/');
include_once CODE.'ver.php';
// подключаем Мультиязычность
if(file_exists(LANG.$language.'.php')){
	include (LANG.$language.'.php');
}
function __($key){
	global $lng;
	if(is_array($lng))if(array_key_exists($key,$lng))return $lng[$key];
	return $key;
}

//-----мультиязычность
// 	для utf8
// 	назначаем обработчик строк - MB|Iconv|PHP - в зависимости от конфигурации сервера
define('STRING_HANDLER', (function_exists('mb_strlen')) ? 'MB' : (function_exists('iconv_strlen') ? 'Iconv' : 'PHP'));
switch (STRING_HANDLER) {
	case 'MB':
		mb_internal_encoding('UTF-8');
		mb_http_output('UTF-8');
	break;
	case 'Iconv':
		iconv_set_encoding('input_encoding', 'UTF-8');
		iconv_set_encoding('internal_encoding', 'UTF-8');
		iconv_set_encoding('output_encoding', 'UTF-8');
		iconv_set_encoding('internal_charset', 'UTF-8');
	break;
}
function fstrtolower($str){
	$str = (string)$str;
	if (STRING_HANDLER == 'MB') return mb_strtolower($str, 'utf-8');
	return utf8_encode(strtolower(utf8_decode($str)));
}
function fstrlen ($str) {
	$str = (string)$str;
	if (STRING_HANDLER == 'MB') return mb_strlen($str, 'utf-8');
	if (STRING_HANDLER == 'Iconv') return iconv_strlen($str, 'utf-8');
	return strlen(utf8_decode($str));
}
function fsubstr($str,$start,$scount) {
	$str = (string)$str;
	if (STRING_HANDLER == 'MB') return mb_substr($str,$start,$scount, 'utf-8');
	if (STRING_HANDLER == 'Iconv') return iconv_substr($str,$start,$scount, 'utf-8');
	return utf8_encode(substr(utf8_decode($str),$start,$scount));
}
function fstrpos($str,$ssubstr,$start) {
	$str = (string)$str;
	if (STRING_HANDLER == 'MB') return mb_strpos($str,$ssubstr,$start, 'utf-8');
	if (STRING_HANDLER == 'Iconv') return iconv_strpos($str,$ssubstr,$start, 'utf-8');
	return strpos(utf8_decode($str),$ssubstr,$start);
}
// функция превода текста с кириллицы в транслит совместимо с utf-8
function translit($st){
	$replace_array=array("Ґ"=>"G","Ё"=>"YO","Є"=>"Ye","є"=>"ie","Ї"=>"YI","І"=>"I",
						"і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"#","є"=>"e",
						"ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
						"Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
						"Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
						"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
						"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
						"Ш"=>"SH","Щ"=>"SHCH","Ъ"=>"'","Ы"=>"YI","Ь"=>"",
						"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
						"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
						"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
						"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
						"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
						"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"shch","ъ"=>"'",
						"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
						);
	$st=str_replace(array_keys($replace_array), array_values($replace_array), $st);
 	return $st;
}
//
function linkator($whatpage, $catpage, $subcatpage, $sitetitle) {
	if (!empty($sitetitle)){
		if (empty($whatpage)&&empty($catpage))	$lnav = __('Добро пожаловать!');
		else{
			$lnav = '<a href="'.cc_link('/').'">'.__('Главная').'</a> - ';
			if(!empty($catpage)){
				if(file_exists(ARTICLES.$catpage.'/main.dat')){
					$tlevel = articlesparam('title',file_get_contents(ARTICLES.$catpage.'/main.dat'));
					if(!empty($whatpage)||!empty($subcatpage))$lnav .= '<a href="'.cc_link('/'.$catpage.'/').'">'.$tlevel.'</a> - ';
					if(!empty($subcatpage)){
						if(file_exists(ARTICLES.$catpage.'/'.$subcatpage.'/main.dat')){
							$tlevel = articlesparam('title',file_get_contents(ARTICLES.$catpage.'/'.$subcatpage.'/main.dat'));
							if(!empty($whatpage))$lnav .= '<a href="'.cc_link('/'.$catpage.'/'.$subcatpage.'/').'">'.$tlevel.'</a> - ';
						}
					}
				}
			}
			$lnav .= $sitetitle;
		}
	}
	return $lnav;
}
function admlinkator($catpage, $subcatpage){
	$lnav='<a href="../admin/index.php">'.__('Главная').'</a> - ';
	if($catpage!=''){
		if(file_exists(ARTICLES.$catpage.'/main.dat'))$tlevel = articlesparam('title',file_get_contents(ARTICLES.$catpage.'/main.dat'));
		else $tlevel=$catpage;
		$lnav.='<a href="../admin/index.php?cat='.$catpage.'">'.$tlevel.'</a> - ';
	}
	if($subcatpage!=''){
		if(file_exists(ARTICLES.$catpage.'/'.$subcatpage.'/main.dat'))$tlevel = articlesparam('title',file_get_contents(ARTICLES.$catpage.'/'.$subcatpage.'/main.dat'));
		else $tlevel=$catpage;
		$lnav.='<a href="../admin/index.phpcat='.$catpage.'&subcat='.$subcatpage.'">'.$tlevel.'</a> - ';
	}
	return $lnav;
}
function menulink($name) {
	$name= basename($name,'.dat');
	return fstrtolower(trim($name));
}
function makepermalink($thisurl) {
	$thisurl=str_replace ( ' ', '_', $thisurl);
	$thisurl = preg_replace('/[^a-zа-я0-9-_]/iU','',$thisurl);
	return fstrtolower(trim($thisurl));
}
function articlesparam($findwhat, $data) {
	if(preg_match('/(title)|(content)|(myinclud)|(description)|(keywords)|(templatepage)|(comment)|(pubdate)|(code)|(permission)|(tags)|(order)/',$findwhat)){
		@$text = explode('<!-- Kan_'.trim($findwhat).' -->',$data);
    	$text =  isset($text[1])?trim($text[1]):'';
	}
	return $text;
}
//Представление ссылок в формате без ЧПУ
function cc_link($mylink){
  global $cc_url, $prefflp;
  if (($mylink=='/')|($mylink=='')) return $prefflp.'/';
  if($cc_url){
		$patterns = array(
		"/^\/photo-cat-([-a-zA-Z0-9_]*)?-([0-9]*)?.html$/isu",
		"/^\/photo-cat-([-a-zA-Z0-9_]*).html?/isu",
		"/^\/photo-([0-9]*).html?/is",

		"/^\/news\/?([0-9]*)\.html?/siu",
		"/^\/news\/?([0-9]*)_([0-9]*)\.html/siu",
		"/^\/news\/?page-([0-9]*)\.html?/siu",

		"/^\/news-([0-9]*)\.html?/siu",
		"/^\/news-([0-9]*)_([0-9]*)\.html/siu",
		"/^\/news-page-([0-9]*).html?/siu",

		"/^\/guestbook-([-a-zA-Z0-9_]*)-([0-9]*).html?/isu",
		"/^\/guestbook\/?([-a-zA-Z0-9_]*)-([0-9]*).html?/isu",

		"/^\/cloudtags-([-a-zа-яА-Я0-9_]*)\.html?$/siu",
		"/^\/cloudtags\/?([-a-zа-яА-Я0-9_]*)\.html?$/siu",

		"/^\/([-a-zA-Z0-9_]*)\/?spage-([0-9]*).html?$/isu",
		"/^\/([-a-zA-Z0-9_]*)\/?([-a-zA-Z0-9_]*)\/?spage-([0-9]*).html?$/isu",

		"/^\/katalog-([0-9]*).html?/isu",
		"/^\/katalog-page-([0-9]*).html?/isu",

		"/^\/rss.html?/isu",
		"/^\/download\/?(.*)/isu",
		"/^\/lenta-([0-9]*).html?/isu",

		"/^\/([-a-zA-Z0-9_]*)?.html$/siu",
		"/^\/([-a-zA-Z0-9_]*)\/?([-a-zA-Z0-9_]*)?.html$/isu",
		"/^\/([-a-zA-Z0-9_]*)\/?$/isu",
		"/^\/([-a-zA-Z0-9_]*)\/?([-a-zA-Z0-9_]*)\/?([-a-zA-Z0-9_]*)?.html$/isu",
		"/^\/([-a-zA-Z0-9_]*)\/?([-a-zA-Z0-9_]*)\/?$/isu"
		);
		$replace = array(
		"/index.php?whatpage=photo&amp;cat=$1&amp;page=$2",
		"/index.php?whatpage=photo&amp;cat=$1",
		"/index.php?whatpage=photo&amp;page=$1",

		"/index.php?catpage=news&amp;view=$1",
		"/index.php?catpage=news&amp;view=$1&amp;commentpage=$2",
		"/index.php?catpage=news&amp;newspage=$1",

		"/index.php?whatpage=news&amp;view=$1",
		"/index.php?whatpage=news&amp;view=$1&amp;commentpage=$2",
		"/index.php?whatpage=news&amp;newspage=$1",

		"/index.php?whatpage=guestbook&amp;$1=$2",
		"/index.php?catpage=guestbook&amp;$1=$2",

		"/index.php?whatpage=cloudtags&amp;tags=$1",
		"/index.php?catpage=cloudtags&amp;tags=$1",

		"/index.php?catpage=$1&amp;spage=$2",
		"/index.php?catpage=$1&amp;subcatpage=$2&amp;spage=$3",

		"/index.php?whatpage=katalog&amp;view=$1",
		"/index.php?whatpage=katalog&amp;katalogpage=$1",

		"/rss.php",
		"/download.php?file=$1",
		"/index.php?whatpage=lenta&amp;page=$1",

		"/index.php?whatpage=$1",
		"/index.php?catpage=$1&amp;whatpage=$2",
		"/index.php?catpage=$1",
		"/index.php?catpage=$1&amp;subcatpage=$2&amp;whatpage=$3",
		"/index.php?catpage=$1&amp;subcatpage=$2"
		);

		if($prefflp!==''){
			$pattern='/^\\'.$prefflp.'/isU';
			$mylink=preg_replace($pattern,'',$mylink);
		}
		$url=$prefflp.preg_replace($patterns,$replace,$mylink);
		return $url;

  }else return $prefflp.$mylink;
}
//Время создания странички
function printbuildtime($dopinfo=0){
    //1 - gzip
    //2 - GD
    global $begintime, $support_gzip;
    $result= '('. round(microtime(true)-$begintime,4).' s.';
    if(($dopinfo&1)==1)if($support_gzip) $result.=', gzip';
    if(($dopinfo&2)==2){
	$gdinfo=getgdinfo();
	if(false!==$gdinfo)$result.=', GD'.$gdinfo;
    }
    $result.=')';
    return $result;
}
function statistika($filename) {
	$data[date]=date("d.m.Y",time());
	$data[time]=date("H:i:s",time());
	$data[agent]=$_SERVER[HTTP_USER_AGENT];
	$data[lang]=$_SERVER[HTTP_ACCEPT_LANGUAGE];
	$data[ip]=$_SERVER[REMOTE_ADDR];
	$data[host]=gethostbyaddr($_SERVER[REMOTE_ADDR]);
	$data[referer]=$_GET[referer];
	$data[res]=$_GET[res];
	savedata($filename, $data, 'a+');
}
//Закрытие открытых тегов
function close_dangling_tags($html){
	preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
	$openedtags=$result[1];
	preg_match_all('#</([a-z]+)>#iU',$html,$result);
	$closedtags=$result[1];
	$len_opened = count($openedtags);
	if(count($closedtags) == $len_opened)return $html;
	$openedtags = array_reverse($openedtags);
	for($i=0;$i < $len_opened;$i++)
		if(!in_array($openedtags[$i],$closedtags))$html .= '</'.$openedtags[$i].'>';
		else unset($closedtags[array_search($openedtags[$i],$closedtags)]);
	return $html;
}
//Комментарии
//Количество комментариев
function getcountcomments($idmess, $filename,$moder='0') {
	$moder=(int)$moder;
	$cuntcom=0;
	if(file_exists($filename)){
		if(sizeof($comments=loaddata($filename))>0){
			foreach($comments as $item){
				if($item['id']==$idmess){
					if(($moder>0)&&((int)$item['moderator']==1))$cuntcom++;
					else $cuntcom++;
				}
			}
		}
	}
	return $cuntcom;
}
//Изъять комментарии в массив
function getcomments($idmess, $filename,$moder='0') {
	$moder=(int)$moder;
	$arrcomments=array();
	if(file_exists($filename)){
		$comments=loaddata($filename);
		if(is_array($comments))foreach($comments as $item){
			if($item['id']==$idmess){
				if(($moder>0)&&((int)$item['moderator']==1))$arrcomments[]=$item;
				else $arrcomments[]=$item;
			}
		}
	}
	return $arrcomments;
}
//Добавить комментарий
function addcomments($idmess, $filename, $content, $name, $email) {
	$data=array('id'=>$idmess,
			'id_comment'=>time(),
			'content'=>$content,
			'name'=>$name,
			'email'=>$email,
			'ip'=>$_SERVER[REMOTE_ADDR]);
	return savedata ($filename, $data);
}
//Удаление всех комментариев к сообщению
function dellallcomments($idmess, $filename) {
	if(file_exists($filename)){
		$comments=loaddata($filename);
		if(sizeof($comments)>0){
			foreach($comments as $item)if($item['id']!==$idmess)$ncomments[]=$item;
			return savedataarray($filename,$ncomments,'w');
		}
	}
	return false;
}
//Удаление комментария
function dellcomments($idmess, $idcomment, $filename) {
	if(file_exists($filename)){
		$comments=loaddata($filename);
		foreach($comments as $item)if(!(($item['id']==$idmess)&&($item['id_comment']==$idcomment)))$ncomments[]=$item;
		return savedataarray($filename,$ncomments,'w');
	}
	return false;
}
//Модерация комментария
function modercomments($idmess, $idcomment, $filename, $moder=1) {
	if(file_exists($filename)){
		$comments=loaddata($filename);
		foreach($comments as $item){
	  	    if(($item['id']==$idmess)&&($item['id_comment']==$idcomment))$item['moderator']=$moder;
	  	    $ncomments[]=$item;
		}
		return savedataarray($filename,$ncomments,'w');
	}
	return false;
}
//Функции записи - чтения из файлов
//Запись массива (ключевого) в формат пыха
function savedata ($filename, $data, $rez='a+',$endstr="\n"){
	$f=fopen($filename, $rez);
	if(false===$f)return false;
	flock($f,LOCK_EX);
	if(fwrite($f,serialize($data).$endstr)===false)return false;
	flock($f,LOCK_UN);
	fclose($f);
	return true;
}
//Запись
function save ($filename, $data, $rez='a+'){
	$f=fopen($filename,$rez);
	if(false===$f)return false;
	flock($f,LOCK_EX);
	if(fwrite($f,$data)===false)return false;
	flock($f,LOCK_UN);
	fclose($f);
	return true;
}
//Запись одномерного массива
function savearray ($filename, $data, $rez='a+',$endstr="\n") {
	$f=fopen($filename,$rez);
	if(false===$f)return false;
	flock($f,LOCK_EX);
	$sizeofdata=sizeof($data);
	for($i=0;$i<$sizeofdata;$i++)if(fwrite($f,$data[$i].$endstr)===false)return false;
	flock($f,LOCK_UN);
	fclose($f);
	return true;
}
//Запись двумерного массива
function savedataarray ($filename, $data, $rez='a+',$endstr="\n") {
	$f=fopen($filename,$rez);
	if(false===$f)return false;
	flock($f,LOCK_EX);
	$sizeofdata=sizeof($data);
	for($i=0;$i<$sizeofdata;$i++)if(fwrite($f,serialize($data[$i]).$endstr)===false)return false;
	flock($f,LOCK_UN);
	fclose($f);
	return true;
}
//Читать в двумерный массив
function loaddata ($filename){
	$f=fopen($filename,'r');
	if(false===$f)return false;
	flock($f,LOCK_SH);
	while(!feof($f)){
		$serial=unserialize(fgets($f));
		if(is_array($serial))$data[]=$serial;
	}
	flock($f,LOCK_UN);
	fclose($f);
	return $data;
}
//Читать в массив
function loadsimpledata ($filename){
	$f=fopen($filename,'r');
	if(false===$f)return false;
	flock($f,LOCK_SH);
	$serial=unserialize(fgets($f));
	if(is_array($serial))$data=$serial;
	flock($f,LOCK_UN);
	fclose($f);
	return $data;
}
//Прочитать строку
function loadsimple ($filename){
	$f=fopen($filename,'r');
	if(false===$f)return false;
	flock($f,LOCK_SH);
	$data=fgets($f);
	flock($f,LOCK_UN);
	fclose($f);
	return $data;
}
//Баны
//Проверка забаненного ip
function ip_is_baned($in_ip=null){
	$filename=ENGINE.'banipdb.php';
	@$in_ip=isset($in_ip)?$in_ip:$_SERVER[REMOTE_ADDR];//$_SERVER["HTTP_X_REAL_IP"];  //$_SERVER[REMOTE_ADDR];
	if(file_exists($filename)){
		$data=loaddata($filename);
		if(is_array($data))
			foreach($data as $item)if($item['ip']==$in_ip)return true;
	}
	return false;
}
//Банлист в массив
function get_ip_banlist(){
	$filename=ENGINE.'banipdb.php';
	if(file_exists($filename))return loaddata($filename);
	return array();
}
//ip в бан лист
function ip_baned($in_ip=null){
	$in_ip=isset($in_ip)?$in_ip:$_SERVER[REMOTE_ADDR];
	$filename=ENGINE.'banipdb.php';
	$data=array('ip'=>$in_ip,'data'=>date("d.m.Y"),'time'=>date("H.i.s"));
    if(!savedata ($filename, $data, $rez='a+'))return false;
	return true;
}
//Удалить ip из бан листа
function del_ip_baned($in_ip){
	$filename=ENGINE.'banipdb.php';
	if(file_exists($filename)){
		$data=loaddata($filename);
		foreach($data as $item)if($item['ip']!==$in_ip)$ndata[]=$item;
		if(!savedataarray($filename,$ndata,'w'))return false;
	}
	return true;
}
//Читать случайную строку из файла
function rndstrfromfile($filename) {
	$frases = file($filename);
	$numero_frases = count($frases);
	if ($numero_frases !== 0)$numero_frases--;
	mt_srand((double)microtime()*1000000);
	$numero_aleator = mt_rand(0,$numero_frases);
	return $frases[$numero_aleator];
}
//Конвертировать размер файла
function convert_fsize($size) {
   $unit = array('b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb');
   $delitel=pow(1024, ($i = floor(log($size, 1024))));
   if($delitel==0)return '0b';
   return round($size/$delitel, 2) . $unit[$i];
}
function command2var($page) {
	ob_start();
	eval($page);
	$codepage=ob_get_contents();
	ob_end_clean();
	return $codepage;
}
function getphpcodedpage($page) {
	ob_start();
	require($page);
	$codepage=ob_get_contents();
	ob_end_clean();
	return $codepage;
}
//Удалить каталог рекурсивно
function full_del_dir($directory){
	$dir = opendir($directory);
	while(($file = readdir($dir))){
		if( is_file ($directory.'/'.$file))unlink ($directory.'/'.$file);
		elseif( is_dir ($directory.'/'.$file.'/')&&($file != '.')&&($file != '..'))	full_del_dir($directory.'/'.$file.'/');
	}
	closedir ($dir);
	rmdir ($directory);
}
//Чмодить каталог рекурсивно
function full_chmod_dir($directory,$permdir,$permfile) {
	@chmod($directory,$permdir);
	$dir = opendir($directory);
	while(($file = readdir($dir))){
		if( is_file ($directory.'/'.$file)){
			if($file=='.htaccess') @chmod($directory.'/'.$file,0644);
			else @chmod($directory.'/'.$file,$permfile);
		}elseif( is_dir ($directory.'/'.$file)&&($file != '.')&&($file != '..')){
			@chmod ($directory.'/'.$file, $permdir);
			full_chmod_dir ($directory.'/'.$file,$permdir,$permfile);
		}
	}
	closedir ($dir);
}
//Информация о GD, входные значения
//GD Version, FreeType Support, FreeType Linkage, T1Lib Support,GIF Read Support,GIF Create Support,
//JPG Support, PNG Support, WBMP Support, XPM Support, XBM Support, JIS-mapped Japanese Font Support;
function getgdinfo($info = 'GD Version'){
	if (function_exists('gd_info')) {
		$ver_info = gd_info();
		if($info == 'GD Version'){
			preg_match('/\d/', $ver_info[$info], $match);
			$gdinfo = $match[0];
		}else $gdinfo=$ver_info[$info];
		return $gdinfo;
	}
	return false;
}
//Показать каптчу
function put_captcha() {
	global $prefflp, $g_captcha;
	$cont_captcha='<div class="captcha">
					<div class="captchavotes">
					  ';
	if($g_captcha=='1'){
		$cont_captcha.='<img src="'.$prefflp.'/code/captcha.php" id="captcha-image" alt="'.__('Обновить картинку каптчи').'" title="'.__('Обновить картинку каптчи').'" onclick="document.getElementById(\'captcha-image\').src = \''.$prefflp.'/code/captcha.php?rid=\' + Math.random();" />
		<a href="javascript:void(0);" title="'.__('Обновить картинку каптчи').'" onclick="document.getElementById(\'captcha-image\').src = \''.$prefflp.'/code/captcha.php?rid=\' + Math.random();"><img src="'.$prefflp.'/images/refresh.png" width="48" alt="" /></a>';
	}else{
		$actions= array(__('отнять'), __('прибавить'),
		__('минус'), __('плюс'),__('вычесть'), __('сложить'),
		__('убрать'), __('доложить'),__('отжать'), __('подарить'));
		srand((double) microtime()*1000000);
		$act= rand(0, 9);
		$num1= rand(20, 40);
		$num2= rand(0, 20);
		if(($act % 2)==0){
			$vcaptcha=$num1-$num2;
		}else{
			$vcaptcha=$num1+$num2;
		}
		$_SESSION['vcaptcha']=(string)$vcaptcha;
		$cont_captcha.= $num1.' '.$actions[$act].' '.$num2;
	}
	$cont_captcha.=	'</div>
					<div class="captchatext">
						'.__(($g_captcha=='1')?'Текст на картинке':'Результат').': <br /><input type="text" name="vcaptcha" /><span class="error_message">*</span>
					</div>
				</div>
				';
	return $cont_captcha;
}
//Отправка почты через сокет
function smtp_mail($to,$message,$headers,$arr){
 /*
    $from   	адрес отправителя
    $to     	адрес получателя
    $headers  	заголовок сообщения
    $message  	текст сообщения
*/
	$result=TRUE;
    $address = $arr['addres']; // адрес smtp-сервера
    $port    = $arr['port'];   // порт (стандартный smtp - 25)
    $login   = $arr['login'];  // логин к ящику
    $pwd     = $arr['pwd'];    // пароль к ящику
    $from	 = $arr['frommail'];//отправитель, для корректного отправления мессаг
    try {
        // Создаем сокет
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            throw new Exception('socket_create() failed: '.socket_strerror(socket_last_error())."\n");
        }
        // Соединяем сокет к серверу
       	$protocol= 'Connect to \''.$address.':'.$port.'\' ... ';
        $result = socket_connect($socket, $address, $port);
        if ($result === false) {
            throw new Exception('socket_connect() failed: '.socket_strerror(socket_last_error())."\n");
        } else {
            @$protocol.= "OK\n";
        }
        // Читаем информацию о сервере
        read_smtp_answer($socket);
        // Приветствуем сервер
        write_smtp_response($socket, 'EHLO '.$login);
        read_smtp_answer($socket); // ответ сервера
		//Если с авторизацией
		if(isset($login)){
			if($login!==""){
		        $protocol.=  "Authentication ... ";
		        // Делаем запрос авторизации
		        write_smtp_response($socket, 'AUTH LOGIN');
		        read_smtp_answer($socket); // ответ сервера
		        // Отравляем логин
		        write_smtp_response($socket, base64_encode($login));
		        read_smtp_answer($socket); // ответ сервера
		        // Отравляем пароль
		        write_smtp_response($socket, base64_encode($pwd));
		        read_smtp_answer($socket); // ответ сервера
		        $protocol.=  "OK\n";
		   }
		}
        $protocol.=  "Check sender address ... ";
        // Задаем адрес отправителя
        write_smtp_response($socket, 'MAIL FROM:<'.$from.'>');
        read_smtp_answer($socket); // ответ сервера
        $protocol.=  "OK\n";
        $protocol.=  "Check recipient address ... ";
        // Задаем адрес получателя
        write_smtp_response($socket, 'RCPT TO:<'.$to.'>');
        read_smtp_answer($socket); // ответ сервера
        $protocol.=  "OK\n";
        $protocol.=  "Send message text ... ";
        // Готовим сервер к приему данных
        write_smtp_response($socket, 'DATA');
        read_smtp_answer($socket); // ответ сервера
        // Отправляем данные
        write_smtp_response($socket, $headers.$message."\r\n.");
        read_smtp_answer($socket); // ответ сервера
        $protocol.=  "OK\n";
        $protocol.=  'Close connection ... ';
        // Отсоединяемся от сервера
        write_smtp_response($socket, 'QUIT');
        read_smtp_answer($socket); // ответ сервера
        $protocol.=  "OK\n";
    } catch (Exception $e) {
		echo $protocol;
        echo "\nError: ".$e->getMessage();
        $result=FALSE;
    }
    if (isset($socket)) {
        socket_close($socket);
    }
    return $result;
}
// Функция для чтения ответа сервера. Выбрасывает исключение в случае ошибки
function read_smtp_answer($socket) {
	$read = socket_read($socket, 1024);
	if ($read{0} != '2' && $read{0} != '3') {
		if (!empty($read)) {
			throw new Exception('SMTP failed: '.$read."\n");
		} else {
			throw new Exception('Unknown error'."\n");
		}
	}
}
// Функция для отправки запроса серверу
function write_smtp_response($socket, $msg) {
	$msg = $msg."\r\n";
	if(socket_write($socket, $msg, strlen($msg))===FALSE){throw new Exception('Socket write error'."\n");};
}
//------------почта
//Делает дроплист из массива
function make_droplist($arr,$nameid,$curitem='',$other=''){
	$templatedrop='<select name="'.$nameid.'" id="'.$nameid.'"'.$other.'>';
	foreach($arr as $key=>$item){
		$templatedrop.='<option value="'.$key.'"';
		if($key===$curitem)$templatedrop .=' selected="selected"';
		$templatedrop .='>'.$item.'</option>';
	}
	$templatedrop.='</select>';
	return $templatedrop;
}
//Собирает имена подшаблонов в шаблоне
function get_templatepage($templatepage=''){
	global $template;
	$arr['']='';
	$count_files=sizeof($dir = scandir(LOCALPATH.'/templates/'.$template,1))-2;
	for($i=0;$i<$count_files;$i++)
		if(preg_match('/template([a-zA-Z0-9-_].+)\.php/u',$dir[$i],$matches))
			$arr[trim($matches[1])]=trim($matches[1]);
	return make_droplist($arr,'templatepage',$templatepage);
}
//Собирает имена плагинов в MYCODE
function get_kan_phpfile($currentvalue){
	$count_files=sizeof($dir=scandir(MYCODE,1))-2;
	for ($i = 0; $i < $count_files; $i++){
 		if(getftype($dir[$i])!=='php') continue;
		$data=file(MYCODE.$dir[$i]);
		if(trim($data[1])=='//phpfile'){
			if($data[2]{0}=='#')$title=$data[2];else $title='';
			$arr[basename($dir[$i],'.php')]=basename($dir[$i],'.php').$title;
		}
	}
	return make_droplist($arr,'selectName',$currentvalue,'onchange="parentNode.getElementsByTagName(\'input\')[0].value=value"');
}
//Линейка дерева
function art_catalog($inpath,$separator=''){
	$sizecat=sizeof($catalog=explode('/',$inpath));$sizecat--;
	if((end($catalog)=='main.dat')||(end($catalog)=='main'))$sizecat--;
	if($sizecat==0)$result='';
	if($sizecat>0){
		$result='<a href="'.cc_link('/'.$catalog[0].'/').'">'.articlesparam('title',file_get_contents(ARTICLES.$catalog[0].'/main.dat')).'</a>'.$separator;
		if($sizecat>1){
			$result.='<a href="'.cc_link('/'.$catalog[0].'/'.$catalog[1].'/').'">'.articlesparam('title',file_get_contents(ARTICLES.$catalog[0].'/'.$catalog[1].'/main.dat')).'</a>'.$separator;
		}
	}
	return $result;
}
//Линк на сапу
function sape_link(){
	global $sape;
	if(is_a($sape,'SAPE_client')){
		return $sape->return_links();
	}
	return;
}
//Линк на линкфеед
function linkfeed_link(){
	global $linkfeed;
	if(is_a($linkfeed,'LinkFeedClient')){
		return $linkfeed->return_links();
	}
	return;
}
//Каталог статей в массив
function get_articlessubdir(){
	$dir=scandir(ARTICLES,1);
	$count_files=sizeof($dir)-2;
	for ($i = 0; $i < $count_files; $i++){
		if(is_dir(ARTICLES.$dir[$i])){
			$cat_st[]=$dir[$i];
			$subdir=scandir(ARTICLES.$dir[$i],1);
			$count_files_subdir=sizeof($subdir)-2;
			for ($j = 0; $j < $count_files_subdir; $j++)if(is_dir(ARTICLES.$dir[$i].'/'.$subdir[$j]))$cat_st[]=$dir[$i].'/'.$subdir[$j];
		}
	}
	return $cat_st;
}
//Инклюд странички
function makescript($inval){
	return base64_decode($inval);
}
//Кепа
function to_head($matches){
	global $templatepp, $prefflp, $myinclude, $localpage;
	$to_head='<meta name="generator" content="Kandidat-CMS by it" />
<style type="text/css">
	@import url('.$prefflp.'/css/prettyPhoto.css);
	@import url('.$prefflp.'/css/qTip.css);
	@import url('.$prefflp.'/css/allengine.css);
	@import url('.$prefflp.'/css/bb.css);
</style>
<script type="text/JavaScript" src="'.$prefflp.'/js/qTip.js" charset="utf-8"></script>
<script type="text/javascript" src="'.$prefflp.'/js/jquery.min.js"  charset="utf-8"></script>
<script type="text/javascript" src="'.$prefflp.'/js/jquery.prettyPhoto.js"  charset="utf-8"></script>
<!--[if  IE 6]>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
      $(".gallery a[rel^=\'prettyPhoto\']").prettyPhoto({theme:\''.$templatepp.'\'});
    });
</script>
<![endif]-->
';
	$cssinclude='';
	if(!empty($myinclude)){
  		if(file_exists($localpage.'css/'.$myinclude.'.css'))$cssinclude.='
<style type="text/css">
	@import url('.$prefflp.'/css/'.$myinclude.'.css);
</style>';
  		if(file_exists($localpage.'css/'.$myinclude.'.js'))$cssinclude.='
<script type="text/JavaScript" src="'.$prefflp.'/js/'.$myinclude.'" charset="utf-8"></script>
';
	}
	return '<head>'.$matches[1].$to_head.$cssinclude.'</head>';
}
//Днище
function to_end_body($matches){
	global $templatepp;
	if(!preg_match(makescript('L2thbi1zdHVkaW9cLnJ1L3Vp'),$matches[1])){
	    $to_end_body=makescript('PGRpdiBjbGFzcz0iY29weXJpZ2h0Ij7CqTIwMTAgLiBQb3dlcmVkIGJ5OiA8YSBocmVmPSJodHRwOi8vd3d3Lmthbi1zdHVkaW8ucnUiPkthbmRpZGF0IDxhYmJyPkNNUzwvYWJicj4gYnkgaXQ8L2E+PC9kaXY+Jm5ic3A7Jm5ic3A7==');
	}
	@$to_end_body.='
<script type="text/javascript">
	$(document).ready(function(){
		$(".gallery a[rel^=\'prettyPhoto\']").prettyPhoto({
			theme:\''.$templatepp.'\',
			hideflash: true,
			overlay_gallery: false
		});
 	});
	tooltip.init();
</script>
';
	return '<body>'.$matches[1].$to_end_body.'</body>';
}
function parse_incl($in_incl){
	$out_incl=preg_replace_callback('/<head>(.+)<\/head>/siu','to_head',$in_incl);
	$out_incl=preg_replace_callback('/<body>(.+)<\/body>/siu','to_end_body',$out_incl);
	return $out_incl;
}
//--------инклюд странички
//для создания меню (в массив)
function menudirlist($result,$arr,$prevcat=""){
	$dir = scandir($arr,1);
	$arr2 = str_replace(ARTICLES,'',$arr);
	$countdir=count($dir)-2;
	for ($i = 0; $i < $countdir; $i++){
		$title = "";
		$submenu = "";
		if( preg_match('/\.dat$/iu',$dir[$i])){
			if(preg_match('/(^main)|(^404)\.dat/iu',$dir[$i]))continue;
			if(file_exists($arr.'/'.$dir[$i])){
				$data = file_get_contents($arr.'/'.$dir[$i]);
				$head = str_replace("\n",'',articlesparam('title',$data));
				$page = $arr2.'/'.preg_replace('/\.dat$/i','.html',$dir[$i]);
				$result[]=array('page'=>$page,'head'=>$head,'title'=>$title);
			}
		}else{
			if(preg_match('/(^\.)|(\.[a-z]*)/iu',$dir[$i]))continue;
			if(file_exists($arr.'/'.$dir[$i].'/main.dat')){
				$data = file_get_contents($arr.'/'.$dir[$i].'/main.dat');
				$head = str_replace("\n",'',articlesparam('title',$data));
				$page = $arr2.'/'.$dir[$i].'/';
				$dir2=scandir($arr.'/'.$dir[$i],1);
				foreach($dir2 as $curcat){
					if(!preg_match('/(^main\.dat)|(^404\.dat)|(^\.)|(\.php$)/iu',$curcat)){
						$submenu = '1';
						break;
					}
				}
				$result[]=array('page'=>$page,'head'=>$head,'title'=>$title,'submenu'=>$submenu);
				$data2=array('page'=>$page,'head'=>$head,'title'=>$title,'submenu'=>$submenu);
			}
			$result=menudirlist($result,$arr.'/'.$dir[$i],$data2);
			if($submenu == '1')$result[]=array('submenu'=>'-1');
		}
	}
	return $result;
}
//Описание фотки
function getopisanie($cat,$fname){
	$myFile=PICTURES.$cat.'/namedb.dat';
	$opisanie='';
	if(is_readable($myFile))
		if(sizeof($arr=loaddata($myFile))!=0)foreach($arr as $ta)if(($cat.'/'.$fname)==$ta['fname']){$opisanie=$ta['opisanie'];break;}
	$opisanie=get_magic_quotes_gpc()?stripslashes($opisanie):$opisanie;
	return str_replace('"','&quot;',$opisanie);
}
//
function stripinarr($inarr){
    foreach($inarr as $k=>$v){
		/* если magic_quotes включены, очистить массив post */
		if(get_magic_quotes_gpc())$inarr[$k]=stripslashes($inarr[$k]);
		/* удалить special chars */
		$inarr[$k]=htmlspecialchars(strip_tags($inarr[$k]));
    }
    return $inarr;
}
//Возвращает тип файла (расширение)
function getftype($filename){
	//return strtolower(end(preg_split('/\./',$filename)));
    return substr((string) strrchr($filename, '.'), 1);
}
//Возвращает права пользователя
function getpermision(){
	if(isset($_SESSION['param'])&&isset($_SESSION['id'])){
		$param=$_SESSION['param'];
		return $param[2];
	}
	return false;
}
//Возвращает логин пользователя и строковое представления прав
function getuser($razd=' | '){
	if(isset($_SESSION['param'])&&isset($_SESSION['id'])){
		$q=$_SESSION['param'];
		switch($q[2]){
			case '0': $status='Пользователь';break;
			case '1': $status='Корректор';break;
			case '2': $status = 'Редактор';break;
			case '3': $status = 'Администратор';break;
		}
		return $_SESSION['login'].$razd.$status;
	}
	return '';
}
//Полный фильтр строки
function filtermessage($message,$filtering=false) {
	if ((preg_match('/meta|iframe/isu', $message)!=1)&&(preg_match('/<[^>]+>/isu', $message)!=1)||(!$filtering)) {
		$message=trim($message);
		$message=strip_tags($message);
		$message=preg_replace('/<[^>]+>/isu', '', $message);
		$message=filterquotes($message);
		$message=htmlspecialchars($message);
	}else{
		$message="";
	}
	return $message;
}
//квоте
function filterquotes($message) {
	$message=trim($message);
	$message=preg_replace('/\r/u', '', $message);
	$message=preg_replace('/\n/u', ' ', $message);
	$message=get_magic_quotes_gpc()?stripslashes($message):$message;
	return $message;
}
//Счетчик чтения новости++
function inc_newsread_count($news_id) {
	global $newslogfilename;
	$news=loaddata($newslogfilename);
	$notread=true;
	$newsout=array();
	foreach($news as $data){
		if($data['id']==$news_id){$data['count']++;$notread=false;}
		$newsout[]=$data;
	}
	if($notread)$newsout[]=array( 'id'=>$news_id,'count'=>1);
	savedataarray($newslogfilename,$newsout,'w');
}
//Скока читали новость
function get_newsread_count($news_id) {
	global $newslogfilename;
	$news=file($newslogfilename);
	foreach($news as $data){
		$data=unserialize($data);
		if($data['id']==$news_id)return $data['count'];
	}
	return 0;
}
//Меню в список
function makemenu($id_select_item='mmenuselect'){
	//$id_select_item='mmenuselect'; //id выделенного элемента меню
	global $catpage,$subcatpage,$whatpage;
	$menudb=ENGINE.'/menudb.php';
	$currlink='/';
	if(!empty($catpage))$currlink.=$catpage.'/';
	if(!empty($subcatpage))$currlink.=$subcatpage.'/';
	if(!empty($whatpage))$currlink.=$whatpage.'.html';
	if(file_exists($menudb)){
		$datas=file($menudb);
		$allmenu=count($datas);
		if ($allmenu>=1) {
			$root_item=0;
			for($i=0;$i<$allmenu;$i++){
				$data=unserialize($datas[$i]);
				if($data=='')continue;
				$page=isset($data['page'])?$data['page']:'';
				$head=isset($data['head'])?$data['head']:'';
				$title=isset($data['title'])?$data['title']:'';
				$blank=isset($data['blank'])?$data['blank']:'';
				$submenu=isset($data['submenu'])?$data['submenu']:'';
				$blank=($blank==1)?' target="_blank" ':'';
				if($page==$currlink)$curclass='id="'.$id_select_item.'" ';else $curclass='';
				if($root_item==0)$curclass.='class="rootnav"';
				if($submenu=='1'){
					$menu.='<li '.$curclass.'><a href="'.cc_link($page).'" title="'.$title.'"'.$blank.'>'.$head."</a><ul>\n";
					$root_item++;
				}elseif($submenu=='-1'){
					$menu.="</ul></li>\n";
					$root_item--;
				}else @$menu.='<li '.$curclass.'><a href="'.cc_link($page).'" title="'.$title.'"'.$blank.'>'.$head."</a></li>\n";
	    	}
	    	echo $menu;
		}
	}
}
//Меню верхнее в список
function makeupmenu($id_select_item='mmenuselect',$texttag='span'){
	//$id_select_item='mmenuselect'; //id выделенного элемента меню
	global $catpage,$subcatpage,$whatpage;
	$menudb=ENGINE.'/menudb.php';
	$currlink="/";
	if(!empty($catpage))$currlink.=$catpage.'/';
	if(!empty($subcatpage))$currlink.=$subcatpage.'/';
	if(!empty($whatpage))$currlink.=$whatpage.'.html';
	if(file_exists($menudb)){
		$datas=file($menudb);
		$allmenu=count($datas);
		if ($allmenu>=1) {
			$root_item=0;
			$menu='';
			for($i=0;$i<$allmenu;$i++){
				$data=unserialize($datas[$i]);
				if($data=='')continue;
				if(@$data['viewup']!=='1')continue;
				$page=isset($data['page'])?$data['page']:'';
				$head=isset($data['head'])?$data['head']:'';
				$title=isset($data['title'])?$data['title']:'';
				$blank=isset($data['blank'])?$data['blank']:'';
				$submenu=isset($data['submenu'])?$data['submenu']:'';
				$blank=($blank==1)?' target="_blank" ':'';
				if($page==$currlink)$curclass='id="'.$id_select_item.'" ';else $curclass='';
				$menu.='<li '.$curclass.'><a href="'.cc_link($page).'" title="'.$title.'"'.$blank.'><'.$texttag.'>'.$head.'</'.$texttag."></a></li>\n";
	    	}
	    	echo $menu;
		}
	}
}
?>
