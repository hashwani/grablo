<?php
	session_start();
	if(isset($_SESSION["user"])) {
		header("location: " . $HOME);
	}
	require('libs/_config.php');
	require('libs/_db.php');
	require('libs/_utils.php');
	sql_open();
	
	$error = "";
	
	if(!empty($_POST["submit"])) {
		extract($_POST);
		$redirect = empty($redirect) ? $HOME : $redirect;
		$users = sql_select(
			array(
				"table" => "users",
				"cols"  => "*",
				"where" => "email_address='$email_address' AND password='$password'"
			)
		);
		if(!empty($users[0])) {
			$_SESSION["user"] = $users[0];
			header("location: " . $redirect);
		} else {
			$error = "Please provide valid email address and password";
		}
	}
	$redirect = $HOME;
	if(isset($_REQUEST["redirect"])) {
		$redirect = $HOME . $_REQUEST["redirect"];
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Sing In - <?php echo $SITE_TITLE; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta content="<?php echo $SITE_KEYWORDS; ?>" name="keywords" />
		<meta content="<?php echo $SITE_DESCRIPTION; ?>" name="description" />
		<link href="<?php echo $MEDIA_DIR; ?>style/style.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $MEDIA_DIR; ?>fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" media="screen">
		<script src="<?php echo $MEDIA_DIR; ?>js/IE9.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/jquery164.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript" ></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/validate.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#regForm").validate();
			});
		</script>
	</head>
	<body>
		<div id="container">
			<?php
				if($HOME == $redirect) {
					// site header
					require("includes/header.php");
				}
			?>
			<section id="contentContainer" class="clearfix">
				<div id="registration_wrapper" class="clearfix">					
					<div id="registration_form">
						<h1>Sign in to your <?php echo $SITE_TITLE; ?> Account</h1>
						<p>&nbsp;</p>
						<?php
							if(isset($error)) {
								echo '<div style="color: red; padding: 5px;">' . $error . '</div>';
							}
						?>
						<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" id="regForm">
							<input type="hidden" name="redirect" id="redirect" value="<?php echo $redirect; ?>" />
							<h2>Email:</h2>
							<input type="text" id="email_address" name="email_address" class="required email" />
							
							<h2>Password:</h2>
							<input type="password" id="password" name="password" class="required" minlength="6" />
							
							<p>&nbsp;</p>
							<input type="submit" class="required primary-button" name="submit" value="Sign In" />
						</form>
					</div>
					<div id="form_right">
						<h1>Don't have a <?php echo $SITE_TITLE; ?> account?</h1>
						<p><a href="#register" class="signup">Sign me up!</a></p>
					</div>
				</div>
			</section>
			<?php
				if($HOME == $redirect) {
					// site footer
					require("includes/footer.php");
				}
			?>
		</div>
	</body>
</html>