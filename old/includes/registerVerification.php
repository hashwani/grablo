<?php
	require('../libs/_config.php');
	require('../libs/_db.php');
	sql_open();
	
	if(isset($_POST['email'])){
		$email = $_POST['email'];
		$vCode = md5(uniqid(rand(), true));
		$vQ = sql_select(
			array(
				"table" => "user_verifications",
				"cols"  => "*",
				"where" => "email_address='$email'"
			)
		);
		$rQ = sql_select(
			array(
				"table" => "users",
				"cols"  => "*",
				"where" => "email_address='$email'"
			)
		);
		if(count($vQ) > 0){
			echo '<span style="color: red;">A verficaion code already sent to '. $emaail .'<span>';
		} else if(count($rQ) > 0){
			echo '<span style="color: red;">An user already exist with this email address.<span>';
		} else {
			$insertQ = sql_insert(
				array(
					"table" => "user_verifications",
					"data" => array(
						"email_address" => $email,
						"verification_code" => $vCode
					)
				)
			);
			if(is_int($insertQ) && $insertQ > 0){
				$mail_to = $email;
				$mail_sub = 'Grablo Email Verification';
				$mail_msg = '<h2>Welcome to <span style="color: #9999ff;">Grablo.com</span>!</h2><hr />' .
							'We have received your request to join grablo.com from ' . $email . '.' .
							' To verify your email address, please click on the link below: <br /><br />' .
							'<a href="'. $HOME .'includes/verify.php?vCode='.$vCode.'"><b>'. $HOME .'includes/verify.php?vCode='.$vCode.'</b></a> <br /><br />' .
							'If clicking the link above does not work, copy and paste the URL in a new browser window instead. <br /><br />' .
							'If you have received this mail in error, you do not need to take any action. If you do not click on the link, the address will not be added to your account.<br /><br /><br />' .
							'Sincerely,<br />' .
							'The Grablo Accounts Team<br /><br /><br /><br />' .
							'Note: This email address cannot accept replies.';
				$mail_headers = "MIME-Version: 1.0" . "\r\n";
				$mail_headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
				$mail_headers .= 'From: <no-reply@grablo.com>' . "\r\n";
				$sent_mail = mail($mail_to, $mail_sub, $mail_msg, $mail_headers);
				if($sent_mail) {
					echo '<span style="text-align: left; display: block; font-size: 16px; padding: 5px;">' .
						 '<h2 style="font-size:22px; margin-bottom: 5px; color: #99ff99;">Congratulations!!!</h2>' .
						 'An email have been sent to '. $email .'. Please check your email to verify your email address.' .
						 '</span>';
				 } else {
					echo '<span style="text-align: left; display: block; font-size: 16px; padding: 5px;">' .
						 '<h2 style="font-size:22px; margin-bottom: 5px; color: #ff9999;">Sorry???</h2>' .
						 'Unable to send email try again.' .
						 '</span>';
				}
			} else {
				echo '<span style="text-align: left; display: block; font-size: 16px; padding: 5px;">' .
						 '<h2 style="font-size:22px; margin-bottom: 5px; color: #ff9999;">Sorry???</h2>' .
						 'Unable to send email try again.' .
						 '</span>';
			}
		}
	} else {
		echo '<span style="text-align: left; display: block; font-size: 16px; padding: 5px;">' .
			 '<h2 style="font-size:22px; margin-bottom: 5px; color: #ff9999;">Sorry???</h2>' .
			 'Unable to send email try again.' .
			 '</span>';
	}
?>