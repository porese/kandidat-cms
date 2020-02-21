<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
include CONF.'gbconf.php';
$sitetitle="Управление гостевой книгой";
$start = (isset($_GET['start']))?(int)$_GET['start']:0;
$del = (isset($_GET['del']))?(int)$_GET['del']:0;
$edit = (isset($_GET['edit']))?(int)$_GET['edit']:0;
$moder = (isset($_GET['moder']))?(int)$_GET['moder']:-1;
$id = (isset($_GET['id']))?(int)$_GET['id']:0;
$myFile=ENGINE.'guestbookdb.php';

//Запись
if(isset($_REQUEST['mess'])){
    if ($edit>0) {
		$gbs=loaddata($myFile);
		$data=$gbs[($edit-1)];
		$msg = $_REQUEST['mess'];
		$msg=nl2br($msg);
		$msg=filterquotes($msg);
		$data['mess']=$msg;
		$answer = trim($_REQUEST['answer']);
		if(strip_tags($answer)=='')unset($data['answer']);
		else $data['answer']=filterquotes(nl2br($answer));
		$gbs[$edit-1]=$data;
		savedataarray($myFile,$gbs,'w');
	}
    header('Location: /admin/guestbook.php');

}
@$contentcenter .='<h3>Список записей гостевой книги</h3>';
if(file_exists($myFile)){
	$gbs=file($myFile);
	//Удаление
	if($del>0){
	    @chmod($myFile, 0777);
	    $open=fopen("$myFile","w");
		for($i=0;$i<count($gbs);$i++)
		{
	    	    if(($i+1)!==$del){fwrite($open,$gbs[$i]);}
		}
	    fclose($open);
	    @chmod($myFile, 0644);
	    @$contentcenter.= "<h3>Удаление записи гостевой книги</h3>";
	    $contentcenter.="<div class=\"message_warn_ok\"><B>Запись удалена!</B></div><br><br><a href='javascript:history.back(1)'><B>Вернуться назад</B></a>";
        $contentcenter.="<HTML><HEAD>\n";
        $contentcenter.="<META HTTP-EQUIV='Refresh' CONTENT='3; URL=".$prefflp."/admin/guestbook.php'>\n";
        $contentcenter.="</HEAD></HTML>\n";
	//Модерация
	}elseif($moder>-1){
	    @chmod($myFile, 0777);
	    $open=fopen("$myFile","w");
		for($i=0;$i<count($gbs);$i++)
		{
		    if(($i+1)!=$id){
				fwrite($open,$gbs[$i]);
		    }else{
				$data=unserialize($gbs[$i]);
				$data['moderator']=$moder;
				fwrite($open, serialize($data)."\n");
			}
		}
	    fclose($open);
	    @chmod($myFile, 0644);
		$contentcenter.="<HTML><HEAD>\n";
		$contentcenter.="<META HTTP-EQUIV='Refresh' CONTENT='0; URL=".$prefflp."/admin/guestbook.php'>\n";
        $contentcenter.="</HEAD></HTML>\n";
	//Редактирование
	}elseif($edit>0){
	    $count = count($gbs);
	    $contentcenter.="<div class=\"message_warn\">При нажатии на ссылку (кнопку) для удаления,  запись будет сразу же удалена без подтверждения!</div><br />Всего записей: $count<br>\n";
		$count1 = $count;
		if ($start <= 0) {$start = 0; $count=min($count,$pnumber);}
		for ($i = $start; $i < min($count,$start+$pnumber); $i++){
			$data=unserialize($gbs[$i]);
			if ($data=="") {continue;}
	 	  	$date = $data['time'];
		    $date = date("<b>d-m-Y</b> H:i",$date);
	    	$name = $data['name'];
	    	$city = $data['city'];
		    $city = !empty($city)?"($city)":"";
		    $email = $data['email'];
		    $email = !empty($email)?"e-mail: <a href=mailto:$email>$email</a>&nbsp;&nbsp;":"";
	    	$url = $data['url'];
			$url = !empty($url)?"www: <a href='$url'>$url</a>":"";
			$msg = $data['mess'];
	    	$answer = $data['answer'];
			$moderator = (int)$data['moderator'];
			$ip = $data['ip'];
			$p=$i+$start;$p++;
			if($p!==$edit){
			$answer = !empty($answer)?"<p class='color: #4795F3; font-size: 80%; text-align: justify; margin-top: 10px'><b>Аdmin:</b>&nbsp;$answer</p>":"";
	    	$contentcenter .=<<<EOT
			<table border="0" width="95%" cellpadding="0" cellspacing="0" align="center" style="border-bottom:1px">
			    <tr bgcolor="#F4F4F4">
			        <td rowspan="1" width="62%" height="20"><nobr><p class="font-size: 75%; margin: 0px; text-indent: 0px">&nbsp;&nbsp;<b>$name</b>&nbsp;[<a href="$prefflp/admin/banip.php?add=$ip" title="Бан по ip адресу">$ip</a>]&nbsp;$city</nobr>
			        </p></td>
			        <td width="20%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right">Модератор:&nbsp;

EOT;
			if($moderator==1){
				$contentcenter.= '<a title="Модерация" href="'.$prefflp.'/admin/guestbook.php?moder=0&id='.$p.'"><img src="images/cb_y.png" /></a>&nbsp&nbsp</td>';
			}else{
				$contentcenter.= '<a title="Модерация" href="'.$prefflp.'/admin/guestbook.php?moder=1&id='.$p.'"><img src="images/cb_e.png" /></a>&nbsp&nbsp</td>';
			}

	    	$contentcenter .=<<<EOT
			        <td width="12%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right">от: $date</nobr></td>
			        <td width="3%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right"><a title="Редактировать" href="$prefflp/admin/guestbook.php?edit=$p"><img src="images/edit.png"></nobr></td>
			        <td width="3%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right"><a title="Удалить" href="$prefflp/admin/guestbook.php?del=$p"><img src="images/delete.png"></nobr></td>
			    </tr>
			    <tr valign="top">
			        <td rowspan="2" colspan="4" height="20"><nobr><p class="font-size: 75%; margin: 0px; text-indent: 0px">$email
			        $url</nobr></td>
			    </tr>
			    <tr>
			        <td height="10"><nop></td>
			    </tr>
			    <tr valign="top">
			        <td colspan="4"><p class="color: #989898; font-size: 80%; text-align: justify; text-indent: 20px; margin-left: 40px; margin-top: 0px">
			        $msg
			        <br>
			        $answer
			        <hr>
			        </td>
			    </tr>
			</table>
			<br>

EOT;
			}else{
	    	$contentcenter .=<<<EOT
			<table border="0" width="95%" cellpadding="0" cellspacing="0" align="center"  style="border-bottom:1px">
			    <tr bgcolor="#F4F4F4">
			        <td rowspan="1" width="62%" height="20"><nobr><p class="font-size: 75%; margin: 0px; text-indent: 0px">&nbsp;&nbsp;<b>$name</b>&nbsp;[<a href="$prefflp/admin/banip.php?add=$ip" title="Бан по ip адресу">$ip</a>]&nbsp;$city</nobr>
			        </p></td>
			        <td width="20%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right">Модератор:&nbsp;
EOT;
			if($moderator==1){
				$contentcenter.= '<a title="Модерация" href="'.$prefflp.'/admin/guestbook.php?moder=0&id='.$p.'"><img src="images/cb_y.png" /></a>&nbsp&nbsp</td>';
			}else{
				$contentcenter.= '<a title="Модерация" href="'.$prefflp.'/admin/guestbook.php?moder=1&id='.$p.'"><img src="images/cb_e.png" /></a>&nbsp&nbsp</td>';
			}

	    	$contentcenter .=<<<EOT
			        <td width="12%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right">от: $date</nobr></td>
			        <td width="3%" valign="bottom" align="right" >&nbsp;</td>
			        <td width="3%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right"><a title="Удалить" href="$prefflp/admin/guestbook.php?del=$p"><img src="images/delete.png"></nobr></td>
			    </tr>
			    <tr valign="top">
			        <td rowspan="2" colspan="4" height="20"><nobr><p class="font-size: 75%; margin: 0px; text-indent: 0px">$email
			        $url</nobr></td>
			    </tr>
			    <tr>
			        <td height="10"><nop></td>
			    </tr>
			    <tr valign="top">
			        <td colspan="4"><p class="color: #989898; font-size: 80%; text-align: justify; text-indent: 20px; margin-left: 40px; margin-top: 0px">
						<form action="guestbook.php?edit=$edit" method="post" name="my_form">
						Сообщение:
			        	<textarea  id="editorm" name="mess" cols=60 rows=4>$msg</textarea>
			        	Ответ администратора:
			        	<textarea  id="editorh" name="answer" cols=60 rows=4>$answer</textarea>
	        			<div class="submit"><input type="submit" class="submit-button" name="doing" value="Записать" /></div><br />
				        <hr>
				        </form>
			        </td>
			    </tr>
			</table>
			<br>

EOT;
			}

		}
	}else{
	    $count = count($gbs);
	    $contentcenter.="<div class=\"message_warn\">При нажатии на ссылку (кнопку) для удаления,  запись будет сразу же удалена без подтверждения!</div><br />Всего записей: $count<br>\n";
		$count1 = $count;
		if ($start <= 0) {$start = 0; $count=min($count,$pnumber);}
		for ($i = $start; $i < min($count,$start+$pnumber); $i++){
			$data=unserialize($gbs[$i]);
			if ($data=="") {continue;}
	 	  	$date = $data['time'];
		    $date = date("<b>d-m-Y</b> H:i",$date);
	    	$name = $data['name'];
	    	$city = $data['city'];
		    $city = !empty($city)?"($city)":"";
		    $email = $data['email'];
		    $email = !empty($email)?"e-mail: <a href=mailto:$email>$email</a>&nbsp;&nbsp;":"";
	    	$url = $data['url'];
			$url = !empty($url)?"www: <a href='$url'>$url</a>":"";
			$msg = $data['mess'];
	    	$answer = $data['answer'];
			$answer = !empty($answer)?"<p class='color: #4795F3; font-size: 80%; text-align: justify; margin-top: 10px'><b>Аdmin:</b>&nbsp;$answer</p>":"";
	    	$moderator = (int)$data['moderator'];
	    	$ip = $data['ip'];
			$p=$i+$start;$p++;
	    	$contentcenter .=<<<EOT
			<table border="0" width="95%" cellpadding="0" cellspacing="0" align="center"  style="border-bottom:1px">
			    <tr bgcolor="#F4F4F4">
			        <td rowspan="1" width="62%" height="20"><nobr><p class="font-size: 75%; margin: 0px; text-indent: 0px">&nbsp;&nbsp;<b>$name</b>&nbsp;[<a href="$prefflp/admin/banip.php?add=$ip" title="Бан по ip адресу">$ip</a>]&nbsp;$city</nobr>
			        </p></td>
			        <td width="20%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right">Модератор:&nbsp;
EOT;
			if($moderator==1){
				$contentcenter.= '<a title="Модерация" href="'.$prefflp.'/admin/guestbook.php?moder=0&id='.$p.'"><img src="images/cb_y.png" /></a>&nbsp&nbsp</td>';
			}else{
				$contentcenter.= '<a title="Модерация" href="'.$prefflp.'/admin/guestbook.php?moder=1&id='.$p.'"><img src="images/cb_e.png" /></a>&nbsp&nbsp</td>';
			}

	    	$contentcenter .=<<<EOT
			        </nobr></td>
			        <td width="12%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right">от: $date</nobr></td>
			        <td width="3%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right"><a title="Редактировать" href="$prefflp/admin/guestbook.php?edit=$p"><img src="images/edit.png"></nobr></td>
			        <td width="3%" valign="bottom" align="right" ><nobr><p class="font-size: 60%; margin:0px; text-align: right"><a title="Удалить" href="$prefflp/admin/guestbook.php?del=$p"><img src="images/delete.png"></nobr></td>
			    </tr>
			    <tr valign="top">
			        <td rowspan="2" colspan="4" height="20"><nobr><p class="font-size: 75%; margin: 0px; text-indent: 0px">$email
			        $url</nobr></td>
			    </tr>
			    <tr>
			        <td height="10"><nop></td>
			    </tr>
			    <tr valign="top">
			        <td colspan="4"><p class="color: #989898; font-size: 80%; text-align: justify; text-indent: 20px; margin-left: 40px; margin-top: 0px">
			        $msg
			        <br>
			        $answer
			        <hr>
			        </td>
			    </tr>
			</table>
			<br>

EOT;
		}
	}
		$contentcenter.="<center>";
		$count =min($count, $start + $pnumber);
		if ($start != 0)
		{
		    $contentcenter.= " <A href=/admin/guestbook.php?start=".($start - $pnumber).">Следующие</A> || ";
		}else{
		    $contentcenter.= " Следующие || ";
		}
		if ($count1 > $start + $pnumber)
		{
		    $contentcenter.= " <A href=/admin/guestbook.php?start=".($start + $pnumber).">Предыдущие</A> <br /><br />\n";
		}else
		{
		    $contentcenter.= " Предыдущие<br /><br />\n";
		}

}
include $localpath.'/admin/admintemplate.php';

