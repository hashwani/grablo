<footer>
	<div class="line"></div>
	<div id="footerLinks" class="clearfix">
		<div class="col">
			<h3>About Grablo</h3>
			<ul>
				<li><a href="">About Us</a></li>
				<li><a href="">Frequently Asked Questions</a></li>
				<li><a href="">How It Works</a></li>
				<li><a href="">Earn Grabz</a></li>
				<li><a href="">Contact Us</a></li>
			</ul>
		</div>
		<div class="col">
			<h3>Legal Stuff</h3>
			<ul>
				<li><a href="">Privacy Policy</a></li>
				<li><a href="">Terms &amp; Conditions</a></li>
			</ul>
		</div>
		<div class="col">
			<h3>Latest News</h3>
			<ul>
				<li><a href="">I am news</a></li>
				<li><a href="">I am new news</a></li>
				<li><a href="">Am I new news</a></li>
				<li><a href="">New news am I?</a></li>
			</ul>
		</div>
		<div class="col">
			<h3>Follow Us</h3>
			<ul>
				<li><a href="">Privacy Policy</a></li>
				<li><a href="">Terms &amp; Conditions</a></li>
			</ul>
		</div>
	</div>
	<div id="footerBottom" class="clearfix">
		<div id="copyright">Copyright &copy; <?php echo date('Y'); ?> <span><?php echo $SITE_TITLE; ?>.</span><br /> All Rights Reserved.</div>
		<div id="contact">
			We accept paymens by: <br />
			<img src="<?php echo $MEDIA_DIR; ?>/images/payment-methods.png" />
		</div>
	</div>
</footer>
<script type="text/javascript">
	$(document).ready(function() {
		$(".signup").fancybox();
		$(".signin").fancybox();
	});
	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function sendMail() {
		if($('#register-email').val() == "" || $('#register-email').val() == null || !validateEmail($('#register-email').val())) {
			$('#login').find('.error').slideDown();
		} else {
			$('#loading').show();
			$.post('<?php echo $HOME; ?>includes/registerVerification.php', {email: $('#register-email').val()}, function(data) {
				if(data != ''){
					$('#loading').hide();
					$('.login').html(data);
				}
			});
		}
	}
</script>
<div style="display:none;">
	<div id="register" class="login">
		<form action="" id="regiter-form">
			<h2 style="text-align:left">Enter Email Address To Register:</h2>
			<input type="text" id="register-email" />
			<a class="primary-button" href="javascript:sendMail()">Submit</a>
			<h4 class="error"> Please Enter Valid Email Address!</h4>
			<h3 style="text-align:left"> Email Link Will Send To your address..Follow this email</h3>
		</form>
		<img src="<?php echo $MEDIA_DIR; ?>images/loading.gif" id="loading" alt="loading"/>
	</div>
</div>
<div style="display:none;">
	<div id="login" class="login" style="margin: 0 10px;">
		<h1 style="font-family: 'Museo Sans', Helvetica, 'Helvetica Neue', Arial, sans-serif; font-size: 18px; margin: 5px 0; text-align: left; border-bottom: 1px solid #f0f0f0; padding-bottom: 3px;">Sign in:</h1>
		<form action="signin.php" method="post" id="regiter-form" style="text-align: left; margin: 10px 0;">
			<h2 style="text-align:left">Email</h2>
			<input type="text" id="email_address" name="email_address" />
			<h2 style="text-align:left">Password</h2>
			<input type="password" id="password" name="password" /> <br />
			<input type="submit" id="submit" name="submit" class="primary-button" value="Sing In" style="float: left;" />
			<div style="float: left; margin: 10px 15px; font-size: 14px; font-weight: bold;">or</div>
			<div class="fb-login-button" style="float: left; margin: 10px 0;">Login with Facebook</div>
			<h4 class="error">Please provide valid email address and password.</h4>
		</form>
		<img src="images/loading.gif" id="loading" alt="loading"/>
	</div>
</div>
<div id="fb-root"></div>
<script type="text/javascript">
	window.fbAsyncInit = function() {
	  FB.init({
		appId      : '409531155764491', // App ID
		channelUrl : '//jamal.com/grablo/channel.html', // Channel File
		status     : true, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		xfbml      : true  // parse XFBML
	  });
	};
	// Load the SDK Asynchronously
	(function(d){
	   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	   if (d.getElementById(id)) {return;}
	   js = d.createElement('script'); js.id = id; js.async = true;
	   js.src = "//connect.facebook.net/en_US/all.js";
	   ref.parentNode.insertBefore(js, ref);
	 }(document));
	 (function($){
		$(document).ready(function(){
			$(".fb-login-button").unbind();
			$(".fb-login-button").click(function() {
				FB.getLoginStatus(function(response) {
				  if (response.status === 'connected') {
					alert(response.authResponse.userID);
					// the user is logged in and has authenticated your
					// app, and response.authResponse supplies
					// the user's ID, a valid access token, a signed
					// request, and the time the access token 
					// and signed request each expire
					// $.post(
						// "request.php",
						// {
							// "request_id" : 1,
							// "fb_id" : response.authResponse.userID
						// },
						// function(data) {
							// window.location = window.location;
						// }
					// );
					var uid = response.authResponse.userID;
					var accessToken = response.authResponse.accessToken;
				  } else if (response.status === 'not_authorized') {
					alert("Please authenticate grablo app to login using facebook.");
					// the user is logged in to Facebook, 
					// but has not authenticated your app
				  } else {
					// the user isn't logged in to Facebook.
					FB.login(function(response) {
					   if (response.authResponse) {
						 //console.log('Welcome!  Fetching your information.... ');
						 FB.api('/me', function(response) {
							$.post(
								"request.php",
								{
									"request_id" : 1,
									"fb_id" : response.authResponse.userID
								},
								function(data) {
									window.location = window.location;
								}
							);
						    alert('Good to see you, ' + response.name + '. ' + response.email + '. ' + response.id);
						 });
					   } else {
						 alert('User cancelled login or did not fully authorize.');
					   }
					}, {scope: 'email'});
				  }
				 });
				
				return false;
			});
		});
	 })(jQuery);
</script>