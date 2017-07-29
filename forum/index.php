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
			<ul class="nav nav-pills nav-stacked">
				<li role="presentation"><a href="myforum.php">My Forum/s</a></li>
				<li role="presentation"><a href="createForum.php">Create Forum</a></li>
				

				<?php if ($status == 'Admin') {
					echo '<li role="presentation"><a href="deleteForum.php">Deleted Forums</a></li>';
					echo '<li role="presentation"><a href="manageForum.php">Manage Forums</a></li>';
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
			
			<table class="forum">
				<thead><tr><th>Name</th><th>Description</th><th>Moderator</th></tr></thead>
				
				<?php
				
				$sql="SELECT * from Forum where ForumStatus='Active'";
				$result=mysqli_query($conn,$sql);
				echo '<tbody>';
				while($row = mysqli_fetch_array($result))
				 {         
					echo '<tr>
						<td class="clickforum" onclick="location.href=\'threads/?forumID='.$row['ForumName'].'\'" >'.$row['ForumName'].'</td>
						<td class="clickforum" onclick="location.href=\'threads/?forumID='.$row['ForumName'].'\'" >'.$row['Description'].'</td>										
						<td class="clickforum" onclick="location.href=\'threads/?forumID='.$row['ForumName'].'\'" >'.$row['Moderator'].'</td>
					</tr>';
				 }
				 echo '</tbody>';
				 mysqli_close($conn); 
				 ?> 
				 
			</table>
		</div>
	</div>
	
	
<?php include '../footer.php'?>
	
</div>
<?php $_SESSION['message'] = "";?>
</body>
</html>