<?php
ob_start();
if(!isset($_SESSION['adminUserId']) && $_SESSION['adminUserId']=='')
{
	echo "<script>location.href='login.php'</script>";
	exit;
}
@$_SESSION['msg']=$_SESSION['msg']!=''?$_SESSION['msg']:'';
@$_SESSION['msgtype ']=$_SESSION['msgtype ']!=''?$_SESSION['msgtype ']:'';
 /*error_reporting(E_ALL);
 ini_set("display_errors", 1); */
?>