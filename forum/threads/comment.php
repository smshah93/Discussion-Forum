<?php
/* Displays user information and some useful messages */
session_start();
require_once('../../connect.php');
$_SESSION['message'] = "";

// Check if user is logged in using the session variable
if(!isset($_SESSION["username"])){	
	$_SESSION['message'] = "You must log in before viewing your profile page!";
	header("Location: ../../index.php");
	
	exit(); 
}
else {		
	$username = $_SESSION['username'];
}

$thread = $_GET["threadID"];

//add check if thread exist 
$tcheck = mysqli_query($conn, "Select * from Thread where ThreadNo='$thread'"); 
if(!$thr = mysqli_fetch_assoc($tcheck)) {
	$_SESSION['message'] = "Thread doesn't exist!";
	header("Location: ../index.php");
	
	exit(); 
} 
else {
	$forum = $thr['Forum'];
}


//for editting comments
$comment = '';
if(isset($_GET['commentID']) ) {
	$cid = $_GET['commentID'];
	$co = mysqli_query($conn, "Select PostText from Post where PostNo='$cid' LIMIT 1"); 
	if($csql = mysqli_fetch_assoc($co) ) {
		$comment = $csql['PostText'];
		if(!mysqli_query($conn, "Delete from Post where PostNo='$cid' LIMIT 1")) {
			$_SESSION['message'] = "failed to edit comment";
			header('index.php?forumID='.$thr['Forum']);
		}
	}
}

//post a comment
if(isset($_POST['postID']) ) {
	$comment = $_POST['testcomment'];	
	$id =  rand(1,99) + round(microtime(true) / 100) ; 
	
	if(!mysqli_query($conn, "Insert into Post Values('$forum','$thread','$id',NOW(),'$comment','$username');") ) {
		$_SESSION['message'] = "Failed to post comment!";
	}
	else {
		$_SESSION['message'] = "Comment posted!";
	}
	echo "<script>setTimeout(function(){window.location.href='thread.php?threadID=".$thread."'},8000);</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Post Comment</title>

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
					<li role="presentation"><a href="index.php?forumID=<?php echo $forum;?>">Back to Forum</a></li>
					<li role="presentation"><a href="thread.php?threadID=<?php echo $thread;?>">Back to Thread</a></li>					
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
		
			<h4>Create Post</h4>
				<hr>
				<form action="comment.php?threadID=<?php echo $thread;?>" method="post" enctype="multipart/form-data" autocomplete="off">
					<div class="form-group">
					  <label for="email">Post:</label><br />
					  <textarea name="testcomment" placeholder="Comment" required maxlength="500"><?php echo $comment?></textarea>					 
					</div>								  
				  <input type="submit" class="btn btn-primary" value="post" name="postID" />
				</form>  
		</div>
	</div>
	
	
<?php include '../../footer.php'?>
	
</div>
<?php $_SESSION['message'] = "";?>

</body>
</html>