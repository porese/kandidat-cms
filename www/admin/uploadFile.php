<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include_once $path.'/admin/adminses.php';
function sendtext($out){
    echo '<script type="text/javascript">';
    echo 'window.parent.document.getElementById("res").innerHTML="'.$out.'";';
    echo '</script>';
};

	$maxlimit = 1200000; //максимальный вес файла
	$allowed_ext = array("jpg","jpeg","gif","png");
	$metod_razm = 0; //1 - по ширине и высоте, 0 - по большей из сторон
	$width_or_height = 1280; //максимальная величина одной из сторон большой фотки
	$width_thumb = 150; //максимальная величина одной из сторон уменьшеной фотки
	$height_thumb = $width_thumb;

	$width_z = 200; //Ширина
	$height_z = 200; //Высота

	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
		sendtext('invalid upload<br /><a href=photo.php?cat='.$cat.'&page='.$page.'>Обновите страницу.</a>');
		exit(0);
	}
	$errorList=array();
	$filesize = $_FILES['Filedata']['size'];
	if($filesize > 0){
		$filename = strtolower($_FILES['Filedata']['name']);
		$filename = preg_replace('/\s/', '_', $filename);
		if($filesize < 1){
			$errorList[] = "File size is empty.";
		}
		if($filesize > $maxlimit){
			$errorList[] = "File size is too big.";
		}
		if(count($errorList)<1){
			$filetype = strtolower(end(preg_split("/\./",$filename)));
			if(in_array($filetype,$allowed_ext)){

				list($width_orig_thumb, $height_orig_thumb) = getimagesize($_FILES["Filedata"]["tmp_name"]);
				$ratio_orig_thumb = $width_orig_thumb/$height_orig_thumb;

				if ($width_thumb/$height_thumb > $ratio_orig_thumb) {
					$width_thumb = $height_thumb*$ratio_orig_thumb;
				} else {
	   				$height_thumb = $width_thumb/$ratio_orig_thumb;
				}

				$new_img_thumb = ImageCreateTrueColor($width_thumb, $height_thumb);
				switch($filetype){
					case "gif":
						$image_thumb = imagecreatefromgif($_FILES["Filedata"]["tmp_name"]);
						break;
					case "jpg":
						$image_thumb = imagecreatefromjpeg($_FILES["Filedata"]["tmp_name"]);
						break;
					case "jpeg":
						$image_thumb = imagecreatefromjpeg($_FILES["Filedata"]["tmp_name"]);
						break;
					case "png":
						$image_thumb = imagecreatefrompng($_FILES["Filedata"]["tmp_name"]);
						break;
				}
				imagecopyresampled($new_img_thumb, $image_thumb, 0, 0, 0, 0, $width_thumb, $height_thumb, $width_orig_thumb, $height_orig_thumb);

				list($width_orig, $height_orig) = getimagesize($_FILES["Filedata"]["tmp_name"]);
				$ratio_orig = $width_orig/$height_orig;
				$width = $width_or_height;
				$height = $width;
				if(0 == $metod_razm){
					if(($width_orig<$width) && ($height_orig<$height)){
						$width = $width_orig;
						$height = $height_orig;
					}else{
						if ($width/$height > $ratio_orig) {
		   					$width = $height*$ratio_orig;
						} else {
		   					$height = $width/$ratio_orig;
						}
					}
				}else{
					$width = $width_z;
					$height = $height_z;
				}
				$new_img = ImageCreateTrueColor($width, $height);
				imagefilledrectangle($new_img, 0, 0, $width-1, $height-1, 0);
				switch($filetype){
					case "gif":
						$image = imagecreatefromgif($_FILES["Filedata"]["tmp_name"]);
						break;
					case "jpg":
						$image = imagecreatefromjpeg($_FILES["Filedata"]["tmp_name"]);
						break;
					case "jpeg":
						$image = imagecreatefromjpeg($_FILES["Filedata"]["tmp_name"]);
						break;
					case "png":
						$image = imagecreatefrompng($_FILES["Filedata"]["tmp_name"]);
						break;
				}
				if(0 == $metod_razm){
					imagecopyresampled($new_img, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				}else{
					if($width_orig>$height_orig){
						if($ratio_orig>1){
							$height=$height/$ratio_orig;
						}else{
							$height=$height*$ratio_orig;
						}
					}else{
						if($ratio_orig>1){
							$width=$width/$ratio_orig;
						}else{
							$width=$width*$ratio_orig;
						}
					}
					imagecopyresampled($new_img, $image, ($width_z-$width)/2, ($height_z-$height)/2, 0, 0, $width, $height, $width_orig, $height_orig);
				}

				// Use a output buffering to load the image into a variable
				ob_start();
				imagejpeg($new_img_thumb, null, 100);
				$imagevariable_thumb = ob_get_contents();
				ob_end_clean();

				ob_start();
				imagejpeg($new_img, null, 100);
				$imagevariable = ob_get_contents();
				ob_end_clean();

				$datakod = microtime(true);
				$f_id = ($_FILES["Filedata"]["tmp_name"] + (10000000000-$datakod)*100);
				$file_id =  'i_'.$f_id.'.'.$filetype;

				$pictures_mtime = 0;
				if (is_file(PICTURES . $cat . '/' . $file_id)) {
					$pictures_mtime = filemtime(PICTURES . $cat . '/' . $file_id); }

				$fd = fopen(PICTURES . $cat . '/' . $file_id, "wb");
		  		fwrite($fd, $imagevariable);
		  		fclose($fd);
				$file_id_thumb =  'ti_'.$f_id.'.'.$filetype;

				$pictures_mtime_thumb = 0;
				if (is_file(PICTURES . $cat . '/thumb/' . $file_id_thumb)) {
					$pictures_mtime_thumb = filemtime(PICTURES . $cat . '/thumb/' . $file_id_thumb); }

				$fd_thumb = fopen(PICTURES . $cat . '/thumb/' . $file_id_thumb, "wb");
		  		fwrite($fd_thumb, $imagevariable_thumb);
		  		fclose($fd_thumb);

		      	echo "success";
				sendtext('Файл '.$filename.' загружен.<br /><a href=photo.php?cat='.$cat.'&page='.$page.'>Обновите страницу.</a>');
			}else{
			    echo "error";
				sendtext('Обнаружены следующие ошибки:<br />Файлы данного типа загрузке не подлежат<br /><a href=photo.php?cat='.$cat.'&page='.$page.'>Обновите страницу.</a>');
			}
		}else{
		    echo "error";
			sendtext('Обнаружены следующие ошибки:<br />'.implode('<br />',$errorList).'<br /><a href=photo.php?cat='.$cat.'&page='.$page.'>Обновите страницу.</a>');
		}
	}else{
	    echo "error";
		sendtext('Ничего не загрузилось.<br /><a href=photo.php?cat='.$cat.'&page='.$page.'>Обновите страницу.</a>');
	}
?>
