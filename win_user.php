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


// check if product exists

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
	echo "bid id = ".$last_grabz['user_id']."<br/> user name = ".$last_grabz['full_name']."<img src='".$last_grabz['profile_pic']."'";
	?>
	<div>
	</div>
	
	</body>
</html>