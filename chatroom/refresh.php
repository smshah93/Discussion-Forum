<?php
$con = mysqli_connect("ecsmysql","cs431s29","aingohye");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysqli_select_db($con,"cs431s29");

$result = mysqli_query($con,"SELECT * FROM chat_messages ORDER BY id ASC");


while($row = mysqli_fetch_array($result))
  {
  echo '<p>'.'<span>'.$row['sender'].'</span>'. '&nbsp;&nbsp;' . $row['message'].'</p>';
  }

mysqli_close($con);
?>
