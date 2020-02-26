<?php
//phpfile
#Обратная связь пользователей сайта с каптчей и антифлудом
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');
error_reporting(E_ALL ^ E_NOTICE);
include_once CONF.'feedbackconf.php';

$allowtypes=array("zip", "rar", "gz", "bz2", "7z", "txt", "doc", "xls", "odt", "ods", "jpg", "png", "gif","odt","xml");

function get_ext($key){
	$key=strtolower(substr(strrchr($key, "."), 1));
	$key=str_replace('jpeg','jpg',$key);
	return $key;
}
function phattach($file,$name){
	global $boundary;
	$fp=@fopen($file,"r");
	$str=@fread($fp, filesize($file));
	$str=@chunk_split(base64_encode($str));
	$message="--".$boundary."\n";
	$message.="Content-Type: application/octet-stream; name=\"".$name."\"\n";
	$message.="Content-disposition: attachment; filename=\"".$name."\"\n";
	$message.="Content-Transfer-Encoding: base64\n";
	$message.="\n";
	$message.=$str."\n";
	$message.="\n";
	return $message;
}
function clean($key){
	$key=str_replace("\r", "", $key);
	$key=str_replace("\n", "", $key);
	$find=array(
		"/bcc\:/i",
		"/Content\-Type\:/i",
		"/Mime\-Type\:/i",
		"/cc\:/i",
		"/to\:/i"
	);
  $key=preg_replace($find,"",$key);
  return $key;
}
$error='';
$sent_mail=false;
if(sizeof($allowtypes)>0)$types='*.'.implode(', *.',$allowtypes);else $types='';

if($_POST['submit']==true) {
	$_POST=stripinarr($_POST);
	extract($_POST, EXTR_OVERWRITE);
	if(trim($yourname)=="") {
		$error.=__('Вы не ввели имя').'<br />';
	}
	if(trim($youremail)=="") {
		$error.=__('Вы не ввели адрес электронной почты').'<br />';
	}elseif(!preg_match('/^([.0-9a-zа-я_-]+)@(([0-9a-zа-я-]+\.)+[0-9a-zа-я]{2,4})$/isu',$youremail)) {
		$error.=__('Неправильный адрес электронной почты').'<br />';
	}
	if(trim($emailsubject)=="") {
		$emailsubject=$defaultsubject;
	}
	if(trim($yourmessage)=="") {
		$error.=__('Вы не ввели сообщение').'<br />';
	}
	if (!$captcha) {
	    $error.=__('Каптча введена не правильно').'<br />';
	};
	if($allowattach > 0) {
		//Loopish
		for($i=0; $i <= $allowattach-1; $i++) {

			if($_FILES['attachment']['name'][$i]) {
				$ext=get_ext($_FILES['attachment']['name'][$i]);
				$size=$_FILES['attachment']['size'][$i];
				$max_bytes=$max_file_size*1024;
				//Check if the file type uploaded is a valid file type.
				if(!in_array($ext, $allowtypes)) {
					$error.= __('Недопустимое расширение для вашего файла').': '.$_FILES['attachment']['name'][$i].", only ".$types." are allowed.<br />";
				//Check the size of each file
				} elseif($size > $max_bytes) {
					$error.= __('Ваш файл').': '.$_FILES['attachment']['name'][$i].' '.__('большой. Максимальный допустимый размер файла').' '.$max_file_size.'kb.<br />';
				}
			}
		}
		//Tally the size of all the files uploaded, check if it's over the ammount.
		$total_size=array_sum($_FILES['attachment']['size']);
		$max_file_total_bytes=$max_file_total*1024;
		if($total_size > $max_file_total_bytes) {
			$error.=__('Максимальный допустимый размер вашего файла').' '.$max_file_total."kb<br />";
		}

	}

	if($error) {
		$display_message=$error;
	}else {
		if($use_subject_drop AND is_array($subjects) AND is_array($emails)) {
			$subject_count=count($subjects);
			$email_count=count($emails);

			if($subject_count==$email_count) {
				$myemail=$emails[$emailsubject];
				$emailsubject=$subjects[$emailsubject];
			}
		}
		$boundary=md5(uniqid(time()));
		$yourname=clean($yourname);
		$yourmessage=clean($yourmessage);
		$youremail=clean($youremail);

//		$headers= "Content-type: text/html; charset=\"UTF-8\"\n";
		$headers.="From: ".$yourname." <".$youremail.">\n";
		$headers.="Reply-To: ".$yourname." <".$youremail.">\n";
		$headers.="Subject: ".$emailsubject."\n";
		$headers.="MIME-Version: 1.0\n";
		$headers.="Content-Type: multipart/mixed; charset=UTF-8; boundary=\"".$boundary."\n";
//		$headers.="Content-Type: multipart/mixed; boundary=\"".$boundary."\"\n";

		$headers.="X-Sender: ".$_SERVER['REMOTE_ADDR']."\n";
		$headers.="X-Mailer: PHP/".phpversion()."\n";
		$headers.="X-Priority: ".$priority."\n";
		$headers.="Return-Path: <".$youremail.">\n";
		$headers.="This is a multi-part message in MIME format.\n";

		/*Если русский совсем не идет тогда:
		$headers. = 'From: =?utf-8?B?'.base64_encode($$youremail).'?=';
		-*/

		//Message

		$message = "--".$boundary."\n";
		$message.="Content-Type: text/plain; charset=UTF-8; format=flowed\n";
		$message.="Content-Transfer-Encoding: quoted-printable\n";
		$message.="\n";
		$message.="Отправитель: ".$yourname." e-mail: ".$youremail."\n";
		$message.="\n";
		$message.="-----------------------------------------------------";
		$message.="\n";
		$message.="$ltd\n";
		$message.="$yourmessage";
		$message.="\n";
		//Lets attach to something! =)

		if($allowattach > 0) {
			for($i=0; $i <= $allowattach-1; $i++) {
				if($_FILES['attachment']['name'][$i]) {
					$message.=phattach($_FILES['attachment']['tmp_name'][$i],$_FILES['attachment']['name'][$i]);
				}
			}
		}
		// End the message
		$message.="--".$boundary."--\n";
		// Send the completed message
		if($use_smtp_sent){
			//используя сокеты и левый smtp--
			$sent_mail=smtp_mail($myemail,$message,$headers,$arr_for_smtp);
		}else{
	        //используя mail php-------------
			$sent_mail=mail($myemail,$emailsubject,$message,$headers);
		}
		if(!$sent_mail){
			exit(__('Произошла ошибка, пожалуйста, сообщите об этом администратору сайта').".\n");
		} else {
			$sent_mail=true;
		}
	}

}
$url=$_SERVER['REQUEST_URI'];
//только английские буквы в емыле
//e_regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/;
//русские буковки в емаыле
?>
<script type="text/javascript">
var error="";
e_regex = /^[a-zA-Zа-яА-Я0-9_\.\-]+\@([a-zA-Zа-яА-Я0-9\-]+\.)+[a-zA-Zа-яА-Я0-9]{2,4}$/;

