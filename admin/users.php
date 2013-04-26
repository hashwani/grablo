<?php
	require('authenticate.php');
	require('../libs/_config.php');
	require('../libs/_db.php');
	require('../libs/_utils.php');
	sql_open();
	
	$page = $table_name = "users";
	$module_name = "user";
	$max_rows = 20;
	$cols_to_show = array(
		"id" => array("ID", "15px"),
		"full_name" => array("Name", "100px"),
		"email_address" => array("Email", "150px"),
		"password" => array("Password", "50px"),
		"address_1" => array("Address 1", "50px"),
		"address_2" => array("Address 2", "150px"),
		"city" => array("City", "100px"),
		"state" => array("State", "50px"),
		"country" => array("Country", "50px"),
		"zip_code" => array("Zip Code", "50px"),
		"telephone" => array("Telephone", "50px"),
		"profile_pic" => array("Photo", "50px"),
		"grabz" => array("Grabz", "50px"),
		"grabz_from_adz" => array("Adz' Grabz", "50px"),
		"grabz_from_referrals" => array("Referrals' Grabz", "50px"),
		"grabz_from_signup" => array("Signup' Grabz", "50px"),
		"grabz_from_coupons" => array("Coupons' Grabz", "50px"),
		"ad_credits" => array("Ad Credits", "50px"),
		"type" => array("Type", "50px"),
		"status" => array("Status", "50px"),
		"date_added" => array("Date Added", "125px")
	);
	$cols_count = count($cols_to_show) + 1;
?>
	<?php
		require("includes/header.php");
	?>
	<style type="text/css">
		#rows_container td {
			padding-left: 2px;
			padding-right: 2px;
		}
	</style>
	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="page-full-width cf">
			<div class="content-module">
				<div class="content-module-heading cf">
					<h3 class="fl"><?php echo ucfirst($page); ?> Page</h3>
					<span class="fr expand-collapse-text">Click to collapse</span>
					<span class="fr expand-collapse-text initial-expand">Click to expand</span>
					
				</div> <!-- end content-module-heading -->
				<div class="content-module-main">
					<div class="clearfix">
						<h2 style="float:left;">All <?php echo ucfirst($page); ?></h2>
						<div style="margin-bottom: 5px; float: right;">
							<a class="fancybox_btn button round blue image-right ic-add text-upper" href="user.php?action=add">Add <?php echo ucfirst($module_name); ?></a>
						</div>
					</div>
					<table>
						<thead>
							<tr>
								<!--<th><input type="checkbox" id="table-select-all"></th>-->
								<?php
									foreach($cols_to_show as $key => $val) {
								?>
										<th style="padding-left: 2px; padding-right: 2px; overflow:hidden; width:<?php echo $val[1]; ?>;"><?php echo $val[0]; ?></th>
								<?php
									}
								?>
								<th>Actions</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="<?php echo $cols_count; ?>" class="table-footer" style="padding: 0.5em; text-align: right;">
									<!--<label for="table-select-actions">With selected:</label>
									<select id="table-select-actions">
										<option value="option1">Edit</option>
										<option value="option2">Delete</option>
									</select>
									<a href="#" class="round button blue text-upper small-button">Apply to selected</a>	
									-->
								</td>
							</tr>
						</tfoot>
						<tbody id="rows_container">
							
						</tbody>
					</table>
				</div> <!-- end content-module-main -->
			</div> <!-- end content-module -->
		</div> <!-- end full-width -->
	</div> <!-- end content -->
	<script type="text/javascript">
		var table = "<?php echo $table_name; ?>";
		var max_rows = "<?php echo $max_rows; ?>";
		var cols = "<?php echo '*'; ?>";
		var start = 0;

		function reload_table(start1) {
			start = start1;
			$("#rows_container").empty().append(
				'<tr>' +
					'<td colspan="<?php echo $cols_count; ?>" class="table-footer" style="padding: 0.5em; text-align: center;">' +
						'<img src="images/loading.gif" />' +
					'</td>' +
				'</tr>'
			);
			$.post(
				"request.php",
				{
					"table"      : table,
					"cols"       : cols,
					"max_rows"   : max_rows,
					"start"      : start,
					"request_id" : 1
				},
				populate_data
			);
		}
		
		function populate_data(data1) {
			eval(data1);
			var container = $("#rows_container");
			container.empty();
			for(var i = 0; i < data.length; i++) {
				container.append(
					'<tr>' +
						'<td>' + data[i].id + '</td>' +
						'<td>' + data[i].full_name + '</td>' +
						'<td>' + data[i].email_address + '</td>' +
						'<td>' + data[i].password + '</td>' +
						'<td>' + data[i].address_1 + '</td>' +
						'<td>' + data[i].address_2 + '</td>' +
						'<td>' + data[i].city + '</td>' +
						'<td>' + data[i].state + '</td>' +
						'<td>' + data[i].country + '</td>' +
						'<td>' + data[i].zip_code + '</td>' +
						'<td>' + data[i].telephone + '</td>' +
						'<td>' + data[i].profile_pic + '</td>' +
						'<td style="text-align: right;">' + data[i].grabz + '</td>' +
						'<td style="text-align: right;">' + data[i].grabz_from_adz + '</td>' +
						'<td style="text-align: right;">' + data[i].grabz_from_referrals + '</td>' +
						'<td style="text-align: right;">' + data[i].grabz_from_signup + '</td>' +
						'<td style="text-align: right;">' + data[i].grabz_from_coupons + '</td>' +
						'<td style="text-align: right;">' + data[i].ad_credits + '</td>' +
						'<td>' + data[i].type + '</td>' +
						'<td>' + data[i].status + '</td>' +
						'<td>' + data[i].date_added + '</td>' +
						'<td>' +
							'<a href="user.php?action=update&id=' + data[i].id + '" class="fancybox_btn table-actions-button ic-table-edit"></a>' +
							'<a href="user.php?action=delete&id=' + data[i].id + '" class="table-actions-button ic-table-delete"></a>' +
						'</td>' +
					'</tr>'
				);
			}
			$(".fancybox_btn").fancybox({
				'width' : '50%',
				'height' : '100%',
				'autoScale' : false,
				'transitionIn' : 'none',
				'transitionOut' : 'none',
				'type' : 'iframe',
				'onClosed' : function(){
					reload_table(0);
				}
			});
			$(".ic-table-delete").fancybox({
				'width' : '50%',
				'height' : '100%',
				'autoScale' : false,
				'transitionIn' : 'none',
				'transitionOut' : 'none',
				'type' : 'iframe',
				'onStart' : function(){
					return window.confirm("Do you really want to delete?");
				},
				'onClosed' : function(){
					reload_table(0);
				}
			});
		}
		$(document).ready(function() {
			reload_table(start);
			$(".fancybox_btn").fancybox({
				'width' : '50%',
				'height' : '100%',
				'autoScale' : false,
				'transitionIn' : 'none',
				'transitionOut' : 'none',
				'type' : 'iframe',
				'onClosed' : function(){
					reload_table(0);
				}
			});
		});
	</script>
<?php
	require("includes/footer.php");
?>