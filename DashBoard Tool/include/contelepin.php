<?php
date_default_timezone_set('Africa/Dar_es_Salaam');
$dbname = 'telepin';
$dbhost = '10.76.107.155';
$dbuser = 'mfs';
$dbpass = 'mfs123';

$link = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Connection error : ' . mysql_error());
$db_selected = mysql_select_db($dbname, $link) or die ('Can\'t use telepin : ' . mysql_error());

$db_oracle = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.76.107.164)(PORT = 1521)))(CONNECT_DATA=(SID=mmoney)))";

$conn_oracle = oci_connect('tigopeesa', 'WaeFeso_1234',$db_oracle);

if (!$conn_oracle) {
    	$e = oci_error();
    	echo htmlentities($e['message'], ENT_QUOTES);
		
	}else{
	  //echo "<p>Connected successfully ";
	}

?>
