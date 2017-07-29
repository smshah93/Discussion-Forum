<?php
/* Displays user information and some useful messages */
session_start();
require_once('../../connect.php');

$thread = $_GET["threadID"];
//add check if thread exist 
$tcheck = mysqli_query($conn, "Select * from Thread where ThreadNo='$thread' LIMIT 1"); 
if(!$info = mysqli_fetch_assoc($tcheck)) {
	$_SESSION['message'] = "Thread doesn't exist!";
	header("Location: ../index.php");
	
	exit(); 
} 

// Check if user is logged in using the session variable
if(!isset($_SESSION["username"])){	
	$_SESSION['message'] = "You must log in before viewing your profile page!";
	header("Location: ../../index.php");
	
	exit(); 
}
else {		
	$username = $_SESSION['username'];	
}

$title= $info['Title'];
$description=$info['Description'];

if(isset($_POST['edit'])) {
	$title= $_POST['title'];
	$description=$_POST['desc'];
	
	$update = mysqli_query($conn, "Update Thread set Title='$title', Description='$description' where ThreadNo='$thread';"); 
	if(!update) {
		$_SESSION['message'] = "Database not working right now.";
		header("Location: thread.php?threadID=$thread");
	}
	else {
		$_SESSION['message'] = "Thread Updated";
		header("Location: thread.php?threadID=$thread");	
	}	
} 	



?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $forum; ?> Forum</title>

 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <script type="text/javascript" src="../../JQuery/jquery.tablesorter/jquery.tablesorter.js"></script> 
  
  <link rel="stylesheet" href="../../main.css" />
  
</head>
<body>
<div class="container"  >

<?php include '../../header.php'?>

	<div class="row">
		<div class="side-menu">
			<ul class="nav nav-pills nav-stacked">
				<li role="presentation"><a href="../index.php">All Forums</a></li>
				<li role="presentation"><a href="index.php?forumID=<?php echo $forum; ?>">Back to Forum</a></li>
				<?php if ($username == $mod) {
					echo '<li role="presentation"><a href="process.php?MorID='.$thread.'">Close Thread</a></li>';
					echo '<li role="presentation"><a href="process.php?EPID='.$thread.'">Delete Thread</a></li>';
				}
				?>
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
		
			<form role="form" action="<?php echo $_SERVER['PHP_SELF']."?threadID=".$thread; ?>" method="post" name="loginform">
				<fieldset>
					<legend>Edit Thread</legend>
					
					<div class="form-group">
						<label for="name">Title</label>
						<input type="text" maxlength="32" name="title" value="<?php echo $title; ?>" required class="form-control" />
					</div>
					<div class="form-group">
						<label for="name">Description</label>
						<textarea class="form-control" rows="5" id="comment" maxlength="800" name="desc"  required ><?php echo $description; ?></textarea>
					</div>
					<div class="form-group">
						<input type="submit" maxlength="32" name="edit" value="Edit Confirm" class="btn btn-primary" />
					</div>
				</fieldset>
			</form>
			
		</div>
	</div>
	
	
<?php include '../../footer.php'?>
	
</div>


</body>
</html> 