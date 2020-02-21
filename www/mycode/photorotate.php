<?
//phpfile
#Ротатор фотографий из выбранной категории
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');
include_once CONF.'photoconf.php';

function select_pic($cat_rotate='') {
    if(!file_exists(PICTURES.$cat_rotate))return '';
    $types_pic=array('jpg');
    if ($handle = opendir(PICTURES.$cat_rotate)) {
		$a = 1;
		while (false !== ($file = readdir($handle))) {
		    if($file{0}=='.' || !in_array(getftype($file),$types_pic))continue;
		    $f_name[$a] = $file;
		    $a++;
		}
		closedir($handle);
    }
    $number = rand(1, $a-1);
    $image = $f_name[$number];
    return $image;
}
function showpic($cat_rotate){
	global $prefflp,$gallerypath;
	$random_pic = select_pic($cat_rotate);
	echo '<div class="gallery">
	<a href="'.$prefflp.'/'.$gallerypath.'/'.$cat_rotate.'/'.$random_pic.'"  rel="prettyPhoto[gallery-'.$cat_rotate.']" title="Кликните на изображении для увеличения.">
	<img src="'.$prefflp.'/'.$gallerypath.'/'.$cat_rotate.'/thumb/t'.$random_pic.'" alt=" " /></a>
	</div>';
}
showpic($photorotpath);
?>
