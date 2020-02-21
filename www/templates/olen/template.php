<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="index, follow" />
<meta name="keywords" content="<?php echo $metakeywords;?>" />
<meta name="description" content="<?php echo $metadescription;?>" />
<title><?php echo $sitetitle;?> - <?php echo $sitename;?></title>
<link href="<?php echo $prefflp; ?>/templates/<? echo $template;?>/default.css" rel="stylesheet" type="text/css" media="screen" />

<link rel="stylesheet" type="text/css" href="/css/prettyPhoto.css" />
<script type="text/javascript" src="/js/jquery.min.js"  charset="utf-8"></script>
<script type="text/javascript" src="/js/jquery.prettyPhoto.js"  charset="utf-8"></script>
<!--[if  IE 6]>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
      $(".gallery a[rel^='prettyPhoto']").prettyPhoto({theme:'<?php echo empty($templatepp)?'dark_rounded':$templatepp;?>'});
    });
</script>
<![endif]-->

<script type="text/JavaScript" src="/js/qTip.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="/css/qTip.css" />
</head>
<body>
<div id="links">
<div id="company_logo"></div>
<ul>
	<h2>Навигация</h2>
    <ul id="recently">
		<? include $localpath.'/engine/menu.php'; ?>
  	</ul>

  	<h2>Новости     <a class="rss" href="<?=cc_link("/rss.html");?>"> </a></h2>
    <ul id="lastnews">
    	<li>
		<? include $localpath .'/mycode/lastnews.php'; ?>
		</li>
  	</ul>
	<h2>Случайное фото</h2>
	<ul>
		<li>
			<? include("mycode/photorotate.php"); ?>
		</li>
	</ul>
	<h2>Афоризм</h2>
	<ul>
		<li>
			<? include("mycode/aforizm.php"); ?>
		</li>
	</ul>
	<ul>
		<li>
			<? include("mycode/cloudtags.php"); ?>
		</li>
	</ul>
	<ul>
		<li>
			<p class="entry"><? include("engine/counter.php"); ?></p>
		</li>
		<li>
			<p style="margin-top: 0; margin-bottom: 0">
				<a target="_blank" href="http://www.finalsense.com">
					<img width="91" height="17" src="<? echo $prefflp;?>/templates/<? echo $template;?>/images/finalsense-logo.jpg" alt="Designed by FinalSense" style="border : 0px none;; margin-top:0; margin-bottom:0" />
				</a>
			</p>
		</li>
	</ul>
</ul>
</div>
<div id="content">
	<!-- start of content -->
	<h1><?php echo $sitename;?></h1>
    <div id="navigation-head"><?php echo $lnav;?></div>
	  <!-- Begin .post -->
	<h4><?php echo $sitetitle;?></h4>
    <div class="posts">
		<?echo $contentcenter;?>
	</div>
  <!-- End .post -->
<!-- end of content-->
</div>
<div id="footer">
 ©2010 <?php echo $sitename;?>. Powered by: <a href="http://www.kan-studio.ru">Kandidat <abbr>CMS</abbr></a> <?php echo printbuildtime();?>. Webmaster <a href="http://forum.kan-studio.ru/profile.php?id=117" target="_blank">it aka porese</a><br/>
 <div id="valid">
 <a href="http://validator.w3.org/check/referer" class="xhtml" title="This page validates as XHTML">Valid <abbr>XHTML</abbr></a>
 <a href="http://jigsaw.w3.org/css-validator/check/referer" class="css" title="This page validates as CSS">Valid <abbr>CSS</abbr></a>
 <a href="<?php echo cc_link("/rss.html"); ?>" class="lrss" title="Subscribe to entries web feed"  target="_blank"><abbr>RSS</abbr></a>
 </div>
</div>
<!-- end footer -->
</div>
</body>
</html>
