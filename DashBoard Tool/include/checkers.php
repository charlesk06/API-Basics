<?php 
include('contelepin.php');
#############################################################
		/*Check Data Existence exitance*/
#############################################################
// code for Service MSISDN
if(!empty($_POST["msisdn"])) {
$msisnNo= mysql_real_escape_string($_POST["msisdn"]);
$code = 255;
$msisnNo=$code.$msisnNo;
$msisdnsql ="SELECT MSISDN FROM disbursement_accounts WHERE MSISDN='".$msisnNo."'";
$msisdnquery = mysql_query($msisdnsql);
$cnt=1;
if(mysql_num_rows($msisdnquery) > 0)
{
 echo "<span style='color:#CC0000'> This MSISDN already exists .</span>";
 echo "<script>$('#submit').prop('disabled',true);</script>";
} else{
 echo "<script>$('#submit').prop('disabled',false);</script>";
}
}


// code for Service Nameavailablity
if(!empty($_POST["service_name"])) {
  $servName= $_POST["service_name"];
$namesql ="SELECT SERVICE_NAME FROM disbursement_accounts WHERE SERVICE_NAME='".$servName."'";
$namequery = mysql_query($namesql);
$cnt=1;
if(mysql_num_rows($namequery) > 0)
{
 echo "<span style='color:#CC0000'> Service Name already exists .</span>";
 echo "<script>$('#submit').prop('disabled',true);</script>";
} else{
 echo "<script>$('#submit').prop('disabled',false);</script>";
}
}




/*//Code for recipient checking existence
if (!empty($_POST['recipient']) && is_array($_POST['recipient'])) {
	$number = count($_POST["recipient"]);	

		if ($number > 0) {
		$servname =mysql_real_escape_string($_POST['servname']);
		$email =mysql_real_escape_string($_POST['email']);
		$status = 1;
		$sendInfo = 2;

		//get the msisdn from the servename provided
		$res = mysql_query( "SELECT * FROM `disbursement_accounts` WHERE SERVICE_NAME='$servname'");

		if ($res) {
		while ($fetchrow=mysql_fetch_array($res)) {
			$wallet_msisdn = $fetchrow['MSISDN'];
		}
	}
	for ($i=0; $i < $number; $i++) { 
	if(trim($_POST["recipient"][$i] != '')) { 
	$checkrecipient = mysql_query("SELECT WALLET_MSISDN, RECIPIENT_MSISDN FROM disbursement_recipients WHERE WALLET_MSISDN='$wallet_msisdn' AND RECIPIENT_MSISDN='".mysql_real_escape_string( $_POST["recipient"][$i])."'");
	
	$cnt = 1;

       if (mysql_num_rows($checkrecipient) > 0) {
        	echo "<span style='color:#CC0000'> Recipient already exists to this Account.</span>";
				echo "<script>$('#fub').prop('disabled',true);</script>";;
        }else{
        	echo "<script>$('#fub').prop('disabled',false);</script>";
        } 
   }  
}
	
}

	
}*/


 ?>