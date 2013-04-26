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
	$key = empty($_REQUEST['key']) ? "" : $_REQUEST["key"];
	
	$show_form = true;
	$hidden_fields = "";
	$row_to_update = array();
	
	if($action == "add") {
		if(isset($_POST["submit"])) {
			if($key == "user") {
				extract($_POST);
				if(isset($_FILES["profile_pic"]) && is_valid_image($_FILES["profile_pic"]["name"])){
					$profile_pic = generate_unique_file_name("image_",  $_FILES["profile_pic"]["name"]);
					upload_file("media/profile_pics", $_FILES["profile_pic"], $profile_pic);
				}
				$db = json_decode(file_get_contents("settings.data"), true);
				$keys = array_keys($db["users"]);
				$db["users"][(int)array_pop($keys) + 1] = array(
					"full_name" => $full_name,
					"profile_pic" => $profile_pic,
					"status" => "active",
					"date_added" => date("h:m:s d/m/y")
				);
				$insert_id = file_put_contents("settings.data", json_encode($db));
			}
			if(empty($insert_id)) {
				$error = "Unable to add $key. Try again.";
			} else {
				$success = "One $key added successfully!!!";
			}
		} else {
			$show_form = true;
			$hidden_fields = '<input type="hidden" value="'. $action .'" name="action" id="action" />';
			$hidden_fields .= '<input type="hidden" value="'. $key .'" name="key" id="key" />';
		}
	} else if($action == "update") {
		if(isset($_POST["submit"])) {
			if($key == "user") {
				$profile_pic = $_POST["old_images"];
				if(isset($_POST["update_images"]) && $_POST["update_images"] == "true") {
					if(isset($_FILES["profile_pic"]) && is_valid_image($_FILES["profile_pic"]["name"])){
						$profile_pic = generate_unique_file_name("image_",  $_FILES["profile_pic"]["name"]);
						upload_file("media/profile_pics", $_FILES["profile_pic"], $profile_pic);
					}
				}
				
				extract($_POST);
				$db = json_decode(file_get_contents("settings.data"), true);
				$db["users"][$id] = array(
					"full_name" => $full_name,
					"profile_pic" => $profile_pic,
					"status" => "active",
					"date_added" => date("h:m:s d/m/y")
				);
			}
			file_put_contents("settings.data", json_encode($db));
			
			$success = "One $key updated successfully!!!";
			$show_form = false;
		} else {
			$db = json_decode(file_get_contents("settings.data"), true);
			if($key == "user") {
				$row_to_update = $db["users"][$id];
			}
			$show_form = true;
			$hidden_fields = '<input type="hidden" value="'. $action .'" name="action" id="action" />';
			$hidden_fields .= '<input type="hidden" value="'. $id .'" name="id" id="id" />';
			$hidden_fields .= '<input type="hidden" value="'. $key .'" name="key" id="key" />';
		}
	} else if($action == "delete") {
		$db = json_decode(file_get_contents("settings.data"), true);
		if($key == "user") {
			unset($db["users"][$id]);
		}
		file_put_contents("settings.data", json_encode($db));
		$success = "One $key deleted successfully!!!";
		$show_form = false;
	}
	foreach($row_to_update as $key1 => $val) {
		$$key1 = $val;
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
				<h3 class="fl"><?php echo ucfirst($action); ?> <?php echo ucfirst($key); ?></h3>
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
							if($key == "user") {
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
										<?php
											if($action == "update") {
										?>
												<input type="hidden" value="<?php echo $profile_pic; ?>" name="old_images" id="old_images" />
												<input type="checkbox" value="true" name="update_images" id="update_images" /> Update Images <br />
										<?php
											}
												//echo empty($images) ? "" : $images;
										?>
										<p>
											<label for="profile_pic">Profile Pic</label>
											<input id="profile_pic" name="profile_pic" class="round default-width-input" type="file" />
										</p>
										<div class="stripe-separator"><!--  --></div>
										<input value="<?php echo ucfirst($action); ?> User" id="submit" name="submit" class="round blue ic-right-arrow" type="submit" />
										<p>&nbsp;</p>
									</fieldset>
								</form>
					<?php
							}
						}
					?>
				</div> <!-- end half-size-column -->
			</div>
		</div>
	</body>
</html>