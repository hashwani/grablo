<?php
session_start();

$success = $error = "";

extract($_GET);

if(empty($_SESSION["user"])) {
	header("location: signin.php?redirect=grab_it.php?product_id=$product_id");
}
require('libs/_config.php');
require('libs/_db.php');
require('libs/_utils.php');
sql_open();

$product = sql_select(
	array(
		"table" => "products",
		"cols" => "*",
		"where" => "id=$product_id"
	)
);
	
$user = $_SESSION["user"];
$users = sql_select(
	array(
		"table" => "users",
		"cols" => "*",
		"where" => "id=" . $user["id"]
	)
);
$user = $users[0];

if(!empty($product[0]) && $product[0]["id"] == $product_id && ($user["grabz"] > 0 || $user["free_grabz"] > 0)) {
	if($user["grabz"] > 0) {
		$data = array("grabz" => "`grabz`-1", "status" => "active");
	} else if($user["free_grabz"] > 0) {
		$data = array("free_grabz" => "`free_grabz`-1", "status" => "deactive");
	}
	if(isset($data)) {
		sql_update(
			array(
				"table" => "users",
				"data" => $data,
				"where" => "id=" . $user["id"]
			)
		);
		sql_insert(
			array(
				"table" => "bids",
				"data" => array(
					"product_id" => $product_id,
					"user_id" => $user["id"],
					"status" => $data["status"]
				)
			)
		);
		$success = "Your have successfully grabbed!!!";
	} else {
		$error = "Your have no grabz to grab this.";
	}
} else {
	$error = "Your have no grabz to grab this.";
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="<?php echo $MEDIA_DIR; ?>style/style.css" rel="stylesheet" type="text/css" />
		<script src="<?php echo $MEDIA_DIR; ?>js/IE9.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/jquery164.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/validate.js" type="text/javascript"></script>
	</head>
	<body>
		<div>
			<div id="login" class="login" style="margin: 0 10px; min-width: 150px;">
				<h1 style="font-family: Helvetica, 'Helvetica Neue', Arial, sans-serif; font-size: 18px; margin: 5px 0; text-align: left; border-bottom: 1px solid #f0f0f0; padding-bottom: 3px;">Grab It:</h1>
				<?php
					if(!empty($error)) {
						echo '<div style="color: red; padding: 5px; text-align: left; font-size: 14px;">' . $error . '</div> <a href="buy_bid_packs.php" class="button blue" style="padding: 0.2em 0.6em; float: left;" target="_parent">Buy Grabz</a>';
					} else if(!empty($success)) {
						echo '<div style="color: green; padding: 5px; text-align: left; font-size: 14px;">' . $success . '</div>';
					}
					$total_grabz = ($user["grabz"] + $user["free_grabz"]);
					if($total_grabz > 0) {
				?>
						<h2 style="text-align:left; margin-left: 5px;">Grabz Remainning:<b>&nbsp; <?php echo $total_grabz; ?></b></h2>
				<?php
					}
				?>
			</div>
		</div>
	</body>
</html>