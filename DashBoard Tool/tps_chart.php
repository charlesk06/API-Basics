<!DOCTYPE html>
<html>
<head>
	<?php 
	$dateLeo=date('Y-m-d');
	include('include/head-tps.php'); 
	header( "refresh:180;url=tps_chart.php" );
	?>
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/media/css/dataTables.bootstrap4.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/media/css/responsive.dataTables.css">
</head>
<body>
	<?php include('include/header-tps.php'); ?>
	<?php include('include/sidebar.php'); ?>
	<?php include('include/contelepin.php'); 
	ini_set('display_errors', 1);
				ini_set('memory_limit', '-1');
	
	?>
	<div class="main-container">
		<div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
			<div class="min-height-200px">
			  
				<!-- Export Datatable start -->
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
					<div class="clearfix mb-20">
						<div class="pull-centre">
						   
											  
											
						<form action="tps_chart.php" method="post">
							<table class="table" width="100%" border="0"><tr><td>
							
							<select style="width:200px;" name="data_view" class="custom-select form-control">
							                    <option value="chart">Chart</option>
												<option value="Table">Table</option>
												
											</select>
											  
											</td>
											
										
											
											<td><input style="width:200px;" name="startDate" class="form-control datetimepicker" placeholder="Choose start date " type="text"></td>
											<td><input style="width:200px;" name="endDate" class="form-control datetimepicker" placeholder="Choose end date" type="text"></td>
											<td><input type="submit" name="elemSearch" class="btn btn-primary" value="Search"></td>
											</tr></table> 
											

											</form>
								<?php 
								
								
								$link = new mysqli($dbhost, $dbuser, $dbpass,$dbname) or die ('Connection error : ' . mysql_error());
				
				if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}

