<?php
	require('authenticate.php');
	require('../libs/_config.php');
	require('../libs/_db.php');
	require('../libs/_utils.php');
	$page = "home";
?>

	<?php
		require("includes/header.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
		
		<div class="page-full-width cf">

			<div class="content-module">
			
				<div class="content-module-heading cf">
				
					<h3 class="fl"><?php echo $page; ?> page</h3>
					<span class="fr expand-collapse-text">Click to collapse</span>
					<span class="fr expand-collapse-text initial-expand">Click to expand</span>
				
				</div> <!-- end content-module-heading -->
				
				
				<div class="content-module-main">

					<h2>Welcome to the <?php echo $SITE_TITLE; ?> admin </h2>
					
					<p>This admin panel helps you to add, browse, update and delete.</p>
					
					<p>Click on any of the tab above to go to any module like users, products etc.</p>
					
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					
				</div> <!-- end content-module-main -->
			
			</div> <!-- end content-module -->
		
		</div> <!-- end full-width -->
			
	</div> <!-- end content -->
<?php
	require("includes/footer.php");
?>