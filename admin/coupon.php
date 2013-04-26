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
			$id = sql_insert(
				array(
					"table" => "coupons",
					"data"  => array(
						"code" => $code,
						"grabz" => $grabz,
						"max_claimed" => $max_claimed,
						"users_claimed" => $users_claimed,
						"expiry_date" => $expiry_date,
						"status" => $status
					)
				)
			);
			if(empty($id)) {
				$error = "Unable to add coupon. Try again.";
			} else {
				$success = "One coupon added successfully!!!";
			}
		} else {
			$show_form = true;
			$hidden_fields = '<input type="hidden" value="'. $action .'" name="action" id="action" />';
		}
	} else if($action == "update") {
		if(isset($_POST["submit"])) {
			extract($_POST);
			sql_update(
				array(
					"table" => "coupons",
					"data"  => array(
						"code" => $code,
						"grabz" => $grabz,
						"max_claimed" => $max_claimed,
						"users_claimed" => $users_claimed,
						"expiry_date" => $expiry_date,
						"status" => $status
					),
					"where" => "id=$id"
				)
			);
			$success = "One coupon updated successfully!!!";
			$show_form = false;
		} else {
			$rows = sql_select(
				array(
					"table" => "coupons",
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
				"table" => "coupons",
				"where" => "id=$id"
			)
		);
		$success = "One coupon deleted successfully!!!";
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
										<label for="code">Code</label>
										<input id="code" name="code" class="round default-width-input required" type="text" value="<?php echo empty($code) ? "" : $code; ?>" />
									</p>
									<p>
										<label for="grabz">Grabz</label>
										<input id="grabz" name="grabz" class="round default-width-input required" type="text" value="<?php echo empty($grabz) ? "" : $grabz; ?>" />
									</p>
									<p>
										<label for="max_claimed">Max Claimed</label>
										<input id="max_claimed" name="max_claimed" class="round default-width-input required" type="text" value="<?php echo empty($max_claimed) ? "" : $max_claimed; ?>" />
									</p>
									<p>
										<label for="users_claimed">Users Claimed</label>
										<input id="users_claimed" name="users_claimed" class="round default-width-input required" type="text" value="<?php echo empty($users_claimed) ? "" : $users_claimed; ?>" />
									</p>
									<p>
										<label for="expiry_date">Expiry Date</label>
										<input id="expiry_date" name="expiry_date" class="round default-width-input required" type="text" value="<?php echo empty($expiry_date) ? "" : $expiry_date; ?>" />
									</p>
									<p>
										<label for="status">Type</label>
										<select name="status" id="status" class="round defualt-width-input required" style="width: 100px;">
											<option value="real">Real</option>
											<option value="fake">Fake</option>
										</select>
									</p>
									<div class="stripe-separator"><!--  --></div>
									<input value="<?php echo ucfirst($action); ?> Coupon" id="submit" name="submit" class="round blue ic-right-arrow" type="submit" />
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