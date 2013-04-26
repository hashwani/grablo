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
					"table" => "adz",
					"data"  => array(
						"title" => $title,
						"description" => $description,
						"url" => $url,
						"status" => "active"
					)
				)
			);
			if(empty($id)) {
				$error = "Unable to add ad. Try again.";
			} else {
				$success = "One ad added successfully!!!";
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
					"table" => "adz",
					"data"  => array(
						"title" => $title,
						"description" => $description,
						"url" => $url,
						"status" => "active"
					),
					"where" => "id=$id"
				)
			);
			$success = "One ad updated successfully!!!";
			$show_form = false;
		} else {
			$rows = sql_select(
				array(
					"table" => "adz",
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
				"table" => "adz",
				"where" => "id=$id"
			)
		);
		$success = "One ad deleted successfully!!!";
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
				<h3 class="fl"><?php echo ucfirst($action); ?> Product</h3>
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
										<label for="title">Title</label>
										<input id="title" name="title" class="round default-width-input required" type="text" value="<?php echo empty($title) ? "" : $title; ?>" />
									</p>
									<p>
										<label for="url">URL</label>
										<input id="url" name="url" class="round default-width-input required" type="text" value="<?php echo empty($url) ? "" : $url; ?>" />
									</p>
									<p>
										<label for="description">Description</label>
										<textarea id="description" name="description" class="round full-width-textarea required"><?php echo empty($description) ? "" : $description; ?></textarea>
									</p>
									<div class="stripe-separator"><!--  --></div>
									<input value="<?php echo ucfirst($action); ?> Product" id="submit" name="submit" class="round blue ic-right-arrow" type="submit" />
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