<?php

class ColorList {

    function __construct() {
    }
    function getColorCount($img_path){
		$imgobj = imagecreatefromgif($img_path);
		$count = imagecolorstotal($imgobj);
    	return $count;
    }
    function getColorArray($img_path){
		$ret = array();
		$imgobj = imagecreatefromgif($img_path);
		list($width, $height, $type, $attr) = getimagesize($img_path);
		$count = imagecolorstotal($imgobj);
		for($i=0; $i<$count; $i++){
	      $rgbarray = imagecolorsforindex($imgobj,$i);
	      $color = sprintf("%02x%02x%02x",$rgbarray["red"],$rgbarray["green"],$rgbarray["blue"]);
	      $ret[$i] = $color;
	    }
		return $ret;
	
    }
}

?>