<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
$calendar = '';
$mytitles = '';
$mytitles2 = '';
$currmonth = isset($_POST['currmonth'])?$_POST['currmonth']:"";
$curryear = isset($_POST['curryear'])?$_POST['curryear']:"";
$rpt_event = array();
$rpt_event2 = array();

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
    $pv = $year."-".$prevmonth."-".$i;
    
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
    
        $myevents = array();
        $mytitles = '';
		$mytitles2 = '';
        $mydates = '';
        $avldays = dbQuery($dbConn, "SELECT a.*,b.apply_type,b.hired,b.avlblty,c.title,c.id as jobid,c.jobdate,c.jobdate2 FROM staff_availability a inner join job_status b on a.jobid=b.jobid inner join job_details c on a.jobid=c.id where c.employer_id = '".$_SESSION['loginUserId']."' and availble_date = '".$mydate."'");
		if(dbNumRows($avldays) > 0){
			$fetchdays = dbFetchArray($avldays);
			//if($fetchdays['avlblty'] == 2){
				//if($fetchdays['apply_type'] == 2){
					$avldays2 = dbQuery($dbConn, "SELECT b.hired,c.title,c.id FROM staff_availability a inner join job_status b on a.jobid=b.jobid inner join job_details c on a.jobid=c.id where c.employer_id = '".$_SESSION['loginUserId']."' and availble_date = '".$mydate."' group by a.jobid");
					while($fetchdays2 = dbFetchArray($avldays2)){
						if($fetchdays2['hired'] ==1){
							if(strlen($fetchdays2['title']) > 15)
								$myjobtitle = substr(stripslashes($fetchdays2['title']), 0, 15)."...";
							else
								$myjobtitle = stripslashes($fetchdays2['title']);
						$mytitles .= "<span class='grnback'><a href=".SITEURL."viewjob/".$fetchdays2['id']."?action=contacting>".$myjobtitle."</a></span><br>";
						}
						/*else{
							$mytitles .= "<span class='orgback'>".$fetchdays2['title']."</span><br>";
						}*/
					}
				//}
				
			/*}
			else{
				if($fetchdays['jobdate2']!=""){
				
				$mystartdate = $fetchdays['jobdate'];
				
				$myenddate = $fetchdays['jobdate2'];
				$date1 = new DateTime($mystartdate);
				$date2 = new DateTime($myenddate);
				$days = $date1->diff($date2)->format("%a");
				}
				else{
					$days = 0;
				}
			$days = $days + 1;
			$rpt_event[] = [stripslashes($fetchdays['title']), $mystartdate, $days, $fetchdays['hired'],  $fetchdays['jobid']];
			
			
				foreach ($rpt_event as $event) {
					for ($d = 0; $d <= ($event[2]-1); $d++) {
						if (date('y-m-d', strtotime($year . '-' . $prevmonth . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
							if($event[3] == 1){
								if(strlen($event[0]) > 15)
									$myjobtitle = substr($event[0], 0, 15)."...";
								else
									$myjobtitle = $event[0];
								$mytitles2 = "<span class='grnback'><a href=".SITEURL."viewjob/".$event[4]."?action=contacting>".$myjobtitle."</a></span><br>";
							}
							//else{
								//$mytitles2 = "<span class='orgback'>".$event[0]."</span><br>";
								
							//}
						}
						}
				}
				
				
			}*/
		}

        $calendar .= $adj."<td ".$tdbackcls." id='td_".$pv."'>".$i."<br>".$mytitles.$mytitles2."</td>";
    
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
    $pv = $year."-".$nextmonth."-".$i;
    
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
    
        $myevents = array();
        $mytitles = '';
		$mytitles2 = '';
        $mydates = '';
        
        $avldays = dbQuery($dbConn, "SELECT a.*,b.apply_type,b.hired,b.avlblty,c.title,c.id as jobid,c.jobdate,c.jobdate2 FROM staff_availability a inner join job_status b on a.jobid=b.jobid inner join job_details c on a.jobid=c.id where c.employer_id = '".$_SESSION['loginUserId']."' and availble_date = '".$mydate."'");
		if(dbNumRows($avldays) > 0){
			$fetchdays = dbFetchArray($avldays);
			//if($fetchdays['avlblty'] == 2){
				//if($fetchdays['apply_type'] == 2){
					$avldays2 = dbQuery($dbConn, "SELECT b.hired,c.title,c.id FROM staff_availability a inner join job_status b on a.jobid=b.jobid inner join job_details c on a.jobid=c.id where c.employer_id = '".$_SESSION['loginUserId']."' and availble_date = '".$mydate."' group by a.jobid");
					while($fetchdays2 = dbFetchArray($avldays2)){
						if($fetchdays2['hired'] ==1){
							if(strlen($fetchdays2['title']) > 15)
								$myjobtitle = substr(stripslashes($fetchdays2['title']), 0, 15)."...";
							else
								$myjobtitle = stripslashes($fetchdays2['title']);
						$mytitles .= "<span class='grnback'><a href=".SITEURL."viewjob/".$fetchdays2['id']."?action=contacting>".$myjobtitle."</a></span><br>";
						}
						/*else{
							$mytitles .= "<span class='orgback'>".$fetchdays2['title']."</span><br>";
						}*/
					}
				//}
				
			/*}
			else{
				if($fetchdays['jobdate2']!=""){
				
				$mystartdate = $fetchdays['jobdate'];
				
				$myenddate = $fetchdays['jobdate2'];

				$month_start_date = $year."-".$nextmonth."-01";
					if($mystartdate < $month_start_date)
						$mystartdate = $month_start_date;
					else
						$mystartdate = $mystartdate;

				$date1 = new DateTime($mystartdate);
				$date2 = new DateTime($myenddate);
				$days = $date1->diff($date2)->format("%a");
				}
				else{
					$days = 0;
				}
			$days = $days + 1;
			$rpt_event[] = [stripslashes($fetchdays['title']), $mystartdate, $days, $fetchdays['hired'],  $fetchdays['jobid']];
			
			
				foreach ($rpt_event as $event) {
					for ($d = 0; $d <= ($event[2]-1); $d++) {
						if (date('y-m-d', strtotime($year . '-' . $nextmonth . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
							if($event[3] == 1){
								if(strlen($event[0]) > 15)
									$myjobtitle = substr($event[0], 0, 15)."...";
								else
									$myjobtitle = $event[0];
								$mytitles2 .= "<span class='grnback'><a href=".SITEURL."viewjob/".$event[4]."?action=contacting>".$myjobtitle."</a></span><br>";
							}
							
						}
						}
				}
				
				
			}*/
		}
        
        $calendar .= $adj."<td ".$tdbackcls." id='td_".$pv."'>".$i."<br>".$mytitles.$mytitles2."</td>";
    
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