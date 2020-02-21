<?php
session_start();
$begintime=microtime(true);
define( '_JEXEC', 1 );

if(file_exists('admin/install/index.php')){Header('Location: ../admin/install/index.php');exit();}
include 'conf/config.php';
if($gzip_enable=='1') include 'code/gzip.php';
if($siteoff) {
	include('code/siteoff.php');exit;
}

$captcha = false;
if(isset($_SESSION['vcaptcha'])){
	if($_SESSION['vcaptcha']===$_POST['vcaptcha'])$captcha = true;
	$_SESSION['vcaptcha']='';
}

if(isset($_SESSION['id'])){
	if($_SESSION['id']!=session_id())$_SESSION['name'] = 'Гость';
}

@$whatpage = preg_replace('/[^a-z0-9-_]/iu','',$_REQUEST['whatpage']);
@$catpage = preg_replace('/[^a-z0-9-_]/iu','',$_REQUEST['catpage']);
@$subcatpage = preg_replace('/[^a-z0-9-_]/iu','',$_REQUEST['subcatpage']);
foreach ($_GET as $var => $secvalue) { if((preg_match ('/<[^>]*script*\"?[^>]*>/iu', $secvalue)) || (preg_match ('/<[^>]*object*\"?[^>]*>/iu', $secvalue)) || (preg_match ('/<[^>]*iframe*\"?[^>]*>/iu', $secvalue)) || (preg_match ('/<[^>]*applet*\"?[^>]*>/iu', $secvalue)) || (preg_match ('/<[^>]*meta*\"?[^>]*>/iu', $secvalue)) || (preg_match ('/<[^>]*style*\"?[^>]*>/iu', $secvalue)) || (preg_match ('/<[^>]*form*\"?[^>]*>/iu', $secvalue)) || (preg_match ('/<[^>]*img*\"?[^>]*>/iu', $secvalue)) || (preg_match ('/<[^>]*onmouseover*\"?[^>]*>/iu', $secvalue)) || (preg_match ('/\([^>]*\"?[^)]*\)/iu', $secvalue)) || (preg_match ('/\"/iu', $secvalue))) { die('BAD YOUR CODE'); exit; }}
foreach ($_POST as $secvalue) { if((preg_match ('/<[^>]*script *\"?[^>]*>/iu', $secvalue)) || (preg_match ('/<[^>]*style*\"?[^>]*>/iu', $secvalue))) { die('BAD YOUR CODE'); exit; }}
include 'code/functions.php';
//SAPE
if(isset($sape_user)&&($sape_user!='')&&file_exists($localpath.$sape_user.'/sape.php')){
	require_once($localpath.$sape_user.'/sape.php');
	$sape=new SAPE_client();
}
//LINKFEED
if(isset($linkfeed_user)&&($linkfeed_user!='')&&file_exists($localpath.$linkfeed_user.'/linkfeed.php')){
	require_once($localpath.$linkfeed_user.'/linkfeed.php');
	$linkfeed=new LinkFeedClient();
}
if(file_exists(CONF.'photoconf.php'))include_once(CONF.'photoconf.php');
$templatepp=empty($templatepp)?'dark_rounded':$templatepp;
$myFile=ARTICLES;
if(!empty($catpage))$myFile.=$catpage.'/';
if(!empty($subcatpage))$myFile.=$subcatpage.'/';
$myFile.=empty($whatpage)?'main.dat':$whatpage.'.dat';
if(!file_exists($myFile)){$myFile=ARTICLES.'404.dat'; header('Status: 404'); header('HTTP/1.1 404 Not Found');}
$data=file_get_contents($myFile);
$sitetitle=articlesparam('title',$data);
$contentcenter=articlesparam('content',$data);
$myinclude = articlesparam('myinclude',$data);
if(empty($myinclude))$myinclude='main';
$metadescription=articlesparam('description',$data);
$metakeywords=articlesparam('keywords',$data);
$pubdateofpage=articlesparam('pubdate',$data);
$enablecomment=(int)articlesparam('comment',$data);
//Инклюд и камменты
$contentcenter.='<div class="myinclude">';
ob_start();
require(MYCODE.$myinclude.'.php');
if($enablecomment>0)require(MYCODE.'comments.php');
$contentcenter.=ob_get_contents();
ob_end_clean();
$contentcenter.='</div>';
//локальный шаблон
$what_templatepage=articlesparam('templatepage',$data);
if(!empty($catpage)){
		$myFile=ARTICLES.$catpage.'/main.dat';
		if(file_exists($myFile))$cat_templatepage = articlesparam('templatepage',file_get_contents($myFile));
}
if(!empty($subcatpage)){
		$myFile=ARTICLES.$catpage.'/'.$subcatpage.'/main.dat';
		if(file_exists($myFile))$subcat_templatepage = articlesparam('templatepage',file_get_contents($myFile));
}
$templatepage='template';
if($what_templatepage==''){
	if(empty($subcat_templatepage)){
		if(empty($cat_templatepage)){
			$templatepage .= $what_templatepage;
		}else $templatepage .= $cat_templatepage;
	}else $templatepage .= $subcat_templatepage;
}else $templatepage .= $what_templatepage;
//Локальный шаблон отсутствует тогда используем основной
if(!file_exists(LOCALPATH.'templates/'.$template.'/'.$templatepage.'.php'))$templatepage='template';
$incl=file_get_contents(LOCALPATH.'templates/'.$template.'/'.$templatepage.'.php');
$incl=parse_incl($incl);
$lnav=linkator($whatpage, $catpage, $subcatpage, $sitetitle);
eval('?>'.$incl);
