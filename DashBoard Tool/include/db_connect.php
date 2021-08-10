<?php 
date_default_timezone_set('Africa/Dar_es_Salaam');
$dbname = 'telepin';
$dbhost = '10.76.107.155';
$dbuser = 'mfs';
$dbpass = 'mfs123';

//$link = mysqli_connect('localhost', 'root','', 'telepin');
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (mysqli_connect_errno()){
	echo "Failed to connect to Database ".mysqli_connect_errno();
}



 ?>
