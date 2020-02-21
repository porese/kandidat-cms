<!DOCTYPE HTML>
<!--

Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Title      : Concrete
Version    : 1.0
Released   : 20080825
Description: A Web 2.0 design with fluid width suitable for blogs and small websites.

-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="robots" content="index, follow" />
<meta name="keywords" content="<?php echo $metakeywords;?>" />
<meta name="description" content="<?php echo $metadescription;?>" />
<title><?php echo $sitetitle;?> - <?php echo $sitename;?></title>
<link href="<?php echo $prefflp; ?>/templates/concrete/defaultLeft.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<!-- start header -->
<div id="header">
	<div id="company_logo"></div>
	<div id="logo">
		<h1><a href="/"><?php echo $sitename;?></a></h1>
		<p>слоган сайта</p>
	</div>
	<div id="menu">
		<ul>
			<li class="current_page_item"><a href="/">Главная</a></li>
			<li><a href="<?=cc_link("/news/");?>">Новости</a></li>
			<li><a href="<?=cc_link("/photo.html");?>">Фото</a></li>
			<li><a href="<?=cc_link("/firma.html");?>">О фирме</a></li>
			<li class="last"><a href="<?=cc_link("/contact.html");?>">Контакт</a></li>
		</ul>
	</div>
</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start sidebar -->
	<div id="sidebar">
		<ul>
			<li id="search" style="background: none;">
				<form id="searchform" method="get" action="/index.php?whatpage=search">
					<div>
						<input type="text" name="text" id="s" size="15" />
						<br />
						<input type="submit" value="Поиск" />
					</div>
				</form>
			</li>
			<li id="categories">
				<h2>Навигация</h2>
				<ul class="mmenu">
					<? include $localpath.'/engine/menu.php'; ?>
				</ul>
			</li>
			<li>
				<h2>Новости</h2>
				<ul>
					<? include $localpath .'/mycode/lastnews.php'; ?>
				</ul>
			</li>
			<li>
				<h2>Случайное фото</h2>
				<ul>
					<li id="nobackground"><? include("mycode/photorotate.php"); ?></li>
				</ul>
			</li>
			<li>
				<h2>Афоризм</h2>
				<ul>
					<li><p class="entry"><? include("mycode/aforizm.php"); ?></p></li>
				</ul>
			</li>
			<li>
				<h2>Счетчики</h2>
				<ul>
					<li id="nobackground"><p class="entry"><? include("engine/counter.php"); ?></p></li>
				</ul>
			</li>
		</ul>
	</div>
	<!-- end sidebar -->
	<!-- start content -->
	<div id="content">
      <div id="navigation-head"><?php echo $lnav;?></div>
		<div class="post">
			<div class="title">
      			<h2><?php echo $sitetitle;?></h2>
			</div>
      			<?echo $contentcenter;?>
		</div>
	</div>
	<!-- end content -->
	<br style="clear: both;" />
</div>
<!-- end page -->
<!-- start footer -->
<div id="footer">
	<p class="links">
		<a href="<?php echo cc_link("/rss.html"); ?>" class="rss" title="Subscribe to entries web feed"  target="_blank">Entries (<abbr>RSS</abbr>)</a>
		   
		<a href="http://validator.w3.org/check/referer" class="xhtml" title="This page validates as XHTML">Valid <abbr>XHTML</abbr></a>
		   
		<a href="http://jigsaw.w3.org/css-validator/check/referer" class="css" title="This page validates as CSS">Valid <abbr>CSS</abbr></a>
	</p>
	<p class="legal">
        © 2010 <?php echo $sitename;?>. Powered by: <a href="http://www.kan-studio.ru">Kandidat <abbr>CMS</abbr> by it</a> <?php echo printbuildtime();?>. Webmaster <a href="http://forum.kan-studio.ru/profile.php?id=117" target="_blank">it aka porese</a><br/>
		©2007 Concrete. All Rights Reserved.
		  •  
		Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>
		  •  
		Icons by <a href="http://famfamfam.com/">FAMFAMFAM</a>. </p>
</div>
<!-- end footer -->
</body>
</html>
