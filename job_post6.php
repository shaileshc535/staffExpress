<?php

include('config/config.php'); 

include_once "config/common.php";

include "phpmailer/class.phpmailer.php";

$dbConn = establishcon();


if(!isset($_SESSION['loginUserId'])){

	echo "<script>location.href='".SITEURL."employer_login'</script>";

	exit;

}

$STRIPE_API_KEY = STRIPE_API_KEY;

//if(isset($_POST['jobid'])){

$user = dbQuery($dbConn, "SELECT name,email from users where id = '".$_SESSION['loginUserId']."'");

$row = dbFetchArray($user);



if(count($_POST) > 0 && isset($_POST['myoption'])){

	$eligible_cands = array();

	$qualification = array();

	$staff_quals = array();

	$match1 = 0;

	$match2 = 0;

	$match3 = 0;

	$match4 = 0;

	$match5 = 0;

    $match6 = 0;

	$username = "ninepebblesteam@gmail.com";

	$password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";

	$str = $username.":".$password;

	$auth = base64_encode($str);

    $jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";

    $jobtype = isset($_POST['jobtype'])?tep_db_input($_POST['jobtype']):'2';

    $myoption = isset($_POST['myoption'])?tep_db_input($_POST['myoption']):"";

	

if($jobtype == '2'){

    if($myoption == 1)

    $price = 30;

    else if($myoption == 2)

    $price = 33;

    require_once 'stripe/init.php';

    if(!empty($_POST['stripeToken'])){

        $token  = $_POST['stripeToken'];

        $job = dbQuery($dbConn, "SELECT a.title,a.catid,a.jobdate,a.jobdate2,a.starttime,a.endtime,a.experience,a.experience_month,a.exp_type,a.workmode,a.location,a.street_address,a.suburb,a.state,a.add_time,a.covertype,a.is_shift,a.shifttype,a.noshiftsrttime,a.noshiftendtime,b.email,b.phone,b.name,c.states FROM job_details a inner join users b on a.employer_id=b.id inner join states c on a.state=c.id where a.id = '".$jobid."'");

        $row = dbFetchArray($job);

        $title = stripslashes($row['title']);

        //$jobcat = $row['catid'];
		$jobcatids = array();

		$mycats = dbQuery($dbConn, "SELECT catid from job_cat where jobid = '".$jobid."'");

		while($mycatsrow = dbFetchArray($mycats)){

			$jobcatids[] = $mycatsrow['catid'];

		}


        $jobstrtdate = $row['jobdate'];

        $jobenddate = $row['jobdate2'];

        $strttime = $row['starttime'];

        $endtime = $row['endtime'];
		
		$noshiftsrttime = $row['noshiftsrttime'];

        $noshiftendtime = $row['noshiftendtime'];

        $jobexp = $row['experience'];

        $exp_type = $row['exp_type'];
		
		if($exp_type == 1){
			$jobexptype = "compulsory";
		}
		if($exp_type == 2){
			$jobexptype = "preferred";
		}

        $workmode = $row['workmode'];

        $street_address = stripslashes($row['street_address']);

        $location = $row['location'];

        $jobaddr = $street_address." ".$location;

		$street_address = $street_address.", ".$row['suburb'].", ".$row['states']." - ".$location;

        $itemName = "Payment for job: ".$row['title'];

	    \Stripe\Stripe::setApiKey($STRIPE_API_KEY); 

        

        // Add customer to stripe 

        $customer = \Stripe\Customer::create(array( 

            'email' => $row['email'],

            'source'  => $token 

        ));

        

        // Unique order ID 

        $orderID = strtoupper(str_replace('.','',uniqid('', true))); 

        

        // Convert price to cents 

        $itemPrice = ($price*100);

        

        // Charge a credit or a debit card 

        $charge = \Stripe\Charge::create(array( 

            'customer' => $customer->id, 

            'amount'   => $itemPrice, 

            'currency' => 'aud',

            'description' => $itemName,

            'metadata' => array( 

                'order_id' => $orderID 

            ) 

        )); 



        $charge_id = $charge->id;

        

        // Retrieve charge details 

        $chargeJson = $charge->jsonSerialize();



        if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code'])){

          // Order details  

          $transactionID = $chargeJson['balance_transaction']; 

          $payment_status = $chargeJson['status'];



			if($payment_status == 'succeeded'){
				
				 dbQuery($dbConn, "UPDATE job_details set postcomplete = 1 where id = '".$jobid."'");

                dbQuery($dbConn, "UPDATE job_details set charge_id = '".$charge_id."', transactionID = '".$transactionID."', total_amt = '".$price."' where id = '".$jobid."'");



                $jobday = date('D', strtotime($jobstrtdate));

                $job_day_number = date('N', strtotime($jobday));



                $jobday2 = date('D', strtotime($jobenddate));

                $job_day_number2 = date('N', strtotime($jobday2));


                if($myoption == 2){

                    $catids = array();
					$parentcatids = array();

                $jobquals = dbQuery($dbConn, "SELECT qualifications from qualifictn_required where jobid = '".$jobid."'");

                while($rowqual = dbFetchArray($jobquals)){

                    $qualification[] = $rowqual['qualifications'];

                }



                $candidates = dbQuery($dbConn, "SELECT a.email,b.* FROM users a inner join staff_details b on a.id=b.staff_id where a.type=2 and a.isdeactivated=0");

                if(dbNumRows($candidates) > 0){

                while($res = dbFetchArray($candidates)){

                    /*if($res['catid'] == $jobcat){

                        $match1 = 1;

                    }

                    else

                    $match1 = 0;*/

                    $mycats = dbQuery($dbConn, "SELECT catid from staff_job_cat where staff_id = '".$res['staff_id']."'");

                    while($mycatsrow = dbFetchArray($mycats)){

                        $catids[] = $mycatsrow['catid'];

                    }
					

					foreach($jobcatids as $val){
						if(in_array($val, $catids)){
							$match1 = 1;
							break;
						}
					}

					$period = new DatePeriod(
						new DateTime($jobstrtdate),
						new DateInterval('P1D'),
						new DateTime($jobenddate)
					);
				
				   foreach ($period as $key => $value) {
						$days = $value->format('Y-m-d');
						$jobday = date('D', strtotime($days));
						$job_day_number = date('N', strtotime($jobday));
						
						if(strpos($res['working_days'],$job_day_number) !== false){
							$match2 = 1;
							break;
						}
				   }

                    /*if(strpos($res['working_days'],$job_day_number) !== false){

                        $match2 = 1;

                    }*/

                    

                    /*else if($res['working_days'] == 2){

                        if($job_day_number != 0 && $job_day_number != 6 && $job_day_number2 != 0 && $job_day_number2 != 6 && $job_day_number <= $job_day_number2)

                        $match2 = 1;

                    }

                    else if($res['working_days'] == 3){

                        if(($job_day_number == 0 || $job_day_number == 6) && ($job_day_number2 == 0 || $job_day_number2 == 6))

                        $match2 = 1;

                    }

                    else if($res['working_days'] == 4){

                        if($job_day_number == 1 && $job_day_number2 == 1)

                        $match2 = 1;

                    }

                    else if($res['working_days'] == 5){

                        if($job_day_number == 2 && $job_day_number2 == 2)

                        $match2 = 1;

                    }

                    else if($res['working_days'] == 6){

                        if($job_day_number == 3 && $job_day_number2 == 3)

                        $match2 = 1;

                    }

                    else if($res['working_days'] == 7){

                        if($job_day_number == 4 && $job_day_number2 == 4)

                        $match2 = 1;

                    }

                    else if($res['working_days'] == 8){

                        if($job_day_number == 5 && $job_day_number2 == 5)

                        $match2 = 1;

                    }*/



                    $staff_qual = dbQuery($dbConn, "SELECT qualification from staff_qualification where staff_id = '".$res['staff_id']."'");

                    while($getstffqual = dbFetchArray($staff_qual)){

                        $staff_quals[] = $getstffqual['qualification'];

                    }



                    /*if(count($staff_quals) > 0 && count($qualification) > 0){

                        for($i=0; $i<count($staff_quals); $i++){

                            if(in_array($staff_quals[$i], $qualification)){

                                $match3 = 1;

                                break;

                            }

                            else

                            $match3 = 0;

                        }

                    }

                    else{

                        $match3 = 1;

                    }*/
					$match3 = 1;

                    

                    if($exp_type == 1){

                        /*if($res['experience'] != "10+" && $exp != "10+"){

                            if($res['experience'] >= $exp)

                            $match4 = 1;

                        }
                        else{

                            if($res['experience'] == $exp)

                            $match4 = 1;

                        }*/
						$allexp = array();
						$exp = dbQuery($dbConn, "select a.experience from staff_experience a where a.staff_id='".$res['staff_id']."' order by a.id");
						if(dbNumRows($exp) > 0){
							while($rowexp = dbFetchArray($exp)){
								$allexp[] = $rowexp['experience'];
							}
							
							if(in_array($jobexp, $allexp)){
								$match4 = 1;
							}
							else{
								$match4 = 0;
							}
						}
						else{
							$match4 = 0;
						}

                    }

                    else if($exp_type == 2){

                        //if($res['experience'] >= $exp)

                        $match4 = 1;

                    }

                    /*if($workmode == $res['int_job_type'])
                    $match5 = 1;
                    else
                    $match5 = 0;*/


						$myparentcats = dbQuery($dbConn, "SELECT catid from staff_job_parent_cat where staff_id = '".$res['staff_id']."'");

						while($myparntcatsrow = dbFetchArray($myparentcats)){

							$parentcatids[] = $myparntcatsrow['catid'];
						}
						
						if(in_array(41, $parentcatids)){
							
							if($row['state'] == $res['state']){
								$match6 = 1;
							}
							else{
								$match6 = 0;
							}
						}
						else{
					
							$address = $res['address'];

							$postcode = $res['postcode'];

							$staffaddr = $address." ".$postcode;

							$work_distance = get_distance_between_postcodes($jobaddr,$staffaddr);

							if($work_distance != "NOT_FOUND"){

								if($work_distance <= $res['distance']){

									$match6 = 1;

								}

								else{

									$match6 = 0;

								}

							}

							else

							$match6 = 0;
						}

                   
                    if($match1 == 1 && $match6 == 1){

                        $eligible_cands[] = $res['email'];

                    };
					

                    $match1 = 0;

                    $match2 = 0;

                    $match3 = 0;

                    $match4 = 0;

                    $match5 = 0;

                    $match6 = 0;

                    $staff_quals = array();

                    $catids = array();
					
					$allexp = array();
					
					$parentcatids = array();


                }

                $today = date('Y-m-d');

                $myshifts = array();
                $shifttimes = array();
                $myshifttimes = '';
                $myshifts_str = '';
                foreach($eligible_cands as $val){

                    $users = dbQuery($dbConn, "SELECT a.id,a.name,a.phone,b.notified,b.notified_job_type from users a inner join staff_details b on a.id=b.staff_id where a.email = '".$val."'");

                    $fetch = dbFetchArray($users);

                    dbQuery($dbConn, "INSERT INTO job_status set jobid = '".$jobid."', application_sent_to = '".$fetch['id']."', senton = '".$today."'");

                    $link = SITEURL."job_details/?job=".base64_encode($jobid)."&user=".base64_encode($fetch['id'])."&do=viewjob";

                    if($row['covertype']==1){
                        if($row['add_time'] == 1){
                            $jobinfo = "".$title." job in ".$row['suburb']." from ".date('M j, Y', strtotime($jobstrtdate))." at ".date('h:i a', strtotime($strttime))." - ".date('M j, Y', strtotime($jobenddate))." at ".date('h:i a', strtotime($endtime)).". Experience ".$jobexp." year(s) ".$row['experience_month']." month(s) ".$jobexptype.".";
                        }
                        else{
                            $jobinfo = "".$title." job in ".$row['suburb']." from ".date('M j, Y', strtotime($jobstrtdate))." - ".date('M j, Y', strtotime($jobenddate)).". Experience ".$jobexp." year(s) ".$row['experience_month']." month(s) ".$jobexptype.".";
                        }
                    }
                    else{
                        if($row['add_time'] == 1){
                            $jobinfo = "".$title." job in ".$row['suburb']." from ".date('h:i a', strtotime($strttime))." - ".date('h:i a', strtotime($endtime)).". Experience ".$jobexp." year(s) ".$row['experience_month']." month(s) ".$jobexptype.".";
                        }
                        else{
                            $jobinfo = "".$title." job in ".$row['suburb'].". Experience ".$jobexp." year(s) ".$row['experience_month']." month(s) ".$jobexptype.".";
                        }

                    }
                    //else{
                        if($row['is_shift'] == 1){
                            $shifttype = explode(",", $row['shifttype']);
                            
                            foreach($shifttype as $shval){
                                if($shval == '1')
                                    $value = "Day Shift";
                                if($shval == '2')
                                    $value = "Night Shift";
                                if($shval == '3')
                                    $value = "Overnight Shift";
                                
                                $myshifts[] = $value;
                            }
                            if(count($myshifts) > 0){
                                $myshifts_str = implode(", ", $myshifts);
                            }
                            $allshifts = $myshifts_str;
                            $staffexp = dbQuery($dbConn, "SELECT starttime,endtime from shift_times where jobid = '".$jobid."' order by id");
							if(dbNumRows($staffexp) > 0){
								
								while($staffexprow = dbFetchArray($staffexp)){
									$shifttimes[] = date('h:i A', strtotime($staffexprow['starttime']))." - ".date('h:i A', strtotime($staffexprow['endtime']));
									
							    }

                                if(count($shifttimes) > 0){
                                    $myshifttimes = implode(", ", $shifttimes);
                                    $myshifttimes = "(Shift Times: ".$myshifttimes.")";
                                }
                            }
                            if($row['covertype']==1){
                                $jobinfo = "".$title." job in ".$row['suburb']." from ".date('M j, Y', strtotime($jobstrtdate))." - ".date('M j, Y', strtotime($jobenddate))." with ".$allshifts.". ".$myshifttimes.". Experience ".$jobexp." year(s) ".$row['experience_month']." month(s) ".$jobexptype.".";
                            }
                            else{
                                $jobinfo = "".$title." job in ".$row['suburb']." with ".$allshifts.". ".$myshifttimes.". Experience ".$jobexp." year(s) ".$row['experience_month']." month(s) ".$jobexptype.".";
                            }
                        }
                        else if($row['is_shift'] == 2){
							$jobinfo = "".$title." job in ".$row['suburb']." with Experience ".$jobexp." year(s) ".$row['experience_month']." month(s) ".$jobexptype.".";
                            /*if($row['covertype']==1){
								$jobinfo = "".$title." job in ".$row['suburb']." from ".date('M j, Y', strtotime($jobstrtdate))." at ".date('h:i a', strtotime($noshiftsrttime))." - ".date('M j, Y', strtotime($jobenddate))." at ".date('h:i a', strtotime($noshiftendtime)).". Experience ".$jobexp." year(s) ".$row['experience_month']." month(s) ".$jobexptype.".";
                            }
                            else{
                                
								$jobinfo = "".$title." job in ".$row['suburb']." from ".date('h:i a', strtotime($noshiftsrttime))." - ".date('h:i a', strtotime($noshiftendtime)).". Experience ".$jobexp." year(s) ".$row['experience_month']." month(s) ".$jobexptype.".";
                            }*/
                        }
					
					if($row['covertype'] == 1){
						if($fetch['notified_job_type'] == 1 || $fetch['notified_job_type'] == 3)
							$sendnoti = 1;
						else
							$sendnoti = 0;
					}
					else if($row['covertype'] == 2){
						if($fetch['notified_job_type'] == 2 || $fetch['notified_job_type'] == 3)
							$sendnoti = 1;
						else
							$sendnoti = 0;
					}
					
					
					if($sendnoti == 1){
                    if($fetch['notified'] == "Email" || $fetch['notified'] == "Both"){

                        // send mail to candidates
						/*$mail = new PHPMailer();
						$mail->IsSMTP();

						$mail->Host       = "cp-wc12.syd02.ds.network"; // cp-wc12.syd02.ds.network
						$mail->SMTPAuth   = true;  
						$mail->SMTPSecure = "ssl";                
						$mail->Port       = 465;                    
						$mail->Username   = "contact@staffexpress.com.au";            
						$mail->Password   = "QYhi[=Aoor{t";

						$mail->From = ADMINEMAIL;
						$mail->FromName = "Staff Express";
						$mail->Subject = "Job Offered";
						$mail->isHTML(true);
						$mail->AddAddress($val);*/

                        $to = $val;

                        $subject = "Job Offered";

                        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
                        <tr>
                        <td colspan='2'>&nbsp;</td>
                        </tr>

                        <tr>

                        <td colspan='2' style='padding-left:10px;padding-top:10px;'>Hello ".$fetch['name'].",</td>

                        </tr>

                        <tr><td colspan='2'>&nbsp;</td></tr>

                        <tr>

                        <td colspan='2' style='padding-left:10px;'>".$jobinfo."</td>

                        </tr>

                        <tr><td colspan='2'>&nbsp;</td></tr>

                        <tr>

                        <td colspan='2' style='padding-left:10px;'>Please click to express availability to the role.</td>

                        </tr>

                        <tr>

                        <td colspan='2' style='padding-left:10px;'><a href='".$link."' target='_blank'>".$link."</a></td>

                        </tr>

                        <tr><td colspan='2'>&nbsp;</td></tr>

                        <tr>

                        <td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>

                        </tr>

                        </table>";

                        $headers = "MIME-Version: 1.0" . "\r\n";

                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


                        $headers .= 'From: Staff Express <'.ADMINEMAIL.'>' . "\r\n";

                        mail($to,$subject,$message,$headers);
						/*$mail->Body = $message;
						  if(!$mail->Send()) {
							echo $mail->ErrorInfo;
						  } else { }
						  $mail->clearAllRecipients();*/

                    }

                    if($fetch['notified'] == "SMS" || $fetch['notified'] == "Both"){

                        // sending sms

                        $phone = $fetch['phone'];

                        if(strpos($phone, "+") !== false)

                        $phone = $phone;

                        else

                        $phone = "+".$phone;



                        $smsbody = $jobinfo."<br>Please click to express availability to the role:<br>".$link."";



                        $data_string = '{

                            "messages": [

                            {

                                "to": "'.$phone.'",

                                "source": "sdk",

                                "body": "'.$smsbody.'"

                            }

                            ]

                        }';



                        $ch = curl_init("https://rest.clicksend.com/v3/sms/send");

                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(

                                            'Content-Type: application/json',

                                            'Authorization: Basic '.$auth.''

                                            )

                            );

                            $result = curl_exec($ch);

                            $result = json_decode($result);

                    }
					}
                    $myshifts = array();
                    $shifttimes = array();
                    $myshifttimes = '';
                    $myshifts_str = '';

                }

                }

                }

				// mail to employer
				$mail = new PHPMailer();
				$mail->IsSMTP();

				$mail->Host       = "cp-wc12.syd02.ds.network"; // cp-wc12.syd02.ds.network
				$mail->SMTPAuth   = true;  
				$mail->SMTPSecure = "ssl";                
				$mail->Port       = 465;                    
				$mail->Username   = "contact@staffexpress.com.au";            
				$mail->Password   = "QYhi[=Aoor{t";

				$mail->From = ADMINEMAIL;
				$mail->FromName = "Staff Express";
				$mail->Subject = "Job Posted Successfully";
				$mail->isHTML(true);
				$mail->AddAddress($row['email']);
				//$to = $row['email'];

				//$subject = "Job Posted Successfully";

				$message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
				<tr>
				<td colspan='2'>&nbsp;</td>
				</tr>

				<tr>

				<td colspan='2' style='padding-left:10px;padding-top:10px;'>Hello ".$row['name'].",</td>

				</tr>

				<tr><td colspan='2'>&nbsp;</td></tr>

				<tr>

				<td colspan='2' style='padding-left:10px;'>Your Job Post for ".$title." role at ".$location." postcode is now live and running. Thank you for choosing Staff Express. Providing you with a seamless platform, great candidates for short term cover and also long term positions at a fraction of the cost of other job hiring sites. Expect candidates applying very soon. Remember to use our free to use Clock in Clock Out Feature to keep track of Staff Hours on their day of work. A handy feature.</td>

				</tr>

				<tr><td colspan='2'>&nbsp;</td></tr>

				<tr>

				<td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>

				</tr>

				</table>";

				//$headers = "MIME-Version: 1.0" . "\r\n";

				//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				//$headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

				//mail($to,$subject,$message,$headers);
				$mail->Body = $message;
						  if(!$mail->Send()) {
							//echo $mail->ErrorInfo;
						  }
                          else{}
				
				// sms to employer
				
				$phone = $row['phone'];

				if(strpos($phone, "+") !== false)

				$phone = $phone;

				else

				$phone = "+".$phone;



				$smsbody = "Your Job Post for ".$title." role at ".$location." postcode is now live and running. Thank you for choosing Staff Express. Providing you with a seamless platform, great candidates for short term cover and also long term positions at a fraction of the cost of other job hiring sites. Expect candidates applying very soon. Remember to use our free to use Clock in Clock Out Feature to keep track of Staff Hours on their day of work. A handy feature.";


				$data_string = '{

					"messages": [

					{

						"to": "'.$phone.'",

						"source": "sdk",

						"body": "'.$smsbody.'"

					}

					]

				}';



				$ch = curl_init("https://rest.clicksend.com/v3/sms/send");

					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					curl_setopt($ch, CURLOPT_HTTPHEADER, array(

									'Content-Type: application/json',

									'Authorization: Basic '.$auth.''

									)

					);

					$result = curl_exec($ch);

					$result = json_decode($result);

            }

            else{

                echo "<form action='".SITEURL."jobpost6' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'><input type='hidden' name='error' value='2'></form><script>document.getElementById('gotostep2').submit();</script>";

                exit;

            }

        }

        else{

            echo "<form action='".SITEURL."jobpost6' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'><input type='hidden' name='error' value='2'></form><script>document.getElementById('gotostep2').submit();</script>";

            exit;

        }



    }

    else{

        echo "<form action='".SITEURL."jobpost6' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'><input type='hidden' name='error' value='2'></form><script>document.getElementById('gotostep2').submit();</script>";

        exit;

    }

}



		echo "<form action='".SITEURL."jobupload' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'></form><script>document.getElementById('gotostep2').submit();</script>";

	

	exit;



}

