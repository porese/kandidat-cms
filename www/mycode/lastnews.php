<?php
//phpfile
#Последние новости (определнное количество)
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');

include_once CONF .'newsconf.php';
$news_Glink='/news'.(isset($news_cat)?'/':'-');
$news_Gpage='/news'.(isset($news_cat)?'/':'.html');

$lastnew="";
$data=array_reverse(file($newsdbfilename));
$allnews=count($data);
if($allnews>=1){
	for($i=1;$i<= min($newsonmainpage,$allnews);$i++){
		//$datas=unserialize(array_shift($data));
		$datas=unserialize($data[$i-1]);
		$head=$datas['head'];
		$head=str_replace("&quot;",'"',$datas['head']);
		$mess=strip_tags($datas['mess']);
		$pubdate=$datas['pubdate'];
		$p=$allnews-$i+1;
		if (fstrlen($head)>$lengthhead) {$head=fsubstr($head, 0, $lengthhead);}
		if (fstrlen($mess)>$lengthnews) {$mess=fsubstr($mess, 0, $lengthnews);}
		$head=close_dangling_tags($head);
		$mess=close_dangling_tags($mess);
		$mess.='&nbsp;&nbsp;<a href="'.cc_link($news_Glink.$p.'.html').'" class="more">...'.__('читать далее').'</a>';
		$lastnew.='<li><p class="title"><span class="news-date-time">'.$pubdate.'</span>, '.$head.'</p><div class="entry">'.$mess.'</div></li>';
	}
	$lastnew.='<li id="nobackground"><div class="links"><a href="'.cc_link($news_Gpage).'" class="more">'.__('все новости').'</a></div></li>';
	echo $lastnew;

}else{
    echo '<li><p class="title">'.__('Записей нет!').'</p></li>';
}
?>
