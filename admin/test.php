<?php

$arr = array(
	"users" => array(
		1 => array(
			"full_name" => "Jamal",
			"profile_pic" => "user_234.jpg",
			"status" => "active",
			"date_added" => date("h:m:s d/m/Y")
		),
		2 => array(
			"full_name" => "Riaz",
			"profile_pic" => "user_564.jpg",
			"status" => "active",
			"date_added" => date("h:m:s d/m/Y")
		)
	),
	"bid_packs" => array()
);

echo file_put_contents("settings.data", json_encode($arr));

?>