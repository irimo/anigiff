<?php

require_once(DIRNAME(__FILE__)."/../../conf/conf.php");

class ClearGif {

	var $clear_array;	// 透過色配列
	var $imgpath;
    function __construct($imgpath) {
    	$this->imgpath = $imgpath;
    }
    function setClearArray($array){
    	$this->clear_array = $array;
    }
    function make(){
    	
	  $clear_arr = $this->clear_array;
	
	  $imgpath = $this->imgpath;
	  $fp = fopen($imgpath,"rb");
	  $src = fread($fp,4);
	  $c = fread($fp,1);
	  if(ord($c) === 0x39) {	// GIF89aである(アニメーションかも)
	    $src .= $c;
	    
	    $i = 0;
	    // Graphic Control Extension調整
	    while(!feof($fp)){
	      do{
	        $c = fread($fp,1);
	        $src .= $c;
	      }while(ord($c) !== 0x21 && !feof($fp));	// 拡張ブロック開始まで送る
	      if(feof($fp)){
	      	break;
	      }
	      $c2 = fread($fp,2);
	      $src .= $c2;
	      if(bin2hex($c2) === "f904"){	// Graphic Control Extension固定値
	        
	        $c3 = fread($fp,1);
	        if(isset($clear_arr[$i]) && $clear_arr[$i] < 256){
				$c3 = $c3 | pack("c",0x01);	// 透過する
		        $src .= $c3;
		        $src .= fread($fp,2);	// 表示する際の遅延時間(元ファイルのまま)
		        $src .= pack("c",$clear_arr[$i]);	// 透過色のインデックス
		        fread($fp,1);	// 透過色インデックス(1B)分を送る
	        } else {
	        	$c3 = $c3 & pack("c",0xfe);	// 透過しない
	        	$src .= $c3;
	        	$src .= fread($fp,2);	// 表示する際の遅延時間(元ファイルのまま)
	        	$src .=  fread($fp,1);
	        }
	 	       $i++;
	      }
	      
	    }
	  } else {
	  	$src .= $c;
	  }
	  while(!feof($fp)){
	    $src .= fread($fp,1);
	  }
	  fclose($fp);
	// 元ファイルに上書き
	  file_put_contents($imgpath,$src);
    }
}
?>