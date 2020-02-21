<?php
$path=substr(str_replace('\\','/',dirname(__FILE__)),0,-6);
include $path.'/admin/adminses.php';
include CONF.'photoconf.php';
$page = (int)$_GET[page];
$cat  = (isset ($_GET['cat']))? $_GET['cat'].'/' : '';
/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
		$metod_razm = 0; //1 - по ширине и высоте, 0 - по большей из сторон
		$width_or_height = 1280; //максимальная величина одной из сторон большой фотки
		$width_thumb = 150; //максимальная величина одной из сторон уменьшеной фотки
		$height_thumb = $width_thumb;
		$width_z = 200; //Ширина
		$height_z = 200; //Высота

        $filename=$this->getName();
		$filetype = strtolower(end(preg_split("/\./",$filename)));

		list($width_orig_thumb, $height_orig_thumb) = getimagesize("php://input");
		$ratio_orig_thumb = $width_orig_thumb/$height_orig_thumb;

		if ($width_thumb/$height_thumb > $ratio_orig_thumb) {
			$width_thumb = $height_thumb*$ratio_orig_thumb;
		} else {
			$height_thumb = $width_thumb/$ratio_orig_thumb;
		}

		$new_img_thumb = ImageCreateTrueColor($width_thumb, $height_thumb);
		switch($filetype){
			case "gif":
				$image_thumb = imagecreatefromgif("php://input");
				break;
			case "jpg":
				$image_thumb = imagecreatefromjpeg("php://input");
				break;
			case "jpeg":
				$image_thumb = imagecreatefromjpeg("php://input");
				break;
			case "png":
				$image_thumb = imagecreatefrompng("php://input");
				break;
		}
		imagecopyresampled($new_img_thumb, $image_thumb, 0, 0, 0, 0, $width_thumb, $height_thumb, $width_orig_thumb, $height_orig_thumb);

		list($width_orig, $height_orig) = getimagesize("php://input");
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
				$image = imagecreatefromgif("php://input");
				break;
			case "jpg":
				$image = imagecreatefromjpeg("php://input");
				break;
			case "jpeg":
				$image = imagecreatefromjpeg("php://input");
				break;
			case "png":
				$image = imagecreatefrompng("php://input");
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
		$f_id = (10000000000-$datakod)*100;
		$file_id =  'i_'.$f_id.'.'.$filetype;

		$pictures_mtime = 0;
		if (is_file($path . '/' . $file_id)) {
			$pictures_mtime = filemtime($path . "/" . $file_id); }

		$fd = fopen($path . '/' . $file_id, "wb");
  		fwrite($fd, $imagevariable);
  		fclose($fd);
		$file_id_thumb =  'ti_'.$f_id.'.'.$filetype;

		$pictures_mtime_thumb = 0;
		if (is_file($path . "/thumb/" . $file_id_thumb)) {
			$pictures_mtime_thumb = filemtime($path . "/thumb/" . $file_id_thumb); }

		$fd_thumb = fopen($path . "/thumb/" . $file_id_thumb, "wb");
  		fwrite($fd_thumb, $imagevariable_thumb);
  		fclose($fd_thumb);

        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}


class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->file = new qqUploadedFileXhr();
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty');
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }

        if ($this->file->save($uploadDirectory)){
            return array('success'=>true);
        } else {                          
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }

    }
}

$allowedExtensions = array("jpg","jpeg","gif","png");
$sizeLimit = 2 * 1024 * 1024; //Максимальный размер файла

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload(PICTURES.$cat);
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
