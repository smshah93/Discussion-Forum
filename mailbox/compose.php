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
	$_SESSION['message'] = "";
    $username = $_SESSION['username'];
}

if(isset($_POST['reply']) ) {
	$sendTo = $_POST['sento']; 
}
else {
	$sendTo = ""; 
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
				  <li role="presentation" class="active"><a href="compose.php">Compose</a></li>
				  <li role="presentation"><a href="index.php">Inbox</a></li>
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
			
			<div class="compose"> 				
				<h4>Send Mail</h4>
				<hr>
				<form action="sending.php" method="post" enctype="multipart/form-data" autocomplete="off">
					<div class="form-group">
					  <label for="email">Send to:</label>
					 <input type="text" placeholder="Send to" name="reciever" required maxlength="32" <?php echo 'value="'.$sendTo.'" ';?>/>
					</div>
					<div class="form-group">
					  <label for="email">Subject:</label>
					  <input type="text" placeholder="Subject" name="subject" required maxlength="50" />
					</div> 
					<div class="form-group">
					  <label for="email">Message:</label>
					  <textarea rows="4" cols="50" placeholder="Message" name="message" required maxlength="500" /></textarea>
					</div> 						  
				  <input type="submit" class="btn btn-primary" value="Send" name="sending" />
				</form>     
			</div>	
	</div>
	
	<?php include_once '../footer.php';?>
</div>
</body>
</html>