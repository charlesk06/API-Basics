<?php include('include/contelepin.php'); 
	include('/var/www/html/telepin/Net/SSH2.php');
include_once "/var/www/html/telepin/encrypt_decrypt.php";
header('Content-type: text/plain'); 
define('NET_SFTP_LOGGING', NET_SFTP_LOG_SIMPLE);

	ini_set('display_errors', 1);
				ini_set('memory_limit', '-1');

		$error_message=""; $inp_startDate=""; $inp_endDate="";
		$ErrorHideSeL=0; $Error_sms=""; $timeAgo_int=0;
		$conditionalWhere="WHERE disp_level <='1000'"; $brandname_sms="All Services";

		$queryMaxdate=mysql_query("select max(dtime) as maxdtime FROM dboard_data");
		$fetchMaxdate=mysql_fetch_assoc($queryMaxdate);
		$maxdtime=$fetchMaxdate['maxdtime']; 

		$datefrom = strtotime($maxdtime.' - 60 minute');
		$start_date=date('Y-m-d H:i:s',$datefrom);	

		$whereTimeRange="AND dtime>=DATE_SUB('$maxdtime', INTERVAL 1 HOUR)";

	
$ftp_IP="10.99.2.1";
$ftp_Port="22";

//$salida = shell_exec('ls -ltrh');
//telnet 10.99.1.161 6060 & sleep 5 ; kill $!
$ftp_RPT='telnet '.$ftp_IP.' '.$ftp_Port." & sleep 1 ; kill $!";
$output_ftp_RPT = shell_exec($ftp_RPT);

if(stristr($output_ftp_RPT,'Connected')===FALSE){ 
$ftp_net_msg="reporting server sftp service is down"; $ftp_byPassbg="background-color:red;"; $ftp_NetStatus="border-radius:15px;background-color:red;border: 3px solid red;"; 
}else{ 
$ftp_net_msg="reporting server sftp service is UP and running"; $ftp_byPassbg=""; $ftp_NetStatus="border-radius:15px;background-color:#32CD32;border: 3px solid #32CD32;";  
}
?>