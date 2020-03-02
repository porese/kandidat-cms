<?php
include 'conf/config.php';
include 'code/functions.php';
if(empty($rss_сontent))$rss_сontent=3;
$sitehost="http://".$_SERVER['HTTP_HOST'];
function compare_arr($ar1,$ar2){
	$val1=$ar1['datatime'];
	$val2=$ar2['datatime'];
	if($val1==$val2)
		return 0;
	elseif($val1>$val2)
		return -1;
	else
		return 1;
}
//news
if(($rss_content & 1)>0){
	$newsdbfilename= ENGINE.'/newsdb.php';
	if(file_exists($newsdbfilename)){
	    $data=array_reverse(file($newsdbfilename));
	    $countallnews=count($data);
	    $allnews=($countallnews>6)?6:$countallnews;
	    if ($allnews>=1) {
		for($i=1;$i<=$allnews;$i++){
		    $datas=unserialize($data[$i-1]);
		    $p=$countallnews-$i+1;
		    $datatime=mktime(0, 0, 0, substr($datas['pubdate'],3,2), substr($datas['pubdate'],0,2), substr($datas['pubdate'],6,4));
		    $pubdate=date("D, d M Y H:i:s O", $datatime);	
		    if(file_exists(ARTICLES.'news.dat'))$linknews='/news-'.$p.'.html';
		    else $linknews='/news/'.$p.'.html';
            $return[]=array(
		    'datatime'=>$datatime,
			'head'=>html_entity_decode($datas['head']),
			'pubdate'=>$pubdate,
			'mess'=>close_dangling_tags($datas['mess']),
			'link'=>$sitehost.cc_link($linknews)
		    );
		}
	    }
	}
}
//articles
if(($rss_content & 2)>0){
	$cat_st[]="";
	$dir=scandir(ARTICLES,1);
	$count_files=sizeof($dir)-2;
	for ($i = 0; $i < $count_files; $i++){
		if(is_dir(ARTICLES.$dir[$i]))$cat_st[]=$dir[$i];
	}
	$count_files=sizeof($cat_st);
	for ($i = 0; $i < $count_files; $i++){
		$dd=opendir(ARTICLES.$cat_st[$i]);
		while ($pfile = readdir ($dd)){
	  		$ext=getftype($pfile);
	  		if($pfile!='.' && $pfile!='..' && ($ext=='dat') && ($pfile!='404.dat') && !is_dir(ARTICLES.$pfile) ){
				$data=file_get_contents(ARTICLES.$cat_st[$i]."/".$pfile);
				$pubdate=articlesparam("pubdate",$data);
				if($pubdate!==""){
					$s_text=articlesparam("content",$data);
					$pos = @strpos($s_text, ' ',1000);
					if($pos) $s_text=substr($s_text,0,$pos);
					$s_text.="....";
					$s_text=close_dangling_tags($s_text);

		    		$return[]=array(
				    'datatime'=>$pubdate,
					'head'=>articlesparam("title",$data),
					'pubdate'=>date("D, d M Y H:i:s O", $pubdate),
					'mess'=>$s_text,
					'link'=>$sitehost.cc_link('/'.$cat_st[$i].'/'.substr($pfile,0,-4).'.html')
		  			);
				}
				$arfiles[$cat_st[$i]."/".$pfile]=articlesparam("pubdate",$data);
	    	}
	  	}
	}
}
reset($return);
usort ($return, "compare_arr");
$now = date("D, d M Y H:i:s O");

$output = "<?xml version=\"1.0\" encoding=\"utf8\"?>
<rss version=\"2.0\">
                <!-- Generated Kandidat-CMS -->
                <channel>
                    <title><![CDATA[Последние новости ".$sitename."]]></title>
                    <link>".$sitehost."/rss.html</link>
                    <description><![CDATA[Последние новости с сайта ".$sitehost."]]></description>
                    <lastBuildDate>".$now."</lastBuildDate>
                    <docs>".$sitehost."</docs>
                    <generator>Kadidat-CMS</generator>
                    ";

foreach($return as $line)
{
         $output .="<item>
                        <title><![CDATA[".strip_tags($line['head'])."]]></title>
                        <link>".htmlentities(strip_tags($line['link']),ENT_COMPAT,"cp1251")."</link>
                        <description><![CDATA[".strip_tags($line['mess'])."]]></description>
                        <author><![CDATA[example@example.com (porese)]]></author>
                        <pubDate>".$line['pubdate']."</pubDate>
                        <guid>".htmlentities(strip_tags($line['link']),ENT_COMPAT,"cp1251")."</guid>
                    </item>";

}
$output .= "
                </channel>
</rss>";
echo $output;
?>
