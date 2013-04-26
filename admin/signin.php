<?php
	session_start();
	require('../libs/_config.php');
	require('../libs/_db.php');
	sql_open();
	
	$error = "";
	$success = "";

	if(isset($_POST["submit"])) {
		extract($_POST);
		$users = sql_select(
			array(
				"table" => "users",
				"cols"  => "*",
				"where" => "email_address='$email_address' AND password='$password' AND type='admin'"
			)
		);
		
		if(!empty($users[0])) {
			$_SESSION["user"] = $users[0];
			header("location: index.php");
		} else {
			$error = "Please provide valid email and password???";
		}
	} else if(isset($_GET['signout'])) {
		unset($_SESSION["user"]);
		$success = "You are successfully signed out!!!";
	}
?>
<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Sign in - <?php echo $SITE_TITLE; ?> Admin</title>
	
	<!-- Stylesheets -->
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">

	<!-- Optimize for mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>  
</head>
<body>

	<!-- TOP BAR -->
	<div id="top-bar">
		
		<div class="page-full-width">
		
			<a href="<?php echo $HOME; ?>" class="round button dark ic-left-arrow image-left ">Return to website</a>

		</div> <!-- end full-width -->	
	
	</div> <!-- end top-bar -->
	
	
	
	<!-- HEADER -->
	<div id="header">
		
		<div class="page-full-width cf">
	
			<div id="login-intro" class="fl">
			
				<h1>Sign in to <?php echo $SITE_TITLE; ?> admin</h1>
				<h5>Enter your credentials below</h5>
			
			</div> <!-- login-intro -->
			
			<!-- Change this image to your own company's logo -->
			<!-- The logo will automatically be resized to 39px height. -->
			<a href="#" id="company-branding" class="fr"><img src="images/company-logo.png" alt="Blue Hosting" /></a>
			
		</div> <!-- end full-width -->	

	</div> <!-- end header -->
	
	
	
	<!-- MAIN CONTENT -->
	<div id="content">
	
		<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" id="login-form">
			<?php
				if($error != "") {
			?>
					<div class="error-box round"><?php echo $error; ?></div>
			<?php
				} else if($success != "") {
			?>
					<div class="confirmation-box round"><?php echo $success; ?></div>
			<?php
				}
			?>
			<fieldset>

				<p>
					<label for="email_address">Email</label>
					<input type="text" id="email_address" name="email_address" class="round full-width-input" autofocus />
				</p>

				<p>
					<label for="password">password</label>
					<input type="password" id="password" name="password" class="round full-width-input" />
				</p>
				
				<p>I've <a href="#">forgotten my password</a>.</p>
				
				<input type="submit" id="submit" name="submit" class="button round blue image-right ic-right-arrow" value="LOG IN" />

			</fieldset>

		</form>
		
	</div> <!-- end content -->
<?php
	require("includes/footer.php");
?>