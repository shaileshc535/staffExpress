<tr>
										<td width="40%"><strong>Job Details:</strong></td>
										<td width="60%"><?php echo stripslashes($fetch['description']);?></td>
									</tr>
									<tr class="table-light">
										<td width="40%"><strong>Employer:</strong></td>
										<td width="60%"><?php echo stripslashes($fetch['name']);?></td>
									</tr>
									<tr class="">
										<td><strong>Address:</strong></td>
										<td><?php echo stripslashes($fetch['street_address']);?></td>
									</tr>
									<tr class="table-light">
										<td><strong>Postcode:</strong></td>
										<td><?php echo stripslashes($fetch['location']);?>, <?php echo stripslashes($fetch['cname']);?></td>
									</tr>
									<tr class="">
										<td><strong>Job Date:</strong></td>
										<td><?php echo date('M j, Y', strtotime($fetch['jobdate']));?> at <?php echo date('h:i A', strtotime($fetch['starttime']));?> - <?php echo date('M j, Y', strtotime($fetch['jobdate2']));?> at <?php echo date('h:i A', strtotime($fetch['endtime']));?></td>
									</tr>
									<tr class="table-light">
										<td><strong>Category:</strong></td>
										<td><?php echo stripslashes($fetch['category']);?></td>
									</tr>
									<?php
									if($myquals){
									?>
									<tr class="">
										<td><strong>Qualification Required:</strong></td>
										<td><?php echo $myquals;?></td>
									</tr>
									<?php
									}
									?>
									<?php
									if($fetch['worktype'] == 1)
									$wtype = "Casual";
									else if($fetch['worktype'] == 2)
									$wtype = "Contract";
									else if($fetch['worktype'] == 3)
									$wtype = "Part-time";
									else if($fetch['worktype'] == 4)
									$wtype = "Full-time";

									if($fetch['paytype'] == 1)
									$paytype = "Annual Salary";
									else if($fetch['paytype'] == 2)
									$paytype = "Hourly Rate";
									else if($fetch['paytype'] == 3)
									$paytype = "Annual and Commission";
									?>
									<tr>
										<td><strong>Job Type:</strong></td>
										<td><?php echo $wtype;?></td>
									</tr>
									<tr class="table-light">
										<td><strong><?php echo $paytype;?>:</strong></td>
										<td>Range: <?php echo $fetch['payperhr'];?> AUD - <?php echo $fetch['payperhr_max'];?> AUD</td>
									</tr>
									<?php
									if($addcomp){
									?>
									<tr>
										<td><strong>Additional Compensation:</strong></td>
										<td><?php echo $addcomp;?></td>
									</tr>
									<?php
									}
										if($fetch['covid19']){
										?>
									<tr class="table-light">
										<td><strong>Any COVID-19 Precaution:</strong></td>
										<td><?php echo stripslashes($fetch['covid19']);?></td>
									</tr>
									<?php
										}
										?>
										<?php
                                    if($fetch['exp_type'] == 1)
                                    $exptype = "Compulsory";
                                    else if($fetch['exp_type'] == 2)
                                    $exptype = "Preferred";
                                    ?>
									<tr class="table-light">
										<td><strong>Experience Required:</strong></td>
										<td><?php echo $fetch['experience'];?> years (<?php echo $exptype;?>)</td>
									</tr>
									<tr>
										<td><strong>Status:</strong></td>
										<td><?php 
											if($fetch['isclosed'] == 0){
												if($jobendttime > strtotime($currtime))
												echo "Open";
												else
												echo "Closed";
											}
											else
											echo "Closed";
											?></td>
									</tr>