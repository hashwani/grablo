<html>
	<?php
		extract($_GET);
	?>
	<body onload="document.getElementById('paypal_form').submit()">
		<form id="paypal_form" method="post" name="contributiontracking" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
			<input type="hidden" name="business" value="<?php echo $business; ?>" />
			<input type="hidden" name="item_number" value="<?php echo $item_number; ?>" />
			<input type="hidden" name="no_note" value="0" />
			<input type="hidden" name="return" value="<?php echo $return; ?>" />
			<input type="hidden" name="rm" value="2" />
			<input type="hidden" name="currency_code" value="USD" />
			<input type="hidden" name="cmd" value="_xclick" />
			<input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
			<input type="hidden" name="item_name" value="<?php echo $item_name; ?>" />
			<input type="hidden" name="amount" value="<?php echo $amount; ?>" />
			<input type="submit" value="Continue" />
		</form>
	</body>
</html>