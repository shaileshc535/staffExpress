<?php
session_start();
unset($_SESSION['adminUserId']);
unset($_SESSION['adminUser']);
session_destroy();
echo "<script>location.href='login.php'</script>";
exit;
?>
