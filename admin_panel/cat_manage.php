<?php include('../config/config.php'); 
include('includes/session.php');
include_once "../config/common.php";
$dbConn = establishcon();
if(count($_POST) > 0)
	{
		$mytime = time();
		$category = isset($_POST['category'])?$_POST['category']:"";
		if(isset($_POST['isdisplayhomepage']))
			$isdisplayhomepage = $_POST['isdisplayhomepage'];
		else
			$isdisplayhomepage = 0;
		$id = isset($_POST['id'])?$_POST['id']:"";

		if($id){
			dbQuery($dbConn, "UPDATE category set
			category = '".tep_db_input($category)."', isdisplayhomepage = '".$isdisplayhomepage."'
			where id = '".$id."'");
			
			if(isset($_FILES["catimg"]) && $_FILES["catimg"]["size"]>0)
			{
				
				$updimgnm='';

				$srcfile = $_FILES['catimg']['type'];
				$imageinfo = getimagesize($_FILES['catimg']['tmp_name']); //check image size
				
				$mflname = strtolower($_FILES['catimg']['name']);
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
					if(move_uploaded_file($_FILES["catimg"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
					{
						
					$sqlequip = dbQuery($dbConn, "SELECT catimg from category where id = '".$id."'");
					$row = dbFetchArray($sqlequip);
					@unlink("../uploads/".$row['catimg']);
			
					$updimgnm = $mytime.'_'.$mflname;

					dbQuery($dbConn, "UPDATE category set catimg = '".$updimgnm."' where id='".$id."'");
						
				
					}
				}
			}
			echo "<script>location.href='categories.php?success=2'</script>";
			exit;
		}
		else{
			dbQuery($dbConn, "INSERT INTO category SET category = '".tep_db_input($category)."', isdisplayhomepage = '".$isdisplayhomepage."'");
			
			$catid = dbInsertId($dbConn);
			
			if(isset($_FILES["catimg"]) && $_FILES["catimg"]["size"]>0)
			{
				
				$updimgnm='';

				$srcfile = $_FILES['catimg']['type'];
				$imageinfo = getimagesize($_FILES['catimg']['tmp_name']); //check image size
				
				$mflname = strtolower($_FILES['catimg']['name']);
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
					if(move_uploaded_file($_FILES["catimg"]["tmp_name"],"../uploads/" . $mytime.'_'.$mflname))
					{
						
					$updimgnm = $mytime.'_'.$mflname;

					dbQuery($dbConn, "UPDATE category set catimg = '".$updimgnm."' where id='".$catid."'");
						
				
					}
				}
			}
					
			echo "<script>location.href='categories.php?success=1'</script>";
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
            <h1 class="m-0 text-dark">Category</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Category</li>
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
		$sql = "SELECT * from `category` where id = '".$id."'";
		$sql_res = dbQuery($dbConn,$sql);
		$sql_res_fetch = dbFetchAssoc($sql_res);
		?>
        <!-- Main row -->
        <div class="row">
          
          <section class="col-lg-6 connectedSortable">

            <!-- Map card -->
            <div class="card masteraudit">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="nav-icon fas fa-edit"></i>
                  <?php if($id) echo 'Update'; else echo 'Add';?> Category
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
                          <label for="">Category</label>
							<input type="text" class="form-control required" id="" value="<?php echo stripslashes($sql_res_fetch['category']);?>" placeholder="Enter Category" name="category">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Category Image</label>
								<?php
								if($sql_res_fetch['catimg']){
									?>
									<img src="../uploads/<?php echo stripslashes($sql_res_fetch['catimg']);?>" alt="" width="120"><br>
									<?php
								}
								?>
								<input type="file" class="form-control " id="" name="catimg" accept="image/*">
								<br>(Please upload square image of minimum 100px x 100px)
							</div>
						</div>
						<div class="col-sm-6">
						  <div class="form-group">
                          <label for="">Display on home page?</label>
							<input type="checkbox" name="isdisplayhomepage" value="1" <?php if($sql_res_fetch['isdisplayhomepage']==1) echo "checked";?>>
							</div>
						</div>
          </div>
          
          <div class="row">
						<div class="col-sm-6">
            
              <button type="submit" class="btn btn-primary custombut">Submit</button>
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
function onError(data, status) {
    alert('Network error has occurred, please try again!');
}
$(function(){
  $('#quickForm').validate();
    
});

$(document).on('change', '#stateid', function () {
        var stateid = $(this).val();
        $('#distid').html('');
        var dists = '<option value="">Select</option>';
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "getstate.php",
            cache: false,
            data: {stateid:stateid},
            beforeSend: function () {  },
            complete: function () {  },
            success: function (json) {
                if(json.dists.length > 0){
                    $.each(json.dists, function (i, row) {
						dists += '<option value="' + row.id + '">'+row.district+'</option>';

                    });
                }
                $('#distid').html(dists);

            },
            error: onError
        });

    });
</script>
</body>
</html>
