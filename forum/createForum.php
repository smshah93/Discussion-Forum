<?php
session_start();
require_once('../connect.php');
$_SESSION['message']="";
// Check if user is logged in using the session variable
if(!isset($_SESSION["username"])){	
	$_SESSION['message'] = "You must log in before viewing your profile page!";
	header("Location: ../index.php");
	
	exit(); 
}
else{
$mod=$_SESSION["username"];
if(isset($_POST['submit']))
{
$forumName=mysqli_real_escape_string($conn, $_POST['forumName']);
$sql="Select ForumName from Forum where ForumName='$forumName';";
$result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result)!=0)
{
$_SESSION['message']="Forum with the same name already exist";
}
else
{
$_SESSION['message']="Forum request sent to admin for approval";
$forumDescription=mysqli_real_escape_string($conn, $_POST['forumDescription']);
$mod=mysqli_real_escape_string($conn,$mod);
mysqli_query($conn,"Insert into Forum (ForumName,Description,ForumStatus,Moderator) values ('$forumName', '$forumDescription', 'Need', '$mod')");
}
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>create Forum</title>

 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" href="../main.css" />
  
</head>
<body>
<div class="container">


<?php include '../header.php'?>

	<div class="row">
		<div class="menu">
			
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
		
		<!-- OR --> 
		
		<div class="main">
		<form id="myForm" method="post" action="<?php $_SERVER['PHP_SELF'] ?>" >
		 <label>Forum Name</label><input type="text" name="forumName" required/><br>
		 <label>Forum Description</label><br><textarea rows="4" cols="30" name="forumDescription" required> </textarea><br>
		 <input type="submit" name="submit" />
		</form>
		</div>
		
	</div>
	
	<!-- Footer here-->
	
</div>
<script>
function myFunction() {
    document.getElementById("myForm").reset();
}
</script>
</body>
</html>