<?php
#Облако тегов на js. Подключается по include в удобном месте шаблона.
if(file_exists(ENGINE.'cloudtagsdb.php')){
	$arrclouds = array();
//	$cloud=file(ENGINE.'cloudtagsdb.php');
	$cloud=unserialize(file_get_contents(ENGINE.'cloudtagsdb.php'));
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
		$x=$sdata[2];
		$y=$sdata[3];
		$color=$sdata[4];
		$outlinecolor=$sdata[5];

	}else exit;
}
?>
<script src="/js/jquery.tagcanvas.min.js" type="text/javascript"></script>
<div id="myCanvasContainer">
<canvas width="<?php echo $x;?>" height="<?php echo $y;?>" id="myCanvas">
<p>Anything in here will be replaced on browsers that support the canvas element</p>
</canvas>
</div>
<div id="tags">
	<?php echo $tags;?>
</div>
<script type="text/javascript">
  window.onload = function() {
    try {
      TagCanvas.Start('myCanvas',"tags",{
            textColour: "<?php echo $color;?>",
            outlineColour: "<?php echo $outlinecolor;?>",
            reverse: true,
            depth: 0.8,
            maxSpeed: 0.05
          });
    } catch(e) {
      // something went wrong, hide the canvas container
      document.getElementById('myCanvasContainer').style.display = 'none';
    }
  };
</script>
