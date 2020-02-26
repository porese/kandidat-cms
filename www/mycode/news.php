<?php
//phpfile
#Новости - лента новостей, работает из каталога или файла
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');

include_once(CONF .'newsconf.php');
$news_Glink='/news'.(isset($news_cat)?'/':'-');
$viewnews = (isset($_GET['view']))?(int)$_GET['view']:-1;
if((int)$newsperpage==0)$newsperpage=10;

if(file_exists($newsdbfilename)){
	$news=file($newsdbfilename);
	$countallnews = sizeof($news);
	if($viewnews<0){
		if(!isset($_REQUEST['newspage'])) $_REQUEST['newspage']=1;
		else $_REQUEST['newspage']=(int)$_REQUEST['newspage'];
		$j=($countallnews-1)-(($_REQUEST['newspage']-1)*$newsperpage);
		$i=$j-$newsperpage;
		for($p=$j+1;(($i<$j)&&($j>=0));$p=$j--){
			if(!is_array($new=unserialize($news[$j])))continue;
			echo '<div class="title"><h2><a href="'.cc_link($news_Glink.$p.'.html').'" class="more">'.$new['head'].'</a></h2></div>
				<div class="entry"><p>'.$new['mess'].'</p></div>
				<p class="links"><span class="news-date-time">'.$new['pubdate'].'</span> | '.$new['aname'].' | '.__('Просмотров').': '.get_newsread_count($new['id']).
				' | <a href="'.cc_link($news_Glink.$p.'.html#comment_begin').'">'.__('Комментариев').': '.getcountcomments($new['id'],$commentsdbfilename, $newsmoderator).'</a> | '.
				'<a href="'.cc_link($news_Glink.$p.'.html').'" class="more">'.__('Читать далее').'</a></p><br><br>';
		}
		echo '<div id="navigation-news"><b>'.__('Страницы').':</b>&nbsp;';
		$all = ceil($countallnews/$newsperpage);
		for($i=1;$i<=$all;$i++)
			if($_REQUEST['newspage']==$i)echo '&laquo;<b>'.$i.'</b>&raquo;&nbsp;';
			else echo '<a href="'.cc_link($news_Glink.'page-'.$i.'.html').'">'.$i.'</a>&nbsp;';
		echo '</div><div id="allcount-news">'.__('Всего&nbsp;новостей').':&nbsp;<b>'.$countallnews.'</b></div>';
	} else {
	    if($countallnews>$viewnews-1){
			$new=unserialize($news[$viewnews-1]);
			inc_newsread_count($new['id']);
            $sitetitle.=' - '.$new['head'];
			$metadescription.=','.$new['description'];
			$metakeywords.=','.$new['keywords'];
			$countcomments=getcountcomments($new['id'],$commentsdbfilename, $newsmoderator);
			echo '<div class="title"><h2>'.$new['head'].'</h2></div>
				<div class="entry"><p>'.$new['mess'].'<br />'.$new['extra'].'</p></div>
				<span class="news-date-time">'.$new['pubdate'].'</span> | '.$new['aname'].' | '.__('Просмотров').': '.get_newsread_count($new['id']).' | '.__('Комментариев').': '.getcountcomments($new['id'],$commentsdbfilename, $newsmoderator);
			//Для модуля новости
			$enablecomment=(int)$new['comments'];
			$commentsid=$new['id'];
			$commentsfilename=$commentsdbfilename;
		}else echo '<ul class="error_message"><li>'.__('Записей нет!').'</li></ul>';
		echo '<br />'.$back;
	    if (!empty($error)) echo '<p class="error_header">'.__('Во время добавления записи произошли следующие ошибки').':</p>
        	<ul class="error_message">'.$error.'</ul>';
	}
} else echo '<center><font color="red" size="2">'.__('Записей нет!').'</font></center>';
?>