// 2. Select a database to use 
$db_select = mysqli_select_db($link,$dbname);
if (!$db_select) {
    die("Database selection failed: " . mysqli_error($connection));
}


				$txn_dateArr="";  $ReccArr=""; $ProccArr=""; $RejjArr="";
				$js_dstart="dt.setHours(0,0,0,0);";
				$js_full_currHr="var full_currHr =(curr_year + '-' + curr_month + '-' + curr_date + ' ' + curr_hrs + ':' + curr_min + ':' + curr_sec);";
				$datet="";  $countLimit=(24*3600); 
				$currdate_start=date('Y-m-d 00:00:00');  $inp_startDate=date('Y-m-d 00:00:00'); $inp_endDate=date('Y-m-d 23:59:59');
				$currdate_end=date('Y-m-d 23:59:59'); 
				$whereConditions="CONCAT(txn_date, ' ', txn_time)>='$currdate_start' AND CONCAT(txn_date, ' ', txn_time)<='$currdate_end'";
				
				$dateLeo=date('Y-m-d'); 
				
				$MinTimeStart = strtotime($inp_startDate);
                $MaxTimeEnd = strtotime($inp_endDate);
               $countLimit = $MaxTimeEnd - $MinTimeStart;
			   
			   if(isset($_POST['elemSearch'])){
								$data_view=mysql_real_escape_string($_POST['data_view']);
								$startDate=mysql_real_escape_string($_POST['startDate']);
								$endDate=mysql_real_escape_string($_POST['endDate']);

								if($endDate!=''){ $inp_endDate  = date("Y-m-d H:i:s", strtotime($endDate));}else{ $inp_endDate="";}
								if($startDate!=''){ 
								$dateLeo  = date("Y-m-d", strtotime($startDate)); 
								$inp_startDate  = date("Y-m-d H:i:s", strtotime($startDate));
								
								$currdate_start  = date("Y-m-d H:i:s", strtotime($startDate)); 
								$curr_year  = date("Y", strtotime($startDate));
								$curr_month  = date("m", strtotime($startDate)); 
								$curr_date  = date("d", strtotime($startDate)); 
								$curr_hrs  = date("H", strtotime($startDate)); 
								$curr_min  = date("i", strtotime($startDate)); 
								$curr_sec  = date("s", strtotime($startDate)); 
								
								$MinTimeStart = strtotime($inp_startDate);
                                $MaxTimeEnd = strtotime($inp_endDate);
											
											//Calculate the difference.
							    $countLimit = $MaxTimeEnd - $MinTimeStart;
											
								}else{$inp_startDate=""; }
								
                                      //Convert it into a timestamp.
											
											
								if($data_view=='Table'){ $brandname_sms="All Services";  $conditionalWhere=""; }
								
								$js_full_currHr="var full_currHr =($curr_year + '-' + $curr_month + '-' + $curr_date + ' ' + $curr_hrs + ':' + $curr_min + ':' + $curr_sec);";
								
								}

								if(($inp_startDate!='')&&($inp_endDate!='')&&($inp_startDate<$inp_endDate))
								{
									
									$whereConditions="CONCAT(txn_date, ' ', txn_time)>='$inp_startDate' AND CONCAT(txn_date, ' ', txn_time)<='$inp_endDate'";
									
								}
								/* elseif(($inp_startDate!='')&&($inp_endDate!='')&&($inp_startDate<$inp_endDate)&&($inp_startDate<$maxdtime)&&($inp_endDate>$maxdtime))
								{
									
									$whereTimeRange="AND 1=2";
									
									$Error_sms="<br>Incorrect selection: Can not select beyond ($maxdtime) maximum available data <br>Please make sure data are fully loaded properly<br>";
									$ErrorHideSeL=1;
								} */
								elseif(($inp_startDate!='')&&($inp_endDate!='')&&($inp_startDate>$inp_endDate))
								{
									
									$whereConditions="AND 1=2";
									
									$Error_sms="<br>Incorrect selection: Start date should not be greater than end date<br>";
									$ErrorHideSeL=1;
								}
								/* elseif(($inp_startDate!='')&&($inp_endDate!='')&&($inp_startDate<$inp_endDate)&&($inp_startDate>$maxdtime)&&($inp_endDate>$maxdtime))
								{
									
										$Error_sms="<br>Incorrect selection: Can not select beyond ($maxdtime) maximum available data <br>Please make sure data are fully loaded properly<br>";
									    $ErrorHideSeL=1;
								} */
								
								elseif(($inp_startDate!='')&&($inp_endDate!='')&&($inp_startDate>$inp_endDate)&&($inp_startDate>$maxdtime)&&($inp_endDate>$maxdtime))
								{
									
									$whereConditions="AND 1=2";
									
									$Error_sms="<br>Incorrect selection: Start date should not be greater than end date<br>";
									$ErrorHideSeL=1;
									
									
								}
                                 elseif((($inp_startDate=='')&&($inp_endDate!=''))||(($inp_startDate!='')&&($inp_endDate=='')))
								{
									
									$whereConditions="AND 1=2";
									
									$Error_sms="<br>Incorrect selection: Start/end date should not be empty , when selecting with dates please select both,start and end date<br>";
									$ErrorHideSeL=1;
									
									
								}
				       
					   
				echo "<center><b><font color='red'><h4>Tps chart as of $inp_startDate to $inp_endDate</h4></font></b></center>";
				
				//$db_selected = mysqli_select_db($dbname, $link) or die ('Can\'t use telepin : ' . mysql_error());

				$L=0; $cRej=0;
                $QueryTPS=mysqli_query($link,"SELECT CONCAT(txn_date, ' ', txn_time) AS contxn_date,rec,proc,rej FROM tps WHERE $whereConditions ORDER BY txn_date,txn_time ASC") or die(mysql_error());
				while($FetchTPS=mysqli_fetch_assoc($QueryTPS)){
				
				$newtimestamp = strtotime($currdate_start.' + '.$L.' Second');
				$txn_dateArr[$L]=date('Y-m-d H:i:s', $newtimestamp);			
				$txn_datef=$FetchTPS['contxn_date'];
				$permanentLicenseArr[$L]=300;	
				//$tempLicenseArr[$L]=350;	
				//$txn_dateCurr=date("D M Y H:i:00", strtotime($txn_date));
				$Rejjs=$FetchTPS['rej'];
				if($Rejjs>0){
				$cRej=$cRej+1;
				
				$rejAlarmT[$cRej]=$txn_dateArr[$L];
				$rejAlarmRec[$cRej]=$FetchTPS['rec']; 
				$rejAlarmProc[$cRej]=$FetchTPS['proc']; 
				$rejAlarmRej[$cRej]=$FetchTPS['rej']; 
				
				}
				if($txn_dateArr[$L]==$txn_datef){
				//$ReccArr[$L]=$FetchTPS['rec'];
				if($FetchTPS['rec']<=600){ $ReccArr[$L]=$FetchTPS['rec']; }else{ $ReccArr[$L]=600; }
				//$ProccArr[$L]=$FetchTPS['proc']; 
				if($FetchTPS['proc']<=600){ $ProccArr[$L]=$FetchTPS['proc']; }else{ $ProccArr[$L]=600; }
				if($FetchTPS['rej']<=600){ $RejjArr[$L]=$FetchTPS['rej']; }else{ $RejjArr[$L]=600; }
				
				}else{
				//echo "<br>".$txn_dateArr[$L].":".$txn_datef;
			    $to_time=strtotime($txn_datef); 
			    $from_time=strtotime($txn_dateArr[$L]); 
			    $secCount=$to_time - $from_time; 
				for($gap=0;$gap<$secCount;$gap++){
				$newtimestamp = strtotime($from_time.' + '.$gap.' second');
				$txn_dateArr[$L]=date('Y-m-d H:i:s', $newtimestamp);	

				$ReccArr[$L]=0; 
				$ProccArr[$L]=0; 
				$RejjArr[$L]=0; 
				$L=$L+1;
				}
				}
				
				$L=$L+1;
				
				}
				

				///echo "<b>".$L."</b> : Total Seconds from Mid-night ( <b>$currdate</b> ) ";

			
			 
			?>
						</div>
					</div>
					<div class="row">
						
									
									
									<div id="chartContainer" style="height: 700px; max-width: 1900px; margin: 0px auto;"></div>
			
							
						
					</div>
				
				<!-- Export Datatable End -->
			</div>
			<?php include('include/footer.php'); ?>
		</div>
	</div>
	 <?php include('include/script.php'); ?>
	
	<script src="include/canvasjs.min.js"></script>
	
	<script>
