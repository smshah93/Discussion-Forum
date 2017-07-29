<?php 
$servername = " ";
$username= "username";
$password = "password";
$db="db_name";
$conn = mysqli_connect($servername,$username,$password,$db); 

 if(!$conn) {
    die("Connection failed: " .mysqli_connect_error());
}
//echo "Connected successfully";

?>