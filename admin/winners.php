<?php
	require('authenticate.php');
	require('../libs/_config.php');
	require('../libs/_db.php');
	require('../libs/_utils.php');
	sql_open();
	
	$page = $table_name = "winners";
	$module_name = "user";
	$max_rows = 20;
	$cols_to_show = array(
		"id" => array("ID", "15px"),
		"full_name" => array("Name", "100px"),
		"email_address" => array("Email", "150px"),
		"win_product" => array("Product", "200px"),
		"date_added" => array("Date", "200px")
	);
	$cols_count = count($cols_to_show);
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
						
					</div>
					<table>
						<thead>
							<tr>
								<th style="width:15px;over-flow:hidden">id</th>
								<th style="width:100px;over-flow:hidden">Name</th>
								<th style="width:150px;over-flow:hidden">Email</th>
								<th style="width:220px;over-flow:hidden">Product Title</th>
								<th style="width:100px;over-flow:hidden">Dates</th>
							</tr>
						</thead>
						<tfoot>
							
						</tfoot>
						<tbody id="rows_container">
						
						
							
						</tbody>
					</table>
				</div> <!-- end content-module-main -->
			</div> <!-- end content-module -->
		</div> <!-- end full-width -->
	</div> <!-- end content -->
	<script type="text/javascript">
		/**/
		
    	$(document).ready(function(){
			var id = 5;
		
			var dataString = {    
                request_id:5,
               }
			   
				$.ajax({

						type: "POST",

						url: "request.php",

						data: dataString,

						cache: false,

						success: function(html){

								$("#rows_container").html(html);

						}

				});
			});
	
		
		
		
		/**/
		
		
		
		
		

	
		
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
						'<td>' + data[i].title + '</td>' +
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