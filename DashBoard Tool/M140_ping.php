<?php
include('/var/www/html/telepin/Net/SFTP.php');
require_once "/var/www/html/telepin/coreUtilsnot.php";
require_once "/var/www/html/telepin/coreConfigsnot.php";
include_once "/var/www/html/telepin/aalog.php";
include_once "/var/www/html/telepin/encrypt_decrypt.php";
require_once "/var/www/html/telepin/PHPMailer/PHPMailerAutoload.php";
header('Content-type: text/plain'); 
$alert_message ="There is a high possibility Middleware05 is down , please check and act ASAP!";
//$alert_message="MW05 is Alive";

$link = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Connection error : ' . mysql_error());
$db_selected = mysql_select_db($dbname, $link) or die ('Can\'t use tanzania : ' . mysql_error());

//Send  packet to M140
//sh /home/wlapp/scripts/M140_ping.sh;

//$alive=`awk '{print $3}' /var/tmp/mw140`;

//if ($status =!0){       
$qry_sn ="select msisdn,email_id,grp_level from middleware_alarm where status=1";
$rs2 = selectSQL($qry_sn, $link);
//send alarms
while ($row = mysql_fetch_array($rs2)) {
    $msisdn=$row[0];
    $email_id=$row[1];
    $grp_level=$row[2];
        if ($grp_level==0)
    {
    sendMessage("TigoTCS",$msisdn,"Middleware Connection Alarm:".$alert_message,0,$link);
    }
}

//}       

?>
