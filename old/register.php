<?php
	session_start();
	if(isset($_SESSION["user"])) {
		header("location: " . $HOME);
	}
	require('libs/_config.php');
	require('libs/_db.php');
	require('libs/_utils.php');
	sql_open();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Register - <?php echo $SITE_TITLE; ?></title>
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
			$(document).ready(function() {
				$("#regForm").validate();
			});
		</script>
	</head>
	<body>
		<div id="container">
			<?php
				// site header
				require("includes/header.php");
			?>
			<section id="contentContainer" class="clearfix">
				<?php
					if(!empty($_POST["submit"])) {
						if(
							isset($_FILES["profile_pic"]) &&
							is_valid_image($_FILES["profile_pic"]["name"]) &&
							($profile_pic = generate_unique_file_name("pic_",  $_FILES["profile_pic"]["name"])) &&
							upload_file("media/profile_pics", $_FILES["profile_pic"], $profile_pic)
						) {
							extract($_POST);
							$id = sql_insert(
								array(
									"table" => "users",
									"data"  => array(
										"id" => $id,
										"full_name" => $full_name,
										"email_address" => $email_address,
										"password" => $password,
										"address_1" => $address_1,
										"address_2" => $address_2,
										"city" => $city,
										"state" => $state,
										"country" => $country,
										"zip_code" => $zip_code,
										"telephone" => $telephone,
										"profile_pic" => $profile_pic,
										"bids" => 0,
										"status" => "active"
									)
								)
							);
							echo "<h2 style='color: #55ff55; margin: 20px 10px 5px; font-size: 18px;'>Congrtulations!!!</h2><p style='margin-left: 10px; margin-bottom: 150px;'>You have been successfully registered with ". $SITE_TITLE .". Click here to login ";
						} else {
							echo "<h2 style='color: #ff5555; margin: 20px 10px 5px; font-size: 18px;'>ERROR Uploading Profile Pic</h2><p style='margin-left: 10px; margin-bottom: 150px;'>Profile photo is not a valid image or an error occured while uploading. Try again.";
						}
					} else {
						if(isset($_GET['id']) && $_GET['id'] != '') {
							$id =  decode_id_for_url($_GET['id']);

							$vQ = sql_select(
								array(
									"table" => "user_verifications",
									"cols"  => "*",
									"where" => "id='$id'"
								)
							);
							if(count($vQ) > 0) {
					?>			<div id="registration_wrapper" class="clearfix">
									<div id="registration_form">
										<h1>Sign Up for a <?php echo $SITE_TITLE; ?> Account</h1>
										<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" id="regForm" enctype="multipart/form-data">
											<h2>You Email Address: <span style="color: green; font-weight: bold;"><?php echo $vQ[0]["email_address"]; ?></span>
												<input type="hidden" name="email_address" id="email_address" class="required" value="<?php echo $vQ[0]["email_address"]; ?>" />
											</h2>
											<p>&nbsp;</p>
											<h2>Enter Full Name:</h2>
											<input type="text" id="full_name" name="full_name" class="required" />
											<h2>Password:</h2>
											<input type="password" id="password" name="password" class="required" minlength="6" />
											<h2>Retype Password:</h2>
											<input type="password" id="repassword" name="repassword" class="required"  minlength="6" />
											<h2>Address 1:</h2>
											<input type="text" id="address_1" name="address_1" class="required" />
											<h2>Address 2:</h2>
											<input type="text" id="address_2" name="address_2" class="required" />
											<h2>City:</h2>
											<input type="text" id="city" name="city" class="required" />
											<h2>State:</h2>
											<input type="text" id="state" name="state" class="required" />
											<h2>Country:</h2>
											<select name="country" id="country" class="required">
												<option value="" selected="selected">Select Country</option>
												<option value="United States">United States</option>
												<option value="United Kingdom">United Kingdom</option>
												<option value="Afghanistan">Afghanistan</option>
												<option value="Albania">Albania</option>
												<option value="Algeria">Algeria</option>
												<option value="American Samoa">American Samoa</option>
												<option value="Andorra">Andorra</option>
												<option value="Angola">Angola</option>
												<option value="Anguilla">Anguilla</option>
												<option value="Antarctica">Antarctica</option>
												<option value="Antigua and Barbuda">Antigua and Barbuda</option>
												<option value="Argentina">Argentina</option>
												<option value="Armenia">Armenia</option>
												<option value="Aruba">Aruba</option>
												<option value="Australia">Australia</option>
												<option value="Austria">Austria</option>
												<option value="Azerbaijan">Azerbaijan</option>
												<option value="Bahamas">Bahamas</option>
												<option value="Bahrain">Bahrain</option>
												<option value="Bangladesh">Bangladesh</option>
												<option value="Barbados">Barbados</option>
												<option value="Belarus">Belarus</option>
												<option value="Belgium">Belgium</option>
												<option value="Belize">Belize</option>
												<option value="Benin">Benin</option>
												<option value="Bermuda">Bermuda</option>
												<option value="Bhutan">Bhutan</option>
												<option value="Bolivia">Bolivia</option>
												<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
												<option value="Botswana">Botswana</option>
												<option value="Bouvet Island">Bouvet Island</option>
												<option value="Brazil">Brazil</option>
												<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
												<option value="Brunei Darussalam">Brunei Darussalam</option>
												<option value="Bulgaria">Bulgaria</option>
												<option value="Burkina Faso">Burkina Faso</option>
												<option value="Burundi">Burundi</option>
												<option value="Cambodia">Cambodia</option>
												<option value="Cameroon">Cameroon</option>
												<option value="Canada">Canada</option>
												<option value="Cape Verde">Cape Verde</option>
												<option value="Cayman Islands">Cayman Islands</option>
												<option value="Central African Republic">Central African Republic</option>
												<option value="Chad">Chad</option>
												<option value="Chile">Chile</option>
												<option value="China">China</option>
												<option value="Christmas Island">Christmas Island</option>
												<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
												<option value="Colombia">Colombia</option>
												<option value="Comoros">Comoros</option>
												<option value="Congo">Congo</option>
												<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
												<option value="Cook Islands">Cook Islands</option>
												<option value="Costa Rica">Costa Rica</option>
												<option value="Cote D'ivoire">Cote D'ivoire</option>
												<option value="Croatia">Croatia</option>
												<option value="Cuba">Cuba</option>
												<option value="Cyprus">Cyprus</option>
												<option value="Czech Republic">Czech Republic</option>
												<option value="Denmark">Denmark</option>
												<option value="Djibouti">Djibouti</option>
												<option value="Dominica">Dominica</option>
												<option value="Dominican Republic">Dominican Republic</option>
												<option value="Ecuador">Ecuador</option>
												<option value="Egypt">Egypt</option>
												<option value="El Salvador">El Salvador</option>
												<option value="Equatorial Guinea">Equatorial Guinea</option>
												<option value="Eritrea">Eritrea</option>
												<option value="Estonia">Estonia</option>
												<option value="Ethiopia">Ethiopia</option>
												<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
												<option value="Faroe Islands">Faroe Islands</option>
												<option value="Fiji">Fiji</option>
												<option value="Finland">Finland</option>
												<option value="France">France</option>
												<option value="French Guiana">French Guiana</option>
												<option value="French Polynesia">French Polynesia</option>
												<option value="French Southern Territories">French Southern Territories</option>
												<option value="Gabon">Gabon</option>
												<option value="Gambia">Gambia</option>
												<option value="Georgia">Georgia</option>
												<option value="Germany">Germany</option>
												<option value="Ghana">Ghana</option>
												<option value="Gibraltar">Gibraltar</option>
												<option value="Greece">Greece</option>
												<option value="Greenland">Greenland</option>
												<option value="Grenada">Grenada</option>
												<option value="Guadeloupe">Guadeloupe</option>
												<option value="Guam">Guam</option>
												<option value="Guatemala">Guatemala</option>
												<option value="Guinea">Guinea</option>
												<option value="Guinea-bissau">Guinea-bissau</option>
												<option value="Guyana">Guyana</option>
												<option value="Haiti">Haiti</option>
												<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
												<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
												<option value="Honduras">Honduras</option>
												<option value="Hong Kong">Hong Kong</option>
												<option value="Hungary">Hungary</option>
												<option value="Iceland">Iceland</option>
												<option value="India">India</option>
												<option value="Indonesia">Indonesia</option>
												<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
												<option value="Iraq">Iraq</option>
												<option value="Ireland">Ireland</option>
												<option value="Israel">Israel</option>
												<option value="Italy">Italy</option>
												<option value="Jamaica">Jamaica</option>
												<option value="Japan">Japan</option>
												<option value="Jordan">Jordan</option>
												<option value="Kazakhstan">Kazakhstan</option>
												<option value="Kenya">Kenya</option>
												<option value="Kiribati">Kiribati</option>
												<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
												<option value="Korea, Republic of">Korea, Republic of</option>
												<option value="Kuwait">Kuwait</option>
												<option value="Kyrgyzstan">Kyrgyzstan</option>
												<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
												<option value="Latvia">Latvia</option>
												<option value="Lebanon">Lebanon</option>
												<option value="Lesotho">Lesotho</option>
												<option value="Liberia">Liberia</option>
												<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
												<option value="Liechtenstein">Liechtenstein</option>
												<option value="Lithuania">Lithuania</option>
												<option value="Luxembourg">Luxembourg</option>
												<option value="Macao">Macao</option>
												<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
												<option value="Madagascar">Madagascar</option>
												<option value="Malawi">Malawi</option>
												<option value="Malaysia">Malaysia</option>
												<option value="Maldives">Maldives</option>
												<option value="Mali">Mali</option>
												<option value="Malta">Malta</option>
												<option value="Marshall Islands">Marshall Islands</option>
												<option value="Martinique">Martinique</option>
												<option value="Mauritania">Mauritania</option>
												<option value="Mauritius">Mauritius</option>
												<option value="Mayotte">Mayotte</option>
												<option value="Mexico">Mexico</option>
												<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
												<option value="Moldova, Republic of">Moldova, Republic of</option>
												<option value="Monaco">Monaco</option>
												<option value="Mongolia">Mongolia</option>
												<option value="Montserrat">Montserrat</option>
												<option value="Morocco">Morocco</option>
												<option value="Mozambique">Mozambique</option>
												<option value="Myanmar">Myanmar</option>
												<option value="Namibia">Namibia</option>
												<option value="Nauru">Nauru</option>
												<option value="Nepal">Nepal</option>
												<option value="Netherlands">Netherlands</option>
												<option value="Netherlands Antilles">Netherlands Antilles</option>
												<option value="New Caledonia">New Caledonia</option>
												<option value="New Zealand">New Zealand</option>
												<option value="Nicaragua">Nicaragua</option>
												<option value="Niger">Niger</option>
												<option value="Nigeria">Nigeria</option>
												<option value="Niue">Niue</option>
												<option value="Norfolk Island">Norfolk Island</option>
												<option value="Northern Mariana Islands">Northern Mariana Islands</option>
												<option value="Norway">Norway</option>
												<option value="Oman">Oman</option>
												<option value="Pakistan">Pakistan</option>
												<option value="Palau">Palau</option>
												<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
												<option value="Panama">Panama</option>
												<option value="Papua New Guinea">Papua New Guinea</option>
												<option value="Paraguay">Paraguay</option>
												<option value="Peru">Peru</option>
												<option value="Philippines">Philippines</option>
												<option value="Pitcairn">Pitcairn</option>
												<option value="Poland">Poland</option>
												<option value="Portugal">Portugal</option>
												<option value="Puerto Rico">Puerto Rico</option>
												<option value="Qatar">Qatar</option>
												<option value="Reunion">Reunion</option>
												<option value="Romania">Romania</option>
												<option value="Russian Federation">Russian Federation</option>
												<option value="Rwanda">Rwanda</option>
												<option value="Saint Helena">Saint Helena</option>
												<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
												<option value="Saint Lucia">Saint Lucia</option>
												<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
												<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
												<option value="Samoa">Samoa</option>
												<option value="San Marino">San Marino</option>
												<option value="Sao Tome and Principe">Sao Tome and Principe</option>
												<option value="Saudi Arabia">Saudi Arabia</option>
												<option value="Senegal">Senegal</option>
												<option value="Serbia and Montenegro">Serbia and Montenegro</option>
												<option value="Seychelles">Seychelles</option>
												<option value="Sierra Leone">Sierra Leone</option>
												<option value="Singapore">Singapore</option>
												<option value="Slovakia">Slovakia</option>
												<option value="Slovenia">Slovenia</option>
												<option value="Solomon Islands">Solomon Islands</option>
												<option value="Somalia">Somalia</option>
												<option value="South Africa">South Africa</option>
												<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
												<option value="Spain">Spain</option>
												<option value="Sri Lanka">Sri Lanka</option>
												<option value="Sudan">Sudan</option>
												<option value="Suriname">Suriname</option>
												<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
												<option value="Swaziland">Swaziland</option>
												<option value="Sweden">Sweden</option>
												<option value="Switzerland">Switzerland</option>
												<option value="Syrian Arab Republic">Syrian Arab Republic</option>
												<option value="Taiwan, Province of China">Taiwan, Province of China</option>
												<option value="Tajikistan">Tajikistan</option>
												<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
												<option value="Thailand">Thailand</option>
												<option value="Timor-leste">Timor-leste</option>
												<option value="Togo">Togo</option>
												<option value="Tokelau">Tokelau</option>
												<option value="Tonga">Tonga</option>
												<option value="Trinidad and Tobago">Trinidad and Tobago</option>
												<option value="Tunisia">Tunisia</option>
												<option value="Turkey">Turkey</option>
												<option value="Turkmenistan">Turkmenistan</option>
												<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
												<option value="Tuvalu">Tuvalu</option>
												<option value="Uganda">Uganda</option>
												<option value="Ukraine">Ukraine</option>
												<option value="United Arab Emirates">United Arab Emirates</option>
												<option value="United Kingdom">United Kingdom</option>
												<option value="United States">United States</option>
												<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
												<option value="Uruguay">Uruguay</option>
												<option value="Uzbekistan">Uzbekistan</option>
												<option value="Vanuatu">Vanuatu</option>
												<option value="Venezuela">Venezuela</option>
												<option value="Viet Nam">Viet Nam</option>
												<option value="Virgin Islands, British">Virgin Islands, British</option>
												<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
												<option value="Wallis and Futuna">Wallis and Futuna</option>
												<option value="Western Sahara">Western Sahara</option>
												<option value="Yemen">Yemen</option>
												<option value="Zambia">Zambia</option>
												<option value="Zimbabwe">Zimbabwe</option>
											</select>
											<h2>Zip Code:</h2>
											<input type="text" id="zip_code" name="zip_code" class="required" />
											<h2>Telephone:</h2>
											<input type="text" id="telephone" name="telephone" class="required" />
											<h2>Profile Picture:</h2>
											<input type="file" id="profile_pic" name="profile_pic" class="required" />
											<p>&nbsp;</p>
											<input type="submit" class="required primary-button" name="submit" value="Submit" />
										</form>
									</div>
									<div id="form_right">
										<h1>Already have a <?php echo $SITE_TITLE; ?> account?</h1>
										<p>Well then, you're in the wrong place. <a>Sign in</a>.</p>
									</div>

				<?php
							} else {
								echo '<h4 style="font-size: 24px;width:500px;margin: 10px auto;padding: 20px 15px;line-height: 2;"> oops... <br> <span style="color: red;font-size: 16px;">sorry !  we can\'t open your requeted url </span></h4>';
							}
						} else {
							echo '<h4 style="font-size: 24px;width:500px;margin: 10px auto;padding: 20px 15px;line-height: 2;"> oops... <br> <span style="color: red;font-size: 16px;">sorry !  we can\'t open your requeted url </span></h4>';
						}
					}
				?>
			</section>
			<?php
				// site footer
				require("includes/footer.php");
			?>
		</div>
		<div style="display:none;">
			<div id="register" class="login">
				<form action="" id="regiter-form">
					<h2 style="text-align:left">Enter Email Address To Register:</h2>
					<input type="text" id="register-email"/>
					<a class="primary-button" href="javascript:sendMail()">Submit</a>
					<h4 class="error"> Please Enter Valid Email Address!</h4>
					<h3 style="text-align:left"> Email Link Will Send To your address..Follow this email</h3>
				</form>
				<img src="<?php echo $MEDIA_DIR; ?>images/loading.gif" id="loading" alt="loading"/>
			</div>
		</div>
	</body>
</html>