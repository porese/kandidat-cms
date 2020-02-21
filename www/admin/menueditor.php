<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
if(2>getpermision())header('LOCATION:index.php');
$sitetitle=__('Редактирование меню');
$myFile=ENGINE.'menudb.php';

$what = (isset($_GET['what'])) ? (int)$_GET['what'] : 0;
$edit = (isset($_GET['edit'])) ? (int)$_GET['edit'] : 0;
$issubmenu = (isset($_GET['submenu'])) ? (int)$_GET['submenu'] : 0;
$up = (isset($_GET['up'])) ? (int)$_GET['up'] : 0;
$down = (isset($_GET['down'])) ? (int)$_GET['down'] : 0;
$new = (isset($_GET['new'])) ? (int)$_GET['new'] : 0;
$move = (isset($_GET['move'])) ? (int)$_GET['move'] : 0;
$ltd = (isset($_POST['ltd'])) ? (int)$_POST['ltd'] : 0;
$isblank = (isset($_GET['blank'])) ? (int)$_GET['blank'] : 0;
$viewup = (isset($_GET['viewup'])) ? (int)$_GET['viewup'] : 0;
$makemenudb = (isset($_GET['makemenu'])) ? 1 : 0;

@$updateornot = $_POST['submit'];
@$content = $_POST['article'];
$contentcenter.='<h3>'.__('Редактирование меню').'</h3>';
$contentcenter.='<div class="message_warn">'.__('При нажатии на ссылку (кнопку) для удаления,  пункт меню будет сразу же удален без подтверждения!').'</div><br>';
$contentcenter.='<a title="'.__('Новый пункт меню').'" href="?new=1">'.__('Новый пункт меню').'</a> | ';
$contentcenter.='<a title="'.__('Конец субменю').'" href="?submenu=-1">'.__('Конец субменю').'</a> | ';
$contentcenter.='<a title="'.__('Сформировать новое меню').'" href="?makemenu=1">'.__('Сформировать новое меню').'</a>';

$contentcenter.='<br><table width="98%"  cellpadding="5" cellspacing="0">
		<thead>
	    <tr class="line2" ><th width="20%">'.__('Страница').'</td>
	    <th width="30%">'.__('Заголовок').'</td>
	    <th width="25%">'.__('Подсказка').'</td>
	    <th colspan="1" width="3%">'.__('UP').'</td>
	    <th colspan="1" width="3%">'.__('Нов.').'</td>
	    <th colspan="1" width="3%">'.__('Суб.').'</td>
	    <th colspan="1" width="3%">'.__('Ред.').'</td>
	    <th colspan="1" width="3%">'.__('Удал.').'</td>
	    <th colspan="3" width="10%">'.__('Перем.').'</td>
	    </tr>
	    </thead><tbody>';
