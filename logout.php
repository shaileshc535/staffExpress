<?php
include('config/config.php');
include_once "config/common.php";
$dbConn = establishcon();
$_SESSION['SESSID'] = '';
unset($_SESSION['SESSID']);
session_destroy();
?>
<script language="javascript">
    window.location.href="<?php echo SITEURL;?>";
</script>
<?php
exit;
?>