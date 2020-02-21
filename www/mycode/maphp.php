<?php
//phpfile
#Карта сайта-подробное дерево по всем страничкам
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');
include_once(CONF.'photoconf.php');
function listpict($arr){
  $dir = scandir($arr,1);

  $arr2 = str_replace(LOCALPATH,'',$arr);
  $count_files=sizeof($dir)-2;
  for ($i = 0; $i < $count_files; $i++){
    $title = '';
    $submenu = '';

    if( is_dir($arr.'/'.$dir[$i])){
 	   	if(preg_match('/(^\.)|(\.[a-z]*)|(thumb)/iu',$dir[$i]))continue;
		if(file_exists($arr.'/'.$dir[$i].'/info.dat')){
	    	$fh = @fopen($arr.'/'.$dir[$i].'/info.dat', 'r');
        	$data = fread($fh, filesize($arr.'/'.$dir[$i].'/info.dat'));
        	fclose($fh);

        	$head = str_replace("\n",'',$data);
        	$page = cc_link('/photo-cat-'.$dir[$i].'.html');
        }
       	$tempdir=scandir(PICTURES.$dir[$i],1);
   		$temparr=array_filter($tempdir, 'onlypic');
   		$countpic=count($temparr);
   		$countpic=$countpic>0?' ('.$countpic.' '.__('картинок').')':'';
   		echo '<li class="photo"><a href="'.$page.'" title="'.$descr.'">'.$head.'</a>'.$countpic.'<ul>';
        listpict($arr.'/'.$dir[$i]);
        echo '</ul></li>';
     }
  }
}

function onlydat($var) {
    if( !preg_match('/(^\.)|(main\.dat)|(\.php)|(\.jpg)|(^thumb$)/iu',$var))
    	return $var;
}

function onlycat($var) {
    if( preg_match('/(?!^thumb$)^([0-9a-z_]+)$/iu',$var))
    	return $var;
}

function onlypic($var) {
    if( preg_match('/.jpg$/iu',$var))
    	return $var;
}

function dirlist($arr)
{
  $dir = scandir($arr,1);

  $arr2 = str_replace(ARTICLES,'',$arr);
  for ($i = 0; $i < count($dir)-2; $i++)
  {
    $title = "";
    $submenu = "";
    if( preg_match('/\.dat$/i',$dir[$i]))
    {
		if(preg_match('/(^main)|(^404)\.dat/iu',$dir[$i]))continue;
		if(file_exists($arr.'/'.$dir[$i])){
    		$data = file_get_contents($arr.'/'.$dir[$i]);
      		$head = articlesparam('title',$data);
        	$descr = articlesparam('description',$data);
        	$incl = articlesparam('myinclude',$data);
      		$page = cc_link($arr2.'/'.preg_replace('/\.dat$/i','.html',$dir[$i]));
			$fsize = convert_fsize(filesize($arr.'/'.$dir[$i]));

       		if($incl=='photo'){
	        	$tempdir=scandir(PICTURES,1);
        		$temparr=array_filter($tempdir, 'onlycat');
        		$countdir=count($temparr);
        		$countdir=$countdir>0?': '.$countdir.' '.__('каталога'):'';
        		$temparr=array_filter($tempdir, 'onlypic');
        		$countpic=count($temparr);
        		$countdir.=$countpic>0?' '.$countpic.' '.__('картинок'):'';

     			echo '<li class="photo"><a href="'.$page.'" title="'.$descr.'">'.$head.'</a> ('.__('Каталог').$countdir.")\n";
				echo "<ul>";
				listpict(PICTURES);
				echo '</ul></li>';
			} else	echo '<li  class="file"><a href="'.$page.'" title="'.$descr.'">'.$head.'</a> ('.__('размер').': '.$fsize.")</li>\n";

      	}
   }else{
	   	if(preg_match('/(^\.)|(\.[a-z]*)/iu',$dir[$i]))continue;
		$submenu = "";
		if(file_exists($arr.'/'.$dir[$i].'/main.dat')){
	    	$fh = @fopen($arr.'/'.$dir[$i].'/main.dat', 'r');
        	$data = fread($fh, filesize($arr.'/'.$dir[$i].'/main.dat'));
        	fclose($fh);

     		$head = str_replace("\n",'',articlesparam('title',$data));
        	$descr = str_replace("\n",'',articlesparam('description',$data));
        	$incl = str_replace("\n",'',articlesparam('myinclude',$data));
        	$page = cc_link($arr2.'/'.$dir[$i].'/');
        	$tempdir=scandir($arr.'/'.$dir[$i],1);
        	$tempdir=array_filter($tempdir, 'onlydat');
        	$countindir=count($tempdir);
        	$countindir=$countindir>0?': '.$countindir.' '.__('файла/каталога'):'';
	   		echo '<li class="folder"><a href="'.$page.'" title="'.$descr.'">'.$head.'</a> ('.__('Каталог').$countindir.')';
	   		if($countindir!==''){
	   			echo '<ul>';
    	    	dirlist($arr.'/'.$dir[$i]);
        		echo '</ul>';
        	}
        	echo '</li>';
        }
     }
  }
}

echo '<ul class="primaryNav">';
echo '<li class="home"><a href="'.cc_link('/').'">'.__('Главная страница').'</a><ul>';
dirlist(ARTICLES);
echo '</ul></li></ul>';
?>
