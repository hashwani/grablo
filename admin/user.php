<?php
	require('authenticate.php');
	require('../libs/_config.php');
	require('../libs/_db.php');
	require('../libs/_utils.php');
	sql_open();
	
	$error = "";
	$success = "";
	$action = empty($_REQUEST['action']) ? "" : $_REQUEST["action"];
	$id = empty($_REQUEST['id']) ? "" : $_REQUEST["id"];
	$show_form = true;
	$hidden_fields = "";
	$row_to_update = array();
	
	if($action == "add") {
		if(isset($_POST["submit"])) {
			extract($_POST);
			$profile_pic = "";
			if(isset($_FILES["profile_pic"]) && is_valid_image($_FILES["profile_pic"]["name"])) {
				$profile_pic = generate_unique_file_name("image_",  $_FILES["profile_pic"]["name"]);
				upload_file("media/profile_pics", $_FILES["profile_pic"], $profile_pic);
			}
			$id = sql_insert(
				array(
					"table" => "users",
					"data"  => array(
						"full_name" => $full_name,
						"email_address" => $email_address,
						"password" => $password,
						"address_1" => $address_1,
						"address_2" => $address_2,
						"city" => $city,
						"state" => $state,
						"country" => $country,
						"zip_code" => $zip_code,
						"telephone" => $telephone,
						"profile_pic" => $profile_pic,
						"type" => $type,
						"grabz" => $grabz,
						"grabz_from_adz" => $grabz_from_adz,
						"grabz_from_referrals" => $grabz_from_referrals,
						"grabz_from_signup" => $grabz_from_signup,
						"grabz_from_coupons" => $grabz_from_coupons,
						"ad_credits" => $ad_credits,
						"status" => "active"
					)
				)
			);
			if(empty($id)) {
				$error = "Unable to add user. Try again.";
			} else {
				$success = "One user added successfully!!!";
			}
		} else {
			$show_form = true;
			$hidden_fields = '<input type="hidden" value="'. $action .'" name="action" id="action" />';
		}
	} else if($action == "update") {
		if(isset($_POST["submit"])) {
			$profile_pic = $_POST["old_pic"];
			if(isset($_POST["update_images"]) && $_POST["update_images"] == "true") {
				$profile_pic = "";
				if(isset($_FILES["profile_pic"]) && is_valid_image($_FILES["profile_pic"]["name"])) {
					$profile_pic = generate_unique_file_name("image_",  $_FILES["profile_pic"]["name"]);
					upload_file("media/profile_pics", $_FILES["profile_pic"], $profile_pic);
				}
			}
			
			extract($_POST);
			sql_update(
				array(
					"table" => "users",
					"data" => array(
						"full_name" => $full_name,
						"email_address" => $email_address,
						"password" => $password,
						"address_1" => $address_1,
						"address_2" => $address_2,
						"city" => $city,
						"state" => $state,
						"country" => $country,
						"zip_code" => $zip_code,
						"telephone" => $telephone,
						"profile_pic" => $profile_pic,
						"type" => $type,
						"grabz" => $grabz,
						"grabz_from_adz" => $grabz_from_adz,
						"grabz_from_referrals" => $grabz_from_referrals,
						"grabz_from_signup" => $grabz_from_signup,
						"grabz_from_coupons" => $grabz_from_coupons,
						"ad_credits" => $ad_credits,
						"status" => "active"
					),
					"where" => "id=$id"
				)
			);
			$success = "One user updated successfully!!!";
			$show_form = false;
		} else {
			$rows = sql_select(
				array(
					"table" => "users",
					"cols"  => '*',
					"where" => "id=$id"
				)
			);
			$row_to_update = $rows[0];
			$show_form = true;
			$hidden_fields = '<input type="hidden" value="'. $action .'" name="action" id="action" /><input type="hidden" value="'. $id .'" name="id" id="id" />';
		}
	} else if($action == "delete") {
		sql_delete(
			array(
				"table" => "users",
				"where" => "id=$id"
			)
		);
		$success = "One user deleted successfully!!!";
		$show_form = false;
	}
	foreach($row_to_update as $key => $val) {
		$$key = $val;
	}
