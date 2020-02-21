<?php
if(file_exists('../admin/install/index.php')){
  	Header('Location: ../admin/install/index.php');
    exit();
}
define( '_JEXEC', 1 );
if(!isset($_SESSION))session_start();

if($_SESSION['id'] && !isset($_COOKIE['tzRemember']) && !$_SESSION['rememberMe']){
	session_unset ();
	session_destroy();
}

if($_SESSION['id']!=session_id()){
  	Header('Location: ../admin/login.php');
	session_destroy();
    exit();
}
include_once $path.'/conf/config.php';
include_once $path.'/code/functions.php';
?>
