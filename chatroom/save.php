<?php

if (isset($_POST['text'])) {

$con = mysql_connect("ecsmysql","cs431s29","aingohye");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("cs431s29", $con);

$ddd=$_POST['text'];
	$query = "INSERT INTO chat_messages (message) VALUES ('$ddd')";
	
}

?>