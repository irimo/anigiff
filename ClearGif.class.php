<?php

require_once(DIRNAME(__FILE__)."/../../conf/conf.php");

class ClearGif {

	var $clear_array;	// ���ߐF�z��
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
	  if(ord($c) === 0x39) {	// GIF89a�ł���(�A�j���[�V��������)
	    $src .= $c;
	    
	    $i = 0;
	    // Graphic Control Extension����
	    while(!feof($fp)){
	      do{
	        $c = fread($fp,1);
	        $src .= $c;
	      }while(ord($c) !== 0x21 && !feof($fp));	// �g���u���b�N�J�n�܂ő���
	      if(feof($fp)){
	      	break;
	      }
	      $c2 = fread($fp,2);
	      $src .= $c2;
	      if(bin2hex($c2) === "f904"){	// Graphic Control Extension�Œ�l
	        
	        $c3 = fread($fp,1);
	        if(isset($clear_arr[$i]) && $clear_arr[$i] < 256){
				$c3 = $c3 | pack("c",0x01);	// ���߂���
		        $src .= $c3;
		        $src .= fread($fp,2);	// �\������ۂ̒x������(���t�@�C���̂܂�)
		        $src .= pack("c",$clear_arr[$i]);	// ���ߐF�̃C���f�b�N�X
		        fread($fp,1);	// ���ߐF�C���f�b�N�X(1B)���𑗂�
	        } else {
	        	$c3 = $c3 & pack("c",0xfe);	// ���߂��Ȃ�
	        	$src .= $c3;
	        	$src .= fread($fp,2);	// �\������ۂ̒x������(���t�@�C���̂܂�)
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
	// ���t�@�C���ɏ㏑��
	  file_put_contents($imgpath,$src);
    }
}
?>