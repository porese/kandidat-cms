<?php
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');
if(empty($commentsfilename))$commentsfilename=$myFile.'.comment';
if(empty($commentsid))$commentsid=0;
$error = '';
$_POST=stripinarr($_POST);
if (!empty($_POST['action_comment'])){
    $lenmsg = strlen($_POST['msg']);
    $templen = 0;
    $temp = strtok($_POST['msg'], ' ');
    if (strlen($_POST['msg'])>160)while($templen< $lenmsg){
		if (strlen($temp)>160) {
			$error.='<li>'.__('Текст сообщения содержит слишком много символов без пробелов (очень длинное слово)')."</li>\n";
			break;
		}else $templen+=strlen($temp) + 1;
		$temp=strtok(' ');
    }
	extract($_POST, EXTR_OVERWRITE);
    if(empty($msg)) $error .= '<li>'.__('Вы не ввели сообщение').'</li>';
    if(empty($yourname)) $error .= '<li>'.__('Вы не ввели имя').'</li>';
    if(!empty($email)&&!preg_match('/^[-0-9a-zа-я_\.]+@[-0-9a-zа-я_^\.]+\.[a-zа-я]{2,4}$/iU', $email))$error .='<li>'.__('Неверно введен е-mail.&nbsp Введите e-mail в виде').' <i>something@server.com</i></li>';
	if(!$captcha) $error.='<li>'.__('Каптча введена не правильно').'</li>';
	if (isset($_COOKIE['flood_news'])) $error.='<li>'.__('Возможен флуд, попробуйте через').' '.$newsflood.' '.__('секунд').'</li>';
	if (ip_is_baned($_SERVER[REMOTE_ADDR])) $error.='<li>'.__('Вы забанены на данном сайте').', '.__('Вам запрещено писать комментарии').'</li>';
	print_r($error);
	echo '-----------------'.$cattcha;
    $search_bad_words = array("'хуй'siU","'пизд'siU","'ёб'siU",
                            "'сука'siU","'суки'siU","'дроч'siU","'хуя'siU","'ссуч'siU");
    $replace = array("*","*","*","*","*","*","*","*");
	$msg = strip_tags($msg);
    $msg = preg_replace($search_bad_words,$replace,$msg);
    $yourname = preg_replace($search_bad_words,$replace,$yourname);
    if (empty($error)){
        $msg = nl2br($msg);
        $msg = str_replace("\n"," ",$msg);
        $msg = str_replace("\r"," ",$msg);

		require_once(CODE.'bbParser.php');
		$bbcode = new bbParser($disableURL);
		$msg = $bbcode->getHtml($msg);

		addcomments((int)$idmess, $commentsfilename, $msg, $yourname, $email);
		$msg='';
		@setcookie('flood_news',$newsflood, time()+$newsflood);
	}
}
if(empty($yourname))$yourname=$_SESSION['name'];
if(file_exists($commentsfilename)){
	$commentpage=(isset($_GET['commentpage']))?(int)$_GET['commentpage']:0;
	$arrcomments=getcomments($commentsid, $commentsfilename,$moder_comments);
	$arrcomments=array_reverse($arrcomments);
	$countcomments=getcountcomments($commentsid,$commentsfilename,$moder_comments);
	echo '<br /><br /><div class="comment_head"><a name="comment_begin"></a><h3>'.__('Комментарии').':</h3></div><hr>';
	if(count($arrcomments)==0)echo '<div class="comment"><br/>'.__('Нет комментариев').'.<br/></div>';
	else{
		if($commentpage*$commentsperpage_comments>count($arrcomments))$commentpage=0;
		$i=$commentpage*$commentsperpage_comments;
		if($commentpage==0)$j=min(count($arrcomments),$commentsperpage_comments);
		else $j=min(count($arrcomments),$commentpage*$commentsperpage_comments+$commentsperpage_comments);
		for($i;$i<$j;$i++){
			$currentcomment=$arrcomments[$i];
			echo  '<div class="comment"><div class="comment-timestamp">
				<a href="#Id'.$currentcomment['id_comment'].'" name="Id'.$currentcomment['id_comment'].'"
				title="'.__('Постоянная ссылка на сообщение').'">'.date('d-m-Y H:i:s',$currentcomment['id_comment']).'</a></div>
				<b>'.__('Автор').':</b> '.$currentcomment['name'].'<br/>
				<b>E-mail:</b>  <a href="mailto:'.$currentcomment['email'].'">'.$currentcomment['email'].'</a><br/>
				'.$currentcomment['content'].'</div>';
		}
	}
	echo '<br /><div id="navigation-comment">'.__('Страницы').':&nbsp;&nbsp;';
	if(count($arrcomments)==0){
		echo  '&laquo;<b>1</b>&raquo;';
	}else{
		for($i=0;$i<count($arrcomments)/$commentsperpage_comments;$i++){
			$j=$i+1;
			if($i==($commentpage)) echo '&laquo;'.$j.'&raquo;&nbsp;';
			else echo  '<a href="'.cc_link('/news-'.$viewnews.'_'.$i.'.html').'">'.$j.'</a>&nbsp;';
		}
	}
	echo '</div>';
}
if($enablecomment==2){
	echo '<script type="text/javascript">
	var error="";
	function Checkit(theform) {
		if(theform.yourname.value=="") {
			error+="'.__('Вы не ввели имя').'\n";
		}
		if(theform.msg.value=="") {
			error+="'.__('Вы не ввели сообщение').'\n";
		}
		if(theform.vcaptcha.value=="") {
			error+="'.__('Вы не ввели значение каптчи').'\n";
		}
		if(error) {
			alert("'.__('Во время добавления записи произошли следующие ошибки').':\n\n" + error);
			error="";
			return false;
		} else {
			return true;
		}
	}
	</script>
	<script type="text/javascript" src="'.$prefflp.'/js/bb.js" charset="utf8"></script>
	<form action="" method="post"  enctype="multipart/form-data" name="formular" onsubmit="return Checkit(this);">
		<input type="hidden" name="action_comment" value="post" />
		<input type="hidden" name="idmess" value="'.$commentsid.'" />
		<br /><br /><b>'.__('Добавить комментарий').'</b><br />
		<div class="inpfrmname">'.__('Имя').':</div>
		<div class="inpfrmval"><input type="text" name="yourname" maxlength="52" size="25" value=\''.$yourname.'\' /><span class="error_message">*</span></div>
		<div class="inpfrmend"></div>
		<div class="inpfrmname">'.__('E-mail').':</div>
		<div class="inpfrmval"><input type="text" name="email" size="25" maxlength="32" value=\''.$email.'\' /> ('.__('публикуется').')</div>
		<div class="inpfrmend"></div>
		<div class="inpfrmmessage">
			<label for="msg"><span>'.__('Сообщение').':<span class="error_message">*</span></span></label>
			<fieldset>
				<script>edToolbar(\'msg\',\''.$prefflp.'\');</script>
				<textarea cols="55" rows="10" name="msg" id="msg" style="width:98%" >'.$msg.'</textarea><br>
			</fieldset>
		</div>
		'.put_captcha().'
		<input type="hidden" name="submit" value="true" />
		<input type="submit" value="'.__('Добавить').'" />&nbsp;&nbsp;&nbsp;
		<input type="reset" value="'.__('Сброс').'" />
	</form>';
	if($newsmoderator=='1') echo  __('Ваша запись будет направлена на модерацию, и в случае ее успешного прохождения, ее увидят все');
	echo '<br />'.$back;
}
if(!empty($error)){
	echo  '<p class=error_header>'.__('Во время добавления записи произошли следующие ошибки').':</p>';
	echo  '<ul class=error_message>';
	echo  $error;
	echo  '</ul>';
}
?>
