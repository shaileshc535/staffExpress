	<footer class="footersec">
		<div class="container">
			<div class="topfooterbox">
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-3">
						<div class="footerlogo">
							<a href="<?php echo SITEURL; ?>"><img src="<?php echo SITEURL; ?>images/logo.png" alt=""></a>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6">
						<div class="footerlist">
							<h2>Overview</h2>
							<ul>
								<li><a href="<?php echo SITEURL; ?>">Home</a></li>
								<!--<li><a href="#">Categories</a></li>-->
								<li><a href="<?php echo SITEURL; ?>searchcover">Browse Covers</a></li>
								<li><a href="<?php echo SITEURL; ?>how_it_works">How it works</a></li>
								<?php
								if ($postcoverlink != "") {
								?>
									<li><a href="<?php echo $postcoverlink; ?>">Post for Cover</a></li>
								<?php
								}
								?>
							</ul>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6">
						<div class="footerlist">
							<h2>Company</h2>
							<ul>
								<li><a href="<?php echo SITEURL; ?>about">About Staff</a></li>
								<li><a href="<?php echo SITEURL; ?>contact">Contact</a></li>
							</ul>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6">
						<div class="footerlist">
							<h2>Join</h2>
							<ul>
								<?php
								if (isset($_SESSION['loginUserId']) && $_SESSION['loginUserId'] != "") {
									if ($usertype == 2) {
										$joinlink = "staff_profile";
									}
									if ($usertype == 1) {
										$joinlink = "employer_details";
									}
									$text = "My Account";
								} else {
									$joinlink = "signup";
									$text = "Register";
								}
								?>
								<li><a href="<?php echo SITEURL; ?><?php echo $joinlink; ?>"><?php echo $text; ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="midfooter">
				<div class="row">
					<div class="col-sm-4 col-md-3 col-lg-4">
						<p><?php echo date('Y'); ?> <a href="<?php echo SITEURL; ?>">Staff Express.</a></p>
					</div>
					<div class="col-sm-7 col-md-6 col-lg-7">
						<ul class="policylist">
							<li><a href="<?php echo SITEURL; ?>terms">Terms and Conditions</a></li>
							<li><a href="<?php echo SITEURL; ?>privacy">Privacy Policy</a></li>
						</ul>
					</div>
					<div class="col-sm-1 col-md-3 col-lg-1">
						<ul class="footsocilist">
							<li class="nav-item">
								<a href="https://www.facebook.com/staffexpressteam" class="soslicn"><img src="<?php echo SITEURL; ?>images/facebook.png" alt="facebook" width="20px"></a>
							</li>
							<li class="nav-item splforicon">
								<a class="" href="https://www.instagram.com/staffexpressteam" class="soslicn"><img src="<?php echo SITEURL; ?>images/instagram.png" alt="insta" width="20px"></a>
							</li>
						</ul>
					</div>
				</div>
			</div>

		</div>
	</footer>

	<?php
	if (!isset($_SESSION['loginUserId'])) {
	?>
		<div class="regflotingbutn"><a href="<?php echo SITEURL; ?>signup" class="regfrmbtn"><i class="fa fa-sign-in" aria-hidden="true"></i> &nbsp;Register Now</a></div>
	<?php
	}
	?>


	<?php closeconn($dbConn); ?>
	<!-- Scrolltop icon start -->
	<div class='scrolltop'>
		<div class='scroll icon'><img src="<?php echo SITEURL; ?>images/scrolltop.png" alt="scrolltop" /></div>
	</div>
	<!-- Scrolltop icon end -->
	<!-- JS files -->
	<script src="<?php echo SITEURL; ?>js/jquery-3.2.1.min.js"></script>
	<script src="<?php echo SITEURL; ?>js/jquery-ui.js"></script>
	<script src="<?php echo SITEURL; ?>js/jquery.validate.js"></script>
	<script src="<?php echo SITEURL; ?>js/popper.min.js"></script>
	<script src="<?php echo SITEURL; ?>js/bootstrap.min.js"></script>
	<script src="<?php echo SITEURL; ?>js/flatpickr.js"></script>
	<script src="<?php echo SITEURL; ?>js/owl.carousel.js"></script>
	<link rel="stylesheet" href="<?php echo SITEURL; ?>css/jquery-ui.css">
	<!--<link rel="stylesheet" href="<?php echo SITEURL; ?>css/mobiscroll.jquery.min.css">
	<script src="<?php echo SITEURL; ?>js/mobiscroll.jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/dubrox/Multiple-Dates-Picker-for-jQuery-UI@master/jquery-ui.multidatespicker.js"></script>-->

	<script src="<?php echo SITEURL; ?>js/custom.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
	<script>
		$(document).ready(function() {
			/*$('#demo-multi-day').datepicker({
				multidate: true,
				startDate: new Date(),
				format: 'dd-mm-yyyy'
			});*/

			//$('#demo-multi-day').multiDatesPicker({startDate: new Date()});

			$('input.timepicker').timepicker({
				timeFormat: 'HH:mm',
				dynamic: false,
				dropdown: true,
				scrollbar: true,
				interval: 30,
				maxTime: '23:00',
				startTime: '06:00'
			});
		});
		<?php
		if ($currpage == "job_post3.php" || $currpage == "editjob3.php") {
		?>

			flatpickr("#applicn_deadln_date", {
				minDate: "today",
			});
		<?php
		}

		if ($currpage == "search_cover.php") {
		?>
			var endDate = flatpickr("#to", {});

			flatpickr("#from", {
				onChange: function(dateStr, dateObj) {
					endDate.set("minDate", dateObj);
				}
			});
		<?php
		}
		if ($currpage == "job_post1.php" || $currpage == "index.php" || $currpage == "viewthejob.php" || $currpage == "job_details.php") {
		?>
			flatpickr("#from", {
				mode: "range",
				minDate: "today",
				dateFormat: "Y-m-d"
			});

			flatpickr("#longstartdt", {
				minDate: "today",
				dateFormat: "Y-m-d"
			});
			/*var endDate = flatpickr("#to",
		  {});

		  flatpickr("#from",
		  {
			minDate: "today",
			onChange: function (dateStr, dateObj) {
				endDate.set("minDate", dateObj);
			}
		  });*/
		<?php
		}
		if ($currpage == "editjob1.php") {
		?>
			flatpickr("#from", {
				mode: "range",
				dateFormat: "Y-m-d"
			});

			flatpickr("#longstartdt", {
				minDate: "today",
				dateFormat: "Y-m-d"
			});

		<?php
		}
		if ($currpage == "i_am_staff.php" || $currpage == "sign-up.php") {
		?>
			flatpickr("#dob", {
				maxDate: "today",
				dateFormat: "Y-m-d"
			});

		<?php
		}
		if ($currpage == "managehire.php") {
		?>
			flatpickr("#interview_cal", {
				minDate: "today",
				dateFormat: "Y-m-d"
			});

			<?php
			if (isset($_REQUEST['showmsg']) && trim($_REQUEST['showmsg']) != "") {
				if (strpos($_REQUEST['showmsg'], "_") !== false) {
			?>
					$(document).ready(function() {
						$('ul.messleftshort #view_<?php echo $_REQUEST['showmsg']; ?>').trigger('click');
					});
			<?php
				}
			}
		}
		if ($currpage == "job_post6.php" || $currpage == "editjob5.php") {
			$STRIPE_PUBLISHABLE_KEY = STRIPE_PUBLISHABLE_KEY;
			?>
			Stripe.setPublishableKey('<?php echo $STRIPE_PUBLISHABLE_KEY; ?>');

			// Callback to handle the response from stripe
			function stripeResponseHandler(status, response) {
				if (response.error) {
					// Enable the submit button
					$('#payBtn').removeAttr("disabled");
					// Display the errors on the form
					$(".payment-status").html('<p>' + response.error.message + '</p>');
				} else {
					var form$ = $("#checkout_form");
					// Get token id
					var token = response.id;
					// Insert the token into the form
					form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
					// Submit form to the server
					form$.get(0).submit();
				}
			}
			$(document).on('keypress', '#card_number', function() {
				$(this).val(function(index, value) {
					return value.replace(/\W/gi, '').replace(/(.{4})/g, '$1 ');
				});
			});

			// On form submit
			$(document).on('submit', '#checkout_form', function() {
				if ($(this).valid() == true) {
					// Disable the submit button to prevent repeated clicks
					var card_number = $('#card_number').val();
					var card_exp_month = $('#card_exp_month').val();
					var card_exp_year = $('#card_exp_year').val();
					var card_cvc = $('#card_cvc').val();
					if (card_number != "" && card_exp_month != "" && card_exp_year != "" && card_cvc != "") {
						/*if(isNaN(card_number)){
							alert('Invalid Card Number.');
							return false;
						}*/
						if (isNaN(card_exp_month) || card_exp_month.length != 2) {
							alert('Invalid month.');
							return false;
						} else if (isNaN(card_exp_year) || card_exp_year.length != 4) {
							alert('Invalid year.');
							return false;
						} else if (isNaN(card_cvc)) {
							alert('Invalid cvc.');
							return false;
						} else {
							$('#payBtn').attr("disabled", "disabled");
							Stripe.createToken({
								number: $('#card_number').val(),
								exp_month: $('#card_exp_month').val(),
								exp_year: $('#card_exp_year').val(),
								cvc: $('#card_cvc').val()
							}, stripeResponseHandler);
							return false;
						}
					} else
						return true;
				}
				// Submit from callback
				//return false;
			});
		<?php
		}
		?>
	</script>


	<script>
		jQuery("#carousel").owlCarousel({
			autoplay: true,
			loop: true,
			margin: 20,
			responsiveClass: true,
			autoHeight: true,
			autoplayTimeout: 3000,
			nav: false,
			responsive: {
				0: {
					items: 1
				},

				600: {
					items: 3
				},

				1024: {
					items: 4
				},

				1366: {
					items: 4
				}
			}
		});
	</script>
	<script>
		jQuery(".carousal2").owlCarousel({
			autoplay: true,
			loop: true,
			margin: 20,
			responsiveClass: true,
			autoHeight: true,
			autoplayTimeout: 3000,
			nav: false,
			<?php
			if ($total_cover > 9) {
			?>
				rtl: true,
			<?php
			}
			?>
			responsive: {
				0: {
					items: 1
				},

				600: {
					items: 3
				},

				1024: {
					items: 4
				},

				1366: {
					items: 4
				}
			}
		});
	</script>

	<!-- <script>
		jQuery(".carousal2").owlCarousel({
			autoplay: true,
			loop: true,
			margin: 20,
			responsiveClass: true,
			autoHeight: true,
			autoplayTimeout: 3000,
			nav: false,
			responsive = {
				0: {
					items: 1
				},

				600: {
					items: 3
				},

				1024: {
					items: 4
				},

				1366: {
					items: 4
				}
			}

			<?php
			if ($total_cover > 9) {
			?>
				// rtl: true
			<?php
			}
			?>


		});
	</script> -->


	<?php
	if ($currpage == "contactus.php") {
	?>
		<script src="//code.tidio.co/c80iqlupkkcfmk1oivqvvxtxl55wfuie.js"></script>
	<?php
	}
	?>

	</body>

	</html>