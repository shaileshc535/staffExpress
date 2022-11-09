<?php
function filter($var)
{

	while(strchr($var,'\\')) {

        $string = stripslashes($string);

    } 

	//$new =preg_replace(array('/\x5C(?!\x5C)/u', '/\x5C\x5C/u'), array('','\\'), $var);

	return $string;

}

function encrypt($string, $key)
{
	$result = '';
	for($i=0; $i<strlen($string); $i++)
	{
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	}
	return base64_encode($result);
}

function decrypt($string, $key)
{
	$result = '';
	$string = base64_decode($string);
	for($i=0; $i<strlen($string); $i++)
	{
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	}
	return $result;
}


function filter_template($var)

{


	$var=str_replace("\\","",$var); 

	return $var;

}



//$absolute_path=str_replace("includes\config.php","",__FILE__);



//PHP FILE NAME---------------------------------------------------------------------------------------------

//echo "File Name : ".basename(__FILE__);



//PASSWORD ENCRYPT---------------------------------------------------------------------------------------------

//echo md5("Password");



//DATE TIME FORMAT---------------------------------------------------------------------------------------------

function date_time_format($date_time,$flag=1)

{

	$unx_stamp=strtotime($date_time);

	$date_str="";
	
	switch($flag)
	{

		
		case 1: $date_str=(date("Y-m-d",$unx_stamp)); break;//2004-06-29

		case 2: $date_str=(date("m/d/Y",$unx_stamp)); break;//06-29-2004

		case 3: $date_str=(date("d/m/Y",$unx_stamp)); break;//29-06-2004

		case 4: $date_str=(date("d M Y",$unx_stamp)); break;//29 Jun 2004

		case 5: $date_str=(date("F d, Y",$unx_stamp)); break;//29 June 2004

		case 6: $date_str=(date("jS M Y",$unx_stamp)); break;//29th Jun ,2004

		//case 6: $date_str=date("M j",$unx_stamp)."<sup>".date("S",$unx_stamp)."</sup>".date(", Y",$unx_stamp); break;

		case 7: $date_str=(date("D M dS,Y",$unx_stamp)); break;//Tue Jun 29th,2004

		case 8: $date_str=(date("l, F j,Y",$unx_stamp)); break;//Tuesday, Jun 29th,2004

		case 9: $date_str=(date("l F jS,Y",$unx_stamp)); break;//Tuesday June 29th,2004

		case 10: $date_str=(date("d F Y l",$unx_stamp)); break;//29 June 2004 Tuesday

	}

	
	return $date_str;

}

//echo date_time_format("2012-09-07 10:20 AM",3);	//	1=date	2=time	3=date+time

//---------------------------------------------------------------------------------------------



//----------------------------------Random ID-------------------------------------------------



function unique_id($len = 8)

{

	$temp=mt_rand(1,500);

	$temp=md5($temp);

	$temp=substr($temp,0,$len);

	$temp=strtoupper($temp);

	return $temp;

}

//$pass=unique_id();

//////-----------------------------------------------------------------------------------------



function message($var="",$mode=3)

{

	switch($mode)

	{

		case 1:$var=" <font class='success_style'>".$var."</font> ";	//Success

				break;

		case 2:$var=" <font class='error_style'>".$var."</font> ";	//Error

				break;

		case 3:$var=" <font class='message_style'>".$var."</font> ";	//Message

				break;

		case 4:$var=" <font class='critical_style'>".$var."</font> ";	//Critical

				break;

		default:$var=" <font class='message_style'>".$var."</font> ";	//Message

				break;

	}

	return $var;

}



function text_alert($var="",$mode=3)

{

	switch($mode)

	{

		case 1:$var=" <font class='alert_success'>".$var."</font> ";	//Success

				break;

		case 2:$var=" <font class='alert_error'>".$var."</font> ";	//Error

				break;

		case 3:$var=" <font class='alert_message'>".$var."</font> ";	//Message

				break;

		case 4:$var=" <font class='alert_critical'>".$var."</font> ";	//Critical

				break;

		default:$var=" <font class='alert_message'>".$var."</font> ";	//Message

				break;

	}

	return $var;

}

