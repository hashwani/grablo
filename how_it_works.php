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
		<title>How <?php echo ucwords($SITE_TITLE); ?> Works - <?php echo $SITE_TITLE; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta content="<?php echo $SITE_KEYWORDS; ?>" name="keywords" />
		<meta content="<?php echo $SITE_DESCRIPTION; ?>" name="description" />
		<link href="<?php echo $MEDIA_DIR; ?>style/style.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $MEDIA_DIR; ?>fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" media="screen">
		<script src="<?php echo $MEDIA_DIR; ?>js/IE9.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/jquery164.js" type="text/javascript"></script>
		<script src="<?php echo $MEDIA_DIR; ?>fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript" ></script>
		<script src="<?php echo $MEDIA_DIR; ?>js/validate.js" type="text/javascript"></script>
		<style type="text/css">
			#how_it_works p {
				font-size: 14px;
				line-hieght: 18px;
				margin-bottom: 15px;
			}
		</style>
	</head>
	<body>
		<div id="container">
			<?php
				// site header
				require("includes/header.php");
			?>
			<section id="contentContainer" class="clearfix">
				<section id="how_it_works" class="glass" style="width: 960px; padding: 0px; text-align: left; display: inline-block;">
					<article class="clearfix" style="background-color: #ffffff; margin: 5px; padding: 40px;">   
						<h2 class="page-title">How Grablo Works</h2>
						<p>We Give Books is a free website that enables anyone with access to the Internet to put books in the hands of children who don't have them, simply by reading online.</p>
						<p>Simply choose the charity you want to read for and then select the books you want to read. For each book you read online, we donate a book to a leading literacy group on your behalf.</p>
						<p>The more you read, the more we give.</p>
						<p>We Give Books is a free website that enables anyone with access to the Internet to put books in the hands of children who don't have them, simply by reading online.</p>
						<p>Simply choose the charity you want to read for and then select the books you want to read. For each book you read online, we donate a book to a leading literacy group on your behalf.</p>
						<p>The more you read, the more we give.</p>
						<p>We Give Books is a free website that enables anyone with access to the Internet to put books in the hands of children who don't have them, simply by reading online.</p>
						<p>Simply choose the charity you want to read for and then select the books you want to read. For each book you read online, we donate a book to a leading literacy group on your behalf.</p>
						<p>The more you read, the more we give.</p>
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