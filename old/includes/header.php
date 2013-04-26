<header class="clearfix">
	<nav>
		<ul class="clearfix">
			<li><a href="<?php echo $HOME; ?>" class="logo"><?php echo $SITE_TITLE; ?></a></li>
			<li style="margin-left: 5px; padding-left: 16px; background: url('media/images/how-it-works.png') no-repeat scroll 2px 7px transparent;"><a href="" class="tab">How it works?</a></li>
			<li style="margin-left: 5px; padding-left: 14px; background: url('media/images/faq.png') no-repeat scroll 2px 7px transparent;"><a href="" class="tab">FAQ</a></li>
			<li style="margin-left: 5px; padding-left: 17px; background: url('media/images/earngrabz.png') no-repeat scroll 2px 7px transparent;"><a href="earn_grabz.php" class="tab">Earn Grabz</a></li>
			<li style="margin-left: 5px; padding-left: 17px; background: url('media/images/buycredits.png') no-repeat scroll 2px 7px transparent;"><a href="buy_credits.php" class="tab">Buy Credits</a></li>
			<li class="right">				
				<p class="account">
					<?php
						if(isset($_SESSION["user"])) {
							$user = $_SESSION["user"];
					?>
							Welcome <?php echo $user["full_name"]; ?> | <a href="signout.php">Sign out</a>
					<?php
						} else {
					?>	
							<a href="#register" class="signup">Register</a> | <a href="#login" id="signin" class="signin">Sign in</a>
					<?php
						}
					?>
						
				</p>
				<p><a href="<?php echo $HOME; ?>dashboard.php">Dashboard</a></p>
			</li>
		</ul>
	</nav>
</header>