function Checkit(theform) {

	if(theform.yourname.value=="") {
		error+="<?=__('Вы не ввели имя');?>\n";
	}

	if(theform.youremail.value=="") {
		error+="<?=__('Вы не ввели Ваш email');?>\n";
	} else if(!e_regex.test(theform.youremail.value)) {
		error+="<?=__('Неправильный адрес электронной почты');?>\n";
	}

	if(theform.yourmessage.value=="") {
		error+="<?=__('Вы не ввели сообщение');?>\n";
	}

	if(theform.captcha.value=="") {
		error+="<?=__('Вы не ввели значение каптчи');?>\n";
	}

	if(error) {
		alert('<?=__('Произошли следующие ошибки');?>:\n\n' + error);
		error="";
		return false;
	} else {
		return true;
	}
}
</script>
<?
if($display_message) {
	echo '<div class="error_message"><b>'.$display_message.'</b></div><br />';
}
?>
<?if($sent_mail!=true) {?>
<form method="post" action="<? echo $url;?>" enctype="multipart/form-data" name="phmailer" onsubmit="return Checkit(this);">
<?
if($allowattach > 0){
echo'<div>
		<b>'.__('Поддерживаемые типы файлов').':</b> '.$types.'<br />
		<b>'.__('Максимальный размер файла').':</b> '.$max_file_size.' kb.<br />
		<b>'.__('Максимальный размер архива').':</b> '.$max_file_total.' kb.
	</div>';
}
?>
<div class="inpfrmname"><?=__('Имя');?>:
</div>
<div class="inpfrmval"><input name="yourname" type="text" size="30" value="<?=stripslashes(htmlspecialchars($yourname));?>" /><span class="error_message">*</span>
</div>
<div class="inpfrmend"></div>
<div class="inpfrmname"><?=__('E-mail');?>:
</div>
<div class="inpfrmval"><input name="youremail" type="text" size="30" value="<?=stripslashes(htmlspecialchars($youremail));?>" /><span class="error_message">*</span>
</div>
<div class="inpfrmend"></div>
<div class="inpfrmname"><?=__('Тема');?>:
</div>
<div class="inpfrmval">
	<?if($use_subject_drop AND is_array($subjects)) {?>
			<select name="emailsubject" size="1">
				<?while(list($key,$val)=each($subjects)) {?>
					<option value="<?=htmlspecialchars(stripslashes($val));?>"><?=htmlspecialchars(stripslashes($val));?></option>
				<?}?>
			</select>
	<?}else{?>
		<input name="emailsubject" type="text" size="30" value="<?=stripslashes(htmlspecialchars($emailsubject));?>" />
	<?}?>
</div>
<div class="inpfrmend"></div>
<div class="inpfrmname"><?=__('Кто Вы');?>:
</div>
<div class="inpfrmval">
	<?if($use_ltd_drop AND is_array($ltds)) {?>
			<select name="ltd">
				<?while(list($key,$val)=each($ltds)) {?>
					<option value="<?=htmlspecialchars(stripslashes($val));?>"><?=htmlspecialchars(stripslashes($val));?></option>
				<?}?>
			</select>
	<?}else{?>
		<input name="ltd" type="text" size="30" value="<?=stripslashes(htmlspecialchars($ltd));?>" />
	<?}?>
</div>
<div class="inpfrmend"></div>
<?for($i=1;$i <= $allowattach; $i++) {?>
	<div class="inpfrmname"><?=__('Прикрепить файл');?>:
	</div>
	<div class="inpfrmval"><input name="attachment[]" type="file" />
	</div>
	<div class="inpfrmend"></div>
<?}?>
<div class="inpfrmmessage">
	<label><?=__('Сообщение');?>:
	<textarea name="yourmessage" id="yourmessage" rows="8" cols="55" style="width:95%;font:8pt Verdana;padding:4px"><?=stripslashes(htmlspecialchars($yourmessage));?></textarea></label>
</div>
<?=put_captcha();?><br />
<input type="hidden" name="submit" value="true" />
<input type="submit" value="<?=__('Отправить');?>" /> &nbsp;
<input type="reset" value="<?=__('Сброс');?>" />
</form>
<?}else{?>
<div class="thanks_message"><?=__('Спасибо');?>! <?=__('Ваше письмо было отправлено, мы ответим в ближайшее время');?></div>
<html><head><meta http-equiv='Refresh' content='3; URL=/'></head></html>
<?}?>
