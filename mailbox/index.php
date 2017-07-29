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
$_SESSION['message'] = "";
require_once('../connect.php');

//remove from the database if both deleted 
$sql2="delete from Messages where (MsgStatus='Deleted' and SentStatus='Deleted')";
mysqli_query($conn,$sql2);

//if checked box and pressed deleted button
if (isset($_POST['del']) && isset($_POST['checkbox'])) {
    foreach($_POST['checkbox'] as $del_id){
			//update as deleted to move to trash
			$sql = "UPDATE Messages SET MsgStatus='Trash' WHERE MsgID='$del_id'";
			if (!mysqli_query($conn,$sql) )
			{
			   // an error eoccurred
			   $_SESSION['message'] ="Error could not delete message: " . $del_id ;
			}
	}	
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
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
				  <li role="presentation" class="active"><a href="index.php">Inbox</a></li>
				  <li role="presentation"><a href="sent.php">Sent</a></li>
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

			
			<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
				<table  class="mailbox">
				<tr><th>Delete</th><th>From</th><th>Subject</th><th>Time</th></tr>
				
				<?php
				
				$sql="SELECT * FROM Messages WHERE Receiver='$username' and MsgStatus!='Trash' and MsgStatus !='Deleted' ORDER BY MsgTime DESC";
				$result=mysqli_query($conn,$sql);

				while($row = mysqli_fetch_array($result))
				 {         
							   echo '<tr class="'.$row['MsgStatus'].'" >
									<td><input type="checkbox" name="checkbox[]" value="'.$row['MsgID'].'" ></td>
									<td class="clickmail" onclick="location.href=\'read.php?msgID='.$row['MsgID'].'\'" >
										<span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp;'.$row['Sender'].'</td>
									<td class="clickmail" onclick="location.href=\'read.php?msgID='.$row['MsgID'].'\'" >'.$row['Subject'].'</td>
									<td class="clickmail" onclick="location.href=\'read.php?msgID='.$row['MsgID'].'\'" >'.$row['MsgTime'].'</td>	
								</tr>';
				 }
				 mysqli_close($conn); 
				 ?> 
				 
				</table>
				<input type="submit" class="btn btn-danger" name="del" value="Delete Message">
			</form>
		</div>
	</div>
	
	<?php include_once '../footer.php';?>
	
</div>
<?php $_SESSION['message'] = "";?>
</body>
</html>