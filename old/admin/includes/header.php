<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>
		<?php
			echo ucfirst("$page");
			echo " - ";
			echo $SITE_TITLE; 
		?> Admin
		</title>
	
	<!-- Stylesheets -->
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' />
	<link rel="stylesheet" href="css/style.css" />
	<link href="fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" media="screen">

	<script src="js/jquery164.js" type="text/javascript"></script>
	<script src="fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript" ></script>
	<script src="js/validate.js" type="text/javascript"></script>
	<script src="js/script.js"></script>  
</head>
<body>
	<!-- TOP BAR -->
	<div id="top-bar">
		
		<div class="page-full-width cf">

			<ul id="nav" class="fl">

				<li class="v-sep"><a href="<?php echo $HOME; ?>" class="round button dark ic-left-arrow image-left">Go to website</a></li>
				<li class="v-sep"><a href="#" class="round button dark menu-user image-left">Logged in as <strong><?php echo $_SESSION["user"]["full_name"]; ?></strong></a></li>			
				<li><a href="signin.php?signout=1" class="round button dark menu-logoff image-left">Log out</a></li>
				
			</ul> <!-- end nav -->

		</div> <!-- end full-width -->	

	</div> <!-- end top-bar -->



	<!-- HEADER -->
	<div id="header-with-tabs">
		
		<div class="page-full-width cf">

			<ul id="tabs" class="fl">
				<li><a href="index.php" class="dashboard-tab <?php echo ($page == "home" ? "active-tab" : ""); ?>">Home</a></li>
				<li><a href="products.php" class="dashboard-tab <?php echo ($page == "products" ? "active-tab" : ""); ?>">Products</a></li>
				<li><a href="users.php" class="dashboard-tab <?php echo ($page == "users" ? "active-tab" : ""); ?>">Users</a></li>
				<li><a href="bids.php" class="dashboard-tab <?php echo ($page == "bids" ? "active-tab" : ""); ?>">Bids</a></li>
				<li><a href="settings.php" class="dashboard-tab <?php echo ($page == "settings" ? "active-tab" : ""); ?>">Settings</a></li>
			</ul> <!-- end tabs -->
			
			<!-- Change this image to your own company's logo -->
			<!-- The logo will automatically be resized to 30px height. -->
			<a href="#" id="company-branding-small" class="fr"><img src="images/company-logo.png" alt="Blue Hosting" /></a>
			
		</div> <!-- end full-width -->	

	</div> <!-- end header -->