/*function send_mail($to, $subject, $message ,$from) 

{

	$header = "MIME-Version: 1.0\n";

	$header=$header."From: ".$from."\n";

	$header=$header."Content-Type: text/html; charset=\"iso-8859-1\"\n";

	

	$flag=@mail($to, $subject, $message, $header);



	if($flag==false)	//delete

		$_SESSION['mail_display']="<b>To:</b> $to<br><b>From:</b> $from<br><b>Subject:</b> $subject<br><b>Message:</b><br>$message";	//delete

	return  $flag;

}

*/

function delete_directory($dir)

{

   if (substr($dir, strlen($dir)-1, 1) != '/')

       $dir .= '/';

   if ($handle = opendir($dir))

   {

       while ($obj = readdir($handle))

       {

           if ($obj != '.' && $obj != '..')

           {

               if (is_dir($dir.$obj))

               {

                   if (!deleteDir($dir.$obj))

                       return false;

               }

               elseif (is_file($dir.$obj))

               {

                   if (!unlink($dir.$obj))

                       return false;

               }

           }

       }



       closedir($handle);



       if (!@rmdir($dir))

           return false;

       return true;

   }

   return false;

}



function convert_to_mysql_date($date)		//converts a date form '31/12/2006' => '2006-12-31'

{

	$first_slash_pos=strpos($date,'/');

	$second_slash_pos=strpos($date,'/',strpos($date,'/')+1);

	

	$day=substr($date,0,$first_slash_pos);

	$mon=substr($date,$first_slash_pos+1,($second_slash_pos-$first_slash_pos)-1);

	$year=substr($date,-4);



	$day=strlen($day)==1?"0".$day:$day;	

	$mon=strlen($mon)==1?"0".$mon:$mon;

		

	$date=$year."-".$mon."-".$day;

	return $date;

}

function convert_to_normal_date($date)		//converts a date form '2006-12-31' => '31/12/2006' 

{

	$date_arr=explode("-",$date);

	$date=(integer)$date_arr[2]."/".(integer)$date_arr[1]."/".(integer)$date_arr[0];

	return $date;

}



function month_in_datediff($dformat, $endDate, $beginDate)

{

	$date_parts1=explode($dformat, $beginDate);

	$date_parts2=explode($dformat, $endDate);

	$start_date=gregoriantojd($date_parts1[1], $date_parts1[0], $date_parts1[2]);

	$end_date=gregoriantojd($date_parts2[1], $date_parts2[0], $date_parts2[2]);

	

	$total_day_diff=$end_date - $start_date;

	$month=($total_day_diff/365)*12;

	return round($month,0);

}

//month_in_datediff("/", date("d/m/Y"),"25/7/1981");



function day_in_datediff($dformat, $endDate, $beginDate)

{

	$date_parts1=explode($dformat, $beginDate);

	$date_parts2=explode($dformat, $endDate);

	$start_date=gregoriantojd($date_parts1[1], $date_parts1[0], $date_parts1[2]);

	$end_date=gregoriantojd($date_parts2[1], $date_parts2[0], $date_parts2[2]);

	

	$total_day_diff=$end_date - $start_date;

	

	return $total_day_diff;

}

//day_in_datediff("/", date("d/m/Y"),"25/7/1981");



function error($message)

{

	$_SESSION['msg']=$message;     

	header('Location: error.php');

	exit();

}

function image_exists($path)	//	$path=	"../uploads/a.gif"

{

	if($path=='' || $path==NULL || substr($path,(strlen($path)-1))=='/' || !file_exists($path))

		return false;

	else

		return true;

}

function check_type_support($userfile_type)

{

	$flag=0;

	if ($userfile_type == 'image/x-png')

	{

		$userfile_type = 'image/png';

	}

	if ($userfile_type == 'image/pjpeg')

	{

		$userfile_type = 'image/jpeg';

	}

	

	switch($userfile_type)

	{

		case 'image/gif':

						break;

		case 'image/png':

						break;

		case 'image/jpeg':

						break;

		default:

			  $flag=1;

			  break;

	}

    return $flag;	

}

function encode($str)

{

	return base64_encode($str);

}

function decode($str)

{

	if($str!="")

	{

		return base64_decode($str);

	}

	else

	{

		header('location:error.html');

		exit();

	}

}

