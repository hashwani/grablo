<?php

	session_start();

	require('libs/_config.php');

	require('libs/_db.php');

	sql_open();

	

	if(empty($_SESSION['user'])) {

		header("location: $HOME/signin.php" );

	}

?>

<!DOCTYPE html>

<html lang="en">

	<head>

		<title>Dashboard - <?php echo $SITE_TITLE; ?></title>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<meta content="<?php echo $SITE_KEYWORDS; ?>" name="keywords" />

		<meta content="<?php echo $SITE_DESCRIPTION; ?>" name="description" />

		<link href="<?php echo $MEDIA_DIR; ?>style/style.css" rel="stylesheet" type="text/css" />

		<link href="<?php echo $MEDIA_DIR; ?>fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" media="screen">

		<script src="<?php echo $MEDIA_DIR; ?>js/IE9.js" type="text/javascript"></script>

		<script src="<?php echo $MEDIA_DIR; ?>js/jquery164.js" type="text/javascript"></script>

		<script src="<?php echo $MEDIA_DIR; ?>fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript" ></script>

		<script src="<?php echo $MEDIA_DIR; ?>js/validate.js" type="text/javascript"></script>

		<style type="text/css">

			#dashboard {

				width: 600px;

			}

				#dashboard td {

					padding: 3px;

				}

		</style>

	</head>

	<body>

		<div id="container">

			<?php

				// site header

				require("includes/header.php");

			?>

			<section id="contentContainer" class="clearfix">

				<section class="glass" style="width: 960px; padding: 0px; text-align: left; display: inline-block;">

					<article class="clearfix" style="background-color: #ffffff;  padding: 40px;">   

						<div style="width: 590px; float: left; margin-left: 10px;">

							<h2 style="font-size: 20px; font-weight: bold; margin-bottom: 15px; text-align: left;">Dashboard</h2>

							<p style="color: #333333; font-family: Arial; font-size: 14px; margin: 10px 0;">

								Dashboard contains all of the details of a user and it's grabz.

							</p>

							<div style="margin-top; 24px;">

								<?php

									$user = $_SESSION["user"];

									$user = sql_select(

										array(

											"table" => "users",

											"cols" => "*",

											"where" => "id=" . $user["id"]

										)

									);

									extract($user[0]);

								?>

								<table id="dashboard" style="width: 500px;">

									<tr>
										<td style="width: 100px;">Name:</td>
										<td><b><?php echo $full_name;?></b></td>
									</tr>

									<tr>

										<td>Email:</td>

										<td><b><?php echo $email_address;?></b></td>

									</tr>
									<tr>

										<td>Password:</td>

										<td>
											<b>***********</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="redeem_button_1" class="button_1 blue" style="padding: 5px 10px;">Change Password</a>
											<div class="clearfix" id="password_form" style="display: none;">
												<form>
													<style>  
														#table_change_password td{
															padding:0px;
														}
													</style>
													<table id="table_change_password" cellpadding="0px" cellspacing="0px" width="300px" style="border:1px solid #c0c0c0;">	
													
														<tr>
															<td style="padding:10px;">
																<label for="old_password" style="display: block; float: left; font-weight: bold; padding: 9px 5px;">Old Passoword:</label> 
															</td>	
															<td style="padding:10px;>			
																<input type="password" id="old_password" name="old_password" style="height: 22px; width: 150px;" />
															</td>
														</tr>
														<tr>
															<td style="padding:10px;>
																<label for="new_password" style="display: block; float: left; font-weight: bold; padding: 9px 5px;">New Password:</label> 
															</td>	
															<td style="padding:10px;>			
																<input type="password" id="new_password" name="new_password" style="height: 22px; width: 150px;" />
															</td>
														</tr>
														<tr>
															<td style="padding:10px;>
																<label for="retype_password" style="display: block; float: left; font-weight: bold; padding: 9px 5px;">Retype Password:</label> 
															</td>	
															<td style="padding:10px;>			
																<input type="password" id="retype_password" name="retype_password" style="height: 22px; width: 150px;" />
															</td>
														</tr>
														<tr>
															<td colspan="2" align="right">		
																<a href="#" id="submit_password" class="button_1 blue" style="padding: 5px 10px;">Submit</a>
															</td>
														</tr>	
													</table>
												</form>	
											</div>
										</td>

									</tr>

									<tr>

										<td>Address 1:</td>

										<td><b><?php echo $address_1 . ", " . $city . ", " . $state . ", " . $country . ", " . $zip_code;?></b></td>

									</tr>

									<tr>

										<td>Address 2:</td>

										<td><b><?php echo $address_2;?></b></td>

									</tr>

									<tr>

										<td>Telephone:</td>

										<td><b><?php echo $telephone;?></b></td>

									</tr>

									<tr>

										<td>Total Grabz:</td>

										<td><b><?php echo ($grabz + $grabz_from_adz + $grabz_from_referrals + $grabz_from_signup + $grabz_from_coupons);?></b></td>

									</tr>

								</table>

								<div style="border-top: 1px inset #f9f9f9; margin-top: 15px; padding-top: 10px; width: 450px; height: 40px; font-size: 16px; font-weight: bold;">Do you have a coupon code? <a href="#" id="redeem_button" class="button blue" style="padding: 10px 15px;">Redeem Coupon</a></div>

								<div class="clearfix" id="coupon_form" style="display: none;">

									<form>

										<label for="coupon_code" style="display: block; float: left; font-weight: bold; padding: 9px 5px;">Coupon Code:</label> 

										<input type="text" id="coupon_code" name="coupon_code" style="height: 22px; width: 150px;" />

										<br/><a href="#" id="submit_coupon" class="button blue" style="padding: 10px 15px;">Submit</a>

									</form>

								</div>

								<script type="text/javascript">

									(function($) {

										$(

											function() {

												$("#redeem_button").click(function() {

													$(this).fadeOut();

													$("#coupon_form").slideDown();

													$("#submit_coupon").click(function() {

														$.post(

															"request.php",

															{

																"coupon_code" : $("#coupon_code").val(),

																"request_id" : 7

															},

															function(data) {

																alert(data);

															}

														);

													});

													return false;

												});

												

											}

										);

									})(jQuery);
									
									(function($) {
										$(
											function() {
												$("#redeem_button_1").click(function() {
													$(this).fadeOut();
													$("#password_form").slideDown();
													$("#submit_password").click(function() {
														var new_password = $("#new_password").val();
														var retype_password = $("#retype_password").val();
														alert(new_password + "-" + retype_password);
														if(new_password == retype_password){
															$.post(
																"request.php",
																{
																	"old_password" : $("#old_password").val(),
																	"new_password" : new_password,
																	"request_id" : 8
																},
																function(data) {
																	alert(data);
																}
															);
														}
														else{
															alert('New Password And Retype Password Must Be Same!...');
														}
													});
														
													return false;
												});
											}
												
										);
									})(jQuery);
								</script>
							</div>

						</div>

						<div class="clear"></div>

					</article>

				</section>

			</section>

			<?php

				// site footer

				require("includes/footer.php");

			?>

		</div>

	</body>

</html>