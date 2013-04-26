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
			$product_images = "";
			for($i = 1; $i < 4; $i++) {
				if(isset($_FILES["product_image$i"]) && is_valid_image($_FILES["product_image$i"]["name"])){
					$product_image = generate_unique_file_name("image_",  $_FILES["product_image$i"]["name"]);
					upload_file("media/product_images", $_FILES["product_image$i"], $product_image);
					$product_images .= $product_image . ", ";
				}
			}
			$product_images = substr($product_images, 0, -2);
			$id = sql_insert(
				array(
					"table" => "products",
					"data"  => array(
						"title" => $title,
						"description" => $description,
						"features" => $features,
						"technical_info" => $technical_info,
						"market_retail_price" => $market_retail_price,
						"reserved_price" => $reserved_price,
						"images" => $product_images,
						"status" => "active"
					)
				)
			);
			if(empty($id)) {
				$error = "Unable to add product. Try again.";
			} else {
				$success = "One product added successfully!!!";
			}
		} else {
			$show_form = true;
			$hidden_fields = '<input type="hidden" value="'. $action .'" name="action" id="action" />';
		}
	} else if($action == "update") {
		if(isset($_POST["submit"])) {
			$product_images = $_POST["old_images"];
			if(isset($_POST["update_images"]) && $_POST["update_images"] == "true") {
				$product_images = "";
				for($i = 1; $i < 4; $i++) {
					if(isset($_FILES["product_image$i"]) && is_valid_image($_FILES["product_image$i"]["name"])){
						$product_image = generate_unique_file_name("image_",  $_FILES["product_image$i"]["name"]);
						upload_file("media/product_images", $_FILES["product_image$i"], $product_image);
						$product_images .= $product_image . ", ";
					}
				}
				$product_images = substr($product_images, 0, -2);
			}
			
			extract($_POST);
			sql_update(
				array(
					"table" => "products",
					"data"  => array(
						"title" => $title,
						"description" => $description,
						"features" => $features,
						"technical_info" => $technical_info,
						"market_retail_price" => $market_retail_price,
						"reserved_price" => $reserved_price,
						"images" => $product_images,
						"status" => "active"
					),
					"where" => "id=$id"
				)
			);
			$success = "One product updated successfully!!!";
			$show_form = false;
		} else {
			$rows = sql_select(
				array(
					"table" => "products",
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
				"table" => "products",
				"where" => "id=$id"
			)
		);
		$success = "One product deleted successfully!!!";
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
										<label for="market_retail_price">Market Retail Price</label>
										<input id="market_retail_price" name="market_retail_price" class="round default-width-input required number" type="text" value="<?php echo empty($market_retail_price) ? "" : $market_retail_price; ?>" />
									</p>
									<p>
										<label for="reserved_price">Reserved Price</label>
										<input id="reserved_price" name="reserved_price" class="round default-width-input required number" type="text" value="<?php echo empty($reserved_price) ? "" : $reserved_price; ?>" />
									</p>
									<p>
										<label for="description">Description</label>
										<textarea id="description" name="description" class="round full-width-textarea required"><?php echo empty($description) ? "" : $description; ?></textarea>
									</p>
									<p>
										<label for="features">Features</label>
										<textarea id="features" name="features" class="round full-width-textarea required"><?php echo empty($features) ? "" : $features; ?></textarea>
									</p>
									<p>
										<label for="technical_info">Technical Info</label>
										<textarea id="technical_info" name="technical_info" class="round full-width-textarea required"><?php echo empty($technical_info) ? "" : $technical_info; ?></textarea>
									</p>
									<p>
										<label for="product_image1">Product Images</label>
										<p style="font-size: 1px; margin: 0; line-height: 1px;">&nbsp;</p>
										<?php
											if($action == "update") {
										?>
												<input type="hidden" value="<?php echo $images; ?>" name="old_images" id="old_images" />
												<input type="checkbox" value="true" name="update_images" id="update_images" /> Update Images <br />
										<?php
											}
												//echo empty($images) ? "" : $images;
										?> <br />
										<input id="product_image1" name="product_image1" <?php echo ($action == "update") ? "" : 'class="required"'; ?> type="file" />
										<br /><br />
										<input id="product_image2" name="product_image2" type="file" />
										<br /><br />
										<input id="product_image3" name="product_image3" type="file" />
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