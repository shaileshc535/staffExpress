<?php
include('config/config.php'); 
include_once "config/common.php";
if($_POST['action'] == "getmyjob"){
    $dbConn = establishcon();
	$calendar = '';
    $today = date('Y-m-d');
	$i=0;
	$data = array();
	$avails = array();
    $shifttimes = array();
    $shifttimes_str = '';
    $myallshifts = '';
	$noshift = '';
	$longstartdt = '';
	
    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    
    if(is_numeric($jobid)){
		
		$myjob = dbQuery($dbConn, "SELECT title,add_time,jobdate,jobdate2,starttime,endtime,workmode,covertype,is_shift,shifttype,noshiftsrttime,noshiftendtime,noshifttext,longstartdt FROM job_details where id='".$jobid."'");
			$fetch = dbFetchArray($myjob);
			
			$title = stripslashes($fetch['title']);
            
			$strttime = $fetch['starttime'];
			$endtime = $fetch['endtime'];
        
		// calendar
		/*$currmonth = date('m');
		$curryear = date('Y');
		$month = ($_POST['month']!="")?$_POST['month']:$currmonth;
		$year = ($_POST['year']!="")?$_POST['year']:$curryear;

    $d= 2; // To Finds today's date
    $no_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);//calculate number of days in a month
    $j = date('w',mktime(0,0,0,$month,1,$year)); // This will calculate the week day of the first day of the month
    
    $adj = str_repeat("<td>&nbsp;</td>",$j);  // Blank starting cells of the calendar 
    $blank_at_end = 42-$j-$no_of_days; // Days left after the last day of the month
    if($blank_at_end >= 7){$blank_at_end = $blank_at_end - 7 ;}

    $adj2 = str_repeat("<td>&nbsp;</td>",$blank_at_end); // Blank ending cells of the calendar

    $today = date('Y-m-d');
    $tdbackcls = "";
    $calendar .= "<tr>";
    for($i=1; $i<=$no_of_days; $i++){
        
    if($i < 10)
    $i = '0'.$i;
    else
    $i = $i;
    $pv = $year."_".$month."_".$i;
    
    if($i == date('d') && $month == date('m') && $year==date('Y')){
        $tdbackcls = "style='background:#e4f8ee;'";
        $h6color = "style='font-weight:bold;'";
        $todaytxt = "";
    }
    else{
        $h6color = "";
        $todaytxt = "";
        $tdbackcls = "";
    }

    $mydate = $year."-".$month."-".$i;
    
        $mytitles = '';
		
		if($mydate >= $jobsrtdate && $mydate <= $jobenddate){
			if($today <= $mydate){
				$mytitles = "<a href='javascript:void(0);' class='jobavldates' id='job_".$pv."'>".$i."</a>";
			}
			else{
				$mytitles = $i;	
			}
		}
		else{
			$mytitles = $i;
		}
        
        $calendar .= $adj."<td ".$tdbackcls." id='td_".$pv."'>".$mytitles."</td>";
    
        $adj='';
        $j++;
		if($j==7){
				$calendar .= "</tr><tr>"; // start a new row
				$j=0;
			}
        
        $mytitles = '';

    }
    $calendar .= "</tr>";*/
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
			if($fetch['noshifttext'])
				$noshift = ". ".stripslashes($fetch['noshifttext']);
			else
				$noshift = '';
			//$noshift = "No shift: ".date('h:i A', strtotime($fetch['noshiftsrttime']))." - ".date('h:i A', strtotime($fetch['noshiftendtime']));
		}
		
        $staffexp = dbQuery($dbConn, "SELECT starttime,endtime from shift_times where jobid = '".$jobid."' order by id");
        if(dbNumRows($staffexp) > 0){
            while($staffexprow = dbFetchArray($staffexp)){
                $shifttimes[] = date('h:i A', strtotime($staffexprow['starttime']))." - ".date('h:i A', strtotime($staffexprow['endtime']));
            }
            $shifttimes_str = implode(", ", $shifttimes);
        }
    
		$data = array('success' => 1, 'workmode' => $fetch['workmode'], 'title' => $title, 'jobsrtdate' => $myjobsrtdate, 'jobenddate' => $myjobenddate, 'start_date' => $jobsrtdate, 'end_date' => $jobenddate, 'strttime' => $strttime, 'endtime' => $endtime, 'myallshifts' => $myallshifts, 'shifttimes_str' => $shifttimes_str, 'noshift' => $noshift, 'is_shift' => $fetch['is_shift'], 'covertype' => $fetch['covertype']);
		print json_encode($data);
    }
    else{
        print json_encode(array('success' => 3));
    }
    

    closeconn($dbConn);
}