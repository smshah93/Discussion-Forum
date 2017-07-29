<?php
/* Displays user information and some useful messages */
session_start();
require_once('../../connect.php');

$thread = $_GET["threadID"];
//add check if thread exist 
$tcheck = mysqli_query($conn, "Select * from Thread where ThreadNo='$thread' LIMIT 1"); 
if(!mysqli_fetch_assoc($tcheck)) {
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
	
	$result = mysqli_query($conn, "Select Status from Members where UserName='$username' LIMIT 1"); 
	$row = mysqli_fetch_assoc($result); 
	$status = $row['Status'];
	
}

//get forum information and moderators
$getf = mysqli_query($conn, "Select * from Thread where ThreadNo='$thread' LIMIT 1"); 
$fresult = mysqli_fetch_assoc($getf); 
$forum = $fresult['Forum'];
$_SESSION['forum'] = $forum;

$sql = "SELECT Moderator from Forum where ForumName='$forum' LIMIT 1";
$forumResult = mysqli_query($conn, $sql); 
$info = mysqli_fetch_assoc($forumResult); 
$mod = $info['Moderator'];

//check if banned from forum 
$test = mysqli_query($conn, "Select * from Ban where ForumID='$forum' and BannedUser='$username' LIMIT 1"); 
if($row = mysqli_fetch_array($test) ) {
	$_SESSION['message'] = "You are banned from ". $forum . "!";
	header("Location: ../index.php");
}


//option to not be able to post if closed thread 
$closed = $fresult['ThreadStatus'];
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
				<?php if ($closed != "Deleted") { ?>
				<li role="presentation"><a href="comment.php?threadID=<?php echo $thread; ?>">Create Post</a></li>
				<?php } ?>
				<li role="presentation"><a href="index.php?forumID=<?php echo $forum; ?>">Back to Forum</a></li>
				<?php if ($username == $mod) {
					echo '<li role="presentation"><a href="process.php?MorID='.$thread.'">Close Thread</a></li>';
					echo '<li role="presentation"><a href="process.php?EPID='.$thread.'">Delete Thread</a></li>';
					echo '<li role="presentation"><a href="editThread.php?threadID='.$thread.'">Edit Thread</a></li>';
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
		
			<?php
				echo '<div class="page-header">
					  <h1>'.$fresult['Title'].'</h1><br />
					  <img style="max-width:400px;" src="'.$fresult['Picture'].'" class="rounded float-left" alt="'.$fresult['Picture'].'">
					  <h4><p>Description: '.$fresult['Description'].'</p></h4>
					  <small><p>By : '.$fresult['ThreadStartUser'].'</p></small>
					</div>';
			?>
			
			<div class="acidjs-rating-stars">
				<form action="process.php?SkyID=<?php echo $thread; ?>" method="post">
					<input type="radio" name="group-1" id="group-1-0" value="5" /><label for="group-1-0"></label>
					<input type="radio" name="group-1" id="group-1-1" value="4" /><label for="group-1-1"></label>
					<input type="radio" name="group-1" id="group-1-2" value="3" /><label for="group-1-2"></label>
					<input type="radio" name="group-1" id="group-1-3" value="2" /><label for="group-1-3"></label>
					<input type="radio" name="group-1" id="group-1-4"  value="1" /><label for="group-1-4"></label>
					
					<input style="display:block;padding:3px;float:right;border-radius: 10px;" class="btn-success" type="submit" name="rank" value="Submit" />
				</form>
			</div>
			
			<?php
				$sqlposts = "SELECT * from Post where ThrNo='$thread'";
				$postResult = mysqli_query($conn, $sqlposts); 
				
				while($post = mysqli_fetch_array($postResult))
				{    
					echo '<div class="panel panel-default">
						<div class="panel-heading">
						<strong>'.$post['PostUser'].'</strong> <span class="text-muted">'.$post['TimePosted'].'</span>
						</div>
						<div class="panel-body">
						'.$post['PostText'].'
						</div>';
						
					echo '<div class="panel-heading">';
					if($username == $post['PostUser']) {
						echo '<a style="margin:0 10px 0 10px;" class="post" href="comment.php?commentID='.$post['PostNo'].'">Edit</a>';
					}
					if($mod == $username ) {
						echo '<a style="margin:0 10px 0 10px;" class="post" href="process.php?TESID='.$post['PostNo'].'">Delete</a>  
							<a style="margin:0 10px 0 10px;" href="process.php?DCID='.$username.'">Ban from Forum</a>';
					}
					if($status == 'Admin' ) {
						echo '<a style="margin:0 10px 0 10px;" href="process.php?ADIO='.$username.'">Ban from website</a>';						
					}
						echo '</div>';
					echo '</div>'; //end panel full
				}
			?>
		</div>
	</div>
	
	
<?php include '../../footer.php'?>
	
</div>


</body>
</html> 