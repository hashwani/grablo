<?php

require('authenticate.php');
require('../libs/_config.php');
require('../libs/_db.php');
require('../libs/_utils.php');
sql_open();
//$_POST = $_GET;
extract($_POST);
extract($_GET);

// request id = 1 select rows from table for module viewer
if($request_id == 1) {
	$rows = array();
	if($table == "products") {
		sql_update(
			array(
				"table" => "products",
				"data" => array (
					"auction_order" => "`id`"
				),
				"where" => "auction_order=0"
			)
		);
		$options = array(
				"table" => $table,
				"cols"  => $cols,
				"limit" => "$start,$max_rows"
		);
		if(!empty($order_by)) {
			$options["order_by"] = $order_by;
		}
		$rows = sql_select($options);

		$rows1 = sql_select(
			array(
				"table" => "products",
				"cols"  => "id, auction_order",
				"order_by"  => "auction_order",
				"where" => "status='active'"
			)
		);
		$rows[] = $rows1;
	} else if($table == "users") {
		$rows = sql_select(
			array(
				"table" => $table,
				"cols"  => $cols,
				"limit" => "$start,$max_rows"
			)
		);
	} else if($table == "bids") {
		$rows = sql_select_query("SELECT b.*,p.title,u.full_name FROM (`bids` AS b LEFT JOIN products AS p ON b.product_id=p.id) LEFT JOIN users AS u ON b.user_id=u.id LIMIT $start,$max_rows");
	} else if($table == "adz") {
		$rows = sql_select(
			array(
				"table" => "$table a",
				"cols"  => 'a.*, u.full_name',
				"left_join" => 'users u ON a.user_id=u.id',
				"limit" => "$start,$max_rows"
			)
		);
	} else if($table == "coupons") {
		$rows = sql_select(
			array(
				"table" => $table,
				"cols"  => $cols,
				"limit" => "$start,$max_rows"
			)
		);
	}
	echo "var data =" .  json_encode($rows) . ";";
	
// request id = 2 works for products module's auction order change and 
// handle request whenever auction order of any of the product get changed
} else if($request_id == 2) {
	// product1 contains an array whose first index is id of the product and on second is auction order of the product
	$product1 = explode('_', $product_1_id_and_order);
	$product2 = explode('_', $product_2_id_and_order);
		
	$rows = sql_select(
		array(
			"table" => "products",
			"cols"  => "id, auction_order",
			"order_by" => "auction_order"
		)
	);
	
	if($product1[1] > $product2[1]) {
		sql_update(
			array(
				"table" => "products",
				"data"  => array(
					"auction_order" => $product2[1]
				),
				"where" => "id=" . $product1[0]
			)
		);
		$status = true;
		for($i = 0; $i < count($rows); $i++) {
			if(($rows[$i]["id"] != $product2[0] && $status) || $rows[$i]["id"] == $product1[0]) {
				continue;
			} else {
				$status = false;
			}
			sql_update(
				array(
					"table" => "products",
					"data"  => array(
						"auction_order" => $rows[$i]["auction_order"] + 1
					),
					"where" => "id=" . $rows[$i]["id"]
				)
			);
		}
	} else if($product1[1] < $product2[1]) {
		$status = true;
		for($i = 0; $i < count($rows); $i++) {
			if($status && $rows[$i]["id"] != $product1[0]) {
				continue;
			} else {
				$status = false;
			}
			if($rows[$i]["id"] == $product1[0]) {
				continue;
			}
			
			sql_update(
				array(
					"table" => "products",
					"data"  => array(
						"auction_order" => $rows[$i]["auction_order"] - 1
					),
					"where" => "id=" . $rows[$i]["id"]
				)
			);
			if($rows[$i]["id"] == $product2[0]) {
				break;
			}
		}
		sql_update(
			array(
				"table" => "products",
				"data"  => array(
					"auction_order" => $product2[1]
				),
				"where" => "id=" . $product1[0]
			)
		);
	}
// request id 3 server requests for browsing of users for bot
} else if($request_id == 3) {
	if($key == "users") {
		$db = json_decode(file_get_contents("settings.data"), true);
		echo "var data =" .  json_encode($db["users"]) . ";";
	}
// request id 4 server requests for updating min_grabz and cost_per_grab
} else if($request_id == 4) {
	$db = json_decode(file_get_contents("settings.data"), true);
	if($key == "min_grabz") {
		$db["min_grabz"] = (int)$_POST["min_grabz"];
		echo "done";
	} if($key == "grabz_packats") {
		$db["grabz_packats"] = $_POST["grabz_packats"];
		echo "done";
	} else if($key == "cost_per_grab") {
		$db["cost_per_grab"] = $_POST["cost_per_grab"];
		echo "done";
	} else if($key == "default_time") {
		$db["default_time"] = $_POST["default_time"];
		echo "done";
	}
	file_put_contents("settings.data", json_encode($db));
} else if($request_id == '5') {
// this requeset id use for geting winners list

$rows = mysql_query("SELECT r.id , u.full_name as fullName, u.email_address, p.title as title, r.date_added FROM reviews AS r , users AS u , products AS p WHERE r.user_id = u.id AND r.product_id = p.id ");

while($winners = mysql_fetch_array($rows)){
 
 echo "<tr>
 	<td>".$winners['id']."</td>
 	<td>".$winners['fullName']."</td>
 	<td>".$winners['email_address']."</td>
 	<td>".$winners['title']."</td>
 	<td>".$winners['date_added']."</td>
 	</tr>";
}

	}else {
	echo "unknown request";
}

?>