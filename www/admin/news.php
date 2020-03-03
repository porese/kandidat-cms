<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
$sitetitle='Управление новостями';
$what = (isset($_GET['what'])) ? (int)$_GET['what'] : 0;
$edit = (isset($_GET['edit'])) ? (int)$_GET['edit'] : 0;
$newsid = (isset($_GET['newsid'])) ? (int)$_GET['newsid'] : 0;
$del = (isset($_GET['del'])) ? (int)$_GET['del'] : 0;
$moder = (isset($_GET['moder'])) ? (int)$_GET['moder'] : -1;
$commentsid = (isset($_GET['commentsid'])) ? (int)$_GET['commentsid'] : 0;
$ok = isset($_GET['ok'])?(int)$_GET['ok']:''; 

$myFile=ENGINE.'newsdb.php';
$commentFile=ENGINE.'commentsdb.php';
$newsperpage=30;
$contentcenter='';

if(file_exists($myFile)){
	//Запись
	if (!empty($_REQUEST['action'])){
	    $news=file($myFile);
	    $countallnews = count($news);
	    if ($edit>0) {
			$new=unserialize($news[$edit-1]);
			@$adminemail = $new['admmail'];
			@$idmess = $new['id'];
			@$time = $new['pubtime'];

			$head=filtermessage($_REQUEST['header']);
			$startnews=filterquotes($_REQUEST['editorh']);
			$fullnews=filterquotes($_REQUEST['editor']);
			$date=trim($_REQUEST['pubdate']);
			$adminname=filterquotes($_REQUEST['adminname']);
			$comments=trim($_REQUEST['comments']);
			$description=trim($_REQUEST['description']);
			$keywords=trim($_REQUEST['keywords']);
			$tags=trim($_REQUEST['tags']);
			if(empty($idmess))$idmess=time();

	        $data=array('head'=>$head,
			    'mess'=>$startnews,
	    	    'aname'=>$adminname,
			    'admmail'=>$adminemail,
			    'pubdate'=>$date,
			    'pubtime'=>$time,
			    'extra'=>$fullnews,
			    'comments'=>$comments,
			    'description'=>$description,
			    'keywords'=>$keywords,
			    'tags'=>$tags,
			    'id'=>$idmess);

			$news[$edit-1]=serialize($data)."\n";
			savearray($myFile,$news,'w','');
			if($_REQUEST['action']=='go')header('Location: ../admin/news.php');

	    }
	}

	$news=file($myFile);
	//Удаление
	if ($what>0) {
		if(2>getpermision())header('LOCATION:news.php');
		$idmess=unserialize($news[$what-1]);
		$countallnews = count($news);
		if($ok>0){
			dellallcomments($idmess['id'], $commentFile);
			array_splice($news,$what-1,1);
			savearray($myFile,$news,'w','');
			@$contentcenter.= '<table><h3>Удаление новости</h3>';
		    $contentcenter.='<div class="message_warn_ok"><B>Новость и комментарии удалены!</B></div><br><br><a href="../admin/news.php"><B>Вернуться назад</B></a>';
		}else{
		$contentcenter.= 'Вы действительнохотите физически удалить новость <B>'.$idmess['head'].'/</B>?
			<br /><a title="Удалить" href="../admin/news.php?what='.$what.'&ok=1">ДА</a> | <a title="Отложить"  href=\'javascript:history.back(1)\'>НЕТ</a>
			<br /><br /><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';
		}
	//Комментарии
	} elseif ($newsid>0) {
		if ($commentsid>0){
			if($del>0){
				dellcomments($newsid, $commentsid,  $commentFile);
				header('LOCATION: ../admin/news.php?newsid='.$newsid);
			}
			if($moder>-1){
				modercomments($newsid, $commentsid,  $commentFile, $moder);
				header('LOCATION: ../admin/news.php?newsid='.$newsid);
			}
		}
		@$contentcenter.='<table cellpadding="2" cellspacing="0" width="90%"><h3>Комментарии к новости</h3><br /><br />';
		$arrcomments=getcomments($newsid, $commentFile);
		for($i=0;$i<count($arrcomments);$i++){
				$currentcomment=$arrcomments[$i];
				$contentcenter.= '<tr><td  width="80%" colspan="1"><b>Автор:</b> '.$currentcomment['name'].'  <b>ip:</b>[<a href="../admin/banip.php?add='.$currentcomment['ip'].'" title="Бан по ip адресу">'.$currentcomment['ip'].'</a>]<br/>';
				$contentcenter.= '<b>E-mail:</b>  <a href="mailto:'.$currentcomment['email'].'">'.$currentcomment['email'].'</a></td>';
				$contentcenter.= '<td width="15%" colspan="1">Модерация: <a title="Модерация" href="../admin/news.php?newsid=' . $newsid . '&commentsid=' . $currentcomment['id_comment'];
				if($currentcomment['moderator']==1){
					$contentcenter.= '&moder=0"><img src="images/cb_y.png" /></a></td>';
				}else{
					$contentcenter.= '&moder=1"><img src="images/cb_e.png" /></a></td>';
				}
				$contentcenter.= '<td width="5%" colspan="1"><a title="Удалить комментарий" href="../admin/news.php?newsid=' . $newsid . '&commentsid=' . $currentcomment['id_comment'] . '&del=1"><img src="images/delete.png" /></a></td></tr>';
				$contentcenter.= '<tr><td class="line3" colspan="3">'.$currentcomment['content']."</td></tr>";
		}
		$contentcenter.='<tr><td><center><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a></center></td></tr>';
	//Редактирование
	} elseif ($edit>0) {
	    $countallnews = count($news);
		$new=unserialize($news[$edit-1]);
		$head = $new['head'];
		$startnews = $new['mess'];
		$adminname = $new['aname'];
		$date = $new['pubdate'];
		$fullnews = $new['extra'];
		$comments = (int)$new['comments'];
		$description = $new['description'];
		$keywords = $new['keywords'];
		$tags = $new['tags'];
		$is_checked_commentpage=array('','','');
		$is_checked_commentpage[$comments]='checked="checked"';
	    @$contentcenter .=<<<EOT
	    <form action="$url" method="post" name="my_form">
		<input type="hidden" name="action" id="action" value="go" />
		<h3>Редактирование новости:</h3>Заполните все поля формы!<br>
		<label title='Выбор даты'>Дата новости:
		<input type="text" name="pubdate" id="pubdate" size="10" maxlength="10" readonly="readonly" value=$date />
		<img src='images/calendar.png' /></label>&nbsp;&nbsp;
        <label>Автор новости:
		<input type="text" name="adminname" id="adminname" size="15" maxlength="100" value="$adminname" /></label><br /><br />
		<label><b>Комментарии:</b></label>
		<label><input name="comments" type="radio" value="0" $is_checked_commentpage[0] /> запрещены</label>
		<label><input name="comments" type="radio" value="1" $is_checked_commentpage[1] /> остановлены</label>
		<label><input name="comments" type="radio" value="2" $is_checked_commentpage[2] /> разрешены</label>
		<br /><br />
		<input type="text" name="header" size="70" maxlength="255" value='$head' /><br/><br/>
		<textarea  id="editorh" name="editorh" cols=89 rows=4>$startnews</textarea><br />
		<textarea  id="editor" name="editor" cols=89 rows=10>$fullnews</textarea><br />
		<label title="Описание страницы :: Описание страницы вводится как обычный текст; он заполняет метатег meta-description создаваемой страницы.  В описании указывают краткое содержание того, что представлено на странице. Не стоит данное поле заполнять ключевыми словами. Текст описания мало влияет на степень отношения поисковика к странице сайта."><b>Описание (description):</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
		<input type="description" name="description" id="description" value="$description" /></label><br /><br />
		<label title="Ключевые слова :: Ключевые слова вводятся через запятую; оно заполняет метатег meta-keywords создаваемой страницы. Каждая страница продвигается в поисковых системах по одному или нескольким ключевым словам. Стоит помнить, что некоторые поисковые системы (например, Яндекс) не придают значения указываемым ключевым словам. Ключевые слова, записанные для конкретной страницы обязательно должны присутствовать в ней. Плотность ключевых слов (число ключевых слов, отнесенных к общему числу слов) в тексте не должна превышать 5-10%, иначе поисковые роботы могут счесть страницу за спам."><b>Ключевые слова:</b>
		<input type="text" name="keywords" id="keywords" value="$keywords"  size="40" /></label><br /><br />
		<label title="Теги :: Теги (метки) вводятся через пробел; используется в облаке тегов создаваемой страницы."><b>Теги (метки):</b>
		<input type="text" name="tags" class="settings" id="keywords" value="$tags" size="40" /></label><br /><br />
		<div class="submit"><input type="submit" class="submit-button" value="Записать новость" /></div><br />
	    </form>
EOT;
	} else {
	    $countallnews = count($news);
	    @$contentcenter.='Всего новостей: '.$countallnews."<br />\n";
	    if(!isset($_REQUEST['newspage'])) {$_REQUEST['newspage']=1;
	    } else {$_REQUEST['newspage'] = (int)$_REQUEST['newspage'];}
	    $j = ($countallnews-1)-(($_REQUEST['newspage']-1)*$newsperpage);
	    $i = $j-$newsperpage;
	    @$contentcenter.= '<h3>Список опубликованных новостей</h3><br />';
	    $contentcenter.= '<br><table cellpadding="6" cellspacing="0">
	    <thead><tr class="line2" ><td width="60">Дата</td>
	    <td width="60%">Страница </td>
	    <td colspan="1" width="10%">Редактировать</td>
	    <td colspan="1" width="10%">Удалить</td>
	    <td colspan="2" width="10%">Комментарии</td>
	    </tr></thead><tbody>';

		$dumbcount=0;
		for($j;(($i<$j)&&($j>=0));$j--)  {
			$dumbcount++;
			$class = 'cline' . ($dumbcount % 2);
			$new=unserialize($news[$j]);
			if ($new=='')continue;
			$head = $new['head'];
			$startnews = $new['mess'];
			$adminemail = $new['admmail'];
			$adminname = $new['aname'];
			$date = $new['pubdate'];
			$time = $new['pubtime'];
			$description = $new['description'];
			$keywords = $new['keywords'];
			$tags = $new['tags'];
			$idmess = $new['id'];
			$p = $j+1;
			$contentcenter.='<tr class="'.$class.'">
			    <td class="line3" width="60">'.$date.'</td>
			    <td class="line3" width="60%">'.$head.'</td>
			    <td class="line3" colspan="1" width="10%"><a title="Редактировать" href="../admin/news.php?edit='.$p.'"><img src="images/edit.png"></a></td>
			    <td class="line3" colspan="1" width="10%"><a title="Удалить" href="../admin/news.php?what='.$p.'"><img src="images/delete.png"></a></td>
			    <td class="line3" colspan="1" width="6%">'.getcountcomments($idmess,$commentFile).'</td>
			    <td class="line3" colspan="1" width="4%"><a title="Комментарии" href="../admin/news.php?newsid='.$idmess.'"><img src="images/info.png"></a></td>
			</tr>';
		}
		$contentcenter.='</tbody>';
	}
} else $contentcenter.='<h3>Список опубликованных новостей</h3><br /><br /><div class="message_warn">Новостей нет!</div>';
$contentcenter .='</table>';
$contentcenter .='<br /><br /> Страницы:';
$all = ceil($countallnews/$newsperpage);
for($i=1;$i<=$all;$i++) {
	if(isset($_REQUEST['newspage'])&&($_REQUEST['newspage']==$i)) {
	$contentcenter.='&laquo;<b>'.$i.'</b>&raquo;&nbsp;';
	}
	else {
		if(isset($whatpage)&&($whatpage=='news')) $contentcenter.='<a href="news.php?newspage='.$i.'">'.$i.'</a>&nbsp;';
		else $contentcenter.= '<a href="news.php?newspage='.$i.'">'.$i.'</a>&nbsp;';
	}
}
include $localpath.'/admin/admintemplate.php';
?>
