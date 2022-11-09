<?php
include('config/config.php'); 
include_once "config/common.php";
if($_POST['action'] == "getavlity"){
    $dbConn = establishcon();
	$calendar = '';
    $today = date('Y-m-d');
	$i=0;
    $startdate = '';
    $fromtime = '';
    $totime = '';
    $enddate = '';
    $availble_dates = array();
    $availble_dates_cal = array();
    $availble_dates_str = '';
    $availble_dates_str_cal = '';
	$data = array();
	$avails = array();
    $shifttimes = array();
    $shifttimes_str = '';
    $myallshifts = '';
	$noshift = '';
	$longstartdt = '';
	
    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";
    
    $check = dbQuery($dbConn, "SELECT avlblty,notes,apply_type from job_status where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
    if(dbNumRows($check) > 0){
        $rowavl = dbFetchArray($check);
		
		$myjob = dbQuery($dbConn, "SELECT title,jobdate,jobdate2,starttime,endtime,workmode,add_time,covertype,is_shift,shifttype,noshiftsrttime,noshiftendtime,noshifttext,longstartdt FROM job_details where id='".$jobid."'");
			$fetch = dbFetchArray($myjob);
			
			$title = stripslashes($fetch['title']);
			/*if($fetch['jobdate'] != '0000-00-00')
			$jobsrtdate = $fetch['jobdate'];
            else
            $jobsrtdate = date('Y-m-d');

            if($fetch['jobdate2'] != '0000-00-00')
			$jobenddate = $fetch['jobdate2'];
            else
            $jobenddate = "";*/
			$strttime = $fetch['starttime'];
			$endtime = $fetch['endtime'];
        
		if($rowavl['avlblty'] == 1){
			

		}
		if($rowavl['avlblty'] == 2){
            if($rowavl['apply_type'] == 2){
                $getavl = dbQuery($dbConn, "SELECT id,availble_date,starttime,endtime from staff_availability where jobid = '".$jobid."' and staff_id = '".$userid."'");
                while($recavl = dbFetchArray($getavl)){
                    $availble_dates[] = $recavl['availble_date'];
                    $availble_dates_cal[] = '"'.$recavl['availble_date'].'"';
					$avails[$i]['availble_id'] = $recavl['id'];
                    $avails[$i]['availble_date'] = date('M j, Y', strtotime($recavl['availble_date']));
                    $avails[$i]['starttime'] = date('H:i', strtotime($recavl['starttime']));
                    $avails[$i]['endtime'] = date('H:i', strtotime($recavl['endtime']));
                    $i++;
                }
                if(count($availble_dates) > 0){
                    $availble_dates_str = implode(", ", $availble_dates);
                    $availble_dates_str_cal = implode(', ', $availble_dates_cal);
                    $availble_dates_str_cal = "[".$availble_dates_str_cal."]";
                }
            }
            else{
                $avlstart = dbQuery($dbConn, "SELECT availble_date,starttime,endtime from staff_availability where jobid = '".$jobid."' and staff_id = '".$userid."' order by id limit 0,1");
                $recstart = dbFetchArray($avlstart);
                $startdate = $recstart['availble_date'];
                $startdate_cal = '"'.$startdate.'"';
                $fromtime = date("H:i", strtotime($recstart['starttime']));
                $totime = date("H:i", strtotime($recstart['endtime']));

                $avlend = dbQuery($dbConn, "SELECT availble_date from staff_availability where jobid = '".$jobid."' and staff_id = '".$userid."' order by id desc limit 0,1");
                $recend = dbFetchArray($avlend);
                $enddate = $recend['availble_date'];
                $enddate_cal = '"'.$enddate.'"';
                $availble_dates_str_cal = "[".$startdate_cal.', '.$enddate_cal."]";
            }
		}
		
        if($fetch['add_time'] == 1){
            $strttime = date('h:i A', strtotime($strttime));
            $endtime = date('h:i A', strtotime($endtime));
        }
        else{
            $strttime = "";
            $endtime = "";
        }

        if($fetch['covertype']==1){
			if($fetch['jobdate'] != '0000-00-00')
			$jobsrtdate = $fetch['jobdate'];
            else
            $jobsrtdate = date('Y-m-d');

            if($fetch['jobdate2'] != '0000-00-00'){
				$jobenddate = $fetch['jobdate2'];
			}
            else{
				$jobenddate = $fetch['jobdate'];
			}
			
            $myjobsrtdate = date('M j, Y', strtotime($jobsrtdate));
            $myjobenddate = date('M j, Y', strtotime($jobenddate));
        }
        else{
			if($fetch['longstartdt'] != "0000-00-00"){
				$jobsrtdate = $fetch['longstartdt'];
				$myjobsrtdate = date('M j, Y', strtotime($jobsrtdate));
			}
			else{
				$jobsrtdate = date('Y-m-d');
				$myjobsrtdate = "";
			}
			$jobenddate = "";
			$myjobenddate = "";
        }
        if($fetch['is_shift'] == 1){
            $shifttype = explode(",", $fetch['shifttype']);
            $myshifts = array();
            foreach($shifttype as $val){
                if($val == '1')
                    $value = "Day Shift";
                if($val == '2')
                    $value = "Night Shift";
                if($val == '3')
                    $value = "Overnight Shift";
                
                $myshifts[] = $value;
            }
            if(count($myshifts) > 0){
                $myshifts = implode(", ", $myshifts);
            }
            $myallshifts = "(".$myshifts.")";
        }
		else if($fetch['is_shift'] == 2){
			//$noshift = "No shift: ".date('h:i A', strtotime($fetch['noshiftsrttime']))." - ".date('h:i A', strtotime($fetch['noshiftendtime']));
			if($fetch['noshifttext'])
				$noshift = ". ".stripslashes($fetch['noshifttext']);
			else
				$noshift = '';
		}
		
        $staffexp = dbQuery($dbConn, "SELECT starttime,endtime from shift_times where jobid = '".$jobid."' order by id");
        if(dbNumRows($staffexp) > 0){
            while($staffexprow = dbFetchArray($staffexp)){
                $shifttimes[] = date('h:i A', strtotime($staffexprow['starttime']))." - ".date('h:i A', strtotime($staffexprow['endtime']));
            }
            $shifttimes_str = implode(", ", $shifttimes);
        }

		$data = array('success' => 1, 'title' => $title, 'jobsrtdate' => $myjobsrtdate, 'jobenddate' => $myjobenddate, 'start_date' => $jobsrtdate, 'end_date' => $jobenddate, 'strttime' => $strttime, 'endtime' => $endtime, 'avlblty' => $rowavl['avlblty'], 'workmode' => $fetch['workmode'], 'notes' => stripslashes($rowavl['notes']), 'avails' => $avails, 'apply_type' => $rowavl['apply_type'], 'rangestartdate' => $startdate, 'rangeenddate' => $enddate, 'rangestartdatedisp' => date('M j, Y', strtotime($startdate)), 'rangeenddatedisp' => date('M j, Y', strtotime($enddate)), 'fromtime' => $fromtime, 'totime' => $totime, 'availble_dates_str' => $availble_dates_str, 'availble_dates_str_cal' => $availble_dates_str_cal, 'startdate_cal' => $startdate, 'enddate_cal' => $enddate, 'myallshifts' => $myallshifts, 'shifttimes_str' => $shifttimes_str, 'noshift' => $noshift, 'is_shift' => $fetch['is_shift'], 'covertype' => $fetch['covertype']);
		print json_encode($data);
    }
    else{
        print json_encode(array('success' => 3));
    }
    

    closeconn($dbConn);
}