function tep_session_is_registered($variable) {

    if (PHP_VERSION < 4.3) {

      return session_is_registered($variable);

    } else {

      return isset($_SESSION) && array_key_exists($variable, $_SESSION);

    }

  }

  

  function tep_get_uprid($prid, $params) {

    if (is_numeric($prid)) {

      $uprid = $prid;



      if (is_array($params) && (sizeof($params) > 0)) {

        $attributes_check = true;

        $attributes_ids = '';



        reset($params);

        while (list($option, $value) = each($params)) {

          if (is_numeric($option) && is_numeric($value)) {

            $attributes_ids .= '{' . (int)$option . '}' . (int)$value;

          } else {

            $attributes_check = false;

            break;

          }

        }



        if ($attributes_check == true) {

          $uprid .= $attributes_ids;

        }

      }

    } else {

      $uprid = tep_get_prid($prid);



      if (is_numeric($uprid)) {

        if (strpos($prid, '{') !== false) {

          $attributes_check = true;

          $attributes_ids = '';



// strpos()+1 to remove up to and including the first { which would create an empty array element in explode()

          $attributes = explode('{', substr($prid, strpos($prid, '{')+1));



          for ($i=0, $n=sizeof($attributes); $i<$n; $i++) {

            $pair = explode('}', $attributes[$i]);



            if (is_numeric($pair[0]) && is_numeric($pair[1])) {

              $attributes_ids .= '{' . (int)$pair[0] . '}' . (int)$pair[1];

            } else {

              $attributes_check = false;

              break;

            }

          }



          if ($attributes_check == true) {

            $uprid .= $attributes_ids;

          }

        }

      } else {

        return false;

      }

    }



    return $uprid;

  }

function tep_get_prid($uprid) {

    $pieces = explode('{', $uprid);



    if (is_numeric($pieces[0])) {

      return $pieces[0];

    } else {

      return false;

    }

  }

  function tep_db_input($string) {

    if(get_magic_quotes_gpc() == 1)
	return trim($string);
	else
	return trim(addslashes($string));

  }

function tep_db_perform($table, $data, $action = 'insert', $parameters = '') {
$dbConn = establishcon();
    reset($data);

    if ($action == 'insert') {

      $query = 'insert into ' . $table . ' (';

      while (list($columns, ) = each($data)) {

        $query .= $columns . ', ';

      }

      $query = substr($query, 0, -2) . ') values (';

      reset($data);

      while (list(, $value) = each($data)) {

        switch ((string)$value) {

          case 'now()':

            $query .= 'now(), ';

            break;

          case 'null':

            $query .= 'null, ';

            break;

          default:

            $query .= '\'' . tep_db_input($value) . '\', ';

            break;

        }

      }

      $query = substr($query, 0, -2) . ')';

    } elseif ($action == 'update') {

      $query = 'update ' . $table . ' set ';

      while (list($columns, $value) = each($data)) {

        switch ((string)$value) {

          case 'now()':

            $query .= $columns . ' = now(), ';

            break;

          case 'null':

            $query .= $columns .= ' = null, ';

            break;

          default:

            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';

            break;

        }

      }

     $query = substr($query, 0, -2) . ' where ' . $parameters;

    }

    return dbQuery($dbConn,$query)or die(mysqli_error());

  }

  function tep_not_null($value) {

    if (is_array($value)) {

      if (sizeof($value) > 0) {

        return true;

      } else {

        return false;

      }

    } else {

      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {

        return true;

      } else {

        return false;

      }

    }

  }

  function tep_parse_input_field_data($data, $parse) {

    return strtr(trim($data), $parse);

  }



  function tep_output_string($string, $translate = false, $protected = false) {

    if ($protected == true) {

      return htmlspecialchars($string);

    } else {

      if ($translate == false) {

        return tep_parse_input_field_data($string, array('"' => '&quot;'));

      } else {

        return tep_parse_input_field_data($string, $translate);

      }

    }

  }



  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '') {

    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {

      return false;

    }

	

// alt is added to the img tag even if it is null to prevent browsers from outputting

// the image filename as default

    $image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';



    if (tep_not_null($alt)) {

      $image .= ' title=" ' . tep_output_string($alt) . ' "';

    }



    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {

      if ($image_size = @getimagesize($src)) {

        if (empty($width) && tep_not_null($height)) {

          $ratio = $height / $image_size[1];

          $width = intval($image_size[0] * $ratio);

        } elseif (tep_not_null($width) && empty($height)) {

          $ratio = $width / $image_size[0];

          $height = intval($image_size[1] * $ratio);

        } elseif (empty($width) && empty($height)) {

          $width = $image_size[0];

          $height = $image_size[1];

        }

      } elseif (IMAGE_REQUIRED == 'false') {

        return false;

      }

    }



    if (tep_not_null($width) && tep_not_null($height)) {

      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';

    }



    if (tep_not_null($parameters)) $image .= ' ' . $parameters;



    $image .= '>';



    return $image;

  }


