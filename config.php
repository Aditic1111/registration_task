<?php
session_start();
$db_name = "registration_task";
$db = mysqli_connect("localhost","root","",$db_name);
if(!$db){
	echo "Could not connect to server! Please try again";
	exit();
}
?>