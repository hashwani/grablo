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
	$row_to_update = $users = $products = array();
	
	if($action == "add") {
		if(isset($_POST["submit"])) {
			extract($_POST);
			$id = sql_insert(
				array(
					"table" => "bids",
					"data"  => array(
						"user_id" => $user_id,
						"product_id" => $product_id,
						"bid" => $bid,
						"status" => $status
					)
				)
			);
			if(empty($id)) {
				$error = "Unable to add bid. Try again.";
			} else {
				$success = "One bid added successfully!!!";
			}
		} else {
			$users = sql_select(
				array(
					"table" => "users",
					"cols"  => 'id,full_name',
					"where"  => "status!='deactive'"
				)
			);
			$products = sql_select(
				array(
					"table" => "products",
					"cols"  => 'id, title',
					"where"  => "status!='sold'"
				)
			);
			$show_form = true;
			$hidden_fields = '<input type="hidden" value="'. $action .'" name="action" id="action" />';
		}
	} else if($action == "update") {
		if(isset($_POST["submit"])) {
			extract($_POST);
			sql_update(
				array(
					"table" => "bids",
					"data"  => array(
						"user_id" => $user_id,
						"product_id" => $product_id,
						"bid" => $bid,
						"status" => $status
					),
					"where" => "id=$id"
				)
			);
			$success = "One bid updated successfully!!!";
			$show_form = false;
		} else {
			$rows = sql_select(
				array(
					"table" => "bids",
					"cols"  => '*',
					"where" => "id=$id"
				)
			);
			$row_to_update = $rows[0];
			$users = sql_select(
				array(
					"table" => "users",
					"cols"  => 'id,full_name',
					"where"  => "status!='deactive'"
				)
			);
			$products = sql_select(
				array(
					"table" => "products",
					"cols"  => 'id, title',
					"where"  => "status!='sold'"
				)
			);
			$show_form = true;
			$hidden_fields = '<input type="hidden" value="'. $action .'" name="action" id="action" /><input type="hidden" value="'. $id .'" name="id" id="id" />';
		}
	} else if($action == "delete") {
		sql_delete(
			array(
				"table" => "bids",
				"where" => "id=$id"
			)
		);
		$success = "One bid deleted successfully!!!";
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
				<h3 class="fl"><?php echo ucfirst($action); ?> Bid</h3>
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
										<label for="user_id">User</label>
										<select id="user_id" name="user_id" class="round default-width-input required">
											<?php
												for($i = 0; $i < count($users); $i++) {
											?>
													<option value="<?php echo $users[$i]["id"]; ?>" <?php echo (isset($user_id) && $user_id == $users[$i]["id"]) ? 'selected="selected"' : ''; ?>><?php echo $users[$i]["full_name"]; ?></option>
											<?php
												}
											?>
										</select>
									</p>
									<p>
										<label for="product_id">Product</label>
										<select id="product_id" name="product_id" class="round default-width-input required">
											<?php
												for($i = 0; $i < count($products); $i++) {
											?>
													<option value="<?php echo $products[$i]["id"]; ?>" <?php echo (isset($product_id) && $product_id == $products[$i]["id"]) ? 'selected="selected"' : ''; ?>><?php echo $products[$i]["title"]; ?></option>
											<?php
												}
											?>
										</select>
									</p>
									<p>
										<label for="bid">Bid</label>
										<input id="bid" name="bid" class="round default-width-input required" type="text" value="<?php echo empty($bid) ? "" : $bid; ?>" />
									</p>
									<p>
										<label for="status">Status</label>
										<input id="status" name="status" class="round default-width-input required" type="text" value="<?php echo empty($status) ? "" : $status; ?>" />
									</p>
									<div class="stripe-separator"><!--  --></div>
									<input value="<?php echo ucfirst($action); ?> Bid" id="submit" name="submit" class="round blue ic-right-arrow" type="submit" />
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