function safeurl($str)
{

   return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($str)), '-');

}

function maskurl($str)
{
 /*	$returnstr=preg_replace('/[^a-zA-z0-9/s','',$str);*/
	$returnstr=str_replace("_","-",$str);
	$returnstr=str_replace("~","-",$returnstr);
	$returnstr=str_replace("@","-",$returnstr);
	$returnstr=str_replace("#","-",$returnstr);
	$returnstr=str_replace("$","-",$returnstr);
	$returnstr=str_replace("%","-",$returnstr);
	$returnstr=str_replace("^","-",$returnstr);
	$returnstr=str_replace("&","-",$returnstr);
	$returnstr=str_replace("*","-",$returnstr);
	$returnstr=str_replace("(","-",$returnstr);
	$returnstr=str_replace(")","-",$returnstr);
	
	$returnstr=str_replace("+","-",$returnstr);
	$returnstr=str_replace("=","-",$returnstr);
	$returnstr=str_replace("|","-",$returnstr);
	$returnstr=str_replace("\\","-",$returnstr);
	$returnstr=str_replace("{","-",$returnstr);
	$returnstr=str_replace("[","-",$returnstr);
	$returnstr=str_replace("]","-",$returnstr);
	$returnstr=str_replace("}","-",$returnstr);	

	$returnstr=str_replace(":","-",$returnstr);
	$returnstr=str_replace(";","-",$returnstr);
	$returnstr=str_replace("?","-",$returnstr);
	$returnstr=str_replace(">","-",$returnstr);
	$returnstr=str_replace("<","-",$returnstr);
	$returnstr=str_replace(" ","-",$returnstr);	
	$returnstr=str_replace("'","",$returnstr);	
	$returnstr=str_replace("/","-",$returnstr);	
	$returnstr=str_replace(".","-",$returnstr);	
	

   if(strpos($returnstr,"----")===false)
   {
	   $returnstr= $returnstr;
	   
   }
   else
   {
	   
	   $returnstr= str_replace("----","-",$returnstr);
   }

   if(strpos($returnstr,"---")===false)
   {
	   $returnstr= $returnstr;
	   
   }
   else
   {
	   
	   $returnstr= str_replace("---","-",$returnstr);
   }
   
   if(strpos($returnstr,"--")===false)
   {
	   $returnstr= $returnstr;
	   
   }
   else
   {
	   
	   $returnstr= str_replace("--","-",$returnstr);
   }

   $lastchar=substr($returnstr, -1);
  
   if($lastchar=='-')
   {
	   
	  $returnstr=substr($returnstr,0,(strlen($returnstr)-1)); 
	  
	}
	   if($lastchar=='--')
   {
	   
	  $returnstr=substr($returnstr,0,(strlen($returnstr)-2)); 
	}
	
	if($lastchar=='---')
   {
	   
	  $returnstr=substr($returnstr,0,(strlen($returnstr)-3)); 
	}
	return $returnstr;
}