//window.onload = function () {

var txn_dateArr = <?php echo json_encode($txn_dateArr); ?>;
		var ReccArr = <?php echo json_encode($ReccArr); ?>;
		var ProccArr = <?php echo json_encode($ProccArr); ?>;
		var RejjArr = <?php echo json_encode($RejjArr); ?>;
		var permanentLicenseArr = <?php echo json_encode($permanentLicenseArr); ?>;
		//var tempLicenseArr = <?php echo json_encode($tempLicenseArr); ?>;
		var countLimit = <?php echo $countLimit; ?>;
		
var dataPoints1 = [];
var dataPoints2 = [];
var dataPoints3 = [];
var dataPoints4 = [];
//var dataPoints5 = [];

var chart = new CanvasJS.Chart("chartContainer", {
	zoomEnabled: true,
	title: {
		text: "" // title of the graph to be put here
	},
	axisX: {
		title: "Time"
	},
	axisY:{
		prefix: "",
		includeZero: false
	}, 
	toolTip: {
		shared: true
	},
	legend: {
		cursor:"pointer",
		verticalAlign: "top",
		fontSize: 20,
		fontColor: "dimGrey",
		itemclick : toggleDataSeries
	},
	data: [{ 
		type: "line",
		xValueType: "dateTime",
		yValueFormatString: "####.00",
		xValueFormatString: "hh:mm:ss",
		showInLegend: true,
		name: "Rec",
		dataPoints: dataPoints1
		},
		{ 
		type: "line",
		xValueType: "dateTime",
		yValueFormatString: "####.00",
		xValueFormatString: "hh:mm:ss",
		showInLegend: true,
		name: "Rej",
		dataPoints: dataPoints2
		},
		{ 
		type: "line",
		xValueType: "dateTime",
		yValueFormatString: "####.00",
		xValueFormatString: "hh:mm:ss",
		showInLegend: true,
		name: "Proc",
		dataPoints: dataPoints3
		},
		{ 
		type: "line",
		xValueType: "dateTime",
		yValueFormatString: "####.00",
		xValueFormatString: "hh:mm:ss",
		showInLegend: true,
		name: "P.Licence",
		dataPoints: dataPoints4
		}
		/*,
		{				
			type: "line",
			xValueType: "dateTime",
			yValueFormatString: "####.00",
			showInLegend: true,
			name: "T.license" ,
			dataPoints: dataPoints5
	}*/
	]
});

