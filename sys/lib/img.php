<?php

/*
+--------------------------------------------------------------------------
| 生成缩略图&加水印的图片类
| ============================
| by Dash
| VeryCD.com - 共享互联网
| Modified by Brant 
| ============================
| 网址：http://www.VeryCD.com
| 邮件：Dash@VeryCD.com
+-------------------------------------------------------------------------- 
*/

Class img{

	var $input_type = "";	//输入图片的格式
	var $output_type = "";	//输出图片的格式
	var $limit = 0;  //图片大小限制
	var $limit_w = 0;
	var $limit_h = 0;
	var $filename = "";  //输入图片的文件名(也可以直接是图片数据)
	var $file_tmp_name ="";
	var $jpeg_quality = 90;	//jpeg图片质量
	var $save_file = '';  //输出文件名
	var $wm_text = "";  //水印文字( 不支持中文:'( )
	var $wm_size = 50;	//水印文字大小
	var $wm_angle = 0;	//水印文字角度
	var $wm_x = 50;  //水印x坐标
	var $wm_y = 50;  //水印y坐标
	var $wm_color = "#cccccc";	//水印颜色
	var $wm_fontfile = "geodesic.ttf";//水印字体文件
  var $display = false; // 生成后显示图片
   
  function upload(  $file = FALSE ) // 将上传文件生成缩略图 。
  {
    if ($file){
      $this->filename = $file['name'];
      $this->file_tmp_name = $file['tmp_name'];
    }
    $this->create();
  }
  
  function create() //生成缩略图
  {
    if ($this->limit !=0 ) {
      $this->limit_w = $this->limit_h = $this->limit;
    }
  
    if (!$this->input_type) $this->get_type();
    if (!$this->output_type) $this->output_type = $this->input_type;
    
    if ($this->input_type == "jpg") $this->input_type = "jpeg";
    if ($this->output_type == "jpg") $this->output_type = "jpeg";
    
    switch ($this->input_type){
      case 'gif':
      $src_img=ImageCreateFromGIF($this->file_tmp_name);
      break;
  
      case 'jpeg':
      $src_img=ImageCreateFromJPEG($this->file_tmp_name);
      break;
  
      case 'png':
      $src_img=ImageCreateFromPNG($this->file_tmp_name);
      break;
  
      default:
      $src_img=ImageCreateFromString($this->file_tmp_name);
      break;
  
    }
    
    
    $src_w=ImageSX($src_img);
    $src_h=ImageSY($src_img);
    
	  /* 限制尺寸
	         根据 宽度限制的情况
	     1. 宽度有限制，高度无限制
	     2. 宽度 高度 有限制, 宽高限制比 < 实际宽高比   
	  */
	              
	  $userWidth = FALSE;
      if ( $this->limit_h == 0 ) {
            $useWidth = TRUE;
      }
      elseif (($src_w/$src_h) >= ($this->limit_w/$this->limit_h)) {
          $useWidth = TRUE;
      }
      
      if ( $useWidth ){
      	if ($src_w>$this->limit_w){
        $new_w=$this->limit_w;
        $new_h=($this->limit_w / $src_w)*$src_h;
      	}
      }
      else{
      	if ($src_h>$this->limit_h){
        $new_h=$this->limit_h;
        $new_w=($this->limit_h / $src_h)*$src_w;
      	}
      }
      
      if ($new_h){
      	$dst_img=imagecreatetruecolor($new_w,$new_h);
      	imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,ImageSX($src_img),ImageSY($src_img));
      }
      else{
      	$dst_img = $src_img;
      }

      if ($this->wm_text)
      {
      	if(preg_match("/([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])/i", $this->wm_color, $color))
      	{
        $red = hexdec($color[1]);
        $green = hexdec($color[2]);
        $blue = hexdec($color[3]);
      	}
      	$wm_color = imagecolorallocatealpha($dst_img, $red, $green, $blue, 90);
      	imagettftext($dst_img, $this->wm_size, $this->wm_angle, $this->wm_x, $this->wm_y, $wm_color, $this->wm_fontfile,  $this->wm_text);
      }

      if ($this->save_file)
      {
      	switch ($this->output_type){
        case 'gif':
        	$src_img=ImagePNG($dst_img, $this->save_file);
        	break;
    	
        case 'jpeg':
        	$src_img=ImageJPEG($dst_img, $this->save_file, $this->jpeg_quality);
        	break;
    	
        case 'png':
        	$src_img=ImagePNG($dst_img, $this->save_file);
        	break;

        default:
        	$src_img=ImageJPEG($dst_img, $this->save_file, $this->jpeg_quality);
        	break;
    	
      	}  
      }
      
      if($this->display)
      {
      	header("Content-type: image/{$this->output_type}");
      	switch ($this->output_type){
        case 'gif':
        	$src_img=ImagePNG($dst_img);
        	break;
    	
        case 'jpeg':
        	$src_img=ImageJPEG($dst_img, "", $this->jpeg_quality);
        	break;
    	
        case 'png':
        	$src_img=ImagePNG($dst_img);
        	break;
    	
        default:
        	$src_img=ImageJPEG($dst_img, "", $this->jpeg_quality);
        	break;
      	}
      }
      imagedestroy($dst_img); 
	}
	
	function get_type()//获取图像文件类型
	{
    $this->filename = strtolower($this->filename);
    if (preg_match("/\.(pjpeg|jpg|jpeg|gif|png)$/", $this->filename, $matches))
    {
      $this->input_type = strtolower($matches[1]);
    }
    else
    {
      $this->input_type = "string";
    }
	}
  
	
}