if(file_exists($myFile)){
	//Запись
	if(isset($_POST['go'])){
		if (trim($_REQUEST['page'])!=""&trim($_REQUEST['head'])!=""){
			$menu=file($myFile);
		    if (($edit>0)&(count($menu)>=$edit)) {
				$page=trim($_REQUEST['page']);
				$head=filtermessage($_REQUEST['head']);
				$title=filtermessage($_REQUEST['title']);
				$submenu=trim($_REQUEST['submenu']);
				$blank=trim($_REQUEST['blank']);
				$viewup=trim($_REQUEST['viewup']);

	   	    	$data=array('page'=>$page,'head'=>$head,'title'=>$title,'blank'=>$blank,'submenu'=>$submenu,'viewup'=>$viewup);

				$menu[$edit-1]=serialize($data)."\n";
				savearray($myFile,$menu,'w','');
			}
			header('LOCATION:menueditor.php');
		}
	}
	$menu=file($myFile);
	//Удаление
	if ($what>0) {
	    if(count($menu)>=$what){
			array_splice($menu,$what-1,1);
			savearray($myFile,$menu,'w','');
		}
	    header('LOCATION:menueditor.php');
	//Субменю
	} elseif ($issubmenu>0) {
	    if(count($menu)>=$issubmenu){
			$data=unserialize($menu[$issubmenu-1]);
			$data['submenu'] = ($data['submenu']=='1')?'':'1';
			$menu[$issubmenu-1]=serialize($data)."\n";
			savearray($myFile,$menu,'w','');
		}
	    header('LOCATION:menueditor.php');
	} elseif ($issubmenu<0) {
    	$data=array('submenu'=>"-1");
		savedata($myFile,$data,'a');
	    header('LOCATION:menueditor.php');
	//в доп меню
	} elseif ($viewup>0) {
	    if(count($menu)>=$viewup){
			$data=unserialize($menu[$viewup-1]);
			$data['viewup'] = ($data['viewup']=='1')?'':'1';
			$menu[$viewup-1]=serialize($data)."\n";
			savearray($myFile,$menu,'w','');
		}
	    header('LOCATION:menueditor.php');
	//нов окно
	} elseif ($isblank>0) {
	    if(count($menu)>=$isblank){
			$data=unserialize($menu[$isblank-1]);
			$data['blank'] = ($data['blank']=='1')?'':'1';
			$menu[$isblank-1]=serialize($data)."\n";
			savearray($myFile,$menu,'w','');
		}
	    header('LOCATION:menueditor.php');
	//Быстро переместили
	} elseif ($move>0) {
	    if(count($menu)>=$move){
			if($ltd>0){
			    if(count($menu)>=$ltd){
					array_splice($menu, $ltd-1, 0, $menu[$move-1]);
					if($ltd<$move)$move++;
					array_splice($menu,$move-1,1);
					savearray($myFile,$menu,'w','');
				}
			    header('LOCATION:menueditor.php');
			}else{
				$menu_item=unserialize($menu[$move-1]);
			    $countmenu = count($menu);
				for($i=0;$i<$countmenu;$i++){
					if(($i+1)!==$move){
						$curmenuitem=unserialize($menu[$i]);
						@$contentmenu.='<option value="'.($i+1).'" >'.$curmenuitem['head'].'</option>';
					}
				}
				$contentcenter='<h3>'.__('Редактирование меню').'</h3>';
				$contentcenter.='<form action="'.$url.'" method="post" name="my_form">
								'.__('Поместить данный пункт меню').' '.$menu_item['head'].' '.__('с адресом').' '.cc_link($menu_item['page']).'<br /><br />
								'.__('перед пунктом').': <select name="ltd">
									<option value="" selected="selected"> - '.__('Выбрать').' - </option>
									'.@$contentmenu.'
						    		</select>
								<input id="submit" type="submit" value="'.__('Переместить').'" name="go" />
								</form><br /><br />
								<center><a href=\'javascript:history.back(1)\'><B>'.__('Вернуться назад').'</B></a></center>';
			}
		}
	//Вверх
	} elseif ($up>0) {
	    if($up>1){
		    if(count($menu)>=$up){
				array_splice($menu, $up-2, 0, $menu[$up-1]);
				array_splice($menu,$up,1);
				savearray($myFile,$menu,'w','');
			}
 		}
	    header('LOCATION:menueditor.php');
	//Bниз
	} elseif ($down>0) {
	    if($down<count($menu)){
			array_splice($menu, $down+1, 0, $menu[$down-1]);
			array_splice($menu,$down-1,1);
			savearray($myFile,$menu,'w','');
		}
	    header('LOCATION:menueditor.php');
	//Новый
	} elseif ($new>0) {
    	$data=array('page'=>'/',
	    'head'=>__('Новый пункт'),
	    'title'=>__('Подсказка')
	    );
		savedata($myFile,$data,'a');
	    header('LOCATION:menueditor.php?edit='.(count($menu)+1));
	//Сформировать меню
	} elseif ($makemenudb>0) {
		$data[]=array('page'=>'/','head'=>__('Главная'),'title'=>'');
		$data=menudirlist($data,ARTICLES);
		savedataarray ($myFile,$data, 'w');
	    header('LOCATION:menueditor.php');
	//Редактирование
	} elseif ($edit>0) {
	    $countmenu = count($menu);
	    for($i=1;$i<=$countmenu;$i++){
			$menu_item=unserialize($menu[$i-1]);
			if ($menu_item=="") {continue;}
			@$page = $menu_item['page'];
			@$head = $menu_item['head'];
			@$title = $menu_item['title'];
			@$blank = $menu_item['blank'];
			@$submenu = $menu_item['submenu'];
			@$viewup = $menu_item['viewup'];
            @$pic_submenu=($submenu=='1')?'cb_y.png':'cb_e.png';
            @$pic_blank=($blank=='1')?'cb_y.png':'cb_e.png';
            @$pic_viewup=($viewup=='1')?'cb_y.png':'cb_e.png';
			@$dumbcount++;
			$class = 'cline' . ($dumbcount % 2);
			if($i==$edit){
				$contentcenter.='<tr  class="'.$class.'"><form action="'.$url.'" method="post" name="my_form">
				<input type="hidden" name="submenu" value='.$submenu.' />
				<input type="hidden" name="blank" value='.$blank.' />
				<input type="hidden" name="viewup" value='.$viewup.' />
			    <td class="line3"><input class="settings" width="99%" type="text" name="page" value="'.$page.'" /></td>
			    <td class="line3"><input class="settings" width="99%" type="text" name="head" value="'.$head.'" /></td>
			    <td class="line3" colspan="1"><input class="settings" width="99%" type="text" name="title" value="'.$title.'" /></td>
			    <td class="line3" colspan="8"><input id="submit"  width="99%" type="submit" value="'.__('Сохранить').'" name="go" /></td>
				</form></tr>';
			}else{
				if($submenu=='-1'){
					$contentcenter.='<tr class="'.$class.'">
				    <td class="line3" colspan="7"><b>'.__('Конец субменю').'</b></td>
				    <td class="line3" colspan="1"><a title="'.__('Удалить').'" href="?what='.$i.'\"><img src="images/delete.png"></a></td>
				    <td class="line3" colspan="1"><a title="'.__('Вверх').'" href="?up='.$i.'"><img src="images/arrow-up.png" /></a></td>
				    <td class="line3" colspan="1"><a title="'.__('Вниз').'" href="?down='.$i.'"><img src="images/arrow-down.png" /></a></td>
				    <td class="line3" colspan="1"><a title="'.__('Быстрое перемещение').'" href="?move='.$i.'"><img src="images/go-jump.png" /></a></td>
					</tr>';
				}else{
					$contentcenter.='<tr class="'.$class.'">
				    <td class="line3">'.$page.'</td>
				    <td class="line3">'.$head.'</td>
				    <td class="line3">'.$title.'</td>
			    	<td class="line3" colspan="1"><a title="'.__('Показывать для верхнего дополнительного меню').'" href="?viewup='.$i.'"><img src="images/'.$pic_viewup.'" /></a></td>
			    	<td class="line3" colspan="1"><a title="'.__('Открывать в новом окне').'" href="?blank='.$i.'"><img src="images/'.$pic_blank.'" /></a></td>
				    <td class="line3" colspan="1"><a title="'.__('Имеет подменю').'" href="?submenu='.$i.'"><img src="images/'.$pic_submenu.'" /></a></td>
			    	<td class="line3" colspan="1"><a title="'.__('Редактировать').'" href="?edit='.$i.'"><img src="images/edit.png" /></a></td>
				    <td class="line3" colspan="1"><a title="'.__('Удалить').'" href="?what='.$i.'"><img src="images/delete.png" /></a></td>
				    <td class="line3" colspan="1"><a title="'.__('Вверх').'" href="?up='.$i.'"><img src="images/arrow-up.png" /></a></td>
				    <td class="line3" colspan="1"><a title="'.__('Вниз').'" href="?down='.$i.'"><img src="images/arrow-down.png" /></a></td>
				    <td class="line3" colspan="1"><a title="'.__('Быстрое перемещение').'" href="?move='.$i.'"><img src="images/go-jump.png" /></a></td>
					</tr>';
			    }
			}
		}
	} else {
	    $countmenu = count($menu);
	    for($i=1;$i<=$countmenu;$i++){
			$menu_item=unserialize($menu[$i-1]);
			if ($menu_item=="") {continue;}
			@$page = $menu_item['page'];
			@$head = $menu_item['head'];
			@$title = $menu_item['title'];
			@$blank = $menu_item['blank'];
			@$submenu = $menu_item['submenu'];
			@$viewup = $menu_item['viewup'];
            @$pic_submenu=($submenu=='1')?'cb_y.png':'cb_e.png';
            @$pic_blank=($blank=='1')?'cb_y.png':'cb_e.png';
            @$pic_viewup=($viewup=='1')?'cb_y.png':'cb_e.png';
			@$dumbcount++;
			$class = 'cline' . ($dumbcount % 2);
			if($submenu=='-1'){
				$contentcenter.='<tr class="'.$class.'">
			    <td class="line3" colspan="7"><b>'.__('Конец субменю').'</b></td>
			    <td class="line3" colspan="1"><a title="'.__('Удалить').'" href="?what='.$i.'\"><img src="images/delete.png"></a></td>
			    <td class="line3" colspan="1"><a title="'.__('Вверх').'" href="?up='.$i.'"><img src="images/arrow-up.png" /></a></td>
			    <td class="line3" colspan="1"><a title="'.__('Вниз').'" href="?down='.$i.'"><img src="images/arrow-down.png" /></a></td>
			    <td class="line3" colspan="1"><a title="'.__('Быстрое перемещение').'" href="?move='.$i.'"><img src="images/go-jump.png" /></a></td>
				</tr>';

			}else{
				$contentcenter.='<tr class="'.$class.'">
			    <td class="line3">'.$page.'</td>
			    <td class="line3">'.$head.'</td>
			    <td class="line3">'.$title.'</td>
			    <td class="line3" colspan="1"><a title="'.__('Показывать для верхнего дополнительного меню').'" href="?viewup='.$i.'"><img src="images/'.$pic_viewup.'" /></a></td>
		    	<td class="line3" colspan="1"><a title="'.__('Открывать в новом окне').'" href="?blank='.$i.'"><img src="images/'.$pic_blank.'" /></a></td>
			    <td class="line3" colspan="1"><a title="'.__('Имеет подменю').'" href="?submenu='.$i.'"><img src="images/'.$pic_submenu.'" /></a></td>
		    	<td class="line3" colspan="1"><a title="'.__('Редактировать').'" href="?edit='.$i.'"><img src="images/edit.png" /></a></td>
			    <td class="line3" colspan="1"><a title="'.__('Удалить').'" href="?what='.$i.'"><img src="images/delete.png" /></a></td>
			    <td class="line3" colspan="1"><a title="'.__('Вверх').'" href="?up='.$i.'"><img src="images/arrow-up.png" /></a></td>
			    <td class="line3" colspan="1"><a title="'.__('Вниз').'" href="?down='.$i.'"><img src="images/arrow-down.png" /></a></td>
			    <td class="line3" colspan="1"><a title="'.__('Быстрое перемещение').'" href="?move='.$i.'"><img src="images/go-jump.png" /></a></td>
				</tr>';
			}
		}
	}
}
$contentcenter.='</tbody></table>';
include $localpath.'/admin/admintemplate.php';

?>
