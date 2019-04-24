<?php
// mana 20081030-

require_once(DIRNAME(__FILE__)."/../../conf/conf.php");
require_once (CLASS_DIR."/anigiff/GIFEncoder.class.php");

class CreateGifAnimeFromImage{
	var $url;
	var $file;
	var $filename;
	var $fileaname_arr;
	var $delay_arr;
	var $loop = 0;
	function __construct($fileaname_arr){
		$this->filename = date("Ymdsim").'.gif';
		$this->file = TMP_DIR."/".$this->filename;
		$this->url = DOCROOT."tmp_img/".$this->filename;
		$this->filename_arr = $fileaname_arr;
	}
	function setSecond($delay_arr){
		$this->delay_arr = $delay_arr;
	}
	function setLoop($loop){
		$this->loop = $loop;
	}

	function create(){
		$images = $this->filename_arr;
		if(is_array($this->delay_arr)){
			$second = $this->delay_arr;
		} else {
			for($i=0; $i<count($this->filename_arr); $i++){
				$second[] = 30;
			}
		}

		$ani_class = new GIFEncoder($images,$second,$this->loop,2, 255,255,255,'url');
		$img = $ani_class->getAnimation();
		
		file_put_contents($this->file,$img);

		copy($this->file,DOCROOT_DIR."/tmp_img/".$this->filename);
	}

	function getImgUrl(){
		return $this->url;
	}
	
	function getFilePath(){
		return DOCROOT_DIR."/tmp_img/".$this->filename;
	}
	
}