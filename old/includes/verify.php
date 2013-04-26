<?php
	require('../libs/_config.php');
	require('../libs/_db.php');
	require('../libs/_utils.php');
	sql_open();
	if(isset($_GET['vCode']) && $_GET['vCode'] != '') {
	
		$vCode = $_GET['vCode'];
		
		$vQ = sql_select(
			array(
				"table" => "user_verifications",
				"cols"  => "*",
				"where" => "verification_code='$vCode'"
			)
		);
		if(count($vQ) > 0) {
			if($vQ[0]["status"] == "verified") {
				echo "This email address already have been verified.";
			} else if($vQ[0]["verification_code"] == $vCode) {
				$email = $vQ[0]["email_address"];
				$rows_affected = sql_update(
					array(
						"table" => "user_verifications",
						"data"  => array(
							"status" => "verified"
						),
						"where" => "id=" . $vQ[0]["id"]
					)
				);
				if($rows_affected > 0) {
					header('location: ' . $HOME . 'register.php?id='. encode_id_for_url($vQ[0]["id"]));
				} else {
					echo "Unable to verify you. Try again.";
				}
			} else {
				echo "Your verification code is not valid. Try again.";
			}
		} else {
			echo "Your verification code is not valid. Try again.";
		}
	} else {
		echo "Your verification code is not valid. Try again.";
	}
?>