<?php
	function inList($in_list,$item)	{
		return strstr(",".$in_list.",", ",".$item.",");
	}


	function getFilesInDir($path,$extensionList="",$prependPath=0,$order="")	{
		$filearray=array();
		$sortarray=array();
		if ($path)	{
			$path = ereg_replace("/$","",$path);
			$extensionList = strtolower($extensionList);
			$d = @dir($path);
			if (is_object($d))	{
				while($entry=$d->read()) {
					if (@is_file($path."/".$entry))	{	
						$fI = pathinfo($entry);
						$key = md5($path."/".$entry);
						if (!$extensionList || inList($extensionList,strtolower($fI["extension"])))	{
						    $filearray[$key]=($prependPath?$path."/":"").$entry;
							if ($order=="mtime") {$sortarray[$key]=filemtime($path."/".$entry);}
								elseif ($order)	{$sortarray[$key]=$entry;}
						}
					}
				}
				$d->close();
			} else return "error";
		}
		if ($order) {
			asort($sortarray);
			reset($sortarray);
			$newArr=array();
			while(list($k,$v)=each($sortarray))	{
				$newArr[$k]=$filearray[$k];
			}
			$filearray=$newArr;
		}
		reset($filearray);
		return $filearray;
	}



if (isset($HTTP_GET_VARS["file"]))	{
	$info = pathinfo($HTTP_GET_VARS["file"]);
	if (isset($info["extension"]))	{
		switch(strtolower($info["extension"]))	{
			case "png":
				$im = imagecreatefrompng($HTTP_GET_VARS["file"]);
			break;
			case "gif":
				$im = imagecreatefromgif($HTTP_GET_VARS["file"]);
			break;
			case "jpg":
			case "jpeg":
				$im = imagecreatefromjpeg($HTTP_GET_VARS["file"]);
			break;
		}
    // Colors
    $white = imagecolorallocate($im, 255, 255, 255);
    $black = imagecolorallocate($im, 0, 0, 0);
    $grey = imagecolorallocate($im, 200, 200, 200);

    imagettftext ($im, 25, 0, 6, 100, $black, './vera.ttf', "Hello Andreas Ottomatic");


	imagetruecolortopalette($im, 1, 255);
#imageFilterBlur($im,5);



    // Grid so you can see
    #imagerectangle($im, 10, 6, 206, 300, $grey);
    #imagerectangle($im, 10, 6, 206, 153, $grey);
    #imagerectangle($im, 10, 6, 108, 300, $grey);
#	imagetruecolortopalette($im, 1, 3);
		switch(strtolower($HTTP_GET_VARS["output"]?$HTTP_GET_VARS["output"]:$info["extension"]))	{
			case "png":
				  header ("Content-type: image/png");
				  imagepng ($im);
			break;
			case "gif":
				  header ("Content-type: image/gif");
				  imagegif ($im);
			break;
			case "jpg":
			case "jpeg":
				  header ("Content-type: image/jpeg");
				  imagejpeg ($im);
			break;
		}
	}
} else {
	$files = getFilesInDir("./","jpeg,jpg,gif,png",1);
	$opt=array();
	$opt[]='<option></option>';
	foreach($files as $filename)	{
		$opt[]='<option value="'.htmlspecialchars($filename).'">'.htmlspecialchars($filename).'</option>';
	}

	$opt2=array();
	$opt2[]='<option></option>';
	$opt2[]='<option value="jpg">JPG</option>';
	$opt2[]='<option value="png">PNG</option>';
	$opt2[]='<option value="gif">GIF</option>';

	echo '<form action="./imagecreate_test.php" method="get" target="image">
		<select name="file">'.implode("",$opt).'</select>
		<select name="output">'.implode("",$opt2).'</select>
		<input type="submit" name="SHOW">
		</form>';
}
	
	
/*	
	

$im = imagecreatefrompng(t3lib_extMgm::extPath("install")."imgs/jesus.png");

			


imagetruecolortopalette ($src,1,255);

  header ("Content-type: image/gif");
  imagegif ($src);
*/


?>
