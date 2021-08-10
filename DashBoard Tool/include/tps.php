<!DOCTYPE HTML>
<html>
<head>
<title>Tigopesa TPS Chart</title>

			
<meta charset="UTF-8">	

</head>
<body>
<?php 
 
                header( "refresh:120;url=tps.php" );
				error_reporting(E_ALL);
				ini_set('display_errors', 1);
				ini_set('memory_limit', '-1');
				require_once "mfs_db.php";  
				$datet="";
				$currdate=date('Y-m-d 00:00:00'); 
				$dateLeo=date('Y-m-d'); 
			
				
				$link = new mysqli($dbhost, $dbuser, $dbpass,$dbname) or die ('Connection error : ' . mysql_error());
				
				if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}

// 2. Select a database to use 
$db_select = mysqli_select_db($link,$dbname);
if (!$db_select) {
    die("Database selection failed: " . mysqli_error($connection));
}

				//$db_selected = mysqli_select_db($dbname, $link) or die ('Can\'t use telepin : ' . mysql_error());

				$L=0; $cRej=0;

				$QueryTPS=mysqli_query($link,"SELECT CONCAT(txn_date, ' ', txn_time) AS contxn_date,rec,proc,rej FROM tps WHERE CONCAT(txn_date, ' ', txn_time)>='$currdate' ORDER BY txn_date,txn_time ASC") or die(mysql_error());
				while($FetchTPS=mysqli_fetch_assoc($QueryTPS)){
				
				$newtimestamp = strtotime($currdate.' + '.$L.' Second');
				$txn_dateArr[$L]=date('Y-m-d H:i:s', $newtimestamp);			
				$txn_datef=$FetchTPS['contxn_date'];
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
				$ReccArr[$L]=$FetchTPS['rec']; 
				$ProccArr[$L]=$FetchTPS['proc']; 
				$RejjArr[$L]=$FetchTPS['rej'];
				
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
<div id="chartContainer" style="height: 1000px; max-width: 2100px; margin: 0px auto;"></div>
<script src="include/canvasjs.min.js"></script>
<script>
window.onload = function () {

var txn_dateArr = <?php echo json_encode($txn_dateArr); ?>;
		var ReccArr = <?php echo json_encode($ReccArr); ?>;
		var ProccArr = <?php echo json_encode($ProccArr); ?>;
		var RejjArr = <?php echo json_encode($RejjArr); ?>;
		
var dataPoints1 = [];
var dataPoints2 = [];
var dataPoints3 = [];

var chart = new CanvasJS.Chart("chartContainer", {
	zoomEnabled: true,
	title: {
		text: "Tigopesa TPS Chart <?php echo $dateLeo; ?> (Tps Vs Time )"
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
		fontSize: 30,
		fontColor: "dimGrey",
		itemclick : toggleDataSeries
	},
	data: [{ 
		type: "line",
		xValueType: "dateTime",
		yValueFormatString: "####.00",
		xValueFormatString: "hh:mm:ss",
		showInLegend: true,
		fontSize: 12,
		name: "Rec",
		dataPoints: dataPoints1
		},
		{ 
		type: "line",
		xValueType: "dateTime",
		yValueFormatString: "####.00",
		xValueFormatString: "hh:mm:ss",
		showInLegend: true,
		fontSize: 12,
		name: "Rej",
		dataPoints: dataPoints2
		},
		{				
			type: "line",
			xValueType: "dateTime",
			yValueFormatString: "####.00",
			showInLegend: true,
			name: "Proc" ,
			dataPoints: dataPoints3
	}]
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

var currNetHour = new Date();

//dlastmid.setHours(0,0,0,0); // last midnight

var curr_sec = currNetHour.getSeconds(); curr_sec=("0" + curr_sec).slice(-2);
var curr_min = currNetHour.getMinutes(); curr_min=("0" + curr_min).slice(-2);
var curr_hrs = 0;  curr_hrs=("0" + curr_hrs).slice(-2);
var curr_date = currNetHour.getDate(); curr_date=("0" + curr_date).slice(-2);
var curr_month = currNetHour.getMonth()+1; curr_month=("0" + curr_month).slice(-2);
var curr_year = currNetHour.getFullYear(); curr_year=("0" + curr_year).slice(-4);
var full_currHr =(curr_year + "-" + curr_month + "-" + curr_date + " " + curr_hrs + ":00:00");



function updateChart(count) {
	//count = count || 1;
	count = (24*3600);
	var deltaY1, deltaY2,deltaY3;
	for (var i = 0; i < count; i++) {
		
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
					
		deltaY1=Math.floor(Received);  
		deltaY2=Math.floor(Rejected);  
		deltaY3=Math.floor(Proccessed);  
		
       
	
	yValue1 = deltaY1;
	yValue2 = deltaY2;
	yValue3 = deltaY3;

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

}
</script>
</body>
</html>