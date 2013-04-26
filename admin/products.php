<?php
	require('authenticate.php');
	require('../libs/_config.php');
	require('../libs/_db.php');
	require('../libs/_utils.php');
	sql_open();
	
	$page = $table_name = "products";
	$module_name = "product";
	$max_rows = 30;
	$cols_to_show = array(
		"id" => array("ID", "15px"),
		"title" => array("Title", "150px"),
		"description" => array("Description", "250px"),
		"features" => array("Features", "250px"),
		"technical_info" => array("Technical Info", "250px"),
		"market_retail_price" => array("MRP", "50px"),
		"reserved_price" => array("RP", "50px"),
		"images" => array("Images", "150px"),
		"auction_order" => array("Order", "100px"),
		"status" => array("Status", "50px"),
		"date_added" => array("Date Added", "125px")
	);
	$cols_count = count($cols_to_show) + 1;
?>
	<?php
		require("includes/header.php");
	?>
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
							<a class="fancybox_btn button round blue image-right ic-add text-upper" href="product.php?action=add">Add <?php echo ucfirst($module_name); ?></a>
						</div>
					</div>
					<table>
						<thead>
							<tr>
								<!--<th><input type="checkbox" id="table-select-all"></th>-->
								<?php
									foreach($cols_to_show as $key => $val) {
								?>
										<th style="width:<?php echo $val[1]; ?>;"><?php echo $val[0]; ?></th>
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
		var cols = "<?php echo '`' . implode('`,`', array_keys($cols_to_show)) . '`'; ?>";
		var start = 0;
		var auction_order = [];
		
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
					"order_by"   : "auction_order ASC",
					"request_id" : 1
				},
				populate_data
			);
		}
		function create_auction_order_select(id, current_order) {
			var select = '<select id="'+ (id + "_" + current_order) +'" name="auction_order" class="auction_order_control" style="width: 50px;">';
			for(var i = 0; i < auction_order.length; i++) {
				select += '<option value="'+ (auction_order[i].id + "_" + auction_order[i].auction_order) +'" '+ ((auction_order[i].id == id) ? "selected=selected" : "") +'>'+ (i + 1) +'</option>';
			}
			return (select + "</select>");
		}
		function populate_data(data1) {
			eval(data1);
			auction_order = data[data.length - 1];
			var container = $("#rows_container");
			container.empty();
			for(var i = 0; i < data.length - 1; i++) {
				container.append(
					'<tr>' +
						'<td>' + data[i].id + '</td>' +
						'<td>' + data[i].title + '</td>' +
						'<td>' + (data[i].description.length > 150 ?  data[i].description.substring(0, 150) + " ..." : data[i].description) + '</td>' +
						'<td>' + (data[i].features.length > 150 ?  data[i].features.substring(0, 150) + " ..." : data[i].features) + '</td>' +
						'<td>' + (data[i].technical_info.length > 150 ?  data[i].technical_info.substring(0, 150) + " ..." : data[i].technical_info) + '</td>' +
						'<td>' + data[i].market_retail_price + '</td>' +
						'<td>' + data[i].reserved_price + '</td>' +
						'<td>' + data[i].images + '</td>' +
						'<td>' + create_auction_order_select(data[i].id, data[i].auction_order) + '</td>' +
						'<td>' + data[i].status + '</td>' +
						'<td>' + data[i].date_added + '</td>' +
						'<td>' +
							'<a href="product.php?action=update&id=' + data[i].id + '" class="fancybox_btn table-actions-button ic-table-edit"></a>' +
							'<a href="product.php?action=delete&id=' + data[i].id + '" class="table-actions-button ic-table-delete"></a>' +
						'</td>' +
					'</tr>'
				);
			}
			$(".auction_order_control").change(
				function() {
					$.post(
						"request.php",
						{
							"table" : table,
							"product_1_id_and_order" : $(this).attr("id"),
							"product_2_id_and_order" : $(this).val(),
							"request_id" : 2
						},
						function() {
							reload_table(start);
						}
					);
					//alert( + " " + $(this).val());
				}
			);
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