include_once('header.php');

?>

<script src="https://js.stripe.com/v2/"></script>

<section class="login_page">

	<div class="container">

		<div class="splnav">

			<ul class="onlyemployernav">

				<li <?php if($page == "success.php" || $page == "viewmyjob.php" || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">My Jobs</a></li>

				<li class="active"><a href="<?php echo SITEURL."jobpost1";?>">Post a Job</a></li>

				<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>

				<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>

				<!--<li><a href="<?php echo SITEURL."logout";?>">Logout</a></li>-->

			</ul>

		</div>

		<div class="stepbystp">

			<ul>

				<li>Job Details</li>

				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>

				<li>Compensation</li>

				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>

				<li>Additional Details</li>

				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>

				<li class="actv">Post Job</li>
				
				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>

				<li>Upload</li>

			</ul>

		</div>

	

		<div class="row">

			<!--<div class="col-lg-4 tabhiddn">

				<div class="job_post_img">

					<img src="<?php echo SITEURL;?>/images/jobpostimag.png" class="img-fluid" alt="">

				</div>

			</div>-->

			<div class="col-sm-8 offset-sm-2">

				<div class="login onlyscrol jobpostnewbgdesn">

					<h4>Payment</h4>

                    <?php

                        if(isset($_POST['error']) && $_POST['error'] == 2){

							echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Payment failed. Please try again.</div>";

						}

						?>

                        <form action='<?php echo SITEURL;?>jobpost3' method='post' class="formback">

                        <input type='hidden' name='jobid' value='<?php echo trim($_POST['jobid']);?>'>

                        </form>

					<form action="" method="post" id="checkout_form" class="eplyfrm" enctype="multipart/form-data">

                    <input type="hidden" value="<?php echo trim($_POST['jobid']);?>" name="jobid">

						

                        <div id="payment_option">

                            <div class="row">

			                    <div class="col-lg-6 classic">

									<div class="clasicfst">

										<h4>Classic</h4>

										<div class="clsdets">

											<span style="text-align:center;font-size: 20px;"><strong>$30</strong></span>

											<ul>

												<li>Find staffs already searching on the site</li>

											</ul>

											<ul class="homebtn">

												<li><a href="javascript:void(0);" id="classic">Select</a></li>

											</ul>

										</div>

									</div>                                    

                                </div>

                                <div class="col-lg-6 premium">

									<div class="clasicfst">

										<h4>Premium</h4>

										<div class="clsdets">

											<span style="text-align:center;font-size: 20px;"><strong>$33</strong></span>

											<ul>

												<li>Great for urgent covers</li>

												<li>Email/SMS notifications sent to matching candidates</li>

												<li>Excellent feedback from employers and highly preferred</li>

											</ul>

											<ul class="homebtn">

												<li><a href="javascript:void(0);" id="premium">Select</a></li>

											</ul>

										</div>

									</div>

                                   

                                </div>

                            </div>

                        </div>



                        <div id="payforjob" style="display:none;">

                        <div class="form-group">

                        <label style="text-transform:none;">Please enter card details below to pay. &nbsp; <span style="font-size:12px;">(We do not store any of your bank card details for safety and security.)</span>
                        </label>

                        <input type="hidden" id="myoption" name="myoption" value="">

						<input type="text" placeholder="Card Number" name="card_number" id="card_number" class="form-control " autocomplete="off" maxlength="19">

						</div>

                        <div class="form-group crddtls">

						<input type="text" placeholder="Expiry Month (MM)" name="card_exp_month" id="card_exp_month" class="form-control  digits" autocomplete="off" maxlength="2">

                        <input type="text" placeholder="Expiry Year (YYYY)" name="card_exp_year" id="card_exp_year" class="form-control  digits" autocomplete="off" maxlength="4">

                        <input type="text" placeholder="CVC Code" name="card_cvc" id="card_cvc" class="form-control  digits" autocomplete="off">

						</div>

                        </div>



			    			<div class="form-group">

								<input type="checkbox" name="check" value="1" required> <span>By ticking this box, you agree to our <a href="<?php echo SITEURL;?>terms" target="_blank">Terms of Service</a></span>

						</div>

						<div class="row">

							<div class="col-sm-3">

								<input type="button" value="Back" id="back" class="onlybutfulwdt">

							</div>

							<div class="col-sm-3">

								<input type="submit" value="Next" class="onlybutfulwdt">

							</div>

						</div>

                        

                        <div class="payment-status" style="color:#f00;font-weight:bold;text-align:center;"></div>

					</form>

					

				</div>

			</div>

		</div>

	</div>

</section>

	

<?php 

//}

include_once('footer.php');?>