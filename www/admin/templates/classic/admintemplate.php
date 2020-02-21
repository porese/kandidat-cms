<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
    <meta http-equiv="content-type" content="text/html;charset=utf8" />
	<title><?php echo $sitetitle;?></title>
	<meta name="description" content="Kan-Sdudio" />
	<link href="templates/<? echo $admintemplate;?>/style.css" type="text/css" rel="stylesheet" />
	<!-- jQuery and jQuery UI -->
	<script src="<?php echo $prefflp;?>/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $prefflp;?>/js/jquery-ui.custom.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="<?php echo $prefflp;?>/js/jquery.ui.datepicker-ru.js"></script>
	<link rel="stylesheet" href="<?php echo $prefflp;?>/js/ui-themes/smoothness/jquery-ui.custom.css" type="text/css" media="screen" charset="utf-8">
	<!-- elRTE -->
	<script src="elrte/js/elrte.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="elrte/css/elrte.full.css" type="text/css" media="screen" charset="utf-8">
	<!-- elFinder -->
	<script src="elrte/js/elfinder.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="elrte/css/elfinder.css" type="text/css" media="screen" charset="utf-8">
	<!-- russian messages -->
	<script src="elrte/js/i18n/elrte.ru.js" type="text/javascript" charset="utf-8"></script>
	<script src="elrte/js/i18n/elfinder.ru.js" type="text/javascript" charset="utf-8"></script>
	<!-- Editor -->
	<script src="elrte/js/admfunctions.js" type="text/javascript" charset="utf-8"></script>
	</head>
	<? flush();?>
	<body bgcolor="#646464">
		<div align="center">
			<table align="center" width="1020" border="0" cellspacing="0" cellpadding="0" bgcolor="white">
				<tr>
					<td bgcolor="#646464"></td>
				</tr>
				<tr>
					<td class="header" style="letter-spacing: 2px; font-size: 26px;">Панель управления сайтом
					</td>
				</tr>
							<tr>
					<td style="border-bottom: 1px solid #dadada;"></td>
				</tr>
				<tr>
					<td class="main_cell" valign="top">
						<table style="height: 100%;" width="99%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="border-right: 1px dashed #666666; padding: 5px;" valign="top" align="left" width="160">
                                	<div class="arrowlistmenu">

										<h3 class="headerbar">User: <?=str_replace(' | ','<br />',getuser());?></h3>
										<h3 class="headerbar">Навигация</h3>
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
										<? if(file_exists('../admin/katalog.php')){?>
										<h3 class="headerbar">Каталог</h3>
										<ul>
										<li><a href="katalog.php">Обзор материалов</a></li>
										<li><a href="addkatalog.php">Добавить материал</a></li>
										</ul>
										<? } ?>
										<h3 class="headerbar">Новости</h3>
										<ul>
										<li><a href="news.php">Обзор новостей</a></li>
										<li><a href="addnews.php">Добавить новость</a></li>
										<li><a href="newsset.php">Настройка</a></li>
										</ul>
										<? if(file_exists('../admin/photo.php')){?>
										<h3 class="headerbar">Фото-альбом</h3>
										<ul>
										<li><a href="photo.php">Обзор фото</a></li>
										<li><a href="potoset.php">Настройка</a></li>
										</ul>
										<? } ?>
										<? if(file_exists('../admin/guestbook.php')){?>
										<h3 class="headerbar">Гостевая книга</h3>
										<ul>
										<li><a href="guestbook.php">Редактирование</a></li>
										<li><a href="gbset.php">Настройка</a></li>
										</ul>
										<? } ?>
										<h3 class="headerbar">Пользователи</h3>
										<ul>
										<li><a href="users.php">Управление</a></li>
										<li><a href="banip.php">Бан-лист по ip</a></li>
										</ul>
										<h3 class="headerbar">Сервис</h3>
										<ul>
										<li><a href="chmod.php">Установка прав</a></li>
										<li><a href="convert.php">Конвертация в utf8</a></li>
										<li><a href="baner.php">Система банеров</a></li>
										<li><a href="upgrade.php">Обновление CMS</a></li>
										<li><a href="backup.php">Архивирование данных</a></li>
										</ul>
										<h3 class="headerbar">Информация</h3>
										<ul>
										<li><a href="info.php">Инфо о настройках PHP</a></li>
										<li><a href="help.php">Раздел помощи</a></li>
										</ul>
									</div>
									<center>
									<a href="../" target="_blank">ПЕРЕЙТИ НА САЙТ</a>
									<form method="POST" action="/admin/login.php" >
									<input type="hidden" name="name" value="usersite">
									<input type="hidden" name="pass" value="kandidat">
									<input type="submit" style="margin-top:10px; display:block; border:1px solid #CCCCCC; width:100px; height:20px;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-left:2px; padding-right:2px; padding-top:0px; padding-bottom:2px; line-height:14px; background-color:#EFEFEF;" value="Выйти" name="logout">
									</form>
									</center>
								</td>
								<td style="padding: 5px;" valign="top" align="left"><?echo $contentcenter;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
			    <td style="padding: 5px;" valign="top">
					<table style="height: 100%;" width="99%" border="0" cellspacing="0" cellpadding="0">
						<hr noshade="noshade" size="1" />
						<tr>
							<td>
 								Powered by : <a href="http://www.Kan-Studio.ru">Kandidat CMS (utf8) by it</a>
 							</td>
	 						<td align="right">
	 							<? echo $choisetemplate;?>
 							</td>
 						</tr>
 					</table>
 				</td>
				</tr>
			</table>
			<p></p>
		</div>
	</body>

</html>
