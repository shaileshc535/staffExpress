<?php
class MyPICUPLOAD
{
	var $picpath;
	var $thumbwidth;
	var $thumbheight;
	var $fieldname;
	var $mode;
	var $expicname;
	
	
	function MyPICUPLOAD($picpath, $thumbwidth, $thumbheight,$fieldname,$mode,$expicname)
	{
		$this->picpath=$picpath;
		$this->thumbwidth=$thumbwidth;
		$this->thumbheight=$thumbheight;
		$this->fieldname=$fieldname;
		$this->mode=$mode;
		$this->expicname=$expicname;
	
		
	}
	
	function checkmyimgfile()
	{
	 $srcfile=$_FILES[$this->fieldname]['type'];
	 $mysrcarr=explode("/",$srcfile);
	 if($mysrcarr[0]=='image')
	 {
	  $errstring="";
	 }
	 else
	 {
	   $errstring="File format not supported";
	 }
	 return $errstring;
	}
	
	
	
	function checkmypdffile()
	{
		 $srcfile=$_FILES[$this->fieldname]['type'];
		 if($srcfile=="")
		 {
		  $errstring="Please Upload advertisement in Pdf format";
		 }
		 else
		 {
			 $mysrcarr=explode("/",$srcfile);
			 if(trim($mysrcarr[1])=='pdf')
			 {
			  $errstring="";
			 }
			 else
			 {
			   $errstring="File format not supported";
			 }
		 }
	  return $errstring;
	}
	function uploadpic()
	{
		
		$today=time();
		$newfname="";
		if(isset($_FILES[$this->fieldname]) && $_FILES[$this->fieldname]["size"]>0)
			{
					
				$srcfile=$_FILES[$this->fieldname]['type'];
				$imageinfo = getimagesize($_FILES[$this->fieldname]['tmp_name']); //check image size
				$mflname=strtolower($_FILES[$this->fieldname]['name']);
				
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
			  $errstring1="";
			}
			else
			{
			  $errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html",".exe");
			
			if(strpos($mflname,".php")===false && strpos($mflname,".phtml")===false && strpos($mflname,".php3")===false && strpos($mflname,".php4")===false && strpos($mflname,".php5")===false && strpos($mflname,".html")===false && strpos($mflname,".htm")===false && strpos($mflname,".exe")===false) 
					{
								
						$errstring2="";
					}
					else
					{
						
						$errstring2="Please Upload only image file.(.jpg,.png,.gif)";
					}
					
					$dblchk = array();
					
					$dblchk=explode(".",$mflname);
					
					if(count($dblchk)==2) 
					{
								
						$errstring3="";
					}
					else
					{
						
						$errstring3="Please Upload only image file.(.jpg,.png,.gif)";
					}
				
				if($errstring1=="" && $errstring2=="" && $errstring3=="")
				{
					$srcfilename=$_FILES[$this->fieldname]['tmp_name'];
					$name=$_FILES[$this->fieldname]['name'];
					$srcfile=$_FILES[$this->fieldname]['type'];
				
					//list($n, $ext) = split('[.]', $name);
					$dimension = getimagesize($_FILES[$this->fieldname]['tmp_name']);
					$width=$dimension[0];
					$height=$dimension[1];
					$newfname=$today."_".strtolower($name);
				
			
					if(!is_dir($this->picpath."/thumb"))
					{
						mkdir($this->picpath."/thumb", 0777);
						chmod($this->picpath."/thumb", 0777);
					}
					
				
				
					$destimage=$this->picpath."/".$newfname;
					
					move_uploaded_file($_FILES[$this->fieldname]['tmp_name'],$destimage);
				
						$type=$srcfile;
						$new_path=$destimage;
						
						$thumb_path=$this->picpath."/thumb/";
						
						
						$model_width=$this->thumbwidth;
						$model_height=$this->thumbheight;
						
						if($height<$model_height && $width<$model_width)
						{
							$height1=$height;
							$width1=$width;
						}
						else
						{
						
								
						$img_ratio = $height/$width;
						$std_ratio = $model_height/$model_width;
						
						if ($height > $model_height && $width <= $model_width) {
							$height1 = $model_height;
							$width1 = $height1 * (1/$img_ratio);
						} 
						elseif ($width > $model_width && $height <= $model_height) {
							$width1 = $model_width;
							$height1 = $width1 * $img_ratio;
						} 
						elseif ($height <= $model_height && $width <= $model_width) {
							$height1=$height;
							$width1=$width;
						} 
						elseif ($height > $model_height && $width > $model_width) {
							if ($std_ratio > $img_ratio) {
									$width1 = $model_width;
									$height1 = $width1 * $img_ratio;
								} else {
									$height1 = $model_height;
									$width1 = $height1 * (1/$img_ratio);
								}
						}
						}	
						
						$sx=0;
						$sy=0;
						if($type=="image/gif")
						{
						$m_img=imagecreatefromgif($new_path);
						}
						elseif($type=="image/jpeg" || $type=="image/pjpeg")
						{
						$m_img=imagecreatefromjpeg($new_path);
						}
						elseif($type=="image/png" || $type=="image/x-png")
						{
						$m_img=imagecreatefrompng($new_path); 
						}
						
						$t_img=imagecreatetruecolor($width1,$height1);
						imagecopyresampled($t_img,$m_img,0,0,$sx,$sy,$width1,$height1,$width,$height);
						if($type=="image/gif")
						{
						imagegif($t_img,$thumb_path.$newfname);
						}
						elseif($type=="image/jpeg" || $type=="image/pjpeg")
						{
						imagejpeg($t_img,$thumb_path.$newfname); 
						}
						elseif($type=="image/png" || $type=="image/x-png")
						{
						imagepng($t_img,$thumb_path.$newfname); 
						}
						
						}
						
				if($this->mode=='edit')
				{
				 @unlink($this->picpath."/".$this->expicname);
				 @unlink($this->picpath."/thumb/".$this->expicname);
				}
						
			}
			 else
			 {
			   if($this->mode=='edit')
			   {
			     $newfname=$this->expicname;
			   }
			   elseif($this->mode=='add' || $this->mode=='')
			   {
			     $newfname='';
			   }
			 }
		
		
		
		
		
		return $newfname;
		
	}
	
	function uploaddocument()
	{}
	
	function uploadpicspcl()
	{}
	
	
}
?>