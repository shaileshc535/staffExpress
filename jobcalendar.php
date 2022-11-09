<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
$calendar = '';
$mytitles = '';
$mytitles2 = '';
$currmonth = isset($_POST['currmonth'])?$_POST['currmonth']:"";
$curryear = isset($_POST['curryear'])?$_POST['curryear']:"";
$jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
$rpt_event = array();

$myjob = dbQuery($dbConn, "SELECT title,jobdate,jobdate2,starttime,endtime,workmode FROM job_details where id='".$jobid."'");
$fetch = dbFetchArray($myjob);
			
$jobsrtdate = $fetch['jobdate'];
$jobenddate = $fetch['jobdate2'];

if($_POST['action'] == "previous"){
    if($currmonth == 1){
        $prevmonth = 12;
        $year = $curryear - 1;
    }
    else{
        $prevmonth = $currmonth - 1;
        if($prevmonth < 10)
        $prevmonth = '0'.$prevmonth;
        else
        $prevmonth = $prevmonth;
        $year = $curryear;
    }

    $d= 2; // To Finds today's date
    $no_of_days = cal_days_in_month(CAL_GREGORIAN, $prevmonth, $year);//calculate number of days in a month
    $j = date('w',mktime(0,0,0,$prevmonth,1,$year)); // This will calculate the week day of the first day of the month
    
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
    $pv = $year."_".$prevmonth."_".$i;
    
    if($i == date('d') && $prevmonth == date('m') && $year==date('Y')){
        $tdbackcls = "style='background:#e4f8ee;'";
        $h6color = "style='font-weight:bold;'";
        $todaytxt = "";
    }
    else{
        $h6color = "";
        $todaytxt = "";
        $tdbackcls = "";
    }

    $mydate = $year."-".$prevmonth."-".$i;
    
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
		$mytitles2 = '';

    }
    $calendar .= "</tr>";

    print json_encode(array('success' => 1, 'calendar' => $calendar, 'prevmonth' => date("F", mktime(0, 0, 0, $prevmonth, 10)), 'year' => $year, 'month' => $prevmonth));
    
}
if($_POST['action'] == "next"){
    if($currmonth == 12){
        $nextmonth = 1;
        $year = $curryear + 1;
    }
    else{
        $nextmonth = $currmonth + 1;
        $year = $curryear;
    }

    if($nextmonth < 10)
    $nextmonth = '0'.$nextmonth;
    else
    $nextmonth = $nextmonth;


    $d= 2; // To Finds today's date
    $no_of_days = cal_days_in_month(CAL_GREGORIAN, $nextmonth, $year);//calculate number of days in a month
    $j = date('w',mktime(0,0,0,$nextmonth,1,$year)); // This will calculate the week day of the first day of the month
    
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
    $pv = $year."_".$nextmonth."_".$i;
    
    if($i == date('d') && $nextmonth == date('m') && $year==date('Y')){
        $tdbackcls = "style='background:#e4f8ee;'";
        $h6color = "style='font-weight:bold;'";
        $todaytxt = "";
    }
    else{
        $h6color = "";
        $todaytxt = "";
        $tdbackcls = "";
    }

    $mydate = $year."-".$nextmonth."-".$i;
    
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
		$mytitles2 = '';
    }
    $calendar .= "</tr>";

    print json_encode(array('success' => 1, 'calendar' => $calendar, 'nextmonth' => date("F", mktime(0, 0, 0, $nextmonth, 10)), 'year' => $year, 'month' => $nextmonth));
    
}

closeconn($dbConn);