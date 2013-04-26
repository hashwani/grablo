<?php
	session_start();
	require('libs/_config.php');
	require('libs/_db.php');
	sql_open();
	
	if(empty($_SESSION['user'])) {
		header("location: $HOME/signin.php" );
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Eearn Grabs by clicking on Adz - <?php echo $SITE_TITLE; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta content="<?php echo $SITE_KEYWORDS; ?>" name="keywords" />
		<meta content="<?php echo $SITE_DESCRIPTION; ?>" name="description" />
		<link href="<?php echo $MEDIA_DIR; ?>style/style.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $MEDIA_DIR; ?>fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" media="screen">
		<script src="<?php echo $MEDIA_DIR; ?>js/IE9.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/jquery164.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript" ></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/validate.js" type="text/javascript"></script>
	</head>
	<body>
		<div id="container">
			<?php
				// site header
				require("includes/header.php");
			?>
			<section id="contentContainer" class="clearfix">
				<section class="glass" style="width: 960px; padding: 0px; text-align: left; display: inline-block;">
					<article class="clearfix" style="background-color: #ffffff; margin: 5px; padding: 40px;">   
						<?php
							// get product available for auction
							$rows = sql_select(
								array(
									"table" => "adz a",
									"cols"  => "a.*, u.full_name",
									"where" => "a.status='active'",
									"left_join" => "users u ON a.user_id=u.id",
									"order_by" => "a.title",
									"limit" => "30"
								)
							);
						?>
						<h2 class="page-title">Earn Grabz - Click on the adz below and get free Grabz!!!</h2>
						<?php
							for($i = 0; $i < count($rows); $i++) {
								$users_clicked = explode(',', $rows[$i]["users_clicked"]);
						?>
								<div class="ad_wrapper<?php echo (count($users_clicked) > 0 && in_array($_SESSION["user"]["id"], $users_clicked) ? " deactive" : ""); ?>">
									<h4><a href="<?php echo $HOME . "request.php?request_id=2&ad_id=" . $rows[$i]["id"]; ?>"><?php echo $rows[$i]["title"]; ?></a></h4>
									<p>Posted By: <b><?php echo $rows[$i]["full_name"]; ?></b> on <?php echo date("d M, Y", strtotime($rows[$i]["date_added"])); ?></p>
									<p><?php echo $rows[$i]["description"]; ?></p>
								</div>
						<?php
							}
						?>
					</article>
				</section>
			</section>
			<?php
				// site footer
				require("includes/footer.php");
			?>
		</div>
	</body>
</html>