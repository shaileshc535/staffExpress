<?php include('../config/config.php'); 
include('includes/session.php');
include_once "../config/common.php";
$dbConn = establishcon();
if(count($_POST) > 0)
	{
		$name = strip_tags(trim($_POST['name']));
		$catid = trim($_POST['catid']);
		$description = isset($_POST['description'])?strip_tags(trim($_POST['description'])):"";
		$location = isset($_POST['location'])?trim($_POST['location']):"";
		$myloc = isset($_POST['myloc'])?trim($_POST['myloc']):"";
		$min_hiring_timeline = trim($_POST['min_hiring_timeline']);
		$rate_per_day = strip_tags(trim($_POST['rate_per_day']));
		//$value_of_equip = trim($_POST['value_of_equip']);
		$id = isset($_POST['id'])?trim($_POST['id']):"";

		$checkloc = dbQuery($dbConn, "select id from locations where place_id = '".$location."'");
		if(dbNumRows($checkloc) == 0){
			dbQuery($dbConn, "INSERT INTO locations SET place_id = '".$location."', location = '".$myloc."'");
			$locid = dbInsertId($dbConn);
		}
		else{
			$getplace = dbQuery($dbConn, "SELECT id from locations where place_id = '".$location."'");
			$locid = dbFetchArray($getplace);
			$locid = $locid['id'];
		}

		$getcat = dbQuery($dbConn, "select catid from subcategories where id = '".$catid."'");
		$getcatid = dbFetchArray($getcat);

		if($id != ""){
				dbQuery($dbConn, "UPDATE `equipments` SET 
				provider_id = '".$_SESSION['loginUserId']."',
				name = '".tep_db_input($name)."',
				catid = '".tep_db_input($catid)."',
				parent_catid = '".tep_db_input($getcatid['catid'])."',
				description = '".tep_db_input($description)."',
				location = '".$locid."',
				min_hiring_timeline = '".tep_db_input($min_hiring_timeline)."',
				rate_per_day = '".tep_db_input($rate_per_day)."'
				WHERE id = '".$id."'
				");

				if(isset($_FILES["picture"]) && $_FILES["picture"]["size"]>0)
				{
					
					$updimgnm='';

					$mytime=time();
					
					$srcfile = $_FILES['picture']['type'];
					$imageinfo = getimagesize($_FILES['picture']['tmp_name']); //check image size
					
					$mflname = strtolower($_FILES['picture']['name']);
					
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
						if(move_uploaded_file($_FILES["picture"]["tmp_name"],"../uploads/" . $mytime.'_'.$_FILES["picture"]["name"]))
						{
							
						$sqlequip = dbQuery($dbConn, "SELECT picture from equipments where id = '".$id."'");
						$row = dbFetchArray($sqlequip);
						@unlink("../uploads/".$row['picture']);
				
						$updimgnm = $mytime.'_'.$_FILES["picture"]["name"];

						dbQuery($dbConn, "UPDATE equipments set picture = '".$updimgnm."' where id='".$id."'");
							
					
						}
					}
				}

				echo "<script>location.href='equipment.php?success=2'</script>";
				exit;
			}
		else{
			
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
  <style>
  #mylocations{z-index: 999;padding: 15px;position: absolute;background-color: #fff;width: 100%;border: 1px solid #ccc;border-top:0;padding-top:5px;top:96px;}
  #mylocations ul li{font-size:12px;}
  #mylocations ul li a{color:#666;}
	.proloctn{border-radius: 20px;width: 94% !important;margin-top: -25px;}
  </style>
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
		$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):"";
		$sqlequip = dbQuery($dbConn, "SELECT a.*,b.place_id,b.location from equipments a inner join locations b on a.location=b.id where a.id = '".$id."'");
		$row = dbFetchArray($sqlequip);
		?>
        <!-- Main row -->
        <div class="row">
          
          <section class="col-lg-12 connectedSortable">

            <!-- Map card -->
            <div class="card masteraudit">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="nav-icon fas fa-edit"></i>
                  <?php if($id) echo 'Update'; else echo 'Add';?> Equipment
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
								<input type="text" class="form-control required" id="" value="<?php echo stripslashes($row['name']);?>" placeholder="Enter Name" name="name">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Category</label>
								<select name="catid" id="catid" class="form-control required">
			<option value="">Select Category</option>
			<?php
			$getcats = dbQuery($dbConn, "SELECT * from categories order by category");
			while($catrow = dbFetchArray($getcats)){
				?>
				<optgroup label="<?php echo stripslashes($catrow['category']);?>">
				<?php
				 $getsubcats = dbQuery($dbConn, "SELECT id,subcat from subcategories where catid = '".$catrow['id']."' order by subcat");
				 while($subcatrow = dbFetchArray($getsubcats)){
				 ?>
					<option value="<?php echo $subcatrow['id'];?>" <?php if($subcatrow['id'] == $row['catid']) echo "selected";?>><?php echo stripslashes($subcatrow['subcat']);?></option>
					<?php
				 }
				 ?>
				</optgroup>
				<?php
			}
			?>
			</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
              				<label for="">Location</label>
							  <input id="location" name="myloc" type="text" placeholder="Location" class="form-control required" autocomplete="off" value="<?php echo stripslashes($row['location']);?>" />
						<div id="mylocations" class="proloctn" style="display:none;z-index:999;padding:15px;">
						<ul></ul>
						</div>
						<input type="hidden" id="slctloc" name="location" value="<?php echo stripslashes($row['place_id']);?>" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Picture</label>
								<?php
								if($row['picture']){
								?>
								<img src="<?php echo SITEURL."uploads/".$row['picture'];?>" alt="<?php echo stripslashes($row['name']);?>" style="height:200px;" /><br/><br/>
								<?php
								}
								?>
									<input type="file" name="picture" id="picture" class="form-control <?php if($id=="") echo 'required';?>">
							</div>
            		</div>
					</div>

					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
							
              				<label for="">Minimum Hiring Timeline</label>
								<select name="min_hiring_timeline" id="min_hiring_timeline" class="form-control required">
								<option value="">Select Minimum Hiring Timeline</option>
								<option value="1" <?php if($row['min_hiring_timeline'] == 1) echo "selected";?>>1 day</option>
								<option value="3" <?php if($row['min_hiring_timeline'] == 3) echo "selected";?>>3 days</option>
								<option value="5" <?php if($row['min_hiring_timeline'] == 5) echo "selected";?>>5 days</option>
								<option value="7" <?php if($row['min_hiring_timeline'] == 7) echo "selected";?>>7 days</option>
								<option value="10" <?php if($row['min_hiring_timeline'] == 10) echo "selected";?>>10 days</option>
								</select>
							</div>
						</div>
						</div>
						<div class="row">
						
						<div class="col-sm-4">
							<div class="form-group">
								<label for="">Rate/day</label>
								<input type="text" placeholder="Enter Rate/day in <?php echo strtoupper($row['currency']);?>" name="rate_per_day" id="rate_per_day" class="form-control required digits" value="<?php echo stripslashes($row['rate_per_day']);?>"> in <?php echo strtoupper($row['currency']);?>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
							
              				<label for="">Description</label>
							  <textarea placeholder="Enter Description" name="description" id="description" class="form-control" style="height:100px;"><?php echo stripslashes($row['description']);?></textarea>
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
  var siteurl = "https://symmetrix.site/staging/p2p/";

var typingTimer;
var doneTypingInterval = 1000;
var $input = $('#location');

//on keyup, start the countdown
$input.on('keyup', function () {
    if ($(this).val() != "")
        $("#loading").show();
    else
        $("#loading").hide();
    clearTimeout(typingTimer);

    typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown 
$input.on('keydown', function () {
    clearTimeout(typingTimer);
});

function doneTyping() {
    //do something
    var mylocations = '';
    var keyword = $input.val();
    if (keyword != "") {
        if (keyword.length > 3) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: siteurl + "getplace.php",
                cache: false,
                data: { keyword: keyword, action: 'getplaces' },
                beforeSend: function () { },
                complete: function () { $("#loading").hide(); },
                success: function (json) {
                    if (json.success == '1') {
                        if (json.locations.length > 0) {
                            $.each(json.locations, function (i, row) {
                                mylocations += "<li><a href='javascript:void(0);' data-id='" + row.place_id + "' class='mylocations'>" + row.mydesc + "</a></li>";
                            });
                            $("#mylocations ul").html(mylocations);
                            $("#mylocations").show();
                        }
                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }
    else {
        $("#mylocations").hide();
    }
}

});
$(document).on('click', '.mylocations', function () {
    var placeid = $(this).data("id");
    var mytext = $(this).text();
    if (placeid != "") {
        $("#location").val(mytext);
        $("#slctloc").val(placeid);
        $("#mylocations").hide();
    }
});
</script>
</body>
</html>
