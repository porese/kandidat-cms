<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');
$sitetitle='Настройки пользователей';
$adminsset=CONF.'users.php';
$contentcenter ='';

$_POST=stripinarr($_POST);
$error='';
if(isset($_POST['submit'])){
	extract($_POST, EXTR_OVERWRITE);
	$file = file($adminsset);
	$szfile = sizeof($file);
	if(isset($_GET['add'])){
		for($i=0;$i<$szfile;$i++){
			$q = explode('::', $file[$i]);
			if($q[0]==$login){
				$error.='login <b>'.$login.'</b> уже занят.<br />';
				break;
			}
		}
		if(''==$login){
			$error.='Введите login.<br />';
		}
		if(preg_match('/[^0-9a-zа-я_]/isu',$login)){
			$error.='Введен неправильный login.<br />';
		}
		if(''==$error){
			$line=$login.'::'.md5($login.$password).'::'.(int)$mode.'::'.$email.'::';
			$line.=$name.'::'.$sity.'::'.$site.'::'.$icq.'::'.$jabber.'::'.time()."::\n";
			save($adminsset,$line);
		    header('LOCATION:users.php');
			exit;
		}
	}elseif(isset($_GET['edit'])){
		for($i=0;$i<$szfile;$i++){
			$q = explode('::', $file[$i]);
			if($q[0]==trim($_POST['login'])){
				if(trim($password)=='')$password=$q[1];else $password=md5($login.$password);
				$line=$login.'::'.$password.'::'.$mode.'::'.$email.'::'.$name.'::';
				$line.=$sity.'::'.$site.'::'.$icq.'::'.$jabber.'::'.$q[9]."::\n";
				$file[$i]=$line;
				break;
			}
		}
		savearray($adminsset,$file,'w','');
	    header('LOCATION:users.php');
		exit;
	}
}

