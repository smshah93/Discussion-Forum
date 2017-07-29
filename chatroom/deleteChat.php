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

  if (isset($_POST['del']) && isset($_POST['checkbox'])) {
    foreach($_POST['checkbox'] as $del_id){
			//delete chat contents 
			$sql = "DELETE from chat_messages where Roomno='$del_id';";
			if (!mysqli_query($conn,$sql) )
			{
			   // an error eoccurred
			   $_SESSION['message'] ="Error could not delete contents from " . $del_id ;
			}
			//delete chat users
			$sql = "DELETE from ChatUser where RNo='$del_id';";
			if (!mysqli_query($conn,$sql) )
			{
			   // an error eoccurred
			   $_SESSION['message'] ="Error could not chat users from " . $del_id ;
			}
			
			//delete chat itself
			$sql = "DELETE from Chatroom where RoomNo='$del_id';";
			if (!mysqli_query($conn,$sql) )
			{
			   // an error eoccurred
			   $_SESSION['message'] ="Error could not delete chatroom: " . $del_id ;
			}
	}	
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=deleteChat.php\">";
}
?>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simple Chat</title>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

</head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../main.css" />
<body>
<div class="container"> 
   
<?php include_once '../header.php';?>

    <div class="row">
		<div class="side-menu">
			
		</div>
		<div class="side-main">
			<form action="" method="post">
				<table style="margin:10px; padding:10px;">
					<tr><th style="padding:10px;"><a>Delete a Chat</a></th></tr>
					<?php
				$sql="SELECT ChatName,RoomNo from Chatroom";
						//$hi ="SELECT Roomid from Chatroom";
				$result=mysqli_query($conn,"SELECT ChatName, RoomNo from Chatroom") or die($posts . "<br/>" . mysqli_error($conn));
				
						$numrows = mysqli_num_rows($result);
						$num = 1;
						while($row = mysqli_fetch_array($result))
						 {  
							
							echo '<tr><td style="padding:10px;"><input type="checkbox"  value='.$row['RoomNo'].' name="checkbox[]">'.$row['ChatName'].'<br /></input></td></tr>';
							if($num < $numrows)
							{
								$num = $num + 1;
							}
							
						 }
						echo '</table>';
					echo '<input type="submit" name="del" value="Delete" class="btn btn-danger btn-sm">';		   
				?>				

			</form>	
		</div>
			
		
	</div>
	
<?php include_once '../footer.php';?>	
</div>
</body>
  
