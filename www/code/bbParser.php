<?php
  class bbParser{
    public $disableURL=0;
    public function __construct($disableURL=0){
	$this->disableURL=(int)$disableURL;
    }

    public function getHtml($str){
      $bb[] = "/\[b\](.*?)\[\/b\]/si";
      $html[] = "<b>\\1</b>";
      $bb[] = "/\[i\](.*?)\[\/i\]/si";
      $html[] = "<i>\\1</i>";
      $bb[] = "/\[u\](.*?)\[\/u\]/si";
      $html[] = "<u>\\1</u>";
      $bb[] = "/\[hr\]/si";
      $html[] = "<hr>";
	  $bb[] = "/\[s\](.*?)\[\/s\]/si";
      $html[] = "<span style=\"text-decoration: line-through;\">\\1</span>";
	  $bb[] = "/\[left\](.*?)\[\/left\]/si";
      $html[] = "<div style=\"text-align: left;\">\\1</div>";
	  $bb[] = "/\[center\](.*?)\[\/center\]/si";
      $html[] = "<div style=\"text-align: center;\">\\1</div>";
	  $bb[] = "/\[right\](.*?)\[\/right\]/si";
      $html[] = "<div style=\"text-align: right;\">\\1</div>";
      $bb[] = "/\[code\](.*?)\[\/code\]/si";
      $html[] = "<p><code>\\1</code></p>";
	  $bb[] = "/\[code\](.*?)\[\/code\]/si";
      $html[] = "<p><code>\\1</code></p>";
      $bb[] = "/\[list=\*\](.*?)\[\/list\]/si";
      $html[] = "<ul>\\1</ul>";
      $bb[] = "/\[list=1\](.*?)\[\/list\]/si";
      $html[] = "<ol>\\1</ol>";
      $bb[] = "/\[\*\](.*?)\[\/\*\]/si";
      $html[] = "<li>\\1</li>";

      $str = preg_replace ($bb, $html, $str);
      $patern="/\[url=([^\]]*)\]([^\[]*)\[\/url\]/i";
      if($this->disableURL==1) $replace='\\2';
      else $replace='<a href="\\1" target="_blank" rel="nofollow">\\2</a>';
      $str=preg_replace($patern, $replace, $str); //преобразование ссылок
      $patern="/\[email\]([^\[]*)\[\/email\]/i";
      $replace='<a href="mailto:\\1">\\1</a>';
      $str=preg_replace($patern, $replace, $str); //преобразование мыла
      $patern="/\[video\]([^\[]*)\[\/video\]/i";
      $replace='<embed type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" src="\\1" style="width: auto; height: auto;" play="true" loop="true" menu="true" height="300">';
      $str=preg_replace($patern, $replace, $str); //преобразование флеша
      $patern="/\[img\]([^\[]*)\[\/img\]/i";
      $replace='<img src="\\1" alt="" />';
      $str=preg_replace($patern, $replace, $str);  //преобразование картинок
      return $str;
    }
  }
?>
