<?php
/* Displays user information and some useful messages */
session_start();
require_once('../../connect.php');
$_SESSION['message'] = "";

$forum = $_GET["forumID"];
//add check if forum exist 
$fcheck = mysqli_query($conn, "Select * from Forum where ForumName='$forum'"); 
if(!mysqli_fetch_assoc($fcheck)) {
	$_SESSION['message'] = "Forum doesn't exist!";
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

//check if banned from forum 
$test = mysqli_query($conn, "Select * from Ban where ForumID='$forum' and BannedUser='$username' LIMIT 1"); 
if($row = mysqli_fetch_array($test) ) {
	$_SESSION['message'] = "You are banned from ". $forum . "!";
	header("Location: ../index.php");
}

if (isset($_POST['createthread']) ) {
//---------------------------------------------------------
		//Folder to place items
		$target_dir = 'images/'; 
	
		//Make a unique file name 
		$fn = str_replace(' ','',trim($_POST["title"])); //take out all whitespace 
		$temp = explode(".", $_FILES["fileToUpload"]["name"]);
		$id = round(microtime(true)) . '.' . end($temp);
		
		$target_file = $target_dir . $fn . $id;
				
		$uploadOk = 1;
		//find the file extension
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$_SESSION['message'] = $_SESSION['message'] ."</br>File is an image - " . $check["mime"] . ".";
				$uploadOk = 1; 
			} else {
				$_SESSION['message'] = $_SESSION['message'] . "</br>File is not an image.";
				$uploadOk = 0;
			}
		}
		
		// Check if file already exists
		if (file_exists($target_file)) {
			$_SESSION['message'] = $_SESSION['message'] ."</br>Sorry, file already exists.";
			$uploadOk = 0;
		}
		
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 50000000) {
			$_SESSION['message'] = $_SESSION['message'] . "</br>Sorry, your file is too large.";
			$uploadOk = 0;
		}
		
		$info = getimagesize($_FILES['fileToUpload']['tmp_name']);
		//print_r($info); 
		if ($info == FALSE) {
		   $_SESSION['message'] = $_SESSION['message'] . "</br>Sorry, not an image.";
			$uploadOk = 0;
		}
		
		
//add to Database=========================================
		
			//set all the post variables, username, title, file location, and caption
			//file location $target_file
			//username $username
			$title = mysqli_real_escape_string($conn, $_POST['title']);
			$caption = mysqli_real_escape_string($conn, $_POST['caption']);
			$ident = rand(1,99) + round(microtime(true) / 100) ; 
			
			//insert user data into database
			$sql = "INSERT INTO Thread VALUES ('$forum', '$ident', '$caption', '$title', NOW(), 'Active', '$username', '$target_file');";		

						
			//if the query is successsful, redirect to welcome.php page, done!
			if (mysqli_query($conn, $sql) == true){
			
				// Then upload file to directory 
				if ($uploadOk == 0) {
					$sql = "DELETE FROM Thread WHERE Picture='$target_file';";	
					mysqli_query($conn, $sql);		
					$_SESSION['message'] = $_SESSION['message'] . "<br />Sorry, your file was not uploaded.";
					echo "<script>setTimeout(function(){window.location.href='index.php?forumID=".$forum."'},8000);</script>";	
					
				// if everything is ok, try to upload file
				} else {
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
						$test = true; 
					} else {
						$test = false; 
					}
				}	
					
				//check if upload successful 
				if($test == true && $uploadOk != 0) {
					$_SESSION['message'] = $_SESSION['message'] . "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.
					</br>Upload successful!";
				}
				else {
					$sql = "DELETE FROM Thread
								WHERE Thread= '$ident';";	
					mysqli_query($conn, $sql);			
					$_SESSION['message'] = $_SESSION['message'] . "Sorry, there was an error uploading your file.";
				}					
				
			}
			else {
				$_SESSION['message'] = 'Something went wrong with database!';
			}          
					
				
	echo "<script>setTimeout(function(){window.location.href='index.php?forumID=".$forum."'},8000);</script>";		
} //end if form 

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
		
			<h4>Create Thread</h4>
				<hr>
				<form action="create.php<?php echo '?forumID='.$forum;?>" method="post" enctype="multipart/form-data" autocomplete="off">
					<div class="form-group">
					  <label for="email">Title:</label>
					 <input type="text" placeholder="Thread Title" name="title" required maxlength="32" />
					</div>
					<div class="form-group">
					  <label for="email">Description:</label>
					  <textarea type="text"  placeholder="Description" name="caption" required maxlength="800""></textarea>
					</div> 
					<div class="form-group">
					  <label for="email">Picture:</label>
					  <input type="file" name="fileToUpload" id="fileToUpload"> </br>
					</div> 						  
				  <input type="submit" class="btn btn-primary" value="createthread" name="createthread" />
				</form>  
		</div>
	</div>
	
	
<?php include '../../footer.php'?>
	
</div>
<?php $_SESSION['message'] = "";?>
</body>
</html>