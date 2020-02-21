<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf8" />
<title><?php echo $sitename;?> - <?php echo $sitetitle;?></title>
<meta name="keywords" content="<?php echo $metakeywords;?>" />
<meta name="description" content="<?php echo $metadescription;?>" />
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="/templates/extended/default.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1><?php echo $sitename;?></h1>
		<p>ваш слоган сайта</p>
	</div>
</div>
<!-- end header -->
<!-- start menu -->
<div id="menu">
	<ul>
		<li class="current_page_item"><a href="/">Главная</a></li>
		<li class="current_page_item"><a href="/news/">Новости</a></li>
		<li class="current_page_item"><a href="/stati/">Статьи</a></li>
		<li class="current_page_item"><a href="/firma.html">О фирме</a></li>
		<li class="current_page_item"><a href="/servis.html">Сервис</a></li>
		<li class="current_page_item"><a href="/files/">Файлы</a></li>
		<li class="current_page_item"><a href="/contact.html">Контакт</a></li>
		<li class="current_page_item"><a href="/karta_sayta.html">Карта сайта</a></li>
<li class="current_page_item"><a href="/rss.html" target="_form"><img src="/templates/extended/images/rss.gif" border="0" /></a></li>

	</ul>
</div>
<!-- end menu -->
<!-- start page -->
<div id="page">
	<!-- start content -->
	<div id="content">
		<h1 class="pagetitle"><?php echo $sitetitle;?></h1><div align=right><?php echo $lnav;?>   </div>
		<div class="post">
			<div class="entry"><?echo $contentcenter;?>
			</div>
			<p class="meta">     </p>
		</div>
	</div>
	<!-- end content -->
	<!-- start sidebar -->
	<div id="sidebar">
		<ul>
			<li>
				<h2>Навигация</h2>
				<ul><? include $localpath .'/engine/menu.php'; ?>
				</ul>
			</li>
			<li>
				<h2>Последние новости</h2>
				<ul>
				<? include $localpath .'/mycode/lastnews.php'; ?>
				</ul>
			</li>
			<li>
				<h2>Случайное фото</h2>
				<ul>
				<? include $localpath .'/mycode/photorotate.php'; ?>
				</ul>
			</li>
			<li>
				<h2>Облако меток</h2>
				<ul>
				<? include $localpath .'/mycode/cloudtags.php'; ?>
				</ul>
			</li>
			<li>
				<h2>Афоризм</h2>
				<ul>
				<? include $localpath .'/mycode/aforizm.php'; ?>
				</ul>
			</li>
			<li>
				<h2>Наши спонсоры</h2>
				<ul>
					<li><a href=https://rbkmoney.ru/ target=_blank onClick=this.href='https://rbkmoney.ru/Register.aspx?partner=7ca2ba4a-db4e-4e3f-a142-a01de67b3b4d'>RBK Money</a></li>
					<li><a href=http://www.rbcforex.ru/ target=_blank onClick=this.href='http://www.rbcforex.ru/?id=20884'>RBC forex</a></li>
					<li><a href=http://forexhunt.org/ target=_blank onClick=this.href='http://forexhunt.org/?a=hifx'>ForexHunt</a></li>
					<li><a href=http://www.fxopen.com/ target=_blank onClick=this.href='http://fxopen.com?agent=215774'>FXopen</a></li>
				</ul>
			</li>
		</ul>
	</div>
	<!-- end sidebar -->
	<div style="clear: both;"> </div>
</div>
<!-- end page -->
<div id="footer">
	<p>© 2010 <?php echo $sitename;?><br />
Powered by : <a href="http://www.kan-studio.ru">Kandidat CMS</a><br />
Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a></p>
</div>
</body>
</html>