function toggleDataSeries(e) {
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else {
		e.dataSeries.visible = true;
	}
	chart.render();
}

//var updateInterval = 3000;
// initial value

var dt = new Date();
dt.setHours(0,0,0,0);

<?php //echo $js_dstart; ?>

var currNetHour = new Date();
currNetHour.setHours(0,0,0,0);

//dlastmid.setHours(0,0,0,0); // last midnight

var curr_sec = currNetHour.getSeconds(); curr_sec=("0" + curr_sec).slice(-2);
var curr_min = currNetHour.getMinutes(); curr_min=("0" + curr_min).slice(-2);
var curr_hrs = 0;  curr_hrs=("0" + curr_hrs).slice(-2);
var curr_date = currNetHour.getDate(); curr_date=("0" + curr_date).slice(-2);
var curr_month = currNetHour.getMonth()+1; curr_month=("0" + curr_month).slice(-2);
var curr_year = currNetHour.getFullYear(); curr_year=("0" + curr_year).slice(-4);
<?php echo $js_full_currHr; ?> 



function updateChart(count) {
	//count = count || 1;
	count = countLimit;
	var deltaY1, deltaY2,deltaY3,deltaY4;
	for (var i = -1; i < count; i++) {
		
		var dt = new Date(full_currHr);
		 dt.setSeconds(dt.getSeconds() +i);
					 
var curr_sec = dt.getSeconds(); curr_sec=("0" + curr_sec).slice(-2);
var curr_min = dt.getMinutes(); curr_min=("0" + curr_min).slice(-2);
var curr_hrs = dt.getHours();  curr_hrs=("0" + curr_hrs).slice(-2);
var curr_date = dt.getDate(); curr_date=("0" + curr_date).slice(-2);
var curr_month = dt.getMonth()+1; curr_month=("0" + curr_month).slice(-2);
var curr_year = dt.getFullYear(); curr_year=("0" + curr_year).slice(-4);
var newDate=(curr_year + "-" + curr_month + "-" + curr_date + " " + curr_hrs + ":" + curr_min + ":" + curr_sec);

		
		var Received = ReccArr[i];
					var Proccessed = ProccArr[i];
					var Rejected = RejjArr[i];
					var PermanentLicensed = permanentLicenseArr[i];
					//var tempLicensed = tempLicenseArr[i];
					
		deltaY1=Math.floor(Received);  
		deltaY2=Math.floor(Rejected);  
		deltaY3=Math.floor(Proccessed); 
       deltaY4=Math.floor(PermanentLicensed); 
       //deltaY5=Math.floor(tempLicensed);	   
		
       
	
	yValue1 = deltaY1;
	yValue2 = deltaY2;
	yValue3 = deltaY3;
	yValue4 = deltaY4;
	//yValue5 = deltaY5;

	// pushing the new values
	dataPoints1.push({
		x: dt.getTime(),
		y: yValue1
	});
	dataPoints2.push({
		x: dt.getTime(),
		y: yValue2
	});
	dataPoints3.push({
		x: dt.getTime(),
		y: yValue3
	});
	dataPoints4.push({
		x: dt.getTime(),
		y: yValue4
	});
	/*dataPoints5.push({
		x: dt.getTime(),
		y: yValue5
	});*/
	}

	// updating legend text with  updated with y Value 
	chart.options.data[0].legendText = " Rec ";
	chart.options.data[1].legendText = " Rej "; 
	chart.options.data[2].legendText = " Proc "; 
	chart.render();
}
// generates first set of dataPoints 
updateChart(100);	
setInterval(function(){updateChart()}, updateInterval);
myChart.updateChart();
//}
</script>

</body>
</html>