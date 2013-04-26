<?php
	session_start();
	require('libs/_config.php');
	require('libs/_db.php');
	sql_open();
	if(empty($_SESSION["user"])) {
		exit();
	}
	$user = $_SESSION["user"];
	
	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
		$req .= "&$key=$value";
	}

	// post back to PayPal system to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

	$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
	
	$fh = fopen("data.txt", 'w');
	fwrite($fh, json_encode($_POST) . "\n");
	if (!$fp) {
		fwrite($fh, "Sockets error: Unable to connect to paypal for transection validation\n");
	} else {
		fputs ($fp, $header . $req);
		while (!feof($fp)) {
			$res = fgets ($fp, 1024);
			if (strcmp ($res, "VERIFIED") == 0) {
				fwrite($fh, "$res\n");
				
				$db = json_decode(file_get_contents("admin/settings.data"), true);
				sql_update(
					array(
						"table" => "users",
						"data" => array("grabz" => (number_format($_POST["mc_gross"], 2, '.', '') / $db["cost_per_grab"]),
						"where" => "id=" . $user["id"]
					)
				);
				break;
			}else if (strcmp ($res, "INVALID") == 0) {
				fwrite($fh, "$res\n");
				break;
			}
		}
		fclose ($fp);
	}
	
	fclose($fh);
	
?>