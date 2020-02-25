<?php
#Облако тегов на js. Подключается по include в удобном месте шаблона.
if(file_exists(ENGINE.'cloudtagsdb.php')){
	$arrclouds = array();
	$cloud=file(ENGINE.'cloudtagsdb.php');
	$sizeofcloud=sizeof($cloud);
	if($sizeofcloud>0){
		$sdata=explode('%',$cloud[0]);
		for($i=1;$i<$sizeofcloud;$i++){
			$data=explode('%%',$cloud[$i]);
			$arrclouds[$data[0]]=$arrclouds[$data[0]]+1;
		}
		$tags = '<ul>';
		if(is_array($arrclouds))foreach($arrclouds as $key=>$val)$tags .= '<li><a href="'.cc_link('/cloudtags-'.translit($key).'.html').'" style="font-size: '.($sdata[1]+$val-1).'pt">'.$key.'</a></li>';
		$tags.= '</ul>';
	}else exit;
}
?>
<script src="/js/jquery.tagcanvas.min.js" type="text/javascript"></script>
<div id="myCanvasContainer">
<canvas width="200" height="300" id="myCanvas">
<p>Anything in here will be replaced on browsers that support the canvas element</p>
	<?php echo $tags;?>
</canvas>
</div>
<script type="text/javascript">
  window.onload = function() {
    try {
      TagCanvas.Start('myCanvas');
    } catch(e) {
      // something went wrong, hide the canvas container
      document.getElementById('myCanvasContainer').style.display = 'none';
    }
  };
</script>
