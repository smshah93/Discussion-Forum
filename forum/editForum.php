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

else
{       $username = $_SESSION['username'];
	$forumName=$_GET['forumID'];
	$result = mysqli_query($conn, "Select * from Forum where ForumName='$forumName' and ForumStatus='Active'");
	$row=mysqli_fetch_array($result);
   if(isset($_POST['edit']))
{	
$forumId=mysqli_real_escape_string($conn, $_POST['forumName']);
$sql2="Select ForumName from Forum where ForumName='$forumId'";
$result2 = mysqli_query($conn,$sql2);
$count=mysqli_num_rows($result2);
if($count>1)
{
$_SESSION['message']="Forum with the same name already exist. Try with different name";
}
else
{
   	$description=mysqli_real_escape_string($conn, $_POST['description']);
	$update = mysqli_query($conn, "Update Forum set ForumName='$forumId', Description='$description' where ForumName='$forumName'"); 
	if(!update) {
		$_SESSION['message'] = "Database not working right now.";
		header("Location: index.php");
	}
	else {
		$_SESSION['message'] = "Forum Updated";
			
	}	

}
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Forums</title>

 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <script type="text/javascript" src="../JQuery/jquery.tablesorter/jquery.tablesorter.js"></script> 
  <script type="text/javascript">
  $(document).ready(function() 
		{ 
			$(".forum").tablesorter(); 
		} 
	); 
	</script>
  <link rel="stylesheet" href="../main.css" />
  
</head>
<body>
<div class="container"  >

<?php include '../header.php'?>

	<div class="row">
		<div class="side-menu">
			
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
		</div>	
		 <div class="main">
			<form action="<?php echo $_SERVER['PHP_SELF']. "?forumID=".$forumName;?>" method="post">
				
		 		<label>Forum Name</label><input type="text" name="forumName" required/><br>
				 <label>Forum Description</label><br><textarea rows="4" cols="30" name="description" required value=" "> </textarea><br>
				 <input type="submit" name="edit" value="Confirm Edit" />
		
			
			</form>

		</div>
	</div>
	
	
<?php include '../footer.php'?>
	
</div>
<?php $_SESSION['message'] = "";?>
</body>
</html> 