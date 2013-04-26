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
		<title>Buy Credits - <?php echo $SITE_TITLE; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta content="<?php echo $SITE_KEYWORDS; ?>" name="keywords" />
		<meta content="<?php echo $SITE_DESCRIPTION; ?>" name="description" />
		<link href="<?php echo $MEDIA_DIR; ?>style/style.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $MEDIA_DIR; ?>fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" media="screen">
		<script src="<?php echo $MEDIA_DIR; ?>js/IE9.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/jquery164.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript" ></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/validate.js" type="text/javascript"></script>
	</head>
	<body>
		<div id="container">
			<?php
				// site header
				require("includes/header.php");
			?>
			<section id="contentContainer" class="clearfix">
				<section class="glass" style="width: 960px; padding: 0px; text-align: left; display: inline-block;">
					<article class="clearfix" style="background-color: #ffffff; margin: 5px; height: 385px; padding: 40px;">
						<div style="width: 130px; float: left">
							<img alt="payPal" src="<?php echo $MEDIA_DIR; ?>images/paypal_big_logo.gif">
						</div>     
						<div style="width: 590px; float: left; margin-left: 10px;">
							<h2 style="font-size: 20px; font-weight: bold; margin-bottom: 15px; text-align: center;">Buy Credits via PayPal</h2>
							<p style="color: #333333; font-family: Arial; font-size: 14px; margin: 10px 0;">
								Use PayPal to add Grabz credits to your <?php echo $SITE_TITLE; ?> account. PayPal is a safe and easy way to pay online.
							</p>
							<div style="margin-top; 24px;">
								<div style="margin-bottom: 10px; padding: 0;">
									<div style="margin: 0; padding: 0;">
										<div style="color: #333333; font-family: arial; font-size: 14px; margin: 10px 0;">
											Add
											<?php
												$db = json_decode(file_get_contents("admin/settings.data"), true);
												$grabz_packats = explode(',', $db["grabz_packats"]);
											?>
											<select name="credits" id="credits" style="width: 75px; border: 1px solid #a8afb5; color: #333333; font-family: arial; font-size: 14px; height: 28px; margin-bottom: 3px; vertical-align: middle; padding: 3px;">
												<?php
													foreach($grabz_packats as $val) {
												?>
														<option value="<?php echo $val; ?>"><?php echo $val; ?></option>
												<?php
													}
												?>
											</select>
											<b>OR</b> enter number of Grabz to add: 
											<input autocomplete="off" name="manual_credits" maxlength="9" id="manual_credits" type="text" style="width: 100px; border: 1px solid #a8afb5; color: #333333; font-family: arial; font-size: 14px; height: 22px; margin-bottom: 3px; vertical-align: middle;" />
											<span class="grabz_less_error" style="display: none; color: #ff0000;">Grabz must be greater then <?php echo $db["min_grabz"]; ?></span>
											<br />
											<p style="font-size: 16px; line-height: 25px; font-weight: bold;">Total Cost: $<b id="total_cost">0</b></p>
											<script type="text/javascript">
												(function($) {
													$(document).ready(
														function() {
															var cost_per_grab = <?php echo $db["cost_per_grab"]; ?>;
															var min_grabz = <?php echo $db["min_grabz"]; ?>;
															var total_cost = $("#total_cost");
															total_cost.text(cost_per_grab * parseInt($("#credits").val()));
															$("#credits").change(
																function() {
																	total_cost.text((cost_per_grab * parseInt($(this).val())).toFixed(2));
																}
															);
															$("#manual_credits").keyup(
																function() {
																	if(parseFloat($(this).val()) < min_grabz) {
																		$(".grabz_less_error").show();
																	} else {
																		$(".grabz_less_error").hide();
																	}
																	total_cost.text((cost_per_grab * parseInt($(this).val())).toFixed(2));
																}
															);
															$("#continue_button").click(
																function() {
																	if(parseFloat($(this).val()) < min_grabz) {
																		$(".grabz_less_error").show();
																	} else {
																		$(".grabz_less_error").hide();
																		alert($("#credits").val());
																		window.location = "<?php echo $HOME; ?>request.php?request_id=6&grabz=" + ((total_cost.val() == "") ? $("#credits").val() : total_cost.val());
																	}
																	
																}
															);
														}
													);
												})(jQuery);
											</script>
										</div>
									</div>
								</div>
								<a id="continue_button" class="button blue" href="#" style="padding: 10px 30px;">Continue</a>
								<span class="paragraph"> or <a href="<?php echo $HOME; ?>" class="toggle" rel="addFunds" rev="showFunds">Cancel</a></span>
								<p style="color: #333333; font-family: Arial; font-size: 14px; margin: 20px 0;">
									You will be redirected to the PayPal website to complete the funds transfer.<br />
									When the transfer is complete, you will be returned to <?php echo $SITE_TITLE; ?>.<br />
								</p>
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