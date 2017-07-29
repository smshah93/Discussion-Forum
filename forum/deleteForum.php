<?php
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
	if (isset($_POST['approve']) && isset($_POST['checkbox'])) {
    foreach($_POST['checkbox'] as $app_id){
			//update as active if approved by the admin
			$sql = "UPDATE Forum SET ForumStatus='Active' WHERE ForumName='$app_id'";
			if (!mysqli_query($conn,$sql) )
			{
			   // an error eoccurred
			   $_SESSION['message'] ="Error approving the forum request: " . $app_id ;
			}
	}	
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=deleteForum.php\">";
	$_SESSION['message'] ="Selected Forum/s Restored!!";
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
			
			<table class="forum">
			<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

				<thead><tr><th>Name</th><th>Description</th><th>Moderator</th></tr></thead>
				
				<?php
				
				$sql="SELECT * from Forum where ForumStatus='Deleted'";
				$result=mysqli_query($conn,$sql);
				echo '<tbody>';
				while($row = mysqli_fetch_array($result))
				 {         
					echo '<tr>
						
						<td class="clickforum">'.$row['ForumName'].'</td>
						<td class="clickforum">'.$row['Description'].'</td>										
						<td class="clickforum">'.$row['Moderator'].'</td>
						<td><input type="checkbox" name="checkbox[]" value="'.$row['ForumName'].'" ></td>
					</tr>';
				 }
				 echo '</tbody>';
				 mysqli_close($conn); 
				 ?> 
				 
			</table>
			
			  <input type="submit" class="btn btn-primary" name="approve" value="Restore">

			</form>

		</div>
	</div>
	
	
<?php include '../footer.php'?>
	
</div>
<?php $_SESSION['message'] = "";?>
</body>
</html> 