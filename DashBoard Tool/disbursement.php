<!DOCTYPE html>
<html>
<head>
	<?php 
	include('include/head-dw.php'); 
	echo "<meta http-equiv='refresh' content='120'>";
	?>
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/media/css/dataTables.bootstrap4.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/media/css/responsive.dataTables.css">
	<script src="src/scripts/Chart.min.js"></script>

</head>
<body>
	<?php include('include/header-dw.php'); ?>
	<?php include('include/sidebar.php'); ?>
	<?php include('include/contelepin.php'); 
	ini_set('display_errors', 1);
				ini_set('memory_limit', '-1');
	
	?>
	<div class="main-container">
		<div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
			<div class="min-height-200px">
			   
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
					<div class="clearfix mb-20">
						<div class="pull-centre">

											<?php echo "<h5 align='center'>Displaying Current Services Balance</h5>"; ?>
						</div>
												<div class="pull-centre">

<?php 
$qry_memcache=mysql_query("select message,round((substring_index(substring_index(message,'curr_connections is ',-1),',',1)/4096)*100,2) as conn_perc from memcached_monitor where port =  '11212' order by ttime desc limit 1")or die(mysql_error());
if ($fetch_qry_memcache=mysql_fetch_array($qry_memcache)){
$message=$fetch_qry_memcache['message'];
$conn_perc=$fetch_qry_memcache['conn_perc'];
if($conn_perc < 80 ){
$conn_perc_status="<button type='button' class='btn btn-success'>$conn_perc.%</button>";
$bgcolor="";                                            
}elseif($conn_perc > 80){
$conn_perc_status="<button type='button' class='btn btn-danger'>$conn_perc.%</button>";
$bgcolor="style='background-color:#ffcccc;'";
}
}
echo "<h5 align='center'>$message</h5><h5 align='center'>Conn Utilization is $conn_perc_status</h5>"; ?>
						</div>
					</div>

					<div class="row">
	
						<table class="stripe hover multiple-select-row data-table-export nowrap">
							<thead>
								<tr>
									<th>Service</th>
									<th><div align="right">Balance</div></th>
									<th><div align="right">Threshold</div></th>
									<th><div align="right">Balance Date</div></th>
									<th><div align="right">Balance/Threshold</div></th>
									<th><div align="right">Balance Status</div></th>
									<th><div align="center">More..</div></th>
								</tr>
							</thead>
							<tbody>
							
					<?php 
					$balance_Arr="";
					$service_Arr="";

					
                     //DISBURSEMENT CODES 					
					$queryServicesDis=mysql_query("SELECT service_name from disbursement_accounts where status='1'")or die(mysql_error());
														
					while($fetchServicesDis=mysql_fetch_array($queryServicesDis)){
					$disb_service_name=$fetchServicesDis['service_name'];
					
					$queryMaxDisburse=mysql_query("SELECT MAX(recordid) as maxRecID from disbursement_balances where service_name='$disb_service_name'")or die(mysql_error());
					$fetchMaxDisburse=mysql_fetch_array($queryMaxDisburse);
					$MaxDisRecID=$fetchMaxDisburse['maxRecID'];
					
					$queryDisbursements=mysql_query("SELECT service_name, balance, threshold, bal_date, bal_time FROM disbursement_balances where recordid='$MaxDisRecID'")or die(mysql_error());
					$fetchDisbursements=mysql_fetch_array($queryDisbursements);												
					
					$balance=$fetchDisbursements['balance'];
					$balance_Tsh=number_format($balance);
					$threshold=$fetchDisbursements['threshold'];
					$threshold_Tsh=number_format($threshold);
					$bal_date=$fetchDisbursements['bal_date'];
					$bal_time=$fetchDisbursements['bal_time'];
			
                   /* if($balance < $threshold){ 
					$balance_status="<button type='button' class='btn btn-danger'>INSUF</button>";
					$bgcolor="style='background-color:#ffcccc;'";
					
					}elseif($balance >= $threshold){ 
					$balance_status="<button type='button' class='btn btn-success'>SUFF</button>";
					$bgcolor="";
					}	
                     */
					 
					 $balance_ratio=round(($balance/$threshold), 2);
					 
					 
					 if(($balance_ratio) > 1.2){
					 $balance_status="<button type='button' class='btn btn-success'>OK</button>";
					 $bgcolor="";						 
					 }
					 elseif(($balance_ratio) >= 0.8 AND ($balance_ratio <= 1.2)){
					 $balance_status="<button type='button' class='btn btn-warning'>WARNING</button>";
					 $bgcolor="style='background-color:#ffcccc;'";
					 }
					 elseif(($balance_ratio) < 0.8){
					 $balance_status="<button type='button' class='btn btn-danger'>CRITICAL</button>";
					 $bgcolor="style='background-color:#ffcccc;'";
					 }
	              				 
					
					//Graph arrays
                    $service_Arr[]=$fetchServicesDis['service_name'];
                    $balance_Arr[]=$fetchDisbursements['balance'];
                    $threshold_Arr[]=$fetchDisbursements['threshold'];

					
									echo "<tr $bgcolor>
									<td>$disb_service_name</td>
									<td align='right'>$balance_Tsh</td>
									<td align='right'>$threshold_Tsh</td>
									<td align='right'>$bal_date $bal_time</td>
									<td align='right'>$balance_ratio</td>
									<td align='right'>$balance_status</td>
									<div class='dropdown' align='center'><td>
			<a class='btn btn-outline-primary role='button' href='disbursement_graph.php?servName=$disb_service_name&balDate=$bal_date' target='_blank'>View Trend</a>
			                         </td></div>
								</tr>";
							 } 						 
							 
							?>
													
							</tbody>
						</table>
											
					</div>
					
				</div>

				<!-- Export Datatable End -->
			</div>
			<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
			  <canvas id="bar-chart-grouped" width="800" height="450"></canvas>
			</div>
			
			<!--<option value="bar-chart-grouped">Select Chart</option>
			<option value="line-chart-grouped">Line Area Chart</option>
			<option value="bar-chart-grouped">Bar Chart</option>
			<option value="line-chart-grouped-nofill">Line Linear chart</option> 
			
			Chart type= bar of line
			-->
			
			<?php 
			include('include/footer.php'); 
			
			$fill_color="true";
			?>
		</div>
	</div>
	<?php include('include/script.php'); ?>
	<script src="src/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
	<script src="src/plugins/datatables/media/js/dataTables.bootstrap4.js"></script>
	<script src="src/plugins/datatables/media/js/dataTables.responsive.js"></script>
	<script src="src/plugins/datatables/media/js/responsive.bootstrap4.js"></script>
	<!-- buttons for Export datatable -->
	<script src="src/plugins/datatables/media/js/button/dataTables.buttons.js"></script>
	<script src="src/plugins/datatables/media/js/button/buttons.bootstrap4.js"></script>
	<script src="src/plugins/datatables/media/js/button/buttons.print.js"></script>
	<script src="src/plugins/datatables/media/js/button/buttons.html5.js"></script>
	<script src="src/plugins/datatables/media/js/button/buttons.flash.js"></script>
	<script src="src/plugins/datatables/media/js/button/pdfmake.min.js"></script>
	<script src="src/plugins/datatables/media/js/button/vfs_fonts.js"></script>
	<script>

	//Start Graph	
	var txn_dateArr = <?php echo json_encode($service_Arr); ?>;
    
	
	new Chart(document.getElementById("bar-chart-grouped"), {
    type:'bar',
    data: {
      labels:<?php echo json_encode($service_Arr); ?>, //service array
      datasets: [
        {
			label: "Balance",
          backgroundColor: "#8e5672",
          data: <?php echo json_encode($balance_Arr); ?>, //Balance array
		  fill:<?php echo $fill_color; ?>
		  
        },  
		{
          label: "Threshold",
          backgroundColor: "#DC143C",
          data: <?php echo json_encode($threshold_Arr); ?>, //Threshold array
		  fill:<?php echo $fill_color; ?>
          
        }
      ]
    },
    options: {
      title: {
        display: true,
        text: 'Services Vs Balance'
      }
    }
});
//End Graph	


	
			$('document').ready(function(){
			$('.data-table').DataTable({
				scrollCollapse: true,
				autoWidth: false,
				responsive: true,
				aaSorting: [[4, 'asc']],
				columnDefs: [{
					targets: "datatable-sort",
					orderable: true,
				}],
				"lengthMenu": [[18, 25, 50, -1], [10, 25, 50, "All"]],
				"language": {
					"info": "_START_-_END_ of _TOTAL_ entries",
					searchPlaceholder: "Search"
				},
			});
			$('.data-table-export').DataTable({
				scrollCollapse: true,
				autoWidth: false,
				responsive: true,
				aaSorting: [[4, 'asc']],
				columnDefs: [{
					targets: "datatable-sort",
					orderable: true,
				}],
				"lengthMenu": [[18, 25, 50, -1], [10, 25, 50, "All"]],
				"language": {
					"info": "_START_-_END_ of _TOTAL_ entries",
					searchPlaceholder: "Search"
				},
				dom: 'Bfrtip',
				buttons: [
				'copy', 'csv', 'pdf', 'print'
				]
			});
			var table = $('.select-row').DataTable();
			$('.select-row tbody').on('click', 'tr', function () {
				if ($(this).hasClass('selected')) {
					$(this).removeClass('selected');
				}
				else {
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
				}
			});
			var multipletable = $('.multiple-select-row').DataTable();
			$('.multiple-select-row tbody').on('click', 'tr', function () {
				$(this).toggleClass('selected');
			});
		});
	</script>
</body>
</html>
