<?php include('../config/config.php'); 
include('includes/session.php');
include_once "../config/common.php";
$dbConn = establishcon();
if(count($_POST) > 0)
	{
		$name = isset($_POST['name'])?$_POST['name']:"";
		$lname = isset($_POST['lname'])?$_POST['lname']:"";
		$phone = isset($_POST['phone'])?$_POST['phone']:"";
		$address = isset($_POST['address'])?$_POST['address']:"";
		$contact_ofc = isset($_POST['contact_ofc'])?$_POST['contact_ofc']:"";
		$abnacn = isset($_POST['abnacn'])?strip_tags(trim($_POST['abnacn'])):"";
		$number = isset($_POST['number'])?strip_tags(trim($_POST['number'])):"";
		$isdeactivated = isset($_POST['isdeactivated'])?trim($_POST['isdeactivated']):"";

		$id = isset($_POST['id'])?$_POST['id']:"";

		if($id){
			$phone = str_replace(" ", "", $phone);
	$firstdigit = substr($phone, 0, 1);
	if($firstdigit == 0)
	$phone = "61".substr($phone, 1, 10);
	else
	$phone = "61".$phone;
			
			dbQuery($dbConn, "UPDATE users set `name` = '".tep_db_input($name)."',
			lname = '".tep_db_input($lname)."',
			phone = '".tep_db_input($phone)."',
			`address` = '".tep_db_input($address)."',
			contact_ofc = '".tep_db_input($contact_ofc)."',
			abnacn = '".tep_db_input($abnacn)."',
        	`number` = '".tep_db_input($number)."',
        	isdeactivated = '".tep_db_input($isdeactivated)."' where id = '".$id."'");

			// User photo
			/*if(isset($_FILES["image"]) && $_FILES["image"]["size"]>0)
			{
				
				$updimgnm='';

				$mytime=time();
				
				$srcfile = $_FILES['image']['type'];
				$imageinfo = getimagesize($_FILES['image']['tmp_name']); //check image size
				
				$mflname = strtolower($_FILES['image']['name']);
				
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
				
				if($errstring1=="" && $errstring2=="" && $errstring3=="")
				{
					if(move_uploaded_file($_FILES["image"]["tmp_name"],"../uploads/user/" . $mytime.'_'.$_FILES["image"]["name"]))
					{
						
					$sqlequip = dbQuery($dbConn, "SELECT image from users where id = '".$id."'");
					$row = dbFetchArray($sqlequip);
					@unlink("../uploads/user/".$row['image']);
			
					$updimgnm = $mytime.'_'.$_FILES["image"]["name"];

					dbQuery($dbConn, "UPDATE users set image = '".$updimgnm."' where id='".$id."'");
						
				
					}
				}
			}*/
			
			echo "<script>location.href='users.php?success=2'</script>";
			exit;
		}
		else{
			$sqlcheck = dbQuery($dbConn, "select id from users where phone='".$phone."'");
			if(dbNumRows($sqlcheck) == 0){
				$sqlcheck2 = dbQuery($dbConn, "select id from users where email='".$email."'");
				if(dbNumRows($sqlcheck2) == 0){
						$pass = mt_rand(100000, 999999);
						dbQuery($dbConn, "INSERT INTO users SET 
							name = '".tep_db_input($name)."',
							phone = '".tep_db_input($phone)."',
							email = '".tep_db_input($email)."',
							pass = '".md5($pass)."',
							`address` = '".tep_db_input($address)."',
							`type` = '".tep_db_input($type)."',
							`status` = 1,
							regdate = '".date('Y-m-d')."',
							`aadharno` = '".tep_db_input($aadharno)."'");
					$insert_id = dbInsertId($dbConn);
					// User photo
					if(isset($_FILES["photo"]) && $_FILES["photo"]["size"]>0)
					$updimgnm='';
					{
						
							$mytime=time();
							
							$srcfile = $_FILES['photo']['type'];
							$imageinfo = getimagesize($_FILES['photo']['tmp_name']); //check image size
							
							$mflname = strtolower($_FILES['photo']['name']);
							
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
							
							if($errstring1=="" && $errstring2=="" && $errstring3=="")
							{
								if(move_uploaded_file($_FILES["photo"]["tmp_name"],"../uploads/user/" . $mytime.'_'.$_FILES["photo"]["name"]))
								{
									$updimgnm = $mytime.'_'.$_FILES["photo"]["name"];

									dbQuery($dbConn, "UPDATE users set photo = '".$updimgnm."' where id='".$insert_id."'");
									
							
								}
							}
					}
					
					if($type == 3){
						$to = $email;
						$subject = "Successfully Registered";

						$message = "<table width='100%'>
						<tr>
						<td colspan='2'>Hello ".$name.",</td>
						</tr>
						<tr><td colspan='2'>&nbsp;</td></tr>
						<tr>
						<td colspan='2'>You are successfully registered in our App. You can now login from our App.</td>
						</tr>
						<tr><td colspan='2'>&nbsp;</td></tr>
						<tr>
						<td colspan='2'>Your login details is given below:</td>
						</tr>
						<tr>
						<td colspan='2'><strong>Email:</strong> ".$email."<br>
						<strong>Password:</strong> ".$pass."</td>
						</tr>
						<tr><td colspan='2'>&nbsp;</td></tr>
						<tr>
						<td colspan='2'>Thanks,<br>Audit</td>
						</tr>
						</table>";

						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

						$headers .= 'From: Audit <noreply@symmetrix.site>' . "\r\n";
						//$headers .= 'Cc: myboss@example.com' . "\r\n";

						mail($to,$subject,$message,$headers);
					}
					else{

						if($type == 1)
						$loginas = 'Manager';
						else if($type == 2)
						$loginas = 'Assistant';

						$to = $email;
						$subject = "Successfully Registered";

						$message = "<table width='100%'>
						<tr>
						<td colspan='2'>Hello ".$name.",</td>
						</tr>
						<tr><td colspan='2'>&nbsp;</td></tr>
						<tr>
						<td colspan='2'>You are successfully registered as ".$loginas.". You can now login into our admin area.</td>
						</tr>
						<tr><td colspan='2'>&nbsp;</td></tr>
						<tr>
						<td colspan='2'>Your login details is given below:</td>
						</tr>
						<tr>
						<td><strong>Email:</strong> ".$email."</td>
						<td><strong>Password:</strong> ".$pass."</td>
						</tr>
						<tr><td colspan='2'>&nbsp;</td></tr>
						<tr>
						<td colspan='2'>Thanks,<br>Audit</td>
						</tr>
						</table>";

						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

						$headers .= 'From: <noreply@symmetrix.site>' . "\r\n";

						mail($to,$subject,$message,$headers);

					}
					
					echo "<script>location.href='users.php?success=1'</script>";
					exit;
					
				}
				else{
					echo "<script>location.href='user_manage.php?error=2'</script>";
					exit;
				}
				
			}
			else{
				echo "<script>location.href='user_manage.php?error=1'</script>";
				exit;
			}
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
            <h1 class="m-0 text-dark">User</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">User</li>
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
	if(isset($_REQUEST['error']) && $_REQUEST['error']==1)
	{
	?>
	<div style="margin:5px 0; padding:10px; background:#F6CDCD; border:1px solid #f00; color:#f00; font-weight:bold;">Phone No already exists.</div>
	<?php 
	}
	if(isset($_REQUEST['error']) && $_REQUEST['error']==2)
	{
	?>
	<div style="margin:5px 0; padding:10px; background:#F6CDCD; border:1px solid #f00; color:#f00; font-weight:bold;">Email already exists.</div>
	<?php 
	}
	if(isset($_REQUEST['error']) && $_REQUEST['error']==3)
	{
	?>
	<div style="margin:5px 0; padding:10px; background:#F6CDCD; border:1px solid #f00; color:#f00; font-weight:bold;">Username already exists.</div>
	<?php 
	}
	?>
	
        <?php 
		$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):'';
		$sql = "SELECT * from `users` where id = '".$id."'";
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
                  <?php if($id) echo 'Update'; else echo 'Add';?> User
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
					<?php
						if($sql_res_fetch['type'] == 1){
						?>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Company Name</label>
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['name']);?>" placeholder="Enter Company Name" name="name">
							</div>
						</div>
						<?php
						}
						if($sql_res_fetch['type'] == 2){
							?>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="">First Name</label>
									<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['name']);?>" placeholder="Enter Name" name="name">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="">Last Name</label>
									<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['lname']);?>" placeholder="Enter Name" name="lname">
								</div>
							</div>
							<?php
							}
						?>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Address</label>
								<input type="text" class="form-control" id="" value="<?php echo stripslashes($sql_res_fetch['address']);?>" placeholder="Address" name="address">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
              				<label for="">Email</label>
								<input type="email" class="form-control required" <?php if($id) echo 'readonly';?> id="" value="<?php echo $sql_res_fetch['email'];?>" placeholder="Email" name="email">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Contact Number</label>
								<input type="text" class="form-control required" id="" value="<?php echo substr($sql_res_fetch['phone'],2,10);?>" placeholder="Contact Number" name="phone">
							</div>
            		</div>
					</div>

						<div class="row">
						<?php
						if($sql_res_fetch['type'] == 1){
						?>
						<div class="col-sm-3">
							<div class="form-group">
								<label for="">Office Contact No</label>
								<input type="text" class="form-control" id="" value="<?php echo strtoupper($sql_res_fetch['contact_ofc']);?>" placeholder="Office Contact Number">
							</div>
						</div>

						<div class="col-sm-3">
							<div class="form-group">
							<label for="">ABN/ACN</label>
								<select name="abnacn" class="form-control ">
									<option value="ABN" <?php if($sql_res_fetch['abnacn']=="ABN") echo "selected";?>>ABN</option>
									<option value="ACN" <?php if($sql_res_fetch['abnacn']=="ACN") echo "selected";?>>ACN</option>
								</select>
						</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
              				<label for="">ABN/ACN Number</label>
								<input type="text" class="form-control digits " name="number" placeholder="ABN/ACN Number" value="<?php echo $sql_res_fetch['number'];?>">
							</div>
						</div>
						<?php
						}
						?>
						<div class="col-sm-3">
							<div class="form-group">
							<label for="">User Status</label>
								<select name="isdeactivated" class="form-control required">
									<option value="0" <?php if($sql_res_fetch['isdeactivated']==0) echo "selected";?>>Active</option>
									<option value="1" <?php if($sql_res_fetch['isdeactivated']==1) echo "selected";?>>Inactive</option>
								</select>
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

  $(function(){
	$('#quickForm').on('submit', function(){
		var pass = $('#pass').val();
		var pass2 = $('#pass2').val();
		if(pass != "" && pass2 != ""){
			if(pass == pass2){
				return true;
			}
			else{
				alert('Password and confirm password must be same.');
				return false;
			}
		}
	});
  });
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