function maskurlpic($str)
{
 /*	$returnstr=preg_replace('/[^a-zA-z0-9/s','',$str);*/
	$returnstr=str_replace("_","-",$str);
	$returnstr=str_replace("~","-",$returnstr);
	$returnstr=str_replace("@","-",$returnstr);
	$returnstr=str_replace("#","-",$returnstr);
	$returnstr=str_replace("$","-",$returnstr);
	$returnstr=str_replace("%","-",$returnstr);
	$returnstr=str_replace("^","-",$returnstr);
	$returnstr=str_replace("&","-",$returnstr);
	$returnstr=str_replace("*","-",$returnstr);
	$returnstr=str_replace("(","-",$returnstr);
	$returnstr=str_replace(")","-",$returnstr);
	
	$returnstr=str_replace("+","-",$returnstr);
	$returnstr=str_replace("=","-",$returnstr);
	$returnstr=str_replace("|","-",$returnstr);
	$returnstr=str_replace("\\","-",$returnstr);
	$returnstr=str_replace("{","-",$returnstr);
	$returnstr=str_replace("[","-",$returnstr);
	$returnstr=str_replace("]","-",$returnstr);
	$returnstr=str_replace("}","-",$returnstr);	

	$returnstr=str_replace(":","-",$returnstr);
	$returnstr=str_replace(";","-",$returnstr);
	$returnstr=str_replace("?","-",$returnstr);
	$returnstr=str_replace(">","-",$returnstr);
	$returnstr=str_replace("<","-",$returnstr);
	$returnstr=str_replace(" ","-",$returnstr);	
	$returnstr=str_replace("'","",$returnstr);	
	$returnstr=str_replace("/","-",$returnstr);	
	

   if(strpos($returnstr,"----")===false)
   {
	   $returnstr= $returnstr;
	   
   }
   else
   {
	   
	   $returnstr= str_replace("----","-",$returnstr);
   }

   if(strpos($returnstr,"---")===false)
   {
	   $returnstr= $returnstr;
	   
   }
   else
   {
	   
	   $returnstr= str_replace("---","-",$returnstr);
   }
   
   if(strpos($returnstr,"--")===false)
   {
	   $returnstr= $returnstr;
	   
   }
   else
   {
	   
	   $returnstr= str_replace("--","-",$returnstr);
   }

   $lastchar=substr($returnstr, -1);
  
   if($lastchar=='-')
   {
	   
	  $returnstr=substr($returnstr,0,(strlen($returnstr)-1)); 
	  
	}
	   if($lastchar=='--')
   {
	   
	  $returnstr=substr($returnstr,0,(strlen($returnstr)-2)); 
	}
	
	if($lastchar=='---')
   {
	   
	  $returnstr=substr($returnstr,0,(strlen($returnstr)-3)); 
	}
	return $returnstr;
}

function createslug($pagetitle, $pageid,$dbConn)
{
	$slug=strtolower(maskurl($pagetitle));	
	if($pageid==0)
	{					
		
		$sql = "select id from zk_category where cat_slug ='".$slug."'";
		$results = dbQuery($dbConn,$sql);
		$numrows = dbNumRows($results);				
		if($numrows>0)
		{
			$slug=$slug.$numrows;
		}
	 }
	else 
	{
		
		$sqlid = "select id from zk_category where id !='".$pageid."' and cat_slug ='".$slug."'";
		$result_id = dbQuery($dbConn,$sqlid);
		$numrows_id = dbNumRows($result_id);				
		if($numrows_id>0)
		{
			$slug=$slug.$numrows_id;
		}
		
	 }
	return $slug;
}

function createslug_prod($pagetitle, $pageid,$dbConn)
{
	$slug=strtolower(maskurl($pagetitle));	
	if($pageid==0)
	{					
		
		$sql = "select id from zk_product where prod_slug ='".$slug."'";
		$results = dbQuery($dbConn,$sql);
		$numrows = dbNumRows($results);				
		if($numrows>0)
		{
			$slug=$slug.$numrows;
		}
	 }
	else 
	{
		
		$sqlid = "select id from zk_product where id !='".$pageid."' and prod_slug ='".$slug."'";
		$result_id = dbQuery($dbConn,$sqlid);
		$numrows_id = dbNumRows($result_id);				
		if($numrows_id>0)
		{
			$slug=$slug.$numrows_id;
		}
		
	 }
	return $slug;
}

function stripword($text,$n)
{
	$text=strip_tags($text);  
	$text = trim(preg_replace("/\s+/"," ",$text));
	$word_array = explode(" ", $text);
	
	$text='';
	
	for($i=0;$i<$n-2;$i++)
	{
		$text.=$word_array[$i]." ";
	   
	}
	$text = substr($text, 0, -1);
	return $text;
}

function getslugbyid($id)
{
	$sql = "SELECT slug FROM dream_cms where cms_id='".$id."'";
	$result = dbQuery($dbConn,$sql) or die(mysqli_error().'<p><b>SQL:</b><br>'.$sql.'</p>');
	$rows = dbFetchArray($result);
	return $rows['slug'];
}

function getRanID($len)
{
	$pool1=time();
	$pool2=5*(time);
	$pool3=3*(time);
	$pool4=7*(time);
	$pool=$pool1.$pool2.$pool3.$pool4;
	$lchr=strlen($pool)-1;
	$ranid="";
	for($i=0;$i<$len;$i++)	$ranid.=$pool[mt_rand(0,$lchr)];
	return $ranid;
}

