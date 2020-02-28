<?php
//phpfile
#Гостевая книга с каптчей и антифлудом, ответы администрации
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');

include CONF.'gbconf.php';
$start = isset($_GET['start'])?$_GET['start']:0;
$addrec = isset($_GET['addrec'])?1:0;

$myFile=ENGINE.'guestbookdb.php';

if($addrec){
	$error = '';
	$action=isset($_POST['action'])?$_POST['action']:'';
	$_POST=stripinarr($_POST);

	if(ip_is_baned())$error.='<li>'.__('Вы забанены на данном сайте').', '.__('Вам запрещено писать сообщения').'</li>';
	if (empty($error)&&!empty($action)){
	    $lenmsg = strlen($_POST['msg']);
	    $templen = 0;
	    $temp = strtok($_POST['msg'], ' ');
	    if (strlen($_POST['msg'])>160)
	        while ($templen < $lenmsg) {
	            if (strlen($temp)>160) {
	                $action = '';
	                $error = $error.'<li>'.__('Текст сообщения содержит слишком много символов без пробелов')."</li>\n";
	                break;
	            } else $templen = $templen + strlen($temp) + 1;
	            $temp = strtok(' ');
	        }
		extract($_POST, EXTR_OVERWRITE);

	    if(empty($msg)) $error .= '<li>'.__('Вы не ввели сообщение')."</li>\n";
	    if(empty($name)) $error .= '<li>'.__('Вы не ввели имя')."</li>\n";
	    if(!empty($email)&&!preg_match('/^[-0-9a-zа-я_\.]+@[-0-9a-zа-я_^\.]+\.[a-zа-я]{2,4}$/isu', $email))
            $error .='<li>'.__('Неверно введен е-mail.&nbsp Введите e-mail в виде')." <i>something@server.com</i></li>\n";
		if(!$captcha) $error.='<li>'.__('Каптча введена не правильно')."</li>\n";
		if(isset($_COOKIE['flood_gb'])) $error.='<li>'.__('Возможен флуд, попробуйте через').' '.$gbflood.' '.__('секунд')."</li>\n";

	    $name = substr($_POST['name'],0,32);
	    $city = substr($_POST['city'],0,32);
	    $email = substr($_POST['email'],0,60);
	    $url = substr($_POST['url'],0,60);
	    $msg = substr($msg,0,1024);

	    $url = strtr($url, 'HTPF', 'htpf');
	    if (trim($url)!="")if (strtolower((substr($url, 0, 7))!='http://') && (strtolower(substr($url, 0, 7))!='ftp://')) $url='http://'.$url;

	    $search_bad_words = array("'хуй'si","'пизд'si","'ёб'si",
	                            "'сука'si","'суки'si","'дроч'si","'хуя'si","'ссуч'si");
	    $replace = array("*","*","*","*","*","*","*","*");
	    $msg = preg_replace($search_bad_words,$replace,$msg);
	    $name = preg_replace($search_bad_words,$replace,$name);
	    $city = preg_replace($search_bad_words,$replace,$city);

	    if (empty($error)){
	        $msg = nl2br($msg);
	        $msg = str_replace("\n"," ",$msg);
	        $msg = str_replace("\r"," ",$msg);

			require_once(CODE.'bbParser.php');
			$bbcode = new bbParser($disableURL);
			$msg = $bbcode->getHtml($msg);

			$data=array('time'=>time(),'name'=>$name,'city'=>$city,'email'=>$email,'url'=>$url,'mess'=>$msg,'ip'=>$_SERVER[REMOTE_ADDR]);
			if(file_exists($myFile)){
				$datas=loaddata($myFile);
			    // Если $catmessage = true органичиваем число
	    	    // сообщений в гостевой книге $nummessage
				if(($catmessage)&&(count($datas)>=$nummessage))array_splice($datas,0,count($datas)-$nummessage+1);
			}
			$datas[]=$data;
			savedataarray($myFile,$datas,'w');

			@setcookie('flood_gb',$gbflood, time()+$gbflood);
	        // Если $sendmail = true отправляем уведомление
	        if($sendmail){
	          	$thm = 'guestbook - a new post';
	          	$msg = "post: $msg\nname: $name";
	          	mail($valmail, $thm, $msg);
	        }
	        echo "<HTML><HEAD>\n";
	        echo "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=".cc_link('/guestbook.html')."'>\n";
	        echo "</HEAD></HTML>\n";
	    }else $action='';
	}

	if(empty($name))$name=isset($_SESSION['name'])?$_SESSION['name']:'';
	if (empty($action)){
?>
	<script type="text/javascript">
	var error='';
	e_regex = /^[a-zA-Zа-яА-Я0-9_\.\-]+\@([a-zA-Zа-яА-Я0-9\-]+\.)+[a-zA-Zа-яА-Я0-9]{2,4}$/;

	function Checkit(theform) {

		if(theform.name.value=="") {
			error+="<?php=__('Вы не ввели имя');?>\n";
		}

		if(theform.msg.value=="") {
			error+="<?php=__('Вы не ввели сообщение');?>\n";
		}

		if(theform.vcaptcha.value=="") {
			error+="<?php=__('Вы не ввели значение каптчи');?>\n";
		}

		if(theform.email.value!="") {
			if(!e_regex.test(theform.email.value)) {
				error+="<?php=__('Неправильный адрес электронной почты');?>\n";
			}
		}

		if(error) {
			alert('<?php=__('Произошли следующие ошибки');?>:\n\n' + error);
			error="";
			return false;
		} else {
			return true;
		}
	}
	</script>

	<script type="text/javascript" src="<?php echo $prefflp;?>/js/bb.js" charset="utf-8"></script>
	<p><?php __('Слово не воробей - вылетит не поймаешь.');?><br /><?php __('Мы призываем Вас не использовать в сообщениях нецензурную лексику!');?></p>
	<form action="#" method="post"  enctype="multipart/form-data" name="formular" onsubmit="return Checkit(this);">
		<input type="hidden" name="action" value="post" />
	 	<h3><?php __('Добавление сообщения');?></h3><br />
	 	<div class="inpfrmname">Имя:
		</div>
	    <div class="inpfrmval"><input type="text" name="name" maxlength="52" size="25" value="<?php echo @$name; ?>" /><span class="error_message">*</span>
		</div>
	    <div class="inpfrmend"></div>
	    <div class="inpfrmname">Город:
		</div>
	    <div class="inpfrmval"><input type="text" name="city" maxlength="32" size="25" value="<?php echo @$city; ?>" /><br />
		</div>
	    <div class="inpfrmend"></div>
	    <div class="inpfrmname">E-mail:
		</div>
	    <div class="inpfrmval"><input type="text" name="email" size="25" maxlength="32" value="<?php echo @$email; ?>" /> (<?=__('публикуется');?>)<br />
		</div>
	    <div class="inpfrmend"></div>
	    <div class="inpfrmname">URL:
		</div>
	    <div class="inpfrmval"><input type="text"  name="url" size="40" maxlength="36" value="<?php echo @$url; ?>" /><br />
		</div>
	    <div class="inpfrmend"></div>
		<div class="inpfrmmessage">
	 	<label for="msg">Сообщение:<span class="error_message">*</span></label>
		<fieldset>
			<script type="text/javascript">edToolbar('msg','<?php echo $prefflp;?>');</script>
			<textarea cols="55" rows="10" name="msg" id="msg" style="width:98%" ><?php echo @$msg; ?></textarea>
		</fieldset>
		</div>
			<?php echo put_captcha();?>
		<input type="hidden" name="submit" value="true" />
	   	<input type="submit" value="Добавить" />&nbsp;&nbsp;&nbsp;
	   	<input type="reset" value="Сброс" />
	</form>
	<?php if($gbmoderator=="1") echo "<br />".__('Ваша запись будет направлена на модерацию, и в случае ее успешного прохождения, ее увидят все'); ?>
	<p class="back"><a href='javascript:history.back(1)'><?php __('Вернуться назад');?></a></p>
<?php
	    if (!empty($error)){
	        echo "<p class=\"error_header\"><font color=green>".__('Во время добавления записи произошли следующие ошибки').": </font></p>\n";
	        echo "<ul class=\"error_message\">\n";
	        echo $error;
	        echo "</ul>\n";
	    }
	}
}else{
	echo '<div class="guestbook">';
	if(file_exists($myFile)){
		$gbs=array_reverse(file($myFile));
	    $count = count($gbs);
	    echo '<h3>'.__('Всего записей').':&nbsp;'.$count.'</h3><br />';
		$count1 = $count;
		if ($start <= 0){$start = 0;$count=min($count,$pnumber);}
		for ($i = $start; $i < min($count,$start+$pnumber); $i++){
			$data=unserialize($gbs[$i]);
			print_r($data);
			if ($data=="") {continue;}
    		$moderator = isset($data['moderator'])?(int)$data['moderator']:0;
	    	if(($moderator!==1)&&($gbmoderator=="1")) {continue;}
	 	  	$date = $data['time'];
		    $date = date("<b>d-m-Y</b> H:i",$date);
	     	$name = $data['name'];
	    	$city = $data['city'];
		    $city = !empty($city)?"(".$city.")":"";
		    $email = $data['email'];
		    $email = !empty($email)?"e-mail: <a href=\"mailto:".$email."\">".$email."</a>&nbsp;&nbsp;":"";
	    	$url = $data['url'];
			$url = !empty($url)?"www: <a href=\"".$url."\">".$url."</a>":"";
			$msg = $data['mess'];
	    	$answer = isset($data['answer'])?$data['answer']:'';
			$answer = isset($answer)?"<b>Аdmin:</b>&nbsp;".$answer:"";
			$p=$i+$start;$p++;
	    	echo '<div class="head">
			        <div style="float: left;width:60%"><b>'.$name.'</b>&nbsp;'.$city.'</div>
			        <div style="text-align: right">'.__('от').': '.$date.'</div>
			        <div style="clear: left">'.$email.'&nbsp;'.$url.'</div>
			    </div>
		    	<div class="entry">
			        '.$msg.'
			    </div>
		    	<div class="admin">
			        '.$answer.'
			    </div>
				<br />';
		}
		echo '<div class="nav">';
		$count =min($count, $start + $pnumber);
		if ($start != 0)
		{
		    echo ' <span class="prev"><a href="'.cc_link('/guestbook-start-'.($start - $pnumber).'.html').'">'.__('Следующие').'</a></span> || ';
		}else{
		    echo ' <span class="next">'.__('Следующие').'</span> || ';
		}
		if ($count1 > $start + $pnumber)
		{
		    echo ' <span class="next"><a href="'.cc_link('/guestbook-start-'.($start + $pnumber).'.html').'">'.__('Предыдущие').'</a></span><br /><br />';
		}else
		{
		    echo ' <span class="prev">'.__('Предыдущие').'</span><br /><br />';
		}
		echo '</div>';
	}
	echo '<p class="back"><a href="'.cc_link('/guestbook-addrec-1.html').'">'.__('Добавить запись').'</a></p>';
	echo "</div>\n";
}
?>

