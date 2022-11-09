<?php include('../config/config.php'); 
include('includes/session.php');
include_once "../config/common.php";
$dbConn = establishcon();
if(count($_POST) > 0)
	{
		$name = isset($_POST['name'])?$_POST['name']:"";
		$description = isset($_POST['description'])?strip_tags($_POST['description'], '<p><h2><h3><strong><ul><ol><li><span><br><a>'):"";
		$meta_keyword = isset($_POST['meta_keyword'])?strip_tags($_POST['meta_keyword']):"";
		$meta_desc = isset($_POST['meta_desc'])?strip_tags(trim($_POST['meta_desc'])):"";
		$slug = strtolower(str_replace(" ", "-", $name));

		$heading1 = isset($_POST['heading1'])?strip_tags(trim($_POST['heading1']), '<span>'):"";
		$heading2 = isset($_POST['heading2'])?strip_tags(trim($_POST['heading2']), '<span>'):"";
		$heading3 = isset($_POST['heading3'])?strip_tags(trim($_POST['heading3']), '<span>'):"";
		$subheading = isset($_POST['subheading'])?strip_tags(trim($_POST['subheading']), '<span><br>'):"";
		// how it works
		$rightheading1 = isset($_POST['rightheading1'])?strip_tags(trim($_POST['rightheading1'])):"";
		$rightsubheading1 = isset($_POST['rightsubheading1'])?strip_tags(trim($_POST['rightsubheading1'])):"";
		$rightheading2 = isset($_POST['rightheading2'])?strip_tags(trim($_POST['rightheading2'])):"";
		$rightsubheading2 = isset($_POST['rightsubheading2'])?strip_tags(trim($_POST['rightsubheading2'])):"";
		$rightheading3 = isset($_POST['rightheading3'])?strip_tags(trim($_POST['rightheading3'])):"";
		$rightsubheading3 = isset($_POST['rightsubheading3'])?strip_tags(trim($_POST['rightsubheading3'])):"";
		$sectntwohead = isset($_POST['sectntwohead'])?strip_tags(trim($_POST['sectntwohead'])):"";
		
		$topjobcathead = isset($_POST['topjobcathead'])?strip_tags(trim($_POST['topjobcathead'])):"";
		$topjobcattext = isset($_POST['topjobcattext'])?strip_tags(trim($_POST['topjobcattext'])):"";
		
		$whystafftext1 = isset($_POST['whystafftext1'])?strip_tags(trim($_POST['whystafftext1'])):"";
		$whystafftext2 = isset($_POST['whystafftext2'])?strip_tags(trim($_POST['whystafftext2'])):"";
		$whystafftext3 = isset($_POST['whystafftext3'])?strip_tags(trim($_POST['whystafftext3'])):"";
		$whystafftext4 = isset($_POST['whystafftext4'])?strip_tags(trim($_POST['whystafftext4'])):"";
		$secthreehead = isset($_POST['secthreehead'])?strip_tags(trim($_POST['secthreehead'])):"";
		$secthreetext1 = isset($_POST['secthreetext1'])?strip_tags(trim($_POST['secthreetext1'])):"";
		$secthreetext2 = isset($_POST['secthreetext2'])?strip_tags(trim($_POST['secthreetext2'])):"";
		$secthreetext3 = isset($_POST['secthreetext3'])?strip_tags(trim($_POST['secthreetext3'])):"";
		$secthreetext4 = isset($_POST['secthreetext4'])?strip_tags(trim($_POST['secthreetext4'])):"";

		$id = isset($_POST['id'])?$_POST['id']:"";

		if($id){
			$sql_res = dbQuery($dbConn,"SELECT slug from `pages` where id = '".$id."'");
			$sql_res_fetch = dbFetchAssoc($sql_res);
			
			dbQuery($dbConn, "UPDATE pages set name = '".tep_db_input($name)."',
			description = '".tep_db_input($description)."',
			`meta_keyword` = '".tep_db_input($meta_keyword)."',
			meta_desc = '".tep_db_input($meta_desc)."' where id = '".$id."'");

			if($sql_res_fetch['slug'] == ''){
				dbQuery($dbConn, "UPDATE pages set slug = '".tep_db_input($slug)."' where id = '".$id."'");
			}

			if($id == 1){
				dbQuery($dbConn, "UPDATE pages set 
				heading1 = '".tep_db_input($heading1)."',
				heading2 = '".tep_db_input($heading2)."',
				heading3 = '".tep_db_input($heading3)."',
				subheading = '".tep_db_input($subheading)."',
				rightheading1 = '".tep_db_input($rightheading1)."',
				rightsubheading1 = '".tep_db_input($rightsubheading1)."',
				rightheading2 = '".tep_db_input($rightheading2)."',
				rightsubheading2 = '".tep_db_input($rightsubheading2)."',
				rightheading3 = '".tep_db_input($rightheading3)."',
				rightsubheading3 = '".tep_db_input($rightsubheading3)."',
				sectntwohead = '".tep_db_input($sectntwohead)."',
				whystafftext1 = '".tep_db_input($whystafftext1)."',
				whystafftext2 = '".tep_db_input($whystafftext2)."',
				whystafftext3 = '".tep_db_input($whystafftext3)."',
				whystafftext4 = '".tep_db_input($whystafftext4)."',
				secthreehead = '".tep_db_input($secthreehead)."',
				secthreetext1 = '".tep_db_input($secthreetext1)."',
				secthreetext2 = '".tep_db_input($secthreetext2)."',
				secthreetext3 = '".tep_db_input($secthreetext3)."',
				secthreetext4 = '".tep_db_input($secthreetext4)."'
				where id = '".$id."'");

			$mytime=time();
			if(isset($_FILES["leftimg"]) && $_FILES["leftimg"]["size"]>0)
			{
				
				$updimgnm='';

				$srcfile = $_FILES['leftimg']['type'];
				$imageinfo = getimagesize($_FILES['leftimg']['tmp_name']); //check image size
				
				$mflname = strtolower($_FILES['leftimg']['name']);
				$mflname = str_replace(" ", "_", $mflname);
				
				if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
				{
					$errstring1="";
				}
				else
				{
					$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
				
				}
				
						$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
				
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
				
				if($errstring1=="" && $errstring2=="")
				{
					if(move_uploaded_file($_FILES["leftimg"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
					{
						
					$sqlequip = dbQuery($dbConn, "SELECT leftimg from pages where id = '".$id."'");
					$row = dbFetchArray($sqlequip);
					@unlink("../uploads/".$row['leftimg']);
			
					$updimgnm = $mytime.'_'.$mflname;

					dbQuery($dbConn, "UPDATE pages set leftimg = '".$updimgnm."' where id='".$id."'");
						
				
					}
				}
			}
			if(isset($_FILES["whystaffimg1"]) && $_FILES["whystaffimg1"]["size"]>0)
			{
				
				$updimgnm='';

				
				$srcfile = $_FILES['whystaffimg1']['type'];
				$imageinfo = getimagesize($_FILES['whystaffimg1']['tmp_name']); //check image size
				
				$mflname = strtolower($_FILES['whystaffimg1']['name']);
				$mflname = str_replace(" ", "_", $mflname);
				
				if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
				{
					$errstring1="";
				}
				else
				{
					$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
				
				}
				
						$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
				
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
				
				if($errstring1=="" && $errstring2=="")
				{
					if(move_uploaded_file($_FILES["whystaffimg1"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
					{
						
					$sqlequip = dbQuery($dbConn, "SELECT whystaffimg1 from pages where id = '".$id."'");
					$row = dbFetchArray($sqlequip);
					@unlink("../uploads/".$row['whystaffimg1']);
			
					$updimgnm = $mytime.'_'.$mflname;

					dbQuery($dbConn, "UPDATE pages set whystaffimg1 = '".$updimgnm."' where id='".$id."'");
						
				
					}
				}
			}

            if(isset($_FILES["whystaffimg2"]) && $_FILES["whystaffimg2"]["size"]>0)
			{
				
				$updimgnm='';

				
				$srcfile = $_FILES['whystaffimg2']['type'];
				$imageinfo = getimagesize($_FILES['whystaffimg2']['tmp_name']); //check image size
				
				$mflname = strtolower($_FILES['whystaffimg2']['name']);
				$mflname = str_replace(" ", "_", $mflname);
				
				if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
				{
					$errstring1="";
				}
				else
				{
					$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
				
				}
				
						$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
				
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
				
				if($errstring1=="" && $errstring2=="")
				{
					if(move_uploaded_file($_FILES["whystaffimg2"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
					{
						
					$sqlequip = dbQuery($dbConn, "SELECT whystaffimg2 from pages where id = '".$id."'");
					$row = dbFetchArray($sqlequip);
					@unlink("../uploads/".$row['whystaffimg2']);
			
					$updimgnm = $mytime.'_'.$mflname;

					dbQuery($dbConn, "UPDATE pages set whystaffimg2 = '".$updimgnm."' where id='".$id."'");
						
				
					}
				}
			}

            if(isset($_FILES["whystaffimg3"]) && $_FILES["whystaffimg3"]["size"]>0)
			{
				
				$updimgnm='';

				
				$srcfile = $_FILES['whystaffimg3']['type'];
				$imageinfo = getimagesize($_FILES['whystaffimg3']['tmp_name']); //check image size
				
				$mflname = strtolower($_FILES['whystaffimg3']['name']);
				$mflname = str_replace(" ", "_", $mflname);
				
				if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
				{
					$errstring1="";
				}
				else
				{
					$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
				
				}
				
						$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
				
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
				
				if($errstring1=="" && $errstring2=="")
				{
					if(move_uploaded_file($_FILES["whystaffimg3"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
					{
						
					$sqlequip = dbQuery($dbConn, "SELECT whystaffimg3 from pages where id = '".$id."'");
					$row = dbFetchArray($sqlequip);
					@unlink("../uploads/".$row['whystaffimg3']);
			
					$updimgnm = $mytime.'_'.$mflname;

					dbQuery($dbConn, "UPDATE pages set whystaffimg3 = '".$updimgnm."' where id='".$id."'");
						
				
					}
				}
			}

            if(isset($_FILES["whystaffimg4"]) && $_FILES["whystaffimg4"]["size"]>0)
			{
				
				$updimgnm='';

				
				$srcfile = $_FILES['whystaffimg4']['type'];
				$imageinfo = getimagesize($_FILES['whystaffimg4']['tmp_name']); //check image size
				
				$mflname = strtolower($_FILES['whystaffimg4']['name']);
				$mflname = str_replace(" ", "_", $mflname);
				
				if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
				{
					$errstring1="";
				}
				else
				{
					$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
				
				}
				
						$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
				
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
				
				if($errstring1=="" && $errstring2=="")
				{
					if(move_uploaded_file($_FILES["whystaffimg4"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
					{
						
					$sqlequip = dbQuery($dbConn, "SELECT whystaffimg4 from pages where id = '".$id."'");
					$row = dbFetchArray($sqlequip);
					@unlink("../uploads/".$row['whystaffimg4']);
			
					$updimgnm = $mytime.'_'.$mflname;

					dbQuery($dbConn, "UPDATE pages set whystaffimg4 = '".$updimgnm."' where id='".$id."'");
						
				
					}
				}
			}

            if(isset($_FILES["secthreerightimg"]) && $_FILES["secthreerightimg"]["size"]>0)
			{
				
				$updimgnm='';

				
				$srcfile = $_FILES['secthreerightimg']['type'];
				$imageinfo = getimagesize($_FILES['secthreerightimg']['tmp_name']); //check image size
				
				$mflname = strtolower($_FILES['secthreerightimg']['name']);
				$mflname = str_replace(" ", "_", $mflname);
				
				if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
				{
					$errstring1="";
				}
				else
				{
					$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
				
				}
				
						$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
				
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
				
				if($errstring1=="" && $errstring2=="")
				{
					if(move_uploaded_file($_FILES["secthreerightimg"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
					{
						
					$sqlequip = dbQuery($dbConn, "SELECT secthreerightimg from pages where id = '".$id."'");
					$row = dbFetchArray($sqlequip);
					@unlink("../uploads/".$row['secthreerightimg']);
			
					$updimgnm = $mytime.'_'.$mflname;

					dbQuery($dbConn, "UPDATE pages set secthreerightimg = '".$updimgnm."' where id='".$id."'");
						
				
					}
				}
			}
		}

		if($id == 6){
			// home
			$leftboxtext = isset($_POST['leftboxtext'])?strip_tags(trim($_POST['leftboxtext'])):"";
			$middleboxtext = isset($_POST['middleboxtext'])?strip_tags(trim($_POST['middleboxtext'])):"";
			$rightboxtext = isset($_POST['rightboxtext'])?strip_tags(trim($_POST['rightboxtext'])):"";
			$secfourhead = isset($_POST['secfourhead'])?strip_tags(trim($_POST['secfourhead'])):"";
			$secfourtext1 = isset($_POST['secfourtext1'])?strip_tags(trim($_POST['secfourtext1'])):"";
			$secfourtext2 = isset($_POST['secfourtext2'])?strip_tags(trim($_POST['secfourtext2'])):"";
			$secfourtext3 = isset($_POST['secfourtext3'])?strip_tags(trim($_POST['secfourtext3']), '<p><h2><h3><strong><br>'):"";
			$secfourtext4 = isset($_POST['secfourtext4'])?strip_tags(trim($_POST['secfourtext4']), '<p><h2><h3><strong><br>'):"";
			$secfourtext5 = isset($_POST['secfourtext5'])?strip_tags(trim($_POST['secfourtext5']), '<p><h2><h3><strong><br>'):"";
			
			$secfourtext6 = isset($_POST['secfourtext6'])?strip_tags(trim($_POST['secfourtext6']), '<p><h2><h3><strong><br>'):"";
			
			$secfourtext7 = isset($_POST['secfourtext7'])?strip_tags(trim($_POST['secfourtext7']), '<p><h2><h3><strong><br>'):"";
			
			$secfourtext8 = isset($_POST['secfourtext8'])?strip_tags(trim($_POST['secfourtext8']), '<p><h2><h3><strong><br>'):"";
			
			$secfourtext9 = isset($_POST['secfourtext9'])?strip_tags(trim($_POST['secfourtext9']), '<p><h2><h3><strong><br>'):"";
			
			$secfourtext10 = isset($_POST['secfourtext10'])?strip_tags(trim($_POST['secfourtext10']), '<p><h2><h3><strong><br>'):"";
			
			$secfourtext11 = isset($_POST['secfourtext11'])?strip_tags(trim($_POST['secfourtext11']), '<p><h2><h3><strong><br>'):"";
			
			$secfivehead = isset($_POST['secfivehead'])?strip_tags(trim($_POST['secfivehead'])):"";
			$secfivesubhead = isset($_POST['secfivesubhead'])?strip_tags(trim($_POST['secfivesubhead'])):"";
			$secsixtext1 = isset($_POST['secsixtext1'])?strip_tags(trim($_POST['secsixtext1']), '<p><h2><h3><strong><br>'):"";
			$secsixtext2 = isset($_POST['secsixtext2'])?strip_tags(trim($_POST['secsixtext2']), '<p><h2><h3><strong><br>'):"";
			$secsixtext3 = isset($_POST['secsixtext3'])?strip_tags(trim($_POST['secsixtext3']), '<p><h2><h3><strong><br>'):"";
			$secsvnhead = isset($_POST['secsvnhead'])?strip_tags(trim($_POST['secsvnhead'])):"";
			$secsvntext1 = isset($_POST['secsvntext1'])?strip_tags(trim($_POST['secsvntext1'])):"";
			$secsvntext2 = isset($_POST['secsvntext2'])?strip_tags(trim($_POST['secsvntext2'])):"";
			$secsvntext3 = isset($_POST['secsvntext3'])?strip_tags(trim($_POST['secsvntext3'])):"";
			$secsvntext4 = isset($_POST['secsvntext4'])?strip_tags(trim($_POST['secsvntext4'])):"";
			$secsvntext5 = isset($_POST['secsvntext5'])?strip_tags(trim($_POST['secsvntext5'])):"";

			dbQuery($dbConn, "UPDATE pages set 
			heading1 = '".tep_db_input($heading1)."',
			heading2 = '".tep_db_input($heading2)."',
			heading3 = '".tep_db_input($heading3)."',
			subheading = '".tep_db_input($subheading)."',
			sectntwohead = '".tep_db_input($sectntwohead)."',
			topjobcathead = '".tep_db_input($topjobcathead)."',
			topjobcattext = '".tep_db_input($topjobcattext)."',
			leftboxtext = '".tep_db_input($leftboxtext)."',
			middleboxtext = '".tep_db_input($middleboxtext)."',
			rightboxtext = '".tep_db_input($rightboxtext)."',
			secfourhead = '".tep_db_input($secfourhead)."',
			secthreehead = '".tep_db_input($secthreehead)."',
			secfourtext1 = '".tep_db_input($secfourtext1)."',
			secfourtext2 = '".tep_db_input($secfourtext2)."',
			secfourtext3 = '".tep_db_input($secfourtext3)."',
			secfourtext4 = '".tep_db_input($secfourtext4)."',
			secfourtext5 = '".tep_db_input($secfourtext5)."',
			secfourtext6 = '".tep_db_input($secfourtext6)."',
			secfourtext7 = '".tep_db_input($secfourtext7)."',
			secfourtext8 = '".tep_db_input($secfourtext8)."',
			secfourtext9 = '".tep_db_input($secfourtext9)."',
			secfourtext10 = '".tep_db_input($secfourtext10)."',
			secfourtext11 = '".tep_db_input($secfourtext11)."',
			secfivehead = '".tep_db_input($secfivehead)."',
			secfivesubhead = '".tep_db_input($secfivesubhead)."',
			secsixtext1 = '".tep_db_input($secsixtext1)."',
			secsixtext2 = '".tep_db_input($secsixtext2)."',
			secsixtext3 = '".tep_db_input($secsixtext3)."',
			secsvnhead = '".tep_db_input($secsvnhead)."',
			secsvntext1 = '".tep_db_input($secsvntext1)."',
			secsvntext2 = '".tep_db_input($secsvntext2)."',
			secsvntext3 = '".tep_db_input($secsvntext3)."',
			secsvntext4 = '".tep_db_input($secsvntext4)."',
			secsvntext5 = '".tep_db_input($secsvntext5)."'
			where id = '".$id."'");

		$mytime=time();
		if(isset($_FILES["toprightimg"]) && $_FILES["toprightimg"]["size"]>0)
		{
			
			$updimgnm='';

			$srcfile = $_FILES['toprightimg']['type'];
			$imageinfo = getimagesize($_FILES['toprightimg']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['toprightimg']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["toprightimg"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT toprightimg from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['toprightimg']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set toprightimg = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["leftboximg"]) && $_FILES["leftboximg"]["size"]>0)
		{
			
			$updimgnm='';

			
			$srcfile = $_FILES['leftboximg']['type'];
			$imageinfo = getimagesize($_FILES['leftboximg']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['leftboximg']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["leftboximg"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT leftboximg from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['leftboximg']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set leftboximg = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}

		if(isset($_FILES["middleboximg"]) && $_FILES["middleboximg"]["size"]>0)
		{
			
			$updimgnm='';

			
			$srcfile = $_FILES['middleboximg']['type'];
			$imageinfo = getimagesize($_FILES['middleboximg']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['middleboximg']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["middleboximg"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT middleboximg from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['middleboximg']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set middleboximg = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}

		if(isset($_FILES["rightboximg"]) && $_FILES["rightboximg"]["size"]>0)
		{
			
			$updimgnm='';

			
			$srcfile = $_FILES['rightboximg']['type'];
			$imageinfo = getimagesize($_FILES['rightboximg']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['rightboximg']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["rightboximg"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT rightboximg from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['rightboximg']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set rightboximg = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}

		if(isset($_FILES["secfourlftimg"]) && $_FILES["secfourlftimg"]["size"]>0)
		{
			
			$updimgnm='';

			
			$srcfile = $_FILES['secfourlftimg']['type'];
			$imageinfo = getimagesize($_FILES['secfourlftimg']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['secfourlftimg']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["secfourlftimg"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT secfourlftimg from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['secfourlftimg']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set secfourlftimg = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}

		if(isset($_FILES["icon1"]) && $_FILES["icon1"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['icon1']['type'];
			$imageinfo = getimagesize($_FILES['icon1']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['icon1']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["icon1"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT icon1 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['icon1']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set icon1 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["icon2"]) && $_FILES["icon2"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['icon2']['type'];
			$imageinfo = getimagesize($_FILES['icon2']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['icon2']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["icon2"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT icon2 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['icon2']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set icon2 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["icon3"]) && $_FILES["icon3"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['icon3']['type'];
			$imageinfo = getimagesize($_FILES['icon3']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['icon3']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["icon3"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT icon3 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['icon3']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set icon3 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["icon4"]) && $_FILES["icon4"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['icon4']['type'];
			$imageinfo = getimagesize($_FILES['icon4']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['icon4']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["icon4"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT icon4 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['icon4']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set icon4 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["icon5"]) && $_FILES["icon5"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['icon5']['type'];
			$imageinfo = getimagesize($_FILES['icon5']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['icon5']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["icon5"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT icon5 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['icon5']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set icon5 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["icon6"]) && $_FILES["icon6"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['icon6']['type'];
			$imageinfo = getimagesize($_FILES['icon6']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['icon6']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["icon6"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT icon6 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['icon6']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set icon6 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["secsvnimg1"]) && $_FILES["secsvnimg1"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['secsvnimg1']['type'];
			$imageinfo = getimagesize($_FILES['secsvnimg1']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['secsvnimg1']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["secsvnimg1"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT secsvnimg1 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['secsvnimg1']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set secsvnimg1 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["secsvnimg2"]) && $_FILES["secsvnimg2"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['secsvnimg2']['type'];
			$imageinfo = getimagesize($_FILES['secsvnimg2']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['secsvnimg2']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["secsvnimg2"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT secsvnimg2 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['secsvnimg2']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set secsvnimg2 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["secsvnimg3"]) && $_FILES["secsvnimg3"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['secsvnimg3']['type'];
			$imageinfo = getimagesize($_FILES['secsvnimg3']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['secsvnimg3']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["secsvnimg3"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT secsvnimg3 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['secsvnimg3']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set secsvnimg3 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["secsvnimg4"]) && $_FILES["secsvnimg4"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['secsvnimg4']['type'];
			$imageinfo = getimagesize($_FILES['secsvnimg4']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['secsvnimg4']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["secsvnimg4"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT secsvnimg4 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['secsvnimg4']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set secsvnimg4 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["secsvnimg5"]) && $_FILES["secsvnimg5"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['secsvnimg5']['type'];
			$imageinfo = getimagesize($_FILES['secsvnimg5']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['secsvnimg5']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["secsvnimg5"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT secsvnimg5 from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['secsvnimg5']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set secsvnimg5 = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
		if(isset($_FILES["secsvnrightimg"]) && $_FILES["secsvnrightimg"]["size"]>0)
		{
			
			$updimgnm='';
			
			$srcfile = $_FILES['secsvnrightimg']['type'];
			$imageinfo = getimagesize($_FILES['secsvnrightimg']['tmp_name']); //check image size
			
			$mflname = strtolower($_FILES['secsvnrightimg']['name']);
			$mflname = str_replace(" ", "_", $mflname);
			
			if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
			{
				$errstring1="";
			}
			else
			{
				$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
			
			}
			
					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
			
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
			
			if($errstring1=="" && $errstring2=="")
			{
				if(move_uploaded_file($_FILES["secsvnrightimg"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
				{
					
				$sqlequip = dbQuery($dbConn, "SELECT secsvnrightimg from pages where id = '".$id."'");
				$row = dbFetchArray($sqlequip);
				@unlink("../uploads/".$row['secsvnrightimg']);
		
				$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE pages set secsvnrightimg = '".$updimgnm."' where id='".$id."'");
					
			
				}
			}
		}
	}
			
			echo "<script>location.href='pages.php?success=2'</script>";
			exit;
		}
		else{
			
                    dbQuery($dbConn, "INSERT INTO pages set name = '".tep_db_input($name)."',
					slug = '".$slug."',
                    description = '".tep_db_input($description)."',
                    `meta_keyword` = '".tep_db_input($meta_keyword)."',
                    meta_desc = '".tep_db_input($meta_desc)."'");

					$insert_id = dbInsertId($dbConn);
					
					echo "<script>location.href='pages.php?success=1'</script>";
					exit;
					
				
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo FOOTERTITLE;?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="css/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="css/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="css/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="css/summernote-bs4.css">
  
  <!---- custom style ----->
  <link rel="stylesheet" href="css/style.css">
  
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php include_once "includes/header.php"; ?>

  <?php include_once "includes/leftBar.php"; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Page</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Page</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	
        <?php 
		$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):'';
		$sql = "SELECT * from `pages` where id = '".$id."'";
		$sql_res = dbQuery($dbConn,$sql);
		$sql_res_fetch = dbFetchAssoc($sql_res);
		?>
        <!-- Main row -->
        <div class="row">
          
          <section class="col-lg-12 connectedSortable">

            <!-- Map card -->
            <div class="card masteraudit">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="nav-icon fas fa-edit"></i>
                  <?php if($id) echo 'Update'; else echo 'Add';?> Page
                </h3>
                <!-- card tools -->
                <div class="card-tools">                  
                  <button type="button"
                          class="btn btn-primary btn-sm"
                          data-card-widget="collapse"
                          data-toggle="tooltip"
                          title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
				  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <form role="form" id="quickForm" method="post" action="" enctype="multipart/form-data">
			  <input type="hidden" name="id" value="<?php echo $id;?>">
              <div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Name</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['name']);?>" placeholder="Enter Name" name="name">
							</div>
						</div>
						<?php
						if($id == 1 || $id == 6){
						?>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Heading 1</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['heading1']);?>" placeholder="Heading 1" name="heading1">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Heading 2</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['heading2']);?>" placeholder="Heading 2" name="heading2">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Heading 3</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['heading3']);?>" placeholder="Heading 3" name="heading3">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Subheading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['subheading']);?>" placeholder="Subheading" name="subheading">
							</div>
						</div>
						
						<?php
						}
						if($id == 1){
						?>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Left Image</label>
								<?php
								if($sql_res_fetch['leftimg']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['leftimg']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="leftimg" accept="image/*">
								<br>(Please upload image of 730px x 563px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Right heading 1</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['rightheading1']);?>" placeholder="Right heading 1" name="rightheading1">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Right subheading 1</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['rightsubheading1']);?>" placeholder="Right subheading 1" name="rightsubheading1">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Right heading 2</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['rightheading2']);?>" placeholder="Right heading 2" name="rightheading2">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Right subheading 2</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['rightsubheading2']);?>" placeholder="Right subheading 2" name="rightsubheading2">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Right heading 3</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['rightheading3']);?>" placeholder="Right heading 3" name="rightheading3">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Right subheading 3</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['rightsubheading3']);?>" placeholder="Right subheading 3" name="rightsubheading3">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section two heading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['sectntwohead']);?>" placeholder="Section two heading" name="sectntwohead">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section two image 1</label>
								<?php
								if($sql_res_fetch['whystaffimg1']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['whystaffimg1']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="whystaffimg1" accept="image/*">
								<br>(Please upload image of 100px x 100px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section two text 1</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['whystafftext1']);?>" placeholder="Section two text 1" name="whystafftext1">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section two image 2</label>
								<?php
								if($sql_res_fetch['whystaffimg2']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['whystaffimg2']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="whystaffimg2" accept="image/*">
								<br>(Please upload image of 100px x 100px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section two text 2</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['whystafftext2']);?>" placeholder="Section two text 2" name="whystafftext2">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section two image 3</label>
								<?php
								if($sql_res_fetch['whystaffimg3']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['whystaffimg3']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="whystaffimg3" accept="image/*">
								<br>(Please upload image of 100px x 100px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section two text 3</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['whystafftext3']);?>" placeholder="Section two text 3" name="whystafftext3">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section two image 4</label>
								<?php
								if($sql_res_fetch['whystaffimg4']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['whystaffimg4']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="whystaffimg4" accept="image/*">
								<br>(Please upload image of 100px x 100px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section two text 4</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['whystafftext4']);?>" placeholder="Section two text 4" name="whystafftext4">
							</div>
						</div>
						<?php
						}
						if($id == 6){
							?>
							<div class="col-sm-12">
							<div class="form-group">
								<label for="">Top right image</label>
								<?php
								if($sql_res_fetch['toprightimg']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['toprightimg']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="toprightimg" accept="image/*">
								<br>(Please upload image of 464px x 309px)
							</div>
						</div>
							<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section two heading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['sectntwohead']);?>" placeholder="Section two heading" name="sectntwohead">
							</div>
							</div>
							<div class="col-sm-12">
							<div class="form-group">
								<label for="">Top Job Categories Heading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['topjobcathead']);?>" placeholder="Top Job Categories Heading" name="topjobcathead">
							</div>
							</div>
							<div class="col-sm-12">
							<div class="form-group">
								<label for="">Top Job Categories text</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['topjobcattext']);?>" placeholder="Top Job Categories text" name="topjobcattext">
							</div>
							</div>
							<div class="col-sm-12">
							<div class="form-group">
								<label for="">Left Box image</label>
								<?php
								if($sql_res_fetch['leftboximg']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['leftboximg']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="leftboximg" accept="image/*">
								<br>(Please upload image of 400px x 391px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Left Box text</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['leftboxtext']);?>" placeholder="Left Box text" name="leftboxtext">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Middle Box image</label>
								<?php
								if($sql_res_fetch['middleboximg']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['middleboximg']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="middleboximg" accept="image/*">
								<br>(Please upload image of 400px x 391px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Middle Box text</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['middleboxtext']);?>" placeholder="Middle Box text" name="middleboxtext">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Right Box image</label>
								<?php
								if($sql_res_fetch['rightboximg']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['rightboximg']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="rightboximg" accept="image/*">
								<br>(Please upload image of 400px x 391px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Right Box text</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['rightboxtext']);?>" placeholder="Right Box text" name="rightboxtext">
							</div>
						</div>
						<?php
						}
						?>
						<?php
						if($id == 1){
						?>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section three heading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secthreehead']);?>" placeholder="Section three heading" name="secthreehead">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section three text 1</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secthreetext1']);?>" placeholder="Section three text 1" name="secthreetext1">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section three text 2</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secthreetext2']);?>" placeholder="Section three text 2" name="secthreetext2">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section three text 3</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secthreetext3']);?>" placeholder="Section three text 3" name="secthreetext3">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section three text 4</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secthreetext4']);?>" placeholder="Section three text 4" name="secthreetext4">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section three image</label>
								<?php
								if($sql_res_fetch['secthreerightimg']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['secthreerightimg']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="secthreerightimg" accept="image/*">
								<br>(Please upload image of 464px x 309px)
							</div>
						</div>
						<?php
						}
						if($id == 6){
						?>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four heading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secfourhead']);?>" placeholder="Section four heading" name="secfourhead">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four left image</label>
								<?php
								if($sql_res_fetch['secfourlftimg']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['secfourlftimg']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="secfourlftimg" accept="image/*">
								<br>(Please upload image of 464px x 309px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four Staff heading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secfourtext1']);?>" placeholder="Section four Staff heading" name="secfourtext1">
							</div>
						</div>
						<!--<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four text 2 (Staff)</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secfourtext2']);?>" placeholder="Section four text 2" name="secfourtext2">
							</div>
						</div>-->
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four text 1 (Staff)</label>
								<textarea class="form-control required" id=""placeholder="Section four text 1 (Staff)" name="secfourtext3"><?php echo stripslashes($sql_res_fetch['secfourtext3']);?></textarea>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four text 2 (Staff)</label>
								<textarea class="form-control required" id="" placeholder="Section four text 2 (Staff)" name="secfourtext4"><?php echo stripslashes($sql_res_fetch['secfourtext4']);?></textarea>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four text 3 (Staff)</label>
								<textarea class="form-control required" id="" placeholder="Section four text 3 (Staff)" name="secfourtext5"><?php echo stripslashes($sql_res_fetch['secfourtext5']);?></textarea>
							</div>
						</div>
						
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four text 4 (Staff)</label>
								<textarea class="form-control required" id="" placeholder="Section four text 4 (Staff)" name="secfourtext6"><?php echo stripslashes($sql_res_fetch['secfourtext6']);?></textarea>
							</div>
						</div>
						
						<!--Employer -->
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four Employer heading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secfourtext7']);?>" placeholder="Section four Employer heading" name="secfourtext7">
							</div>
						</div>
						
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four text 1 (Employer)</label>
								<textarea class="form-control required" id=""placeholder="Section four text 1 (Employer)" name="secfourtext8"><?php echo stripslashes($sql_res_fetch['secfourtext8']);?></textarea>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four text 2 (Employer)</label>
								<textarea class="form-control required" id="" placeholder="Section four text 2 (Employer)" name="secfourtext9"><?php echo stripslashes($sql_res_fetch['secfourtext9']);?></textarea>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four text 3 (Employer)</label>
								<textarea class="form-control required" id="" placeholder="Section four text 3 (Employer)" name="secfourtext10"><?php echo stripslashes($sql_res_fetch['secfourtext10']);?></textarea>
							</div>
						</div>
						
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section four text 4 (Employer)</label>
								<textarea class="form-control required" id="" placeholder="Section four text 4 (Employer)" name="secfourtext11"><?php echo stripslashes($sql_res_fetch['secfourtext11']);?></textarea>
							</div>
						</div>
						
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section five heading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secfivehead']);?>" placeholder="Section five heading" name="secfivehead">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section five subheading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secfivesubhead']);?>" placeholder="Section five subheading" name="secfivesubhead">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section five icon 1</label>
								<?php
								if($sql_res_fetch['icon1']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['icon1']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="icon1" accept="image/*">
								<br>(Please upload small image like 134px x 40px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section five icon 2</label>
								<?php
								if($sql_res_fetch['icon2']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['icon2']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="icon2" accept="image/*">
								<br>(Please upload small image like 134px x 40px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section five icon 3</label>
								<?php
								if($sql_res_fetch['icon3']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['icon3']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="icon3" accept="image/*">
								<br>(Please upload small image like 134px x 40px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section five icon 4</label>
								<?php
								if($sql_res_fetch['icon4']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['icon4']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="icon4" accept="image/*">
								<br>(Please upload small image like 134px x 40px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section five icon 5</label>
								<?php
								if($sql_res_fetch['icon5']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['icon5']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="icon5" accept="image/*">
								<br>(Please upload small image like 134px x 40px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section five icon 6</label>
								<?php
								if($sql_res_fetch['icon6']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['icon6']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="icon6" accept="image/*">
								<br>(Please upload small image like 134px x 40px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section six text 1</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secsixtext1']);?>" placeholder="Section six text 1" name="secsixtext1">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section six text 2</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secsixtext2']);?>" placeholder="Section six text 2" name="secsixtext2">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section six text 3</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secsixtext3']);?>" placeholder="Section six text 3" name="secsixtext3">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven heading</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secsvnhead']);?>" placeholder="Section seven heading" name="secsvnhead">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven image 1</label>
								<?php
								if($sql_res_fetch['secsvnimg1']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['secsvnimg1']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="secsvnimg1" accept="image/*">
								<br>(Please upload image of 500px x 356px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven text 1</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secsvntext1']);?>" placeholder="Section seven text 1" name="secsvntext1">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven image 2</label>
								<?php
								if($sql_res_fetch['secsvnimg2']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['secsvnimg2']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="secsvnimg2" accept="image/*">
								<br>(Please upload image of 500px x 356px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven text 2</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secsvntext2']);?>" placeholder="Section seven text 2" name="secsvntext2">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven image 3</label>
								<?php
								if($sql_res_fetch['secsvnimg3']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['secsvnimg3']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="secsvnimg3" accept="image/*">
								<br>(Please upload image of 500px x 356px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven text 3</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secsvntext3']);?>" placeholder="Section seven text 3" name="secsvntext3">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven image 4</label>
								<?php
								if($sql_res_fetch['secsvnimg4']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['secsvnimg4']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="secsvnimg4" accept="image/*">
								<br>(Please upload image of 500px x 356px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven text 4</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secsvntext4']);?>" placeholder="Section seven text 4" name="secsvntext4">
							</div>
						</div>
						<!--<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven image 5</label>
								<?php
								if($sql_res_fetch['secsvnimg5']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['secsvnimg5']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="secsvnimg5" accept="image/*">
								<br>(Please upload image of 500px x 356px)
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven text 5</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['secsvntext5']);?>" placeholder="Section seven text 5" name="secsvntext5">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Section seven right image</label>
								<?php
								if($sql_res_fetch['secsvnrightimg']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['secsvnrightimg']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="secsvnrightimg" accept="image/*">
								<br>(Please upload image of 464px x 309px)
							</div>
						</div>-->
						<?php
						}
						?>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Description</label>
								<textarea class="form-control" id="" placeholder="Description" name="description" style="height:150px;"><?php echo stripslashes($sql_res_fetch['description']);?></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
              				<label for="">Meta Keyword</label>
								<input type="text" class="form-control" id="" value="<?php echo $sql_res_fetch['meta_keyword'];?>" placeholder="Meta Keyword" name="meta_keyword">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Meta Description</label>
								<textarea class="form-control" id="" placeholder="Meta Description" name="meta_desc" style="height:200px;"><?php echo stripslashes($sql_res_fetch['meta_desc']);?></textarea>
							</div>
            		</div>
					</div>
					
					<div class="row">
						<div class="col-sm-6">
							<button type="submit" class="btn btn-primary custombut">Submit</button>
						</div>
						<div class="col-sm-6">
							&nbsp;
						</div>
					</div>
					
            </div>
            </form>
              <!-- /.card-body-->
             
            </div>
            <!-- /.card -->

          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include_once ("includes/footer.php"); ?>
  <!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery.validate.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
  
</script>
<!-- Bootstrap 4 -->
<script src="js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="js/Chart.min.js"></script>
<!-- Sparkline
<script src="js/sparkline.js"></script>-->
<!-- JQVMap -->
<script src="js/jquery.vmap.min.js"></script>
<script src="js/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="js/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="js/moment.min.js"></script>
<script src="js/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="js/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="js/dashboard.js"></script>-->
<!-- AdminLTE for demo purposes -->
<script src="js/demo.js"></script>
<script>
$(function(){
  $('#quickForm').validate();
});
</script>
</body>
</html>
