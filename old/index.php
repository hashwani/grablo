<?php
	session_start();
	require('libs/_config.php');
	require('libs/_db.php');
	sql_open();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $SITE_TITLE; ?></title>
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
				
				// get product available for auction
				$rows = sql_select(
					array(
						"table" => "products",
						"cols"  => "*",
						"where" => "status!='sold'",
						"order_by" => "auction_order",
						"limit" => "1"
					)
				);
				if(isset($rows[0]['id']) && $rows[0]['status'] != "active") {
					sql_update(
						array(
							"table" => "products",
							"where" => "id=" . $rows[0]['id'],
							"data" => array(
								"status" => "active"
							)
						)
					);
					$users = sql_select(
						array(
							"table" => "users",
							"where" => "status='active'",
							"cols" => "email_address"
						)
					);
					$recievers = array();
					for($i = 0; $i < count($users); $i++) {
						array_push($recievers, $users[$i]["email_address"]);
					}
					send_email(
						array(
							"to" => $recievers,
							"from" => "no-reply@grablo.com",
							"subject" => $SITE_TITLE . ": " . $rows[0]["title"],
							"message" => "<h1>" . $rows[0]["title"] . "</h1>" .
										 "<p>" . $rows[0]["descripion"] . "</p>"
						)
					);
				}
				extract($rows[0]);
			?>
			<ul class="slogans">
				<li>Save up to 95% on brand new products...</li>
				<li>Always FREE traceable worldwide shipping...</li>
				<li>Minimum of $5 credit purchase...</li>
				<li>Only 10c per grab...</li>
				<li>Earn FREE grabz by viewing ads...</li>
				<li>Earn FREE grabz just for inviting others...</li>
			</ul>
			<script type="text/javascript">
				(function($) {
					$(document).ready(
						function() {
							var items = $(".slogans li");
							var total_items = items.length;
							var counter = 1;
							var delay_time = 5000;
							items.hide();
							items.eq(0).fadeIn(300);
							setInterval(showNextItem, delay_time + 1000);
							function showNextItem() {
								items.eq(counter == 0 ? total_items - 1 : counter - 1).hide();
								items.eq(counter).fadeIn(450).delay(delay_time).fadeOut(450);
								counter = (++counter % total_items);
							}
						}
					);
				})(jQuery);
			</script>
			<section id="contentContainer" class="clearfix">
				<div class="clearfix">
					<section id="columnLeft" class="glass">
						<article class="clearfix">
							<h1><?php echo $title; ?></h1>
							<div class="round_corners countdown_wrapper" style="width: 224px;">
								<div class="countdown clearfix" style="padding: 5px 10px;">
									<div id="market_retail_price">$<?php echo number_format($market_retail_price, 2, '.', ','); ?></div>
									<div id="label_market_retail_price">Retail Price (MSRP)</div>
								</div>
							</div>
							<div  id="lastBidderContainer" class="round_corners countdown_wrapper clearfix">
								<div class="countdown clearfix" style="padding: 5px 10px; width: 204px; height: 80px;">
									<p>Last Grbber:</p>
									<figure><img id="user_avatar" src="" alt="" title="" height="50" width="50"></figure>
									<div>
										<b id="last_bidder_name"></b>
									</div>
								</div>
							</div>
							<div class="round_corners countdown_wrapper" style="background-color: #e0e0e0;" id="countdown_wrapper">
								<div class="countdown clearfix">
									<div class="time minutes">
										<h4>47</h4>
										<span class="subtitle">MINS</span>
									</div>
									<div class="time last seconds">
										<h4>23</h4>
										<span class="subtitle">SECS</span>
									</div>
								</div>
							</div>
							
							<a id="grab_it_button" href="#login">Grab It</a>
							
							<div class="round_corners countdown_wrapper" style="background-color: #e0e0e0;" id="countdown_wrapper1">
								<div class="countdown clearfix">
									<div class="time minutes">
										<h4 id="grabz_spent">--</h4>
										<span class="subtitle">SPENT</span>
									</div>
									<div class="time last seconds">
										<h4 id="grabz_remaining">--</h4>
										<span class="subtitle">REMAINING</span>
									</div>
								</div>
							</div>
							<p style="text-align: center; width: 100%; margin-top: 10px;">There is nothing else to pay when you win!</p>
							<a href="show_winner.php?product_id=<?php echo $id; ?>" id="show_winner" class="fancybox_control1"></a>
							<script type="text/javascript">
								var last_bid = {"id": 0};
								<?php
									$db = json_decode(file_get_contents("admin/settings.data"), true);
									echo 'var random_users = '. json_encode($db["users"]) .';';
									echo 'var default_time = '. $db["default_time"] .';';
								?>
								var bot_users = [];
								$.each(random_users, function($key, $val) {
									bot_users.push($val);
								});
								var ticker = 0;
								var reset_time = Math.floor((Math.random() * (default_time - 1)) + 1);
								var seed1 = 0, seed2 = seed1;
								var status1 = false, is_winner = status1;
								var uid = <?php echo (empty($user["id"]) ? 0 : $user["id"]); ?>;
								
								$(document).ready(
									function() {
										get_last_user();
										
										// if user id is 0 then ask for sign in
										if(uid == 0) {
											// show fancy box when 'grab it' button clicked
											$("#grab_it_button").fancybox();
										} else {
											$("#grab_it_button").unbind().click(
												function() {
													$.post(
														"request.php",
														{
															"product_id" : <?php echo $id; ?>,
															"request_id" : 5
														},
														function(data) {
															data = parseInt(data);
															if(data == 0) {
																alert("Please login to Grab");
															} else if(data == 1) {
																get_last_user();
																//alert("You have successfully grabbed");
															} else if(data == 2) {
																if(window.confrim("You doesn't have sufficient grabz to Grab this product.\n Click to buy grabz")) {
																	window.location = "buy_grabz.php";
																} else {
																	
																}
															}
														}
													);
													return false;
												}
											);
										}										
										
										// show fancy box when a user wins
										$(".fancybox_control1").fancybox({
											'width' : '40%',
											'height' : '60%',
											'autoScale' : false,
											'transitionIn' : 'none',
											'transitionOut' : 'none',
											'type' : 'iframe',
											'onClosed' : function(){
												get_last_user();
											}
										});
										ticker = default_time;
										start_countdown();
										seed2 = setInterval(get_last_user, 5000);
									}
								);
								function get_last_user() {
									$.post(
										"request.php",
										{
											"table" : "bids",
											"product_id" : <?php echo $id; ?>,
											"request_id" : 1
										},
										function(data) {
											eval(data);
											bid1 = last_bid1[1];
											$("#grabz_spent").text(last_bid1[0].total_grabz);
											$("#grabz_remaining").text(last_bid1[0].grabz);
											if(last_bid.id != bid1.id && !status) {
												clearTimeout(seed1);
												ticker = default_time;
												$("#countdown_wrapper .seconds h4").css("color", "black");
												$("#countdown_wrapper .minutes h4").css("color", "black");
												reset_time = Math.floor((Math.random() * (default_time - 1)) + 1);
												$("#time_limit").text(reset_time);
												start_countdown();
												status1 = true;
												last_bid = bid1;
												is_winner = last_bid1[0].winner;
												$("#last_bidder_name").text(last_bid.full_name);
												$("#user_avatar").attr({"src" : "<?php echo $MEDIA_DIR; ?>profile_pics/" + last_bid.profile_pic});
											}
										}
									);
								}
								function start_countdown() {
									if(ticker == reset_time && !is_winner) {
										status1 = false;
										ticker = default_time;
										reset_time = Math.floor((Math.random() * (default_time - 1)) + 1);
										$("#countdown_wrapper .seconds h4").css("color", "black");
										$("#countdown_wrapper .minutes h4").css("color", "black");
										rand_user = bot_users[Math.floor(Math.random() * bot_users.length)];
										$("#last_bidder_name").text(rand_user.full_name);
										$("#user_avatar").attr({"src" : "<?php echo $MEDIA_DIR; ?>profile_pics/" + rand_user.profile_pic});
									}
									ticker--;
									if(ticker < 10) {
										$("#countdown_wrapper .seconds h4").css("color", "red");
										$("#countdown_wrapper .minutes h4").css("color", "red");
									}
									if(is_winner) {
										$("#grab_it_button").unbind().click(function(){return false;}).text("Auction Over").css({"color":"red", "background":"#f0f0f0", "border-width":"0"});										
									}
									if(ticker < 0 && is_winner) {
										clearTimeout(seed1);
										clearInterval(seed2);
										$("#show_winner").trigger("click");
									} else if(ticker < 0) {
										reset_time = Math.floor((Math.random() * (default_time - 1)) + 1);
										$("#countdown_wrapper .seconds h4").css("color", "black");
										$("#countdown_wrapper .minutes h4").css("color", "black");
										$("#time_limit").text(reset_time);
									} else {
										var time = secs_to_time_str(ticker);
										$("#countdown_wrapper .minutes h4").text(time[0]);
										$("#countdown_wrapper .seconds h4").text(time[1]);
										seed1 = setTimeout(start_countdown, 1000);
									}
								}
								
								function secs_to_time_str(seconds) {
									var sec_numb = parseInt(seconds);
									var minutes  = Math.floor(sec_numb / 60);
									var seconds  = sec_numb - (minutes * 60);
									return [minutes, seconds];
								}
							</script>
						</article>
					</section>
					<section id="columnRight" class="glass">
						<article>
							<div id="imageWrapper">
								<img id="large_image" src="" alt=""  style="width: 588px; max-height: 450px;"/>
								<?php
									$images = explode(',', $images);
								?>
								<div id="listWrapper" style="margin-left: -<?php echo count($images) * 32.5;?>px">
									<ul class="clearfix">
										<?php
											for($i = 0; $i < count($images); $i++) {
												$arr = explode('.', $images[$i]);
										?>
												<li>
													<a href="#">
														<img src="<?php echo $HOME; ?>thumbs/<?php echo trim($arr[0]) . "0." . $arr[1]; ?>" alt="<?php echo $title; ?>" data="<?php echo $HOME; ?>thumbs/<?php echo trim($arr[0]) . "1." . $arr[1]; ?>" />
														<div class="innerBorder"></div>
													</a>
												</li>
										<?php
											}
										?>
									</ul>
								</div>
							</div>
							<script type="text/javascript">
								//var active = "";
								(function($){
									$(window).load(
										function() {
											var active = $("#listWrapper ul li").eq(0);
											active.addClass("current");
											$("#imageWrapper #large_image").attr("src", active.find("img").attr("data"));
											$("#listWrapper ul li").click(
												function() {
													active.removeClass("current");
													active = $(this);
													active.addClass("current");
													$("#imageWrapper #large_image").attr("src", $(this).find("img").attr("data"));
													return false;
												}
											);
										}
									);
								})(jQuery);
							</script>
						</article>
					</section>
				</div>
				<section id="productDescription">
					<div id="leftColumn">
						<div class="box">
							<ul id="tabMenu" class="clearfix">
								<li class="tab" id="tab1"><a href="#">Description</a></li>
								<li class="tab" id="tab2"><a href="#">Features</a></li>
								<li class="tab" id="tab3"><a href="#">Technical Info</a></li>
								<li class="tab" id="tab4"><a href="#">Top Comments</a></li>
								<li style="float: right;">
									<div class="social clearfix">
										<div class="header"><h1>Share this auction with friends and get FREE grabz:</h1></div>
										<ul class="clearfix">
											<li class="facebook">
												<a class="share" href="http://www.facebook.com/sharer.php?u=http://www.woot.com/offers/incase-sonic-over-ear-headphones-2&amp;src=sp" title="share this on Facebook" target="_blank"></a>
											</li>
											<li class="twitter">
												<a class="share" href="http://twitter.com/share?url=http://www.woot.com/offers/incase-sonic-over-ear-headphones-2&amp;text=Incase Sonic Over Ear Headphones - Black/Green (New) for $59.99&amp;via=woot" title="share this on Twitter" target="_blank"></a>
											</li>
										</ul>
									</div>	
								</li>
							</ul>
							
							<div class="clearfix"></div>
							<div class="boxBody">
								<div class="panel tab1">
									<h3>Description</h3>
									<pre style="font-family: 'Museo Sans',Helvetica,'Helvetica Neue',Arial,sans-serif; padding: 10px 0;">
										<?php
											echo $description;
										?>
									</pre>
								</div>
								<div class="panel tab2">
									<h3>Features</h3>
									<pre style="font-family: 'Museo Sans',Helvetica,'Helvetica Neue',Arial,sans-serif; padding: 10px 0;">
										<?php
											echo $features;
										?>
									</pre>
								</div>
								<div class="panel tab3">
									<h3>Technical Info</h3>
									<pre style="font-family: 'Museo Sans',Helvetica,'Helvetica Neue',Arial,sans-serif; padding: 10px 0;">
										<?php
											echo $technical_info;
										?>
									</pre>
								</div>
								<div class="panel tab4">
									<h3>Top Comments</h3>
									<pre style="font-family: 'Museo Sans',Helvetica,'Helvetica Neue',Arial,sans-serif; padding: 10px 0;">
										<?php
											echo $description;
										?>
									</pre>
								</div>
							</div>
							<script type="text/javascript">
								var active = "";
								(function($){
									$(window).load(
										function() {
											active = $("#tabMenu li.tab").eq(0).addClass("current");
											$("." + active.attr("id")).show();
											
											$("#tabMenu li.tab").click(
												function() {
													var temp = $(this);
													if(temp.attr("id") == active.attr("id")) {
														return false;
													}
													temp.addClass("current");
													
													active.removeClass("current");
													
													$("." + temp.attr("id")).show();
													$("." + active.attr("id")).hide();
													active = temp;
													return false;
												}
											);
										}
									);
								})(jQuery);
							</script>
						</div>
					</div>
				</section>
			</section>
			<?php
				// site footer
				require("includes/footer.php");
			?>
		</div>
	</body>
</html>