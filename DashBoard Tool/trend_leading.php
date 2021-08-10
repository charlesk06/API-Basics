<!DOCTYPE html>
<html>
<head>
	<?php 
	include('include/head-trends.php'); 
	 echo "<meta http-equiv='refresh' content='120'>";
	//http://localhost/app_admin/trend_leading.php?brandname=failure_count&dispFormat=bar-chart-grouped&startDate=&endDate=&elemSearch=Search
	
			
											
	
	?>
	
	
	
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/media/css/dataTables.bootstrap4.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/media/css/responsive.dataTables.css">
	<script src="src/scripts/Chart.min.js"></script>


</head>
<body>
	<?php include('include/header-trends.php'); ?>
	<?php include('include/sidebar.php'); ?>
	<?php include('include/contelepin.php'); 
	ini_set('display_errors', 1);
				ini_set('memory_limit', '-1');
	
	?>
	<div class="main-container">
		<div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
			<div class="min-height-200px">
			   <!--
				<div class="page-header">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<center><div class="title">
								<h2>TigoPesa Traffic Status Dashboard  </h2>
							</div></center>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.php">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Dashboard </li>
								</ol>
							</nav>
						</div>
						
						
						<div class="col-md-6 col-sm-12 text-right">
							<div class="dropdown">
								<a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									January 2018
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#">Export List</a>
									<a class="dropdown-item" href="#">Policies</a>
									<a class="dropdown-item" href="#">View Assets</a>
								</div>
							</div>
							
						</div>
					</div>
				</div>  -->
								<!-- multiple select row Datatable End -->
				<!-- Export Datatable start -->
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
					<div class="clearfix mb-20">
						<div class="pull-centre">
						   
											  
											
						<form action="?" method="get">
							<table class="table" width="100%" border="0"><tr><td>
							
							<select style="width:300px;" name="brandname" class="custom-select form-control">
							<option value="failure_count">Services With Leading..</option>
							<option value="total_count">Total Count</option>
							                    
												<option value="failure_count">Failure Count</option>
												
												<option value="submitted_count">Submitted Count</option>
												<option value="declined_count">Declined Count</option>
												<option value="hold_count">Hold Count</option>
												<option value="doubt_count">Doubt Count</option>
												<option value="refunds_count">Refund Count</option>
												<option value="zero_traffic">Zero traffic</option>
												<option value="posted_count">Success Count</option>
												<option value="posted_count">Posted Count</option>
											</select>
											  
											</td>
											
										
											
											<td>
											<select name="dispFormat" class="custom-select form-control">
											<option value="bar-chart-grouped">Select Chart</option>
												<option value="line-chart-grouped">Line Area Chart</option>
												<option value="bar-chart-grouped">Bar Chart</option>
												<option value="line-chart-grouped-nofill">Line Linear chart</option>
												
											</select> </td>
											
											
											
											<td><input style="width:200px;" name="startDate" class="form-control datetimepicker" placeholder="Choose start date " type="text"></td>
											<td><input style="width:200px;" name="endDate" class="form-control datetimepicker" placeholder="Choose end date" type="text"></td>
											<td><input type="submit" name="elemSearch" class="btn btn-primary" value="Search"></td>
											</tr></table> 
											

											</form>
											
											
											<?php 
									
											$Error_sms=""; $ErrorHideSeL=0; $service_name="";
											$error_message=""; $inp_startDate=""; $inp_endDate="";
											$trendRate="Trsx Per day";
											$chart_element_id="bar-chart-grouped"; 
											$chart_type="bar"; $fill_color="true";
											$traffic_status="";
											$tt_trans_Arr=""; $service_Arr=""; $tt_PST_Arr="";
											$FRate_arr=""; $service_SBM_Arr=""; $service_DECLINED_Arr="";
											$tt_Failure_Arr=""; $service_tt_Arr=""; $service_PST_Arr="";
											$tt_SBM_Arr=""; $tt_DECLINED_Arr=""; $tt_HOLD_Arr=""; $tt_DOUBT_Arr=""; $tt_HOLD_Arr="";
											$tt_Transactions_Arr="";
											$tt_REFUND_Arr="";
											  $conditionalWhere="WHERE disp_level <'500'"; $brandname_sms="Failure Count";
											  
											  $servicesWhere=""; $brandname="failure_count";
											  
								    if(isset($_GET['elemSearch'])){
									  	 $brandname=mysql_real_escape_string($_GET['brandname']);
										 $startDate=mysql_real_escape_string($_GET['startDate']);
										 $endDate=mysql_real_escape_string($_GET['endDate']);
										 $dispFormat=mysql_real_escape_string($_GET['dispFormat']);
										 
										 if($endDate!=''){ $inp_endDate  = date("Y-m-d H:i:00", strtotime($endDate));}else{ $inp_endDate="";}
								if($startDate!=''){$inp_startDate  = date("Y-m-d H:i:00", strtotime($startDate));}else{$inp_startDate=""; }
								
										 //$brandname=mysql_real_escape_string($_GET['brandname']);
										 
										 if($brandname=='failure_count'){ $brandname_sms="Failure Count";  $servicesWhere=""; }
										 
										  if($brandname=='total_count'){ $brandname_sms="Total Count"; }
										  
										  if($brandname=='posted_count'){ $brandname_sms="Total Success"; }
										  
										  if($brandname=='submitted_count'){ $brandname_sms="Submitted Count"; }
										  
										  if($brandname=='declined_count'){ $brandname_sms="Declined Count"; }
										  
                                          if($dispFormat!=''){ 
									  
										  if($dispFormat=='bar-chart-grouped'){ $chart_element_id="bar-chart-grouped"; $chart_type="bar";}
										  if($dispFormat=='line-chart-grouped'){ $chart_element_id="line-chart-grouped"; $chart_type="line";}
										  if($dispFormat=='line-chart-grouped-nofill'){ $chart_element_id="line-chart-grouped"; $chart_type="line"; $fill_color="false";}
										  
										  
										  
										  }										  
										
									}
								

											$queryMaxdate=mysql_query("select max(dtime) as maxdtime FROM dboard_data");
											$fetchMaxdate=mysql_fetch_assoc($queryMaxdate);
											$maxdtime=$fetchMaxdate['maxdtime']; 
											

                                             $start_date = date("Y-m-d H:00", strtotime($maxdtime));
											
											 //$start_date=date_format($maxdtime,"Y-m-d 00:00:00");

											 
											 
											 $last_hr = date("Y-m-d H:i:00", strtotime($maxdtime));
											 $selected_hr = date("Y-m-d", strtotime($maxdtime));
											 $start_hr=date('Y-m-d H:i:00',strtotime('-60 minute',strtotime($last_hr)));
											 
											 $start_date=date('Y-m-d H:i:00',strtotime('-60 minute',strtotime($last_hr)));
											 
									         $current_date_title = date("Y-m-d", strtotime($maxdtime));
											
											$whereTimeRange="AND dtime>='$start_date'";

											$whereTimeRange="AND (dtime>='$start_hr' and dtime<'$last_hr')";	
											
								if(($inp_startDate!='')&&($inp_endDate!='')&&($inp_startDate<$inp_endDate)&&($inp_startDate<$maxdtime)&&($inp_endDate<$maxdtime))
								{
									
									$whereTimeRange="AND dtime>='$inp_startDate' AND dtime<='$inp_endDate'";
									
									$maxdtime=$inp_endDate; $start_date=$inp_startDate;
									
									
								}
								elseif(($inp_startDate!='')&&($inp_endDate!='')&&($inp_startDate<$inp_endDate)&&($inp_startDate<$maxdtime)&&($inp_endDate>$maxdtime))
								{
									
									$whereTimeRange="AND 1=2";
									
									$Error_sms="<br>Incorrect selection: Can not select beyond ($maxdtime) maximum available data <br>Please make sure data are fully loaded properly<br>";
									$ErrorHideSeL=1;
								}
								elseif(($inp_startDate!='')&&($inp_endDate!='')&&($inp_startDate>$inp_endDate)&&($inp_startDate>$maxdtime)&&($inp_endDate<$maxdtime))
								{
									
									$whereTimeRange="AND 1=2";
									
									$Error_sms="<br>Incorrect selection: Start date should not be greater than end date<br>";
									$ErrorHideSeL=1;
								}
								elseif(($inp_startDate!='')&&($inp_endDate!='')&&($inp_startDate<$inp_endDate)&&($inp_startDate>$maxdtime)&&($inp_endDate>$maxdtime))
								{
									
										$Error_sms="<br>Incorrect selection: Can not select beyond ($maxdtime) maximum available data <br>Please make sure data are fully loaded properly<br>";
									    $ErrorHideSeL=1;
								}
								
								elseif(($inp_startDate!='')&&($inp_endDate!='')&&($inp_startDate>$inp_endDate)&&($inp_startDate>$maxdtime)&&($inp_endDate>$maxdtime))
								{
									
									$whereTimeRange="AND 1=2";
									
									$Error_sms="<br>Incorrect selection: Start date should not be greater than end date<br>";
									$ErrorHideSeL=1;
									
									
								}
                                 elseif((($inp_startDate=='')&&($inp_endDate!=''))||(($inp_startDate!='')&&($inp_endDate=='')))
								{
									
									$whereTimeRange="AND 1=2";
									
									$Error_sms="<br>Incorrect selection: Start/end date should not be empty , when selecting with dates please select both,start and end date<br>";
									$ErrorHideSeL=1;
									
									
								}
							        
											
											//Convert it into a timestamp.
											$MaxTime = strtotime($maxdtime);

											//Get the current timestamp.
											$nowTime = time();

											//Calculate the difference.
											$difference = $nowTime - $MaxTime;

											//Convert seconds into minutes.
											if($difference<=3600){
											$minutesTime = floor($difference / 60);
											 $timeAgo=$minutesTime." Mins ";
											 
											}elseif(($difference>3600)&&($difference<=86400)){
											 $HoursTime = floor($difference / (60*60));
											 $timeAgo=$HoursTime." hrs ";
											 
											 }elseif(($difference>86400)&&($difference<=(86400*30))){
												$daysTime = floor($difference / (60*60*24)); 
												 $timeAgo=$daysTime." days ";
											}else{

											/* $date1 = '2000-01-25';
											$date2 = '2010-02-20';

											$ts1 = $nowTime;
											$ts2 = $MaxTime; */

											$year1 = date('Y', $nowTime);
											$year2 = date('Y', $MaxTime);

											$month1 = date('m', $nowTime);
											$month2 = date('m', $MaxTime);

											$timeAgo = (($year1 - $year2) * 12) + ($month1 - $month2)." Months";
												 
											 }
											
											$start_date = date_create($start_date); $maxdtime = date_create($maxdtime);
                                             $start_datex=date_format($start_date, 'Y-m-d H:i');
                                             $maxdtimeX=date_format($maxdtime, 'Y-m-d H:i');
                                           											
											
											if($ErrorHideSeL==0){
												
											if($difference >= 1800){ 
											
		    echo "<h5><b style='color:red';>$timeAgo ago $Error_sms</b> Displaying results for <b>Services with Leading $brandname_sms</b> as of :  
			<b>$start_datex </b> to <b> $maxdtimeX </b>$error_message <button type='button' class='btn btn-danger'>NOK</button></h5>";
											
											}else{ 
											
		    echo "<h5><b style='color:#009900';>$timeAgo ago $Error_sms</b> Displaying results for <b>Services with Leading $brandname_sms</b> as of :  
			<b>$start_datex </b> to <b> $maxdtimeX </b>$error_message <button type='button' class='btn btn-success'>OK</button></h5>";
											
											}	

                                             }											
											?>
						</div>
					</div>
					<?php if($brandname=="failure_count"){ ?>
					<div class="row">
	
						<table class="stripe hover multiple-select-row data-table-export nowrap">
							<thead>
								<tr>
									<th style="width:100px" class="table-plus">S/N</th>
									<th>Service </th>
									<th>Gateway</th>
									<th>SR</th>
									<th>Posted/Total</th>
									<th>DCL</th>
									<th>SBM</th>
									<th>HLD</th>
									<th>DOUBT</th>
									<th>Status</th>
									<th>More..</th>
								</tr>
							</thead>
							<tbody>
							
							<?php 
							       
								    
									if($ErrorHideSeL==0){
									
									 $i=0;
									 
									$queryData=mysql_query("SELECT SUM(counts) AS tt_Failure,service,gateway FROM dboard_data WHERE (status not like 'PST%' AND status!='RFD') $whereTimeRange group by service,gateway order by tt_Failure DESC LIMIT 100");
									while($fetchQuery=mysql_fetch_assoc($queryData)){
								    $i=$i+1;
									$tt_Failure_Arr[]=$fetchQuery['tt_Failure']; 
									$service_Arr[]=$fetchQuery['service'];
									$service_name=$fetchQuery['service'];
									
									$gateway=$fetchQuery['gateway']; 
									$tt_Failure=$fetchQuery['tt_Failure'];
									
									$queryPST=mysql_query("SELECT SUM(counts) AS tt_PST FROM dboard_data WHERE (status like 'PST%' OR status='RFD') and service='$service_name' $whereTimeRange") or die(mysql_error());
									$fetchPST=mysql_fetch_assoc($queryPST)or die(mysql_error());
									$tt_PST=$fetchPST['tt_PST']; 
									
									$queryTotal=mysql_query("SELECT SUM(counts) AS tt_Total FROM dboard_data WHERE  service='$service_name' $whereTimeRange") or die(mysql_error());
									$fetchTotal=mysql_fetch_assoc($queryTotal)or die(mysql_error());
									$tt_Total=$fetchTotal['tt_Total']; 
									
									
									$querySDL=mysql_query("SELECT SUM(counts) AS tt_SDL FROM dboard_data WHERE (status like '%RDCB%' OR status like '%RDCL%' OR status like '%RDCT%' OR status like '%SDL%' OR status like '%DCL%') and service='$service_name' $whereTimeRange") or die(mysql_error());
									$fetchSDL=mysql_fetch_assoc($querySDL)or die(mysql_error());
									$tt_SDL=$fetchSDL['tt_SDL'];
						

									$querySBM=mysql_query("SELECT SUM(counts) AS tt_SBM FROM dboard_data WHERE status like '%SBM%' and service='$service_name' $whereTimeRange") or die(mysql_error());
									 $fetchSBM=mysql_fetch_assoc($querySBM)or die(mysql_error());
									$tt_SBM=$fetchSBM['tt_SBM']; 
																				

									$queryHOLD=mysql_query("SELECT SUM(counts) AS tt_HOLD FROM dboard_data WHERE (status like '%HLD%' OR status like '%SRTO%' OR status like '%SRV%' OR status like '%STO%' OR status like '%STP%' OR status like '%PRF%' OR status like '%RHD%' OR status like '%RTO%' OR status like '%RTO%' OR status like '%RTO%') and service='$service_name' $whereTimeRange") or die(mysql_error());
									$fetchHOLD=mysql_fetch_assoc($queryHOLD)or die(mysql_error());
									$tt_HOLD=$fetchHOLD['tt_HOLD']; 
	
									$queryDUOBT=mysql_query("SELECT SUM(counts) AS tt_DUOBT FROM dboard_data WHERE (status like '%RDBA%' OR status like '%RDBT%' OR status like '%SRDT%' OR status like '%SDT%') and service='$service_name' $whereTimeRange") or die(mysql_error());
									$fetchDUOBT=mysql_fetch_assoc($queryDUOBT)or die(mysql_error());
									$tt_DUOBT=$fetchDUOBT['tt_DUOBT'];
							
									
									if(($tt_Total!="") and ($tt_Total!=0)){ 
									  $successRate=(round((($tt_PST/$tt_Total)*100),2));
									  
									}
									 //if($successRate<100){
									  if(($tt_SBM<10)||($successRate>75)){  $traffic_status="<button type='button' class='btn btn-md btn-success'>SOK</button>";
								       }else{ $traffic_status="<button type='button' class='btn btn-md btn-warning'>NOK</button>"; }
                                      
																		
									  
									  if(($tt_SBM>=10)||(($successRate<=75)&&($tt_Failure>=10))||(($successRate==0)&&($tt_Failure>0))){
										  $bgcolor="style='background-color:#ffcccc;'";
					                      $traffic_status="<button type='button' class='btn btn-md btn-danger'>NOK</button>";
									   }else{$bgcolor="";}
									
									if(($successRate<=75)&&($successRate>0)&&($tt_Failure<10)){
										  $bgcolor="style='background-color:#ffcccc;'";
					                      $traffic_status="<button type='button' class='btn btn-md btn-warning'>NOK</button>";
									   }else{}
									   
									   if(($successRate!=0)&&($successRate<=75)&&($tt_SDL<10)&&($tt_SBM==0)){
										  $bgcolor="";
					                      $traffic_status="<button type='button' class='btn btn-md btn-warning'>NOK</button>";
									   }else{}
									
									echo "<tr $bgcolor>
									<td class='table-plus'> $i </td>
									<td>$service_name</td>
									<td>$gateway</td>
									<td> $successRate % </td>
									<td> $tt_PST/$tt_Total </td>
									<td>$tt_SDL</td>
									<td>$tt_SBM</td>
									<td><font color='red'>$tt_DUOBT</font></td>
									<td><font color='red'>$tt_HOLD</font></td>
									<td>$traffic_status</td>
									
									<td><div class='dropdown'>
											<a class='btn btn-outline-primary dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
												 Explore ...
											</a>
											<div class='dropdown-menu dropdown-menu-right'>	
												<a class='dropdown-item' href='view_transactions.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_name'><i class='fa fa-eye'></i>View Transactions-self</a>
												<a class='dropdown-item' target='_blank' href='view_transactions.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_name'><i class='fa fa-eye'></i>View Transactions-new tab</a>
												
												<a class='dropdown-item' href='trend_normal.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_name'><i class='fa fa-eye'></i>View Trend-Self</a>
												<a class='dropdown-item' target='_blank' href='trend_normal.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_name'><i class='fa fa-eye'></i>View Trend-new tab</a>
												
											</div>
										</div></td>
								</tr>";
							 }
							 
					
							 }
							 
							?>
						
								
							</tbody>
						</table>
						
						
					</div>
					<?php } ?>
					
					
					<?php if($brandname=="total_count"){ ?>
					<div class="row">
	
						<table class="stripe hover multiple-select-row data-table-export nowrap">
							<thead>
								<tr>
									<th style="width:100px" class="table-plus">S/N</th>
									<th>Service </th>
									<th>Gateway</th>
									
									<th>Total</th>
									
									<th>More..</th>
								</tr>
							</thead>
							<tbody>
							
							<?php 
							       
								    
									if($ErrorHideSeL==0){
									
									 $i=0;
									
									$queryData=mysql_query("SELECT SUM(counts) AS tt_Transactions,service,gateway FROM dboard_data WHERE $whereTimeRange group by service,gateway order by tt_Transactions DESC LIMIT 100");
									while($fetchQuery=mysql_fetch_assoc($queryData)){
								    $i=$i+1;
									$tt_Transactions_Arr[]=$fetchQuery['tt_Transactions']; 
									$service_tt_Arr[]=$fetchQuery['service'];
									$service_tt=$fetchQuery['service'];
									
									$gateway=$fetchQuery['gateway']; 
									$tt_Transactions=$fetchQuery['tt_Transactions'];
									
									
								
									echo "<tr>
									<td class='table-plus'> $i </td>
									<td>$service_tt</td>
									<td>$gateway</td>
									
									<td>$tt_Transactions</td>
									
									
									
									<td><div class='dropdown'>
											<a class='btn btn-outline-primary dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
												 Explore ...
											</a>
											<div class='dropdown-menu dropdown-menu-right'>
												<a class='dropdown-item' href='view_transactions.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_tt'><i class='fa fa-eye'></i>View Transactions-self</a>
												<a class='dropdown-item' target='_blank' href='view_transactions.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_tt'><i class='fa fa-eye'></i>View Transactions-new tab</a>
												
												<a class='dropdown-item' href='trend_normal.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_tt'><i class='fa fa-eye'></i>View Trend-Self</a>
												<a class='dropdown-item' target='_blank' href='trend_normal.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_tt'><i class='fa fa-eye'></i>View Trend-new tab</a>
												
											</div>
										</div></td>
								</tr>";
							 }
							 
					
							 }
							 
							?>
						
								
							</tbody>
						</table>
						
						
					</div>
					<?php } ?>
					
					
					<?php if($brandname=="posted_count"){ ?>
					<div class="row">
	
						<table class="stripe hover multiple-select-row data-table-export nowrap">
							<thead>
								<tr>
									<th style="width:100px" class="table-plus">S/N</th>
									<th>Service </th>
									<th>Gateway</th>
									
									<th>Posted</th>
									
									<th>More..</th>
								</tr>
							</thead>
							<tbody>
							
							<?php 
							       
								    
									if($ErrorHideSeL==0){
									
									 $i=0;
									 
									$queryData=mysql_query("SELECT SUM(counts) AS tt_PST,service,gateway FROM dboard_data WHERE status like 'PST%' AND $whereTimeRange group by service,gateway order by tt_PST DESC LIMIT 100");
									while($fetchQuery=mysql_fetch_assoc($queryData)){
								    $i=$i+1;
									$tt_PST_Arr[]=$fetchQuery['tt_PST']; 
									$service_PST_Arr[]=$fetchQuery['service'];
									$service_PST=$fetchQuery['service'];
									
									$gateway=$fetchQuery['gateway']; 
									$tt_PST=$fetchQuery['tt_PST'];
									
									
								
									echo "<tr>
									<td class='table-plus'> $i </td>
									<td>$service_PST</td>
									<td>$gateway</td>
									
									<td>$tt_PST</td>
									
									
									
									<td><div class='dropdown'>
											<a class='btn btn-outline-primary dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
												 Explore ...
											</a>
											<div class='dropdown-menu dropdown-menu-right'>
												<a class='dropdown-item' href='view_transactions.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_PST'><i class='fa fa-eye'></i>View Transactions-self</a>
												<a class='dropdown-item' target='_blank' href='view_transactions.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_PST'><i class='fa fa-eye'></i>View Transactions-new tab</a>
												
												<a class='dropdown-item' href='trend_normal.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_PST'><i class='fa fa-eye'></i>View Trend-Self</a>
												<a class='dropdown-item' target='_blank' href='trend_normal.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_PST'><i class='fa fa-eye'></i>View Trend-new tab</a>
												
											</div>
										</div></td>
								</tr>";
							 }
							 
					         }
							 
							 
							?>
						
								
							</tbody>
						</table>
						
						
					</div>
					<?php } ?>
					
					
					<?php if($brandname=="submitted_count"){ ?>
					<div class="row">
	
						<table class="stripe hover multiple-select-row data-table-export nowrap">
							<thead>
								<tr>
									<th style="width:100px" class="table-plus">S/N</th>
									<th>Service </th>
									<th>Gateway</th>
									
									<th>Submitted</th>
									
									<th>More..</th>
								</tr>
							</thead>
							<tbody>
							
							<?php 
							       
								    
									
									if($ErrorHideSeL==0){
									 $i=0;
									 
									$queryData=mysql_query("SELECT SUM(counts) AS tt_SBM,service,gateway FROM dboard_data WHERE status like 'SBM%' AND $whereTimeRange group by service,gateway order by tt_SBM DESC LIMIT 100");
									while($fetchQuery=mysql_fetch_assoc($queryData)){
								    $i=$i+1;
									$tt_SBM_Arr[]=$fetchQuery['tt_SBM']; 
									$service_SBM_Arr[]=$fetchQuery['service'];
									$service_SBM=$fetchQuery['service'];
									
									$gateway=$fetchQuery['gateway']; 
									$tt_SBM=$fetchQuery['tt_SBM'];
									
									
								
									echo "<tr>
									<td class='table-plus'> $i </td>
									<td>$service_SBM</td>
									<td>$gateway</td>
									
									<td>$tt_SBM</td>
									
									
									
									<td><div class='dropdown'>
											<a class='btn btn-outline-primary dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
												 Explore ...
											</a>
											<div class='dropdown-menu dropdown-menu-right'>
												<a class='dropdown-item' href='view_transactions.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_SBM'><i class='fa fa-eye'></i>View Transactions-self</a>
												<a class='dropdown-item' target='_blank' href='view_transactions.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_SBM'><i class='fa fa-eye'></i>View Transactions-new tab</a>
												
												<a class='dropdown-item' href='trend_normal.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_SBM'><i class='fa fa-eye'></i>View Trend-Self</a>
												<a class='dropdown-item' target='_blank' href='trend_normal.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_SBM'><i class='fa fa-eye'></i>View Trend-new tab</a>
												
											</div>
										</div></td>
								</tr>";
							 }
							 
					         }
							 
							 
							?>
						
								
							</tbody>
						</table>
						
						
					</div>
					<?php } ?>
					
					
					<?php if($brandname=="declined_count"){ ?>
					<div class="row">
	
						<table class="stripe hover multiple-select-row data-table-export nowrap">
							<thead>
								<tr>
									<th style="width:100px" class="table-plus">S/N</th>
									<th>Service </th>
									<th>Gateway</th>
									
									<th>Declined</th>
									
									<th>More..</th>
								</tr>
							</thead>
							<tbody>
							
							<?php 
							       
								    
									if($ErrorHideSeL==0){
									
									 $i=0;
									 
									$queryData=mysql_query("SELECT SUM(counts) AS tt_DECLINED,service,gateway FROM dboard_data WHERE (status like '%SDL%' OR status like '%DCL%' OR status like '%RDC%') AND $whereTimeRange group by service,gateway order by tt_DECLINED DESC LIMIT 100");
									while($fetchQuery=mysql_fetch_assoc($queryData)){
								    $i=$i+1;
									$tt_DECLINED_Arr[]=$fetchQuery['tt_DECLINED']; 
									$service_DECLINED_Arr[]=$fetchQuery['service'];
									$service_DECLINED=$fetchQuery['service'];
									
									$gateway=$fetchQuery['gateway']; 
									$tt_DECLINED=$fetchQuery['tt_DECLINED'];
									
									
								
									echo "<tr>
									<td class='table-plus'> $i </td>
									<td>$service_DECLINED</td>
									<td>$gateway</td>
									
									<td>$tt_DECLINED</td>
									
									
									
									<td><div class='dropdown'>
											<a class='btn btn-outline-primary dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
												 Explore ...
											</a>
											<div class='dropdown-menu dropdown-menu-right'>
												<a class='dropdown-item' href='view_transactions.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_DECLINED'><i class='fa fa-eye'></i>View Transactions-self</a>
												<a class='dropdown-item' target='_blank' href='view_transactions.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_DECLINED'><i class='fa fa-eye'></i>View Transactions-new tab</a>
												
												<a class='dropdown-item' href='trend_normal.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_DECLINED'><i class='fa fa-eye'></i>View Trend-Self</a>
												<a class='dropdown-item' target='_blank' href='trend_normal.php?dashboard&date_init=$start_datex&date_final=$maxdtimeX&serv_got=$service_DECLINED'><i class='fa fa-eye'></i>View Trend-new tab</a>
												
											</div>
										</div></td>
								</tr>";
							 }
							 
					         }
							 
							 
							?>
						
								
							</tbody>
						</table>
						
						
					</div>
					<?php } ?>
					

				</div>
									<?php 

				//if($dispFormat=="tabular"){ ?>
				<table class="table" align="right" border="0" height="50px" style="width:60%;">
											  
											  <tr>
											  <td>SR => Success Rate </td>
											  <td>SRA => Success Rate Agreed </td>
											  <td><button type="button" class="btn btn-success">SOK</button> => Service OK</td>
											  <td><button type="button" class="btn btn-warning">NOK</button> => Traffic not OK</td>
											  <td><button type="button" class="btn btn-danger">NOT</button> => no traffic</td></tr>
											  </table>
				<?php //} ?>
				<!-- Export Datatable End -->
			</div>
			<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
			  <canvas id="<?php echo $chart_element_id; ?>" width="800" height="450"></canvas>
			</div>
			
			<?php include('include/footer.php'); ?>
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
	var txn_dateArr = <?php echo json_encode($service_Arr); ?>;
    
	
	new Chart(document.getElementById("<?php echo $chart_element_id; ?>"), {
    type:'<?php echo $chart_type; ?>',
    data: {
      labels:<?php
	  
	  
	  
	  if(($brandname=="failure_count")){
		   echo json_encode($service_Arr); 
		  }elseif($brandname=="total_count"){
			 echo json_encode($service_tt_Arr); 
		  }elseif($brandname=="posted_count"){
		     echo json_encode($service_PST_Arr); 
		  }elseif($brandname=="submitted_count"){
		     echo json_encode($service_SBM_Arr); 
		  }elseif($brandname=="declined_count"){
		     echo json_encode($service_DECLINED_Arr); 
		  }
	  
	  ?>,
      datasets: [
        {
          label: "<?php echo $brandname_sms; ?>",
          backgroundColor: "#DC143C",
          data: <?php  
		  if(($brandname=="failure_count")){
		   echo json_encode($tt_Failure_Arr); 
		  }elseif($brandname=="total_count"){
			 echo json_encode($tt_Transactions_Arr); 
		  }elseif($brandname=="posted_count"){
		     echo json_encode($tt_PST_Arr); 
		  }elseif($brandname=="submitted_count"){
		     echo json_encode($tt_SBM_Arr); 
		  }elseif($brandname=="declined_count"){
		     echo json_encode($tt_DECLINED_Arr); 
		  }
		  
		  ?>,
		  fill:<?php echo $fill_color; ?>
        }
	
      ]
    },
    options: {
      title: {
        display: true,
        text: '<?php echo $current_date_title; ?> Trend for <?php echo "Services with Leading ".$brandname_sms; ?> (Count Vs Time )'
      }
    }
});




	
	/* 
	new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: {
      labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
      datasets: [
        {
          label: "Population (millions)",
          backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
          data: [2478,5267,734,784,433]
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Predicted world population (millions) in 2050'
      }
    }
}); */
	
		$('document').ready(function(){
			$('.data-table').DataTable({
				scrollCollapse: true,
				autoWidth: false,
				responsive: true,
				columnDefs: [{
					targets: "datatable-nosort",
					orderable: false,
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
				columnDefs: [{
					targets: "datatable-nosort",
					orderable: false,
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