<?php
//phpfile
#Файловое хранилище со счетчиком скаченных файлов из media/file/
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');
error_reporting(E_ALL^E_NOTICE);

$directory=LOCALPATH.'/media/file';//Это путь где хранятся файлы

function getcount($fname){
	  $myFile=ENGINE.'/logdb.php';
  	  @$count="";
	  if(is_readable($myFile)){
    	  $log=file($myFile);
  		  $present=0;
  		  foreach ($log as $key => $val) {
  	  		if (strpos($val,$fname)!==FALSE) { $present = $key+1; break;}
  		  }
	  	  if($present!=0){
  		  		@$data=unserialize($log[$present-1]);
  				@$count=$data['count'];
	  	  }
  	  }
	  return $count;
}

$extension='';
$files_array = array();

$dir_handle = @opendir($directory) or die('There is an error with your file directory!');

while ($file = readdir($dir_handle))
{
	/* Skipping the system files: */
	if($file{0}=='.') continue;

	/* end() returns the last element of the array generated by the explode() function: */
	$extension = getftype($file);

	/* Skipping the php files: */
	if($extension == 'php') continue;
	$files_array[]=array('files'=>$file,
						'size'=>convert_fsize(filesize($directory.DIRECTORY_SEPARATOR."$file")));
}

sort($files_array,SORT_STRING);

?>
<link rel="stylesheet" href="<?php echo $prefflp;?>/css/sorttable.css" />
<script type="text/javascript">
$(document).ready(function(){
	/* Код выполняется после загрузки страницы */

	$('table.sortable tr').click(function(e){
	    var elm = e.target||event.srcElement;
	    if(elm.tagName.toLowerCase() != 'a')    {
    	    return;
		}
		var countSpan = $('.download-count',this);
		countSpan.text( parseInt(countSpan.text())+1);
	});

});
</script>
	<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable">
		<thead>
			<tr>
				<th class="head" width="7%"><h3>ID</h3></th>
				<th class="head" width="40%" title="<?php echo __('Сортировать по имени');?>"><h3><?php echo __('Имя файла');?></h3></th>
				<th class="head" width="43%" title="<?php echo __('Сортировать по комментарию');?>"><h3><?php echo __('Комментарий, размер');?></h3></th>
				<th class="head" width="10%" title="<?php echo __('Сортировать по количеству скачиваний');?>"><h3><?php echo __('Скачали');?></h3></th>
			</tr>
		</thead>
		<tbody>
    <?php
		if($cc_url=="1")$linkdownl=$prefflp.'/download.php?file=';
		else $linkdownl=$prefflp.'/download/';
    	$i=1;
        foreach($files_array as $key=>$val)
        {
      		$count=getcount($val['files']);
            echo '<tr><td><a href="'.$linkdownl.urlencode($val['files']).'">'.$i.'</a></td>
                  <td ><a href="'.$linkdownl.urlencode($val['files']).'">&nbsp;'.$val['files'].'</a></td>
                  <td align="right"><a href="'.$linkdownl.urlencode($val['files']).'">'.$val['size'].'&nbsp;</a></td>
                  <td align="right"><a class="download-count" href="'.$linkdownl.urlencode($val['files']).'" title="'.__('Количество скачиваний').'">'.(int)$count.'</a></td></tr>';
            $i++;
        }
    ?>
       </tbody>
  </table>
	<div id="tcontrols">
		<div id="tperpage">
			<select onchange="sorter.size(this.value)">
				<option value="5">5</option>
				<option value="10" selected="selected">10</option>
				<option value="20">20</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
			<span><?=__('Строк на странице');?></span>
		</div>
		<div id="tnavigation">
			<img src="<?php echo $prefflp;?>/images/first.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" />
			<img src="<?php echo $prefflp;?>/images/previous.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" />
			<img src="<?php echo $prefflp;?>/images/next.gif" width="16" height="16" alt="First Page" onclick="sorter.move(1)" />
			<img src="<?php echo $prefflp;?>/images/last.gif" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" />
		</div>
		<div id="ttext"><?php echo __('Страница');?> <span id="currentpage"></span> из <span id="pagelimit"></span></div>
	</div>
<script type="text/javascript" src="<?php echo $preflp;?>/js/sorttable.js"></script>
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
	sorter.init("table",1);
</script>
