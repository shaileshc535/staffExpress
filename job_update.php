<?php
$id = isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
$myjob = dbQuery($dbConn, "SELECT * FROM job_details where id='".$id."'");
$fetch = dbFetchArray($myjob);
?>
<form action="" method="post" id="jobpost" class="eplyfrm">
						<?php
						if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1){
							echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Job duration must be minimum 1 hour.</div>";
						}
						?>
						
						<div class="form-group">
							<input type="text" placeholder="Job Title" name="title" class="form-control required" value="<?php echo stripslashes($fetch['title']);?>">
						</div>
						<div class="form-group">
							<input type="text" placeholder="Street Address" name="street_address" class="form-control required" value="<?php echo stripslashes($fetch['street_address']);?>">
						</div>
						<div class="form-group">
							<input type="text" placeholder="Location (Postcode)" name="location" class="form-control required digits" value="<?php echo stripslashes($fetch['location']);?>">
						</div>
						<div class="form-group">
							<select class="form-select required" name="catid" id="category">
								<option selected value="">Job Category</option>
								<?php
								$cat = dbQuery($dbConn, "select * from category order by category");
								while($catrow = dbFetchArray($cat)){
								?>
								<option value="<?php echo $catrow['id'];?>" <?php if($catrow['id']==$fetch['catid']) echo "selected";?>><?php echo stripslashes($catrow['category']);?></option>
								<?php
								}
								?>
							  </select>
						</div>
						<div class="form-group" id="qual_req" style="display:none;">
						<label>Qualifications Required</label>
								<div class="check_box" style="width:100%;">
								</div>
						</div>
						
						<div class="form-group">
									<label>Planned Start date</label><br>
									<input type="date" placeholder="Date (Planned Start date)" name="busdate" class="form-control required">
						</div>
						<div class="form-group">
							<div class="fullfrmtime">
								<div class="timlt">
									<label>Start time</label><br>
									<input type="time" placeholder="Start Time" name="starttime" class="form-control required">
								</div>
								<div class="timrt">
									<label>End time</label><br>
									<input type="time" placeholder="End Time" name="endtime" class="form-control required">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Work Type</label><br>
							<?php
							$i=0;
								$wtype = dbQuery($dbConn, "select * from worktype");
								while($row = dbFetchArray($wtype)){
								?>
								<input type="radio" name="worktype" <?php if($i==0) echo 'required';?> value="<?php echo $row['id'];?>"> <span><?php echo $row['wtype'];?></span><br>
								<?php
								$i++;
								}
								?>
						</div>
						<!--<div class="form-group">
							<input type="date" placeholder="How many hours per week" name="hrsperweek" class="form-control required">
						</div>-->
						<div class="form-group">
							<input type="text" placeholder="How many people do you want to hire for this opening?" name="howmnypeople" class="form-control required digits">
						</div>
						<h5>Compensation</h5>
						<div class="form-group">
							<input type="text" placeholder="Pay/Hour (AUD)" name="payperhr" class="form-control required digits">
						</div>
						<div class="form-group">
						<label>Do you offer any of the following supplementary pay?</label><br>
						<?php
						$wtype = dbQuery($dbConn, "select * from addtl_compnstion");
								while($row = dbFetchArray($wtype)){
								?>
								<input type="checkbox" name="addtl_compnstion[]" value="<?php echo $row['id'];?>"> <span><?php echo $row['compensation'];?></span><br>
								<?php
								}
								?>
						</div>
						<div class="form-group">
						<label>Are any of the following benefits offered?</label><br>
						<?php
						$wtype = dbQuery($dbConn, "select * from benefits");
								while($row = dbFetchArray($wtype)){
								?>
								<input type="checkbox" name="benefits[]" value="<?php echo $row['id'];?>"> <span><?php echo $row['benefit'];?></span><br>
								<?php
								}
								?>
						</div>
						
						<div class="form-group">
							<!--<select class="form-select required" name="exp_type" style="margin-bottom:10px;">
								<option value="1">Must</option>
								<option value="2">Preferred</option>
							</select>-->
							<select class="form-select required" name="experience">
								<option selected value="">Experience required (Years)</option>
								<?php
								for($i=0; $i<=10; $i++){
								?>
								<option value="<?php echo $i;?>"><?php echo $i;?></option>
								<?php
								}
								?>
							  </select>
							  
							  <input type="radio" name="exp_type" value="1"> <span>Compulsory <i class="fa fa-question-circle" aria-hidden="true" title="All candidates who do not have required experience will NOT be notified"></i></span>&nbsp;&nbsp;
							  <input type="radio" name="exp_type" value="2" checked> <span>Preferred <i class="fa fa-question-circle" aria-hidden="true" title="All candidates with or without experience will be notified but will be informed that 'experience is preferred'"></i></span>
						</div>

						<h5>Describe the job</h5>
						<div class="form-group">
							<textarea name="description" class="form-control required"></textarea>
						</div>
						<div class="form-group">
							<textarea name="covid19" class="form-control" placeholder="Are you taking any additional COVID-19 precautions?"></textarea>
						</div>
						<div class="form-group">
							<label>How would you like to receive applications?</label><br>
							
								<input type="radio" name="receive_app" value="1" required> <span>Email</span><br>
								<input type="radio" name="receive_app" value="2"> <span>Walk-in</span>
						</div>

						<div class="form-group">
							<label>Would you like the people to submit a resume?</label><br>
								<input type="radio" name="submit_resume" value="1" required> <span>Yes</span><br>
								<input type="radio" name="submit_resume" value="2"> <span>No</span><br>
								<input type="radio" name="submit_resume" value="3"> <span>Optional</span>
						</div>
						<div class="form-group">
							<label>Is there an application deadline?</label><br>
								<input type="radio" name="applicn_deadln" value="1" required> <span>Yes</span><br>
								<input type="radio" name="applicn_deadln" value="2" checked> <span>No</span><br>
						</div>
						<div class="form-group" id="deadlndt" style="display:none;">
							<label>Deadline date</label><br>
								<input type="date" name="applicn_deadln_date" class="form-control">
						</div>
						<h5>Message settings</h5>
						<div class="form-group">
							<label>Do you want to let people who apply to your job start the conversation?</label><br>
								<input type="radio" name="msg_enabled" value="1" required> <span>Yes</span><br>
								<input type="radio" name="msg_enabled" value="2"> <span>No</span><br>
						</div>
						
						
						<input type="submit" value="Post Job">
					</form>