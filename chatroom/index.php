<?php
/* Displays user information and some useful messages */
session_start();
require_once('../connect.php');
$_SESSION['message'] ="";
// Check if user is logged in using the session variable
if(!isset($_SESSION["username"])){	
	$_SESSION['message'] = "You must log in before viewing your profile page!";
	header("Location: ../index.php");
	
	exit(); 
}
else {		
	$username = $_SESSION['username'];
	$result = mysqli_query($conn, "Select Status from Members where UserName='$username' LIMIT 1"); 
	$row = mysqli_fetch_assoc($result); 
	$status = $row['Status'];
	if($status == 'Banned') {
		$_SESSION['message'] = "You are banned!";
		header("Location: ../index.php");
	}
	
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simple Chat</title>
<script language="javascript" src="jquery-1.2.6.min.js"></script>
<script language="javascript" src="jquery.timers-1.0.0.js"></script>


</head>
   <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../main.css" />
<body>
<div class="container" >

	<?php include_once '../header.php';?>

    <div class="row">
		<div class="side-menu">
			<ul class="nav nav-pills nav-stacked">
				<li role="presentation"><a href="createChat.php">Create Chat</a></li>
				
				<?php if ($status == 'Admin') {
					echo '<li role="presentation"><a href="deleteChat.php">Delete Chat</a></li>';
				}				
				?>
			</ul>
			</ul>
		</div> 
		
		<div class="side-main"> 
		
			<ul class="nav nav-pills nav-stacked">
            <?php
		    $sql="SELECT ChatName,RoomNo from Chatroom";
                    //$hi ="SELECT Roomid from Chatroom";
		    $result=mysqli_query($conn,"SELECT ChatName, RoomNo from Chatroom") or die($posts . "<br/>" . mysqli_error($conn));
                    //$result2=mysqli_query($conn,$hi);
                    $numrows = mysqli_num_rows($result);
                    $num = 1;
                    while($row = mysqli_fetch_array($result))
                     {  
                        echo '<li id = $num role="presentation"><a href="chat.php?roomID='.$row['RoomNo'].'">'.$row['ChatName'].'</a></li>';
                        
                        if($num < $numrows)
                        {
                            $num = $num + 1;
                        }
                        
                     }
            ?>
			</ul>
		</div>		
	</div>
	
    <?php include_once '../footer.php';?>
</div>
</body>
</html>