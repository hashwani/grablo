<?php
session_start();
require('libs/_config.php');
require('libs/_db.php');
require('libs/_utils.php');
sql_open();

extract($_GET);
extract($_POST);

if(isset($_SESSION["user"])) {
	extract($_SESSION["user"]);
}

// request id = 1 return the last bid amount
if($request_id == 1) {
	$products = sql_select(
		array(
			"table" => "products" ,
			"cols" => "id AS product_id, reserved_price",
			"where" => "id=$product_id",
			"limit" => "1"
		)
	);
	$product = $products[0];
	
	if(isset($id)) {
		$users = sql_select(
			array(
				"table" => "users" ,
				"cols" => "id AS user_id, profile_pic, grabz, grabz_from_adz, grabz_from_referrals, grabz_from_signup, grabz_from_coupons",
				"where" => "id=$id",
				"limit" => "1"
			)
		);
		$user = $users[0];
		
		$total_grabz_spent = sql_select(
			array(
				"table" => "$table b" ,
				"cols" => "COUNT(id) as total_grabz_spent",
				"where" => "product_id=$product_id AND user_id=$id"
			)
		);
		$total_grabz_spent = $total_grabz_spent[0];
	} else {
		$user = array(
			"grabz_from_adz" => 0,
			"grabz_from_referrals" => 0,
			"grabz_from_signup" => 0,
			"grabz_from_coupons" => 0,
			"grabz" => 0
		);
		$total_grabz_spent = array(
			"total_grabz_spent" => 0
		);
	}
	
	$last_grabz = sql_select(
		array(
			"table" => "$table b" ,
			"cols" => "b.id, u.id AS user_id, u.full_name, u.profile_pic",
			"where" => "b.product_id=$product_id AND b.status='active'",
			"left_join" => "`users` u ON u.id=b.user_id",
			"order_by" => "b.date_added DESC"
		)
	);
	$db = json_decode(file_get_contents("admin/settings.data"), true);
	$row = array(
		array(
			"grabz" => $user["grabz"] + $user["grabz_from_adz"] + $user["grabz_from_referrals"] + $user["grabz_from_signup"] + $user["grabz_from_coupons"],
			"total_grabz" => $total_grabz_spent["total_grabz_spent"],
			"winner" => ($product["reserved_price"] <= ($db["cost_per_grab"] * count($last_grabz)))
		),
		(isset($last_grabz[0]) ? $last_grabz[0] : array())
	);
	echo "var last_bid1=" . json_encode($row) . ";";

// if request id is 2 then an ad is clicked decrement one credit from the ad owner
// and add 0.25 grab to the user who have clicked the ad
} else if($request_id == 2) {
	$ad = sql_select(
		array(
			"table" => "adz a",
			"cols" => "a.url, a.users_clicked, u.id, u.ad_credits",
			"left_join" => "users u ON a.user_id=u.id",
			"where" => "a.id=$ad_id",
			"limit" => "1"
		)
	);
	if(isset($_SESSION["user"])) {
		if(!in_array($_SESSION["user"]["id"], explode(',', $ad[0]["users_clicked"]))) {
			sql_update(
				array(
					"table" => "adz",
					"data" => array(
						"users_clicked" => ($ad[0]["users_clicked"] == "" ? $_SESSION["user"]["id"] : $ad[0]["users_clicked"] . "," . $_SESSION["user"]["id"])
					),
					"where" => "id=$ad_id"
				)
			);
			if($ad[0]["ad_credits"] > 0) {
				sql_update(
					array(
						"table" => "users",
						"data" => array(
							"ad_credits" => "`ad_credits`-1"
						),
						"where" => "id=" . $ad[0]["id"]
					)
				);
				sql_update(
					array(
						"table" => "users",
						"data" => array(
							"grabz_from_adz" => "`grabz_from_adz`+0.25"
						),
						"where" => "id=" . $_SESSION["user"]["id"]
					)
				);
			}
		}
	}
	header("location:" . $ad[0]["url"]);


// login fb user
} else if($request_id == 3) {
	$users = sql_select(
		array(
			"table" => "users",
			"cols" => "*",
			"where" => "fb_id=$fb_id",
			"limit" => "1"
		)
	);
	if(empty($users[0]["id"])) {
		$id = sql_insert(
			array(
				"table" => "users",
				"data" => array(
					"fb_id" => $fb_id,
					"full_name" => $name,
					"email_address" => $email
				)
			)
		);
	}
	$_SESSION["user"] = array(
		"id" => $id,
		"full_name" => $name,
		"email_address" => $email
	);
	echo "done";
// register fb user
} else if($request_id == 4) {

// if request_id is 5 then place a bid and decrement one grab of the user
} else if($request_id == 5) {
	// output 
	// 0 = if not logged in
	// 1 = if successfully grabed
	// 2 = if doesn't have sufficient grabz
	extract($_GET);

	if(empty($_SESSION["user"])) {
		echo 0;
	} else {

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

		if(!empty($product[0]) && $product[0]["id"] == $product_id) {
			if($user["grabz"] > 0) {
				$data = array("grabz" => "`grabz`-1", "status" => "active");
			} else if($user["grabz_from_adz"] > 0) {
				$data = array("grabz_from_adz" => "`grabz_from_adz`-1", "status" => "deactive");
			} else if($user["grabz_from_referrals"] > 0) {
				$data = array("grabz_from_referrals" => "`grabz_from_referrals`-1", "status" => "deactive");
			} else if($user["grabz_from_signup"] > 0) {
				$data = array("grabz_from_signup" => "`grabz_from_signup`-1", "status" => "deactive");
			} else if($user["grabz_from_coupons"] > 0) {
				$data = array("grabz_from_coupons" => "`grabz_from_coupons`-1", "status" => "deactive");
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
				echo 1;
			} else {
				echo 2;
			}
		} else {
			echo 2;
		}
	}
// handle request to buy credits and send request to paypal
} else if($request_id == 6) {
	$db = json_decode(file_get_contents("admin/settings.data"), true);
	$grabz = (empty($_GET["grabz"]) && is_numeric($_GET["grabz"])) ? $db["min_grabz"] : number_format($_GET["grabz"], 2, '.', '');
	$url = "$HOME" . "paypal_integration.php" . 
			"?business=uosarg_1341739843_biz@yahoo.com&" .
			"item_number=1&" .
			"no_note=0&" .
			"return=$HOME&" .
			"rm=2&" .
			"currency_code=USD&" .
			"cmd=_xclick&" .
			"notify_url=$HOME" . "handle_paypal_response.php&" .
			"item_name=$grabz Grabz&" .
			"amount=" . number_format($grabz * $db["cost_per_grab"], 2, '.', '') . "&" .
			"custom=9300922";
	header("location: $url");
	
// handle request to redeem coupon code
} else if($request_id == 7) {
	if(empty($_SESSION["user"]) || empty($_POST["coupon_code"])) {
		echo "Unable to redeem coupon code.";
	} else {
		$coupon_code = sql_select(
			array(
				"table" => "coupons",
				"cols" => "*",
				"where" => "expiry_date >= NOW() AND code='" . $_POST["coupon_code"] . "'"
			)
		);
		
		if(empty($coupon_code[0]["id"])) {
			echo "Coupon is not valid or expired.";
		} else {
			$user = $_SESSION["user"];
			if(in_array($user["id"], explode(',', (($coupon_code[0]["users_claimed"] != "" && $coupon_code[0]["users_claimed"][0] == ',') ? substr($coupon_code[0]["users_claimed"], 1) : $coupon_code[0]["users_claimed"])))) {
				echo "You have already redeemed this coupon code";
			} else {
				$grabz_type = ($coupon_code[0]["status"] == "real" ? "grabz" : "grabz_from_coupons");
				sql_update(
					array(
						"table" => "users",
						"data" => array(
							$grabz_type => "`" . $grabz_type . "` + " . $coupon_code[0]["grabz"]
						),
						"where" => "id=" . $user["id"]
					)
				);
				mysql_query("UPDATE coupons SET users_claimed=CONCAT(`users_claimed`, '," . $user["id"] . "') WHERE id=" . $coupon_code[0]["id"]);
				echo "You have successfully redeemed Grabz";
			}
		}
	}} else if($request_id == 8) {		if($_SESSION["user"]["password"]== $old_password){			sql_update(				array(					"table" => "users",					"where" => "id=".$_SESSION["user"]["id"],					"data" => array("password"=>$new_password)				)			);			$user = sql_select(				array(					"table" => "users",					"cols" => "*",					"where" => "id=".$_SESSION["user"]["id"]				)			);			$_SESSION['user'] = $user[0];			echo 'Your Password Successfully Changed!!!';			}		else{			echo 'Old Password Doesn\'t Matched???';		}} else {
	echo "unknown request id";
}

?>
