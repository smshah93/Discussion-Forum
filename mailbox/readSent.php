<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
if(!isset($_SESSION["username"])){	
	$_SESSION['message'] = "You must log in before viewing your profile page!";
	header("Location: ../index.php");
	
	exit(); 
}
else {	
    // Makes it easier to read
    $username = $_SESSION['username'];
}

require_once('../connect.php');
$id = $_GET['msgID'];  

if (!isset($id) ) {
	$_SESSION['message'] = "No message to read";
	header("Location: index.php");
} 

$_SESSION['message'] = "";

//check if message already deleted 
$sql = "SELECT * FROM Messages WHERE MsgID='$id'";
$msg = mysqli_fetch_array(mysqli_query($conn,$sql) );

//if they pressed deleted 
if (isset($_POST['btnDelete']) ) {
	$id = $_POST['ID'];
    $update = "UPDATE Messages SET SentStatus='Deleted' WHERE MsgID='$id'";
	
	if (!mysqli_query($conn,$update) ) {
		echo "Error updating delete"; 
	}
	header("Location: index.php");
} 

	
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
				  <li role="presentation"><a href="compose.php">Compose</a></li>
				  <li role="presentation"><a href="index.php">Inbox</a></li>
				  <li role="presentation" class="active"><a href="sent.php">Sent</a></li>
				  <li role="presentation"><a href="trash.php">Trash</a></li>
			</ul>
		</div>

		<div class="side-main">
			
			<?php if ($_SESSION['message'] == "" ) { $block = 'style="display:none; margin:0; padding:0;"';}
			
				echo '<div class="alert alert-info" '.$block .' role="alert">
					'.$_SESSION['message'].'
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
					</button>
				</div>';
			?>	
			<div class='readmail'>
				<?php 
						echo "<div class='panel-body'><b>Subject: </b>". $msg['Subject']. "</div>
							<div class='panel-body'><b>Time: </b>". $msg['MsgTime']. "</div>
							<div class='panel-body'><b>To: </b>".	$msg['Receiver']. "</div>
							<div class='panel-body'><b>Message: </b></br>" .$msg['MsgText']."</div>";	
												
						echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post"> 
								<input type="text" style="display:none" name="ID" maxlength="10" value="'.$id.'" />
								<input type="submit" name="btnDelete" class="btn btn-danger" value="Delete Message">
							</form>';
						mysqli_close($conn); 
				?>
			</div>
		</div>
		
<?php include_once '../footer.php';?>
</div>
</body>
</html>