if(isset($_GET['add'])||isset($_GET['edit'])||isset($_GET['view'])){
	if(isset($_GET['add'])){
		$mode_content=0;
		$login='';
		$password='';
		$url='?add=1';
		$contentcenter.='<h3>Добавление нового пользователя</h3></h3>
			<form action="'.$url.'" method="post" name="my_form">';
	}elseif(isset($_GET['edit'])){
		$login=trim($_GET['edit']);
		$url='?edit='.$login;
		$item='';
	    $disable_login='readonly="readonly" ';
	    $disable_all='';
		$file = file($adminsset);
		$count = count($file);
		for($i=0;$i<$count;$i++){
			$q = explode('::', $file[$i]);
			if($q[0]==$login){
				$item = $q;
				break;
			}
		}
		if(!is_array($item))header('LOCATION:users.php');
		list($login,,$mode_content,$email,$name,$siti,$site,$icq,$jabber)=$item;
		$contentcenter.='<h3>Редактирование пользователя</h3></h3>
			<form action="'.$url.'" method="post" name="my_form">';
	}else{
		$login=trim($_GET['view']);
		$url='?edit=1';
		$item='';
	    $disable_login='disabled="disabled" ';
	    $disable_all='disabled="disabled" ';
		$file = file($adminsset);
		$count = count($file);
		for($i=0;$i<$count;$i++){
			$q = explode('::', $file[$i]);
			if($q[0]==$login){
				$item = $q;
				break;
			}
		}
		if(!is_array($item))header('LOCATION:users.php');
		list($login,,$mode_content,$email,$name,$siti,$site,$icq,$jabber)=$item;
		$contentcenter.='<h3>Просмотр пользователя</h3></h3>';

	}
	$mode='<select name="mode" id="mode" '.$disable_all.'>';
	$mode.='<option '.(($mode_content==0)?'selected="selected"':'').' value="0">Пользователь</option>';
	$mode.='<option '.(($mode_content==1)?'selected="selected"':'').' value="1">Корректор</option>';
	$mode.='<option '.(($mode_content==2)?'selected="selected"':'').' value="2">Редактор</option>';
	$mode.='<option '.(($mode_content==3)?'selected="selected"':'').' value="3">Администратор</option>';
	$mode.='</select>';

	$contentcenter.='
		<label>Логин (login) &nbsp;&nbsp;
		<input class="settings" id="login"  name="login" type="text" '.$disable_login.'value="'.$login.'" /></label><br /><br />
		<label title="Ввод пароля :: Рекомендуется использовать пароль длинной не менее 8-ми символов, среди которых желательно одновременное наличие цифр, букв и спецсимволов. ">Пароль&nbsp;&nbsp;
		<input class="settings" id="password" name="password" type="password" '.$disable_all.' /></label><br /><br />
		<label>Права &nbsp;&nbsp;'.$mode.'</label><br /><br />
		<label>E-mail &nbsp;&nbsp;
		<input class="settings" id="email"  name="email" type="text" value="'.$email.'" '.$disable_all.' /></label><br /><br />
		<label>Полное имя &nbsp;&nbsp;
		<input class="settings" size="50" id="name"  name="name" type="text" value="'.$name.'" '.$disable_all.' /></label><br /><br />
		<label>Город &nbsp;&nbsp;
		<input class="settings" id="sity"  name="sity" type="text" value="'.$siti.'" '.$disable_all.' /></label><br /><br />
		<label>Сайт &nbsp;&nbsp;
		<input class="settings" id="site"  name="site" type="text" value="'.$site.'" '.$disable_all.' /></label><br /><br />
		<label>ICQ &nbsp;&nbsp;
		<input class="settings" id="icq"  name="icq" type="text" value="'.$icq.'" '.$disable_all.' /></label><br /><br />
		<label>jabber &nbsp;&nbsp;
		<input class="settings" id="jabber"  name="jabber" type="text" value="'.$jabber.'" '.$disable_all.' /></label><br /><br />';
	if(isset($_GET['add'])||isset($_GET['edit']))$contentcenter.='
		<br /><div class="submit"><input type="submit" class="submit-button" id="submit" name="submit" value="Записать" /></div><br />
	</form>';
	if(''!=$error)$contentcenter.='<div align="center" class="thanks_message">Обнаружены ошибки:<br />'.$error.'</div>';
	$contentcenter.='<br><br><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';

}elseif(isset($_GET['delete'])){
	$login=trim($_GET['delete']);
	if(isset($_GET['ok'])){
		$file = file($adminsset);
		$count = count($file);
		for($a=0;$a<$count;$a++){
			$q = explode('::', $file[$a]);
			if($q[0]!=$login){
				$users[] = $file[$a];
			}
		}
		savearray($adminsset, $users, 'w','');
		Header('Location: users.php');
	}else{
		@$contentcenter .=  '<h3>Удаление пользователя</h3><br />';
		$contentcenter.='<div class="message_quest">Вы хотите удалить пользователя '.$login.'</div>';
		$contentcenter.='<br /><a href="?delete='.$login.'&ok=1"">ДА</a> | ';
		$contentcenter.='<a href="users.php">НET</a>';
		$contentcenter.='<br><br><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';
	}
}else{
	$contentcenter.='<a href=?add=1>Добавить нового пользователя</a><br /><br />
	<link rel="stylesheet" href="'.$prefflp.'/css/sorttable.css" />
	<script type="text/javascript">
	$(document).ready(function(){
		/* Код выполняется после загрузки страницы */

		$(\'table.sortable tr\').click(function(e){
		    var elm = e.target||event.srcElement;
		    if(elm.tagName.toLowerCase() != \'a\')    {
	    	    return;
			}
		});
	});
	</script>
	<table border=0 cellspacing=0 cellpadding=0 width=98% id="table" class="sortable">
	<thead>
	<tr>
	<th width=20%><h3>Логин</h3></td>
	<th width=30%><h3>Имя</h3></td>
	<th width=30%><h3>E-mail</h3></td>
	<th width=15%><h3>Статус</h3></td>
	<th  class="nosort" colspan="2" width=50><h3>Действия</h3></td>
	</tr>
	</thead><tbody>'."\n";
	$file = file($adminsset);
	$count = count($file);
	for($a=0;$a<$count;$a++){$users[] = $file[$a];}
	@array_multisort($users, SORT_ASC, SORT_REGULAR);
	for($i=0;$i<count($users);$i++){
		$q = explode('::', $users[$i]);
		if ($q[2] == '0'){ $status = '<font color=gray>Пользователь</font>'; }
		if ($q[2] == '1'){ $status = '<font color=blue>Корректор</font>'; }
		if ($q[2] == '2'){ $status = '<font color=green>Редактор</font>'; }
		if ($q[2] == '3'){ $status = '<font color=red>Администратор</font>'; }
		$email='<a href="mailto:'.$q[3].'">'.$q[3].'</a>';
		$act='<td><a title="Редактровать" href="?edit='.$q[0].'"><img alt="Редактровать" src="images/edit.png" /></a></td>';
		$act.='<td><a title="Удалить" href="?delete='.$q[0].'"><img alt="Удалить" src="images/delete.png" /></a></td>';
		$contentcenter.='<tr><td><a href=?view='.$q[0].'>'.$q[0].'</a></td><td><a href=?view='.$q[0].'>'.$q[4].'</a></td><td>'.$email.'</td><td>'.$status.'</td>'.$act."</tr>\n";
	}
	$contentcenter.='</tbody></table>
		<div id="tcontrols">
			<div id="tperpage">
				<select onchange="sorter.size(this.value)">
					<option value="5">5</option>
					<option value="10">10</option>
					<option value="20" selected="selected">20</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>
				<span>'.__('Строк на странице').'</span>
			</div>
			<div id="tnavigation">
				<img src="'.$prefflp.'/images/first.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" />
				<img src="'.$prefflp.'/images/previous.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" />
				<img src="'.$prefflp.'/images/next.gif" width="16" height="16" alt="First Page" onclick="sorter.move(1)" />
				<img src="'.$prefflp.'/images/last.gif" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" />
			</div>
			<div id="ttext">'.__('Страница').'<span id="currentpage"></span> из <span id="pagelimit"></span></div>
		</div>
	<script type="text/javascript" src="'.$prefflp.'/js/sorttable.js"></script>
	<script type="text/javascript">
	  var sorter = new TINY.table.sorter("sorter");
		sorter.head = "head";
		sorter.asc = "asc";
		sorter.desc = "desc";
		sorter.even = "evenrow";
		sorter.odd = "oddrow";
		sorter.evensel = "evenselected";
		sorter.oddsel = "oddselected";
		sorter.paginate = true;
		sorter.currentid = "currentpage";
		sorter.limitid = "pagelimit";
		sorter.init("table",0);
	</script>
	';
}
include $localpath.'/admin/admintemplate.php';
?>
