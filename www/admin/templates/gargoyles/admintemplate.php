<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">

<html lang="ru">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $sitetitle;?></title>
		<meta name="description" content="Kan-Sdudio" />
		<link rel="stylesheet" type="text/css" href="templates/<?php echo $admintemplate;?>/st_gargoyles_v4.css" media="screen">
		<style type="text/css"><!--
			@import "templates/<?php echo $admintemplate;?>/st_gargoyles.css";
			@import "templates/<?php echo $admintemplate;?>/st_gargoyles_theme.css";
			@import "<?php echo $prefflp;?>/css/qTip.css";
		--></style>
		<script type="text/javascript" src="/etribou/layouts/javascript/ruthsarian_utilities.js"></script>
		<script type="text/javascript">
		<!--
			if ( ( typeof( NN_reloadPage ) ).toLowerCase() != 'undefined' ) { NN_reloadPage( true ); }
			if ( ( typeof( opacity_init  ) ).toLowerCase() != 'undefined' ) { opacity_init(); }
			if ( ( typeof( set_min_width ) ).toLowerCase() != 'undefined' ) { set_min_width( 'pageWrapper' , 600 , true ); }
		//-->
		</script>

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
		<script type="text/JavaScript" src="<?php echo $prefflp;?>/js/qTip.js" charset="utf-8"></script>

	</head>
	<body>
		<div id="pageWrapper">
			<div id="outerColumnContainer">
				<div id="innerColumnContainer">
					<div id="middleColumn">
						<div id="masthead" class="inside">
							<h1>Kandidat-CMS</h1>
							<h2>Панель управления сайтом</h2>
							<hr class="hide">
						</div>
						<div id="content">
							<div class="hnav bottomBorderOnly">
								<ul class="nshnav"
									><li
										><a href="../" target="_blank">На сайт</a
										><span class="divider"> : </span
									></li
									><li
										><a href="elfinder.php">Файлы</a
										><span class="divider"> : </span
									></li
									><li
										><a href="help.php">Помощь</a
									></li
									><li
										><a href="login.php?logout=1">Выйти</a
										><span class="divider"> : </span
									></li
								></ul>
								<hr class="hide">
							</div>
							<h3 class="pageTitle"><?php echo $sitetitle;?></h3>
							<div id="contentColumnContainer">
								<div id="innerContent">
									<div class="inside">
								  		 <p><?php echo $contentcenter; ?></p>
									</div>
									<hr class="hide">
								</div>

								<div class="clear"></div>

							</div>
						</div>
					</div>
					<div id="leftColumn">
						<div class="inside">

							<div id="cornerLogo">
								<div class="placeHolder">
									<p>
										<img src="<?php echo $prefflp;?>/admin/templates/<?php echo $admintemplate;?>/logo.gif">
										User: <?php getuser();?>
									</p>
								</div>
							</div>

							<div id="mainMenu" class="leftBlock">
								<div class="vnav">
									<ul
										><li
											><a href="http://www.Kan-Studio.ru" target="_form">На сайт Kan-Studio</a
										></li
										><li
											><a href="http://forum.Kan-Studio.ru" target="_form">На форум Kan-Studio</a
										></li
										><li
											><a href="http://forum.kan-studio.ru/viewtopic.php?id=348" target="_form">В тему by it</a
										></li
									></ul>
								</div>
							</div>

							<div class="leftBlock">
								<h3>Навигация</h3>
								<div class="vnav">
									<ul>
										<li><a href="index.php">Начало</a></li>
										<li><a href="settings.php">Настройки</a></li>
										<li><a href="menueditor.php">Редакция меню</a></li>
										<li><a href="templateeditor.php">Редакция шаблонов</a></li>
										<li><a href="elfinder.php">Файловый менеджер</a></li>
										<li><a href="counterarea.php">Блок counter</a></li>
										<li><a href="feedback.php">Обратная связь</a></li>
										<li><a href="cloudtags.php">Облако меток</a></li>
									</ul>
								</div>
							</div>

							<div class="leftBlock">
								<h3>Новости</h3>
								<div class="vnav">
									<ul>
										<li><a href="news.php">Обзор новостей</a></li>
										<li><a href="addnews.php">Добавить новость</a></li>
										<li><a href="newsset.php">Настройка</a></li>
									</ul>
								</div>
							</div>

							<div class="leftBlock">
								<h3>Фото-альбом</h3>
								<div class="vnav">
									<ul>
										<li><a href="photo.php">Обзор фото</a></li>
										<li><a href="potoset.php">Настройка</a></li>
									</ul>
								</div>
							</div>

							<div class="leftBlock">
								<h3>Гостевая книга</h3>
								<div class="vnav">
									<ul>
										<li><a href="guestbook.php">Редактирование</a></li>
										<li><a href="gbset.php">Настройка</a></li>
									</ul>
								</div>
							</div>

							<div class="leftBlock">
								<h3>Пользователи</h3>
								<div class="vnav">
									<ul>
										<li><a href="users.php">Управление</a></li>
										<li><a href="banip.php">Бан-лист по ip</a></li>
									</ul>
								</div>
							</div>

							<div class="leftBlock">
								<h3>Сервис</h3>
								<div class="vnav">
									<ul>
										<li><a href="chmod.php">Установка прав</a></li>
										<li><a href="convert.php">Конвертация в utf8</a></li>
										<li><a href="baner.php">Система банеров</a></li>
										<li><a href="upgrade.php">Обновление CMS</a></li>
										<li><a href="backup.php">Архивирование данных</a></li>
									</ul>
								</div>
							</div>

							<div class="leftBlock">
								<h3>Информация</h3>
								<div class="vnav">
									<ul>
										<li><a href="info.php">Инфо о настройках PHP</a></li>
										<li><a href="help.php">Раздел помощи</a></li>
									</ul>
								</div>
							</div>

							<div class="leftBlock leftTextBlock">
								<p>
									Протестированно в Opera 6, 7, Gecko (FireFox 0.9.x), IE 6, 5.5, 5.0, IE Mac 5.2, Safari.
								</p>
							</div>

							<hr class="hide">
						</div>
					</div>
					<div id="footer">
						<p>
							<div style="float:left">
								&copy; Powered by : <a href="http://www.Kan-Studio.ru">Kandidat CMS <?php echo VERSION;?></a>
							</div>
						   	<div style="float:right">
								<?php echo $choisetemplate;?>
							</div>
						</p>
						<hr class="hide">
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			tooltip.init();
		</script>

	</body>
</html>








