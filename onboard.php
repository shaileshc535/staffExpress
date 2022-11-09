<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
if(!isset($_SESSION["loggedin"])){
  ?>
  <script language="javascript">
    window.location.href="<?php echo SITEURL;?>login";
  </script>		
  <?php
  }
  else{
    if($_SESSION['Usertype'] != 1){
      ?>
      <script language="javascript">
        window.location.href="<?php echo SITEURL;?>";
      </script>
      <?php
    }
    else{
      $STRIPE_API_KEY = STRIPE_API_KEY;

      require_once 'stripe/init.php';

  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'onb'){

    $getonboard = dbQuery($dbConn, "SELECT stripe_account from users where id = '".$_SESSION['loginUserId']."'");
    $row = dbFetchArray($getonboard);
    if($row['stripe_account']){
      $stripe = new \Stripe\StripeClient(
        $STRIPE_API_KEY
      );
      $onboard = $stripe->accountLinks->create([
        'account' => $row['stripe_account'],
        'refresh_url' => SITEURL."onboard",
        'return_url' => SITEURL."onb-success",
        'type' => 'account_onboarding',
      ]);
      
      $chargeJson = $onboard->jsonSerialize();
      if($chargeJson['object'] == 'account_link'){
        $url = $chargeJson['url'];
        echo "<script>location.href='".$url."'</script>";
        exit;
      }
      else{
        echo "<script>location.href='".SITEURL."onboard?error=1'</script>";
        exit;
      }
      
    }
  }

  $check = dbQuery($dbConn, "SELECT stripe_account,isonboardingsuccess from users where id = '".$_SESSION['loginUserId']."'");
    $row = dbFetchArray($check);
    if($row['stripe_account'] && $row['isonboardingsuccess'] == 1){
      echo "<script>location.href='".SITEURL."onb-success'</script>";
      exit;
    }
  
include_once('header.php');
 
?>

<section class="mainpart">
    <section class="gearpart">
		<div class="container">

    <div class="row">
    <?php include_once('provider_left_menu.php');?>
            <div class="col-md-9 col-sm-9">
                <h5>Onboard to submit business details to receive payment</h5>
                <?php
                if(isset($_REQUEST['error']) && $_REQUEST['error']==1){
                  echo '<div class="alert-danger" style="padding:15px;margin-bottom:10px;">Some problem occurs. Please try again.</div>';
                }
                ?>
                <div class="table-responsive bookingtbl">
                <a href="<?php echo SITEURL;?>onboard?action=onb" id="onboard" style="margin-top:5px;" class="savebtn">Onboard</a>
                </div>
            </div>
    </div> 

   </div>
 
</section>
</section>
<?php
    }
  }
?>
<?php
include_once('footer.php');
?>