function getRanID2($len)
{
	$pool1=time();
	$pool2=5*(time());
	$pool3=3*(time());
	$pool4=7*(time());
	$pool5="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	$pool=$pool1.$pool2.$pool3.$pool4.$pool5;
	$lchr=strlen($pool)-1;
	$ranid="";
	for($i=0;$i<$len;$i++)	$ranid.=$pool[mt_rand(0,$lchr)];
	return $ranid;
}

function myinsert_time()
{
	$myinstime=date('Y-m-d H:i:s');
	return $myinstime;
}

function mytimediff()
{
	$myacttmdiff=-27900;
	return $myacttmdiff;
}
function is_decimal( $val )
{
    return is_numeric( $val ) && floor( $val ) != $val;
}

function totalDays($date1, $date2){
	$date1 = new DateTime($date1);
	$date2 = new DateTime($date2);
	$diff = $date1->diff($date2)->format("%a");
	return $diff;
}

function getTimeFromDate($mydate){
	$dt = new DateTime($mydate);
	$time = $dt->format('h:i a');
	return $time;
}

function getTimeDiff($date1, $date2){
	$time1 = new DateTime($date1);
	$time2 = new DateTime($date2);
	$timediff = $time1->diff($time2);
	$month = 0;
	$days = 0;
	$hour = 0;
	if($timediff->y > 0)
	$month = $timediff->y * 12;

	if($timediff->m > 0)
	$days = ($timediff->m + $month) * 30;

	if($timediff->d > 0)
	$hour = ($timediff->d + $days) * 24;

	return ($hour + $timediff->h);
}

function getUserImg($dbConn, $id){

	$getuser = dbQuery($dbConn, "SELECT image,channel_type from users where id = '".$id."'");
	$rowrevw = dbFetchArray($getuser);
	if($rowrevw['image']){
		if($rowrevw['channel_type'] == 1)
		$userimg = $rowrevw['image'];
		else
		$userimg = SITEURL."uploads/user/".$rowrevw['image'];
	}
	else
	$userimg = SITEURL."images/noimg.jpg";

	return $userimg;
}

function getUserType($dbConn, $id){
	$getuser = dbQuery($dbConn, "SELECT type from users where id = '".$id."'");
	$rowrevw = dbFetchArray($getuser);

	return $rowrevw['type'];
}

function get_distance_between_postcodes($src, $dest) {
    $src = str_replace(" ", "", $src);
    $dest = str_replace(" ", "", $dest);

    $api = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$src."&destinations=".$dest."&key=AIzaSyAcG1xQOlp5AVZOFmsaG9hkOozP1eW2qeM");
     $data = json_decode($api);
     //print_r($data);
     //die;
     $distance = $data->rows[0]->elements[0]->distance->value;
     $distance = ceil(number_format(($distance/1609),2));
     if($distance > 0)
         return ($distance*1.6);
     else
         return "NOT_FOUND";
}

function get_latlon_from_address($address) {
	$address = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false&key=AIzaSyAcG1xQOlp5AVZOFmsaG9hkOozP1eW2qeM";
    $details=file_get_contents($url);
    $result = json_decode($details,true);
	
	//echo "<pre>";
	//print_r($result);
	
    $lat = $result['results'][0]['geometry']['location']['lat'];

    $lng = $result['results'][0]['geometry']['location']['lng'];

	if($lat != "" && $lng != ""){
		$latlon = $lat."--".$lng;
	}
	else{
		$latlon = "";
	}
    
	return $latlon;
}

function get_latest_job($id, $dbConn){
	$jobs = dbQuery($dbConn, "SELECT id from job_details where employer_id = '".$id."' and postcomplete=1 order by id desc limit 0,1");
	$row = dbFetchArray($jobs);
	return $row['id'];
}

function get_latest_confirmed_job($id, $dbConn){
	$alljobs = dbQuery($dbConn, "SELECT c.title,c.id from job_status b inner join job_details c on b.jobid=c.id where b.application_sent_to = '".$id."' and b.contacting=1 and b.confirmation_sent=1 and b.emp_option_sent=1 order by b.id desc limit 0,1");
	$rowjobs = dbFetchArray($alljobs);
	return $rowjobs['id'];
}

function total_open_jobs($dbConn){
	$sqlTotal = "SELECT a.id from job_details a where a.title != '' and a.location != '' and a.lat != '' and a.workmode != '' and a.isclosed=0 and a.postcomplete=1";
	$jobsTotal = dbQuery($dbConn, $sqlTotal);
	$total_cover = dbNumRows($jobsTotal);
	return $total_cover;
}
?>