<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(2>getpermision())header('LOCATION:index.php');
$sitetitle=__('Файловый менеджер');
@$contentcenter ='Домашний каталог: <b>'.$localpath.'media/</b><br /><br />';
$contentcenter .=<<<EOT
<script type="text/javascript" charset="windows-1251">
	$().ready(function() {
		var f = $('#finder').elfinder({
			url : 'elrte/connectors/php/connector.php',
			lang : 'ru',
			docked : true,
			height: 490,
			docked : true,
	 		dialog : {
	 			title : 'Файловый менеджер',
	 			height : 500
	 		}
		})
	})
</script>

<div id="finder">finder</div>
EOT;
include $localpath.'/admin/admintemplate.php';
?>
