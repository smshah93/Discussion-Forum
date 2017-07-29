<?php
/* Displays user information and some useful messages */
session_start();
require_once('../connect.php');

// Check if user is logged in using the session variable
if(!isset($_SESSION["username"])){
	$_SESSION['message'] = "You must log in before viewing your profile page!";
	header("Location: ../login.php");
	exit(); 
}
else {
    // Makes it easier to read
    $username = $_SESSION['username'];
}

			//get number of mail and add one to create id
			$id =  rand(1,99) + round(microtime(true) / 100) ; 
			$sub = $_POST['subject']; 
			$msgtxt = mysqli_real_escape_string($conn,$_POST['message']); 
			$reciever = strtolower($_POST['reciever']);
			
			//check if user exists
			$check = mysqli_query($conn,"SELECT * FROM Members WHERE UserName='$reciever'"); 
			if ( mysqli_num_rows($check) == 0 ){ // User doesn't exist
				$_SESSION['message'] = "User with that username doesn't exist! Cannot send message.";
			}
			else { 
				//insert new message 
				$sql = "INSERT INTO Messages (MsgID, MsgTime, Subject, MsgText, Sender, Receiver, MsgStatus, SentStatus)
						VALUES ('$id', NOW(), '$sub', '$msgtxt', '$username', '$reciever', 'Unread', 'Sent')";
					
				//if the query is successful, message sent
				if (!mysqli_query($conn,$sql) ){
					$_SESSION['message'] = "Failed to send message! " . $id;
				}
				else {
					$_SESSION['message'] = 'Message Sent Successfully!';
				}
			}
            mysqli_close($conn);     

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Mailbox for  <?php echo $username; ?></title>

 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" href="../main.css" />
  
</head>
<body>
<div class="container"  >

<?php include_once '../header.php';?>

	<div class="row">
		<div class="side-menu">
			<ul class="nav nav-pills nav-stacked">
				  <li role="presentation" class="active"><a href="compose.php">Compose</a></li>
				  <li role="presentation"><a href="index.php">Inbox</a></li>
				  <li role="presentation"><a href="sent.php">Sent</a></li>
				  <li role="presentation"><a href="trash.php">Trash</a></li>
			</ul>
		</div>

		<div class="side-main">
				
			<h1>Send Mail Status:</h1>         
			<?php 			
					echo '<div class="alert alert-info" '.$block .' role="alert">
						'.$_SESSION['message'].'
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
						</button>
					</div>';
			?>	
		</div>

	</div> 
	
<?php include_once '../footer.php';?>
<?php $_SESSION['message'] = "" ?>
	<!-- refresh to another page in 10 seconds -->
	<script>setTimeout(function(){window.location.href='index.php'},8000);</script>	
</div>
</body>
</html>