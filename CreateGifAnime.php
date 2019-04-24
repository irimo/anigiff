<?php
// mana 20081030-

require_once(DIRNAME(__FILE__)."/../../conf/conf.php");
require_once(DIRNAME(__FILE__)."/GIFEncoder.class.php");

class CreateGifAnime{
	var $url;
	var $file;
	var $filename;
	var $fileaname_arr;
	function __construct($arr){
		$this->filename = date("Ymdsim").'.gif';
		$this->file = TMP_DIR."/".$this->filename;
		$this->url = DOCROOT."/tmp_img/".$this->filename;
		$this->filename_arr = $arr;
	}

	function create($loop = 0){
		$images = $this->filename_arr;
		$second = array();
		for($i=0; $i<count($this->filename_arr); $i++){
			$second[] = 30;
		}
		$delay = 0;
		$disp = 0;

		$ani_class = new GIFEncoder($images,$second,$loop,2, 255,255,254,'url');
		$img = $ani_class->getAnimation();
		
		$fp = fopen($this->file,"wb");
		fwrite($fp,$img,10000);
		fclose($fp);

		copy($this->file,DOCROOT_DIR."/tmp_img/".$this->filename);
	}

	function getImgUrl(){
		return $this->url;
	}
	
}