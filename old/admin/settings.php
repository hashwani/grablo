<?php
	require('authenticate.php');
	require('../libs/_config.php');
	require('../libs/_db.php');
	require('../libs/_utils.php');
	sql_open();
	
	$page = "settings";
	$module_name = "settings";
	
	// $settings_db = (array)json_decode(file_get_contents("settings.data"));
	// print_r($settings_db);
	
?>
	<?php
		require("includes/header.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="page-full-width cf">
			<div class="half-size-column fl">
				<div class="content-module">
					<div class="content-module-heading cf">
						<h3 class="fl">Users for Bot</h3>
						<span class="fr expand-collapse-text" style="display: block;">Click to collapse</span>
						<span class="fr expand-collapse-text initial-expand" style="display: none;">Click to expand</span>
					</div>
					<div class="content-module-main" style="display: block;">
						<div style="margin-bottom: 5px; float: right;">
							<a class="fancybox_btn button round blue image-right ic-add text-upper" href="setting.php?action=add&key=user">Add User</a>
						</div>
						<table>
							<thead>
								<tr>
									<th>ID</th>
									<th>Full Name</th>
									<th>Profile Pic</th>
									<th>Status</th>
									<th>Date Added</th>
									<th>Actions</th>
								</tr>
							</thead>
	
							<tfoot>
								<tr>
									<td colspan="6" class="table-footer">
									</td>
								</tr>
							</tfoot>
							<tbody id="rows_container_users">
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="half-size-column fr">
				<div class="content-module">
					<div class="content-module-heading cf">
						<h3 class="fl">Other Settings</h3>
						<span class="fr expand-collapse-text" style="display: block;">Click to collapse</span>
						<span class="fr expand-collapse-text initial-expand" style="display: none;">Click to expand</span>
					</div>
					<div class="content-module-main" style="display: block;">
						<form id="add_form" action="#" method="post" enctype="multipart/form-data">
							<fieldset>
								<?php
									$db = json_decode(file_get_contents("settings.data"), true);
								?>
								<p>
									<label for="min_grabz">Min Grabz:</label>
									<input id="min_grabz" name="min_grabz" class="round default-width-input required" type="text" value="<?php echo $db["min_grabz"]; ?>" />
									<input value="Update" id="min_grabz_btn" name="submit" class="round blue ic-right-arrow" type="submit" style="padding: 1.3em 3em 1.3em 0.833em;" />
								</p>
								<p>
									<label for="grabz_packats">Grabz Packats:</label>
									<input id="grabz_packats" name="grabz_packats" class="round default-width-input required" type="text" value="<?php echo $db["grabz_packats"]; ?>" />
									<input value="Update" id="grabz_packats_btn" name="submit" class="round blue ic-right-arrow" type="submit" style="padding: 1.3em 3em 1.3em 0.833em;" />
								</p>
								<p>
									<label for="cost_per_grab">Cost Per Grab:</label>
									<input id="cost_per_grab" name="cost_per_grab" class="round default-width-input required" type="text" value="<?php echo $db["cost_per_grab"]; ?>" />
									<input value="Update" id="cost_per_grab_btn" name="submit" class="round blue ic-right-arrow" type="submit" style="padding: 1.3em 3em 1.3em 0.833em;" />
								</p>
								<p>
									<label for="default_time">Default Time for Ticker:</label>
									<input id="default_time" name="default_time" class="round default-width-input required" type="text" value="<?php echo $db["default_time"]; ?>" />
									<input value="Update" id="default_time_btn" name="submit" class="round blue ic-right-arrow" type="submit" style="padding: 1.3em 3em 1.3em 0.833em;" />
								</p>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div> <!-- end full-width -->
	</div> <!-- end content -->
	<script type="text/javascript">
		$(document).ready(function() {
			$("#add_form").validate();
		});
	</script>
	<script type="text/javascript">
		var start = 0;
		function reload_table(start1) {
			start = start1;
			$("#rows_container_users").empty().append(
				'<tr>' +
					'<td colspan="6" class="table-footer" style="padding: 0.5em; text-align: center;">' +
						'<img src="images/loading.gif" />' +
					'</td>' +
				'</tr>'
			);
			$.post(
				"request.php",
				{
					"key" : "users",
					"request_id" : 3
				},
				populate_data
			);
		}
		
		function populate_data(data1) {
			eval(data1);
			var container = $("#rows_container_users");
			container.empty();
			//for(var i = 0; i < data.length; i++) {
			$.each(data, function(key, val) {
				container.append(
					'<tr>' +
						'<td>' + key + '</td>' +
						'<td>' + val.full_name + '</td>' +
						'<td><img src="<?php echo $MEDIA_DIR; ?>profile_pics/' + val.profile_pic + '" width="64px" height="64px" /></td>' +
						'<td>' + val.status + '</td>' +
						'<td>' + val.date_added + '</td>' +
						'<td>' +
							'<a href="setting.php?action=update&key=user&id=' + key + '" class="fancybox_btn table-actions-button ic-table-edit"></a>' +
							'<a href="setting.php?action=delete&key=user&id=' + key + '" class="table-actions-button ic-table-delete"></a>' +
						'</td>' +
					'</tr>'
				);
			});
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
			$("#min_grabz_btn").click(
				function() {
					$.post(
						"request.php",
						{
							"key" : "min_grabz",
							"min_grabz" : $("#min_grabz").val(),
							"request_id" : 4
						},
						function(data) {
							alert(data);
						}
					);
					return false;
				}
			);
			$("#grabz_packats_btn").click(
				function() {
					$.post(
						"request.php",
						{
							"key" : "grabz_packats",
							"grabz_packats" : $("#grabz_packats").val(),
							"request_id" : 4
						},
						function(data) {
							alert(data);
						}
					);
					return false;
				}
			);
			$("#cost_per_grab_btn").click(
				function() {
					$.post(
						"request.php",
						{
							"key" : "cost_per_grab",
							"cost_per_grab" : $("#cost_per_grab").val(),
							"request_id" : 4
						},
						function(data) {
							alert(data);
						}
					);
					return false;
				}
			);
			$("#default_time_btn").click(
				function() {
					$.post(
						"request.php",
						{
							"key" : "default_time",
							"default_time" : $("#default_time").val(),
							"request_id" : 4
						},
						function(data) {
							alert(data);
						}
					);
					return false;
				}
			);
		});
	</script>
<?php
	require("includes/footer.php");
?>