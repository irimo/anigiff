<?php
// mana @20090328

class IfAnimationGif{
	var $path;
	function __construct($path){
		$this->path = $path;
	}
	function if1Img(){
		$imgcnt = 0;
		
		$fp = fopen($this->path,"rb");
		@fread($fp,4);
		$c = @fread($fp,1);
		if(ord($c) === 0x37) {	// GIF87aである(アニメーションではない)
			return true;
		} elseif(ord($c) !== 0x39){
			return false;
		}

		while(!feof($fp)){
			do{
				$c = fread($fp,1);
			}while(ord($c) !== 0x21 && !feof($fp));	// 拡張ブロック開始まで送る
			if(feof($fp)){
				break;
			}
			$c2 = fread($fp,2);
			if(bin2hex($c2) === "f904"){	// Graphic Control Extension固定値
				$imgcnt++;
			}
			if(feof($fp)){
				break;
			}
			
		}
//		var_dump( $imgcnt);
		if($imgcnt > 1){
			return false;
		} else {
			return true;
		}
	}
}/*
$anigif = new IfAnimationGif($_REQUEST["Filedata"]);
echo var_dump($anigif->if1Img());*/
?>
