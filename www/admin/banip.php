<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(3>getpermision())header('LOCATION:index.php');
$sitetitle='Бан по ip адресам';
$add = (isset($_GET['add'])) ? $_GET['add'] : 0;
$del = (isset($_GET['del'])) ? $_GET['del'] : 0;
$filename=ENGINE.'banipdb.php';
@$contentcenter.='<h3>Забаненные ip адреса</h3>';

if($add!==0){
	if(ip_is_baned($add)){
	    $contentcenter.='<div class="message_warn_ok"><b>ip адрес '.$add.' уже забанен!</b></div><br><br><a href=\'javascript:history.back(1)\'><B>Вернуться назад</B></a>';
	    include $localpath.'/admin/admintemplate.php';
	    exit;
	}else{
		ip_baned($add);
		header('LOCATION: banip.php');
	}
}
if($del!==0){
	del_ip_baned($del);
	header('LOCATION: banip.php');
}
$contentcenter.='
	<link rel="stylesheet" href="'.$prefflp.'/css/sorttable.css" />
	<script type="text/javascript">
	$(document).ready(function(){
		/* Код выполняется после загрузки страницы */

		$(\'table.sortable tr\').click(function(e){
		    var elm = e.target||event.srcElement;
		    if(elm.tagName.toLowerCase() != \'a\')    {
	    	    return;
			}
		});
	});
	</script>';

$contentcenter.='<table  border=0 cellspacing=0 cellpadding=0  width="80%" id="table" class="sortable">';
$contentcenter.='<thead><tr>';
$contentcenter.='<th width=\"20%\"><h3>Дата бана</h3></td>';
$contentcenter.='<th width=\"20%\"><h3>Время бана</h3></td>';
$contentcenter.='<th width=\"50%\"><h3>ip адрес</h3></td>';
$contentcenter.='<th class="nosort" colspan="2" width=\"10%\"><h3>Удалить</h3></td>';
$contentcenter.='</tr></thead><tbody>';

@$banlist_ip=get_ip_banlist();
for($i=0; $i<count($banlist_ip);$i++){
	$ban=$banlist_ip[$i];
	$contentcenter.='<tr>';
	$contentcenter.='<td class="line3">'.$ban['data'].'</td>';
	$contentcenter.='<td class="line3">'.$ban['time'].'</td>';
	$contentcenter.='<td class="line3">'.$ban['ip'].'</td>';
	$contentcenter.='<td class="line3"><a title="Удалить" href="../admin/banip.php?del='.$ban['ip'].'"><img src="images/delete.png"></a></td>';
	$contentcenter.='</tr>';
}
$contentcenter.='</tbody></table>';
$contentcenter.='
		<div id="tcontrols">
			<div id="tperpage">
				<select onchange="sorter.size(this.value)">
					<option value="5">5</option>
					<option value="10">10</option>
					<option value="20" selected="selected">20</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>
				<span>'.__('Строк на странице').'</span>
			</div>
			<div id="tnavigation">
				<img src="'.$prefflp.'/images/first.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" />
				<img src="'.$prefflp.'/images/previous.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" />
				<img src="'.$prefflp.'/images/next.gif" width="16" height="16" alt="First Page" onclick="sorter.move(1)" />
				<img src="'.$prefflp.'/images/last.gif" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" />
			</div>
			<div id="ttext">'.__('Страница').'<span id="currentpage"></span> из <span id="pagelimit"></span></div>
		</div>
	<script type="text/javascript" src="'.$prefflp.'/js/sorttable.js"></script>
	<script type="text/javascript">
	  var sorter = new TINY.table.sorter("sorter");
		sorter.head = "head";
		sorter.asc = "asc";
		sorter.desc = "desc";
		sorter.even = "evenrow";
		sorter.odd = "oddrow";
		sorter.evensel = "evenselected";
		sorter.oddsel = "oddselected";
		sorter.paginate = true;
		sorter.currentid = "currentpage";
		sorter.limitid = "pagelimit";
		sorter.init("table",0);
	</script>
	';
$contentcenter.='<br /><br /><center><a href=\'javascript:history.back(1)\'><b>Вернуться назад</b></a></center>';
include $localpath.'/admin/admintemplate.php';
?>
