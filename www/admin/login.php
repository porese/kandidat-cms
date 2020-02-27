<?php
$islogin=false;
foreach ($_POST as $secvalue) {
   if ((preg_match ('/<[^>]*script *\'?[^>]*>/iu', $secvalue)) ||
   (preg_match ('/<[^>]*style*\'?[^>]*>/iu', $secvalue))) {
      die('BAD YOUR CODE');
      exit;
   }
}
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
$adminsset=$path.'/conf/users.php';
//session_name('tzLogin');
// Cookies хранятся 1 неделю
session_set_cookie_params(1*7*24*60*60);
session_start();
$info="&nbsp;";
if(isset($_POST['sub'])){
    $_POST['name']=trim($_POST['name']);
    $_POST['pass']=trim($_POST['pass']);
    if($_POST['name']!="" && $_POST['pass']!=""){
		$file = file($adminsset);
		$szfile = sizeof($file);
		for($i=0;$i<$szfile;$i++){
			$q = explode('::', $file[$i]);
			if($q[0]==$_POST['name']){
				if(1>$q[2])break;
				$corpass=md5($_POST['name'].$_POST['pass']);
				if($corpass==$q[1]){
					$islogin=true;
					$_SESSION['id']=session_id();
					$_SESSION['login']=$q[0];
					$_SESSION['param']=$q;
					$_SESSION['name']=$q[4];
					$_SESSION['rememberMe'] = $_POST['rememberMe'];
					// Создаем tzRemember cookie
					setcookie('tzRemember',$_POST['rememberMe']);
	              	Header('Location: ../admin/index.php');
    	          	exit();
				}else break;
			}
		}
		$info='<font color=red>Не правильно!</font>';
	}else $info='<font color=red>Не правильно!</font>';
}
if((isset($_POST['logout'])) or (isset($_GET['logout']))){
	setcookie('tzRemember',0);
	session_unset ();
	session_destroy ();
	$info='<font color=red>Вы вышли!</font>';
}
?>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf8" />
<head>
  <title>Авторизация</title>
  <style>
 	#enter{
		border-style:solid;
		border-width: 1px;
		border-color:#D2D2D2;
		background-color:#F0F0F0;
		padding:20px;
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		font-size:10pt;
		color:#676767;
		width:280px;
		height:200px;
    }
    #button{
		font-family: Verdana, Helvetica, sans-serif;
		font-size: 12px;
		background-color: #C0C0C0;
		color: #ffffff;
		border: 3px;
		padding: 2px;
		font-weight:700;
    }
    input {font-size:12px; margin: 0px 4px; padding:4px;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px; border:1px solid #9a9a9a;}
  </style>
</head>
<body style="background-color:#EBEBEB">
	<table height=100% width=100%>
		<tr><td align=center valign=center>
		<table ><tr><td id=enter><img style="PADDING-BOTTOM: 0.6em; PADDING-LEFT: 0em; PADDING-RIGHT: 0.6em; PADDING-TOP: 0.6em" alt="" align=left src="../media/admin_b.png" /><h3>Авторизация</h3>
			<?php echo $info."<br /><br />";?>
			<form action="login.php" method="post">
				Логин:&nbsp;&nbsp; <input name="name" type="text" /><br />
				Пароль: <input name="pass" type="password" /><br/ ><br />
				<label>Запомнить меня<input name="rememberMe" type="checkbox" checked="checked" value="1" /></label><br /><br />
				<center><input type="submit" value="Войти" name=sub id=button></center>
			</form>
			</td></tr></table>
	</td></tr></table>
</body>
</html>
