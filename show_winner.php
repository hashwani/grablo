<?php
session_start();

$success = $error = "";

extract($_GET);
extract($_POST);

require('libs/_config.php');
require('libs/_db.php');
require('libs/_utils.php');
sql_open();

// select the product by id
$product = sql_select(
	array(
		"table" => "products",
		"cols" => "*",
		"where" => "id=$product_id"
	)
);

// init $user and $last_grabz to default values
$user = array("id" => 0);
$last_grabz = array(array("user_id" => 0));

if(isset($_SESSION["user"]["id"])) {
	$user = $_SESSION["user"];
}

// check if product exists
if(isset($product[0]["id"])) {
	// select all of the last bids with user info
	$last_grabz = sql_select(
		array(
			"table" => "bids b" ,
			"cols" => "b.id, u.id AS user_id, u.full_name, u.profile_pic",
			"where" => "b.product_id=$product_id AND b.status='active'",
			"left_join" => "`users` u ON u.id=b.user_id",
			"order_by" => "b.date_added DESC"
		)
	);
	
	// load configurations data like cost_per_grab etc
	$db = json_decode(file_get_contents("admin/settings.data"), true);
	
	// if reserved price is less then total grabz on this product multiply by cost_per_grab
	// then last grabber is the winner
	if($product[0]["reserved_price"] <= ($db["cost_per_grab"] * count($last_grabz))) {
		
		// select product that is at last in auction order
		$next_product = sql_select(
			array(
				"table" => "products",
				"cols" => "auction_order",
				"order_by" => "auction_order DESC",
				"limit" => 1
			)
		);
		// set product auction order to the last and status to sold
		sql_update(
			array(
				"table" => "products",
				"where" => "id=$product_id",
				"data" => array(
					"auction_order" => $next_product[0]["auction_order"] + 1,
					"status" => "sold"
				)
			)
		);
		
		// if product review and rating form is submitted then add it and upload image
		if(!empty($_POST["submit"]) && strlen($error) < 1) {
			extract($_POST);
			
			// upload winner pic
			$winner_pic = "";
			if(isset($_FILES["winner_image"]) && is_valid_image($_FILES["winner_image"]["name"])){
				$winner_pic = generate_unique_file_name("image_",  $_FILES["winner_image"]["name"]);
				upload_file("media/winner_pics", $_FILES["winner_image"], $winner_pic);
			}
			
			// insert winner review into the DB
			$id = sql_insert(
				array(
					"table" => "reviews",
					"data"  => array(
						"user_id" => $user["id"],
						"comment" => $review,
						"rating" => $rating,
						"winner_pic" => $winner_pic,
						"product_id" => $product_id,
						"status" => "active"
					)
				)
			);
			if(empty($id)) {
				$error = "Unable to add rating and review. Try again.";
			} else {
				$success = "Thanks, your review and rating added successfully!!!";
			}
		}
	} else {
		$error = "Auction doesn't over yet";
	}
	
} else {
	$error = "Product doesn't exist";
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="<?php echo $MEDIA_DIR; ?>style/style.css" rel="stylesheet" type="text/css" />
		<script src="<?php echo $MEDIA_DIR; ?>js/IE9.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/jquery164.js" type="text/javascript"></script>
	</head>
	<body style="width: 420px; min-width: 420px;">
		<div style="text-align: left;">
			<div id="login" class="login clearfix" style="margin: 0 10px; min-width: 150px;c color: #ffffff;">
				<?php
					if(strlen($success) > 1) {
				?>
						<h1 style="font-family: Helvetica, 'Helvetica Neue', Arial, sans-serif; font-size: 22px; margin: 5px 0; text-align: left; border: 1px solid #f0f0f0; padding: 5px;"><?php echo $success; ?></h1>
				<?php
					} else if(strlen($error) < 1) {
				?>
						<h1 style="font-family: Helvetica, 'Helvetica Neue', Arial, sans-serif; font-size: 22px; margin: 5px 0; text-align: left; border-bottom: 1px solid #f0f0f0; padding-bottom: 3px;">Auction Over:</h1>
						<div class="clearfix">
							<div style="width: 105px; float: left; margin: 5px;">
								<img src="<?php echo $MEDIA_DIR; ?>profile_pics/<?php echo $last_grabz[0]["profile_pic"]; ?>" style="max-width: 100px; max-height: 100px;" />
							</div>
							<div style="float: right; width: 280px; text-align: left; font-size: 16px; color: #ffffff; margin: 10px 0;">
								<b><?php echo ucfirst($last_grabz[0]["full_name"]); ?></b> has successfully grabbed '<b><?php echo $product[0]["title"]; ?></b>'
							</div>
						</div>
						<br />
						<?php
							if($user["id"] == $last_grabz[0]["user_id"]) {
						?>
								<div id="register" class="login">
									<p style="color: #ffffff; font-size: 16px;">Rate this product or goto <a style="color: #336699; font-weight: bold;" href="<?php echo $HOME; ?>">Grablo Home</a>.</p>
									<form action="show_winner.php" method="post" id="regForm" enctype="multipart/form-data">
										<input type="hidden" value="<?php echo $product_id; ?>" name="product_id" />
										<h2 style="margin: 15px 0 5px;">Product Rating:</h2>
										<select name="rating" style="width: 110px;">
											<option value="5">5</option>
											<option value="4">4</option>
											<option value="3">3</option>
											<option value="2">2</option>
											<option value="1">1</option>
										</select>
										<h2 style="margin: 15px 0 5px;">Product Review:</h2>
										<textarea name="review" style="width: 220px; height: 75px;" class="required"></textarea>
										<h2 style="margin: 15px 0 0;">Winner Image:</h2>
										<input type="file" name="winner_image" id="winner_image" class="required" />
										<br /> <br />
										<input type="submit" name="submit" class="primary-button" value="Submit Rreview" />
										<br /> <br />
									</form>
									<script src="<?php echo $MEDIA_DIR; ?>js/validate.js" type="text/javascript"></script>
									<script type="text/javascript">
										$(document).ready(function() {
											$("#regForm").validate();
										});
									</script>
								</div>
						<?php
							} else {
						?>
								<div class="clearfix" style="text-align: left; color: #ffffff;">
									<b>New auction will start in 10 secs...</b>
									<a href="<?php echo $HOME; ?>" id="home_link"></a>
								</div>
								<script type="text/javascript">
									(function($) {
										$(window).ready(
											function() {
												$("#login").fadeOut(250).fadeIn(250).fadeOut(250).fadeIn(250).fadeOut(250).fadeIn(250);
												setTimeout(function(){
													window.parent.location = "<?php echo $HOME; ?>";
												}, 10000);
											}
										);
									})(jQuery);
								</script>
						<?php
							}
						?>
				<?php
					} else {
				?>
						<h1 style="font-family: Helvetica, 'Helvetica Neue', Arial, sans-serif; font-size: 22px; margin: 5px 0; text-align: left; border: 1px solid #f0f0f0; padding: 5px;"><?php echo $error; ?></h1>
				<?php
					}
				?>
			</div>
		</div>
	</body>
</html>