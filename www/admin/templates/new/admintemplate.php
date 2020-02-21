<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html;charset=utf8" />
<title><?php echo $sitetitle;?></title>
<meta name="description" content="Kan-Sdudio" />
<link href="templates/<? echo $admintemplate;?>/css/default.css" type="text/css" rel="stylesheet" />
<script src="<?php echo $prefflp;?>/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $prefflp;?>/js/jquery-ui.custom.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $prefflp;?>/js/jquery.ui.datepicker-ru.js"></script>
<link rel="stylesheet" href="<?php echo $prefflp;?>/js/ui-themes/smoothness/jquery-ui.custom.css" type="text/css" media="screen" charset="utf-8">
<script src="elrte/js/elrte.min.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="elrte/css/elrte.full.css" type="text/css" media="screen" charset="utf-8">
<script src="elrte/js/elfinder.min.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="elrte/css/elfinder.css" type="text/css" media="screen" charset="utf-8">
<script src="elrte/js/i18n/elrte.ru.js" type="text/javascript" charset="utf-8"></script>
<script src="elrte/js/i18n/elfinder.ru.js" type="text/javascript" charset="utf-8"></script>
<script src="elrte/js/admfunctions.js" type="text/javascript" charset="utf-8"></script>
</head>
<? flush();?>
<body id="wrapper">
    <div class="bd-blhead">
      <div id="middleside">
       <ul id="logo">
        <img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/logo.png">
       </ul>
       </ul>
      </div>
    </div>
    <ul class="btop">
     <li class="rndleft"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/topl.png" /></li>
     <li class="rndright"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/topr.png" /></li>
     <li class="cntr"></li>
    </ul>
    <div class="bd-block" id="content">
      <div id="hmenu">
       <ul>
        <li id="rndl"></li>
         <?php include $localpath .'/admin/templates/'.$admintemplate.'/upmenu.php'; ?>
         <li id="rndr"></li>
        </li>
       </ul>
      </div>
      <div id="sidebl">

      <p class="bmenu-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenur.png" /></p>
      <div id="sblock_m" class="accordian">
        <h4>User: <?=getuser();?></h4>
      </div>
      <p class="bmenud-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenurd.png" /></p>
      <br>
      <p class="bmenu-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenur.png" /></p>
      <div id="sblock_m" class="accordian">
        <ul>
        <h1>Навигация</h1>
		<?php include $localpath .'/admin/templates/'.$admintemplate.'/menu.php'; ?>
      </div>
      <p class="bmenud-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenurd.png" /></p>
      <br>
      <p class="bmenu-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenur.png" /></p>
      <div id="sblock_m" class="accordian">
        <ul>
        <h1>Новости</h1>
		<?php include $localpath .'/admin/templates/'.$admintemplate.'/newsmenu.php'; ?>
      </div>
      <p class="bmenud-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenurd.png" /></p>
      <br>
      <p class="bmenu-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenur.png" /></p>
      <div id="sblock_m" class="accordian">
        <ul>
        <h1>Фото галерея</h1>
		<?php include $localpath .'/admin/templates/'.$admintemplate.'/photomenu.php'; ?>
      </div>
      <p class="bmenud-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenurd.png" /></p>
      <br>
      <p class="bmenu-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenur.png" /></p>
      <div id="sblock_m" class="accordian">
        <ul>
        <h1>Гостевая книга</h1>
		<?php include $localpath .'/admin/templates/'.$admintemplate.'/gbmenu.php'; ?>
      </div>
      <p class="bmenud-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenurd.png" /></p>
      <br>
      <p class="bmenu-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenur.png" /></p>
      <div id="sblock_m" class="accordian">
        <ul>
        <h1>Пользователи</h1>
		<?php include $localpath .'/admin/templates/'.$admintemplate.'/usersmenu.php'; ?>
      </div>
      <p class="bmenud-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenurd.png" /></p>
      <br>
      <p class="bmenu-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenur.png" /></p>
      <div id="sblock_m" class="accordian">
        <ul>
        <h1>Сервис</h1>
		<?php include $localpath .'/admin/templates/'.$admintemplate.'/servicemenu.php'; ?>
      </div>
      <p class="bmenud-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenurd.png" /></p>
      <br>
      <p class="bmenu-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenur.png" /></p>
      <div id="sblock_m" class="accordian">
        <ul>
        <h1>Информация</h1>
		<?php include $localpath .'/admin/templates/'.$admintemplate.'/info.php'; ?>
      </div>
      <p class="bmenud-r"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bmenurd.png" /></p>
    </div>
    <div id="news">
       <div style="margin-bottom:10px;">
		 <h1><?=$sitetitle; ?></h1>
		 <br>
  		 <p><?php echo $contentcenter; ?></p>
	   </div>
	</div>
	</div>
    <ul class="bbot">
     <li class="rleft"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/bl.png" /></li>
     <li class="rright"><img src="<?php echo $prefflp;?>/admin/templates/<? echo $admintemplate;?>/images/br.png" /></li>
     <li class="centr"></li>
    </ul>
    <div id="footer">
    	<div style="float:left">
    	<ul id="footb">
      	<li id="count"><br></li>
		<li id="copyr">Работает под управлением <a href="http://www.Kan-Studio.ru">Kandidat CMS <? echo VERSION;?></a>
        <br>Дизайн шаблона by Dysha
     	</ul>
     	</div>
    	<div style="float:right">
     	<br /><? echo $choisetemplate;?>
     	</div>
    </div>
	</body>
</html>
