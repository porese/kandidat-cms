<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf8" />
<title><?php echo $sitename;?> - <?php echo $sitetitle;?></title>
<meta name="keywords" content="<?php echo $metakeywords;?>" />
<meta name="description" content="<?php echo $metadescription;?>" />
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="/templates/solone/style.css" />
</head>
<body>
<div id="container">
	<div id="header">
		<h1><?php echo $sitename;?></h1>
		<div id="topmenu">
		<ul>
			<li><a href="/">Главная</a></li>
			<li><a href="/news/">Новости</a></li>
			<li><a href="/stati/">Статьи</a></li>
			<li><a href="/firma.html">О фирме</a></li>
			<li><a href="/servis.html">Сервис</a></li>
			<li><a href="/files/">Файлы</a></li>
			<li><a href="/contact.html">Контакт</a></li>
			<li><a href="/karta_sayta.html">Карта сайта</a></li>
			<li><a href="/rss.html" target="_form"><img src="/templates/solone/images/rss.gif" border="0" /></a></li>
		</ul>
		</div>
	</div>
	<div id="contentcontainer">
		<div id="content">
		<div class="post">
			<h2><?php echo $sitetitle;?></h2>
			<p class="posted"><?php echo $lnav;?></p>
			<div class="entry">
			<?echo $contentcenter;?>
			</div>
		</div>
		<div class="postpagesnav">
		рекламный текст или код банера
		</div>
		</div>

		<div id="sidebar">
		<ul>
		<li><h2>Последние новости</h2>
		<ul>
			<? include $localpath.'/mycode/lastnews.php'; ?>
		</ul>
		</li>
		<li><h2>Навигация</h2>
		<ul>
			<? include $localpath.'/engine/menu.php'; ?>
		</ul>
		</li>
		<li><h2>Случайное фото</h2>
		<ul>
			<? include $localpath.'/mycode/photorotate.php'; ?>
		</ul>
		</li>
		<li><h2>облако меток</h2>
		<ul>
			<? include $localpath.'/mycode/cloudtags.php'; ?>
		</ul>
		</li>
		<li><h2>Афоризмы</h2>
		<ul>
			<? include $localpath.'/mycode/aforizm.php'; ?>
		</ul>
		</li>
		<li><h2>Спонсоры</h2>
		<ul>
			<li><a href=https://rbkmoney.ru/ target=_blank onClick=this.href='https://rbkmoney.ru/Register.aspx?partner=7ca2ba4a-db4e-4e3f-a142-a01de67b3b4d'>RBK Money</a></li>
			<li><a href=http://www.rbcforex.ru/ target=_blank onClick=this.href='http://www.rbcforex.ru/?id=20884'>RBC forex</a></li>
			<li><a href=http://forexhunt.org/ target=_blank onClick=this.href='http://forexhunt.org/?a=hifx'>ForexHunt</a></li>
			<li><a href=http://www.fxopen.com/ target=_blank onClick=this.href='http://fxopen.com?agent=215774'>FXopen</a></li>
		</ul>
		</li>
		</ul>
		</div>
	</div>

<div id="content2">
	<p>© 2010 <?php echo $sitename;?>
	<br />Powered by: <a href="http://www.kan-studio.ru">Kandidat CMS</a>, design: <a href="http://www.solitaireplay.net/">Solitaire</a></p>
</div>

</div>
</body>
</html>
