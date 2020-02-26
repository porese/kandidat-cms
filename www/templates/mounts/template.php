<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="index, follow" />
<meta name="keywords" content="<?php echo $metakeywords;?>" />
<meta name="description" content="<?php echo $metadescription;?>" />
<title><?php echo $sitetitle;?> - <?php echo $sitename;?></title>
<style type="text/css">
	@import url(<?php echo $prefflp; ?>/templates/<?php echo $template;?>/style.css);
</style>

</head>

<body>

<div id="menu-top">
	<ul>
	  <?=makeupmenu('mselected');?>
	</ul>
</div><!--menu-top-->

<!-- mejobloggs: sorry about all these wrappers. It is a lot of structural markup .
		If you know how to do this with less structural markup, contact my throught my
		OWD user page: http://www.openwebdesign.org/userinfo.phtml?user=mejobloggs -->
<div id="wrapper-header">
<div id="header">
<div id="wrapper-header2">
<div id="wrapper-header3">
	<div id="nav-top"><?php echo $lnav;?></div><!--nav-top-->
	<h1><?php echo $sitename;?></h1>
</div><!--wrapper-header3-->
</div><!--wrapper-header2-->
</div><!--header-->
</div><!--wrapper-header-->

<div id="wrapper-content">
	<div id="wrapper-menu-page">
	<div id="menu-page">
		<h3>Навигация</h3>
		<ul>
			<?php echo makemenu();?>
		</ul>

		<h3>Облако меток</h3>
		<ul>
			<?php include("mycode/cloudtags.php"); ?>
		</ul>

		<h3>Новости</h3>
		<ul id="lastnews">
			<?php include $localpath .'/mycode/lastnews.php'; ?>
		</ul>

		<h3>Случайное фото</h3>
		<ul>
			<li><?php include("mycode/photorotate.php"); ?></li>
		</ul>

		<h3>Афоризм</h3>
		<ul>
			<li><?php include("mycode/aforizm.php"); ?></li>
		</ul>

		<ul>
			<li><?php include("engine/counter.php"); ?></li>
		</ul>

	</div><!--menu-page-->
	</div><!--wrapper-menu-page-->

	<div id="content">
		<h2><?php echo $sitetitle;?></h2>
		<?php echo $contentcenter;?>
  </div><!--content-->
</div><!--wrapper-content-->

<div id="wrapper-footer">
	<div id="footer">
		©2010 <?php echo $sitename;?>. Powered by: <a href="http://www.kan-studio.ru">Kandidat <abbr>CMS</abbr> by it</a> <?php echo printbuildtime(3);?> | Webmaster <a href="http://forum.kan-studio.ru/profile.php?id=117" target="_blank">it aka porese</a> | <a href="http://studio7designs.com/">Web Design Victoira BC by Studio7designs.com</a>
		<p id="links">
			<a href="<?php echo cc_link("/rss.html"); ?>" class="rss" title="Subscribe to entries web feed"  target="_blank">Entries (<abbr>RSS</abbr>)</a>
			   
			<a href="http://validator.w3.org/check/referer" class="xhtml" title="This page validates as XHTML">Valid <abbr>XHTML</abbr></a>
			   
			<a href="http://jigsaw.w3.org/css-validator/check/referer" class="css" title="This page validates as CSS">Valid <abbr>CSS</abbr></a>
		</p>
	</div>
</div><!--wrapper-footer-->
</body>
</html>