?>
<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' />
		<link rel="stylesheet" href="css/style.css" />

		<script src="js/jquery164.js" type="text/javascript"></script>
		<script src="js/validate.js" type="text/javascript"></script>
		<script src="js/script.js"></script> 
		<style type="text/css">
			.error { color: red; }
		</style>
	</head>
	<body>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#add_form").validate();
			});
		</script>
		<div class="content-module">
			<div class="content-module-heading cf">
				<h3 class="fl"><?php echo ucfirst($action); ?> User</h3>
			</div>
			<div style="margin-left: 10px;">
				<div class="half-size-column fl">
					<?php
						if($error != "") {
					?>
							<br /><div class="error-box round"><?php echo $error; ?></div>
					<?php
						} else if($success != "") {
					?>
							<br /><div class="confirmation-box round"><?php echo $success; ?></div>
					<?php
						}
					?>
					<?php
						if($show_form) {
					?>
							<form id="add_form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
								<?php
									echo $hidden_fields;
								?>
								
								<fieldset>
									<p>&nbsp;</p>
									<p>
										<label for="full_name">Full Name</label>
										<input id="full_name" name="full_name" class="round default-width-input required" type="text" value="<?php echo empty($full_name) ? "" : $full_name; ?>" />
									</p>
									<p>
										<label for="email_address">Email Address</label>
										<input id="email_address" name="email_address" class="round default-width-input required email" type="text" value="<?php echo empty($email_address) ? "" : $email_address; ?>" />
									</p>
									<p>
										<label for="password">Password</label>
										<input id="password" name="password" class="round default-width-input required" type="password" value="<?php echo empty($password) ? "" : $password; ?>" />
									</p>
									<p>
										<label for="address_1">Address 1</label>
										<input id="address_1" name="address_1" class="round default-width-input required" type="text" value="<?php echo empty($address_1) ? "" : $address_1; ?>" />
									</p>
									<p>
										<label for="address_2">Address 2</label>
										<input id="address_2" name="address_2" class="round default-width-input required" type="text" value="<?php echo empty($address_2) ? "" : $address_2; ?>" />
									</p>
									<p>
										<label for="city">City</label>
										<input id="city" name="city" class="round default-width-input required" type="text" value="<?php echo empty($city) ? "" : $city; ?>" />
									</p>
									<p>
										<label for="state">State</label>
										<input id="state" name="state" class="round default-width-input required" type="text" value="<?php echo empty($state) ? "" : $state; ?>" />
									</p>
									<p>
										<label for="country">Country</label>
										<input id="country" name="country" class="round default-width-input required" type="text" value="<?php echo empty($country) ? "" : $country; ?>" />
									</p>
									<p>
										<label for="zip_code">Zip Code</label>
										<input id="zip_code" name="zip_code" class="round default-width-input required" type="text" value="<?php echo empty($zip_code) ? "" : $zip_code; ?>" />
									</p>
									<p>
										<label for="telephone">Telephone</label>
										<input id="telephone" name="telephone" class="round default-width-input required" type="text" value="<?php echo empty($telephone) ? "" : $telephone; ?>" />
									</p>
									<p>
										<label for="grabz">Grabs</label>
										<input id="grabz" name="grabz" class="round default-width-input required" type="text" value="<?php echo empty($grabz) ? "" : $grabz; ?>" />
									</p>
									<p>
										<label for="free_grabz">Free Grabs</label>
										<input id="free_grabz" name="free_grabz" class="round default-width-input required" type="text" value="<?php echo empty($free_grabz) ? "" : $free_grabz; ?>" />
									</p>
									<p>
										<label for="ad_credits">Ad Credits</label>
										<input id="ad_credits" name="ad_credits" class="round default-width-input required" type="text" value="<?php echo empty($ad_credits) ? "" : $ad_credits; ?>" />
									</p>
									<p>
										<label for="type">Type</label>
										<input id="type" name="type" class="round default-width-input required" type="text" value="<?php echo empty($type) ? "" : $type; ?>" />
									</p>
									<p>
										<label for="profile_pic">Profile Pic</label>
										<p style="font-size: 1px; margin: 0; line-height: 1px;">&nbsp;</p>
										<?php
											if($action == "update") {
										?>
												<input type="hidden" value="<?php echo $profile_pic; ?>" name="old_pic" id="old_pic" />
												<input type="checkbox" value="true" name="update_images" id="update_images" /> Update Images <br />
										<?php
											}
												//echo empty($images) ? "" : $images;
										?> <br />
										<input id="profile_pic" name="profile_pic" <?php echo ($action == "update") ? "" : 'class="required"'; ?> type="file" />
									</p>
									<div class="stripe-separator"><!--  --></div>
									<input value="<?php echo ucfirst($action); ?> User" id="submit" name="submit" class="round blue ic-right-arrow" type="submit" />
									<p>&nbsp;</p>
								</fieldset>
							</form>
					<?php
						}
					?>
				</div> <!-- end half-size-column -->
			</div>
		</div>
	</body>
</html>