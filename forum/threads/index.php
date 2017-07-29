<?php
/* Displays user information and some useful messages */
session_start();
require_once('../../connect.php');
$_SESSION['message'] = "";

$forum = $_GET["forumID"];
//add check if forum exist 
$fcheck = mysqli_query($conn, "Select * from Forum where ForumName='$forum' LIMIT 1"); 
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

//get forum information and moderators
$sql = "SELECT * from Forum where ForumName='$forum' LIMIT 1";
$forumResult = mysqli_query($conn, $sql); 
$info = mysqli_fetch_assoc($forumResult); 
$desc = $info['Description'];
$mod = $info['Moderator'];
$picture = $info['Picture'];

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
  
 <script>
	$.fn.stars = function() {
		return $(this).each(function() {
			// Get the value
			var val = parseFloat($(this).html());
			// Make sure that the value is in 0 - 5 range, multiply to get width
			var size = Math.max(0, (Math.min(5, val))) * 16;
			// Create stars holder
			var $span = $('<span />').width(size);
			// Replace the numerical value with stars
			$(this).html($span);
		});
	}
	
	$(function() {
		$('span.stars').stars();
	});
 </script>
  <link rel="stylesheet" href="../../main.css" />
  
</head>
<body>
<div class="container"  >

<?php include '../../header.php'?>

	<div class="row">
		<div class="side-menu">
			<ul class="nav nav-pills nav-stacked">
				<li role="presentation"><a href="../index.php">All Forums</a></li>
				<li role="presentation"><a href="create.php<?php echo '?forumID='.$forum;?>">Create Thread</a></li>
				<?php 			
				if ($status == 'Admin') {
					echo '<li role="presentation"><a href="../deleteForum.php?forumID='.$forum.'">Delete Forum</a></li>';
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
					  <h1>'.$forum.'</h1><br /><h4><p>Description: '.$desc.'</p></h4><small><p>Moderator: '.$mod.'</p></small>
					</div>';
			?>
		
			<table class="thread">
				<thead><tr><th>Name</th><th>Time</th><th>Creator</th><th>Rating</th></tr></thead>
				
				<?php
				
				$sql="SELECT * from Thread where Forum='$forum'";
				$result=mysqli_query($conn,$sql);
				echo '<tbody>';
				while($row = mysqli_fetch_array($result))
				 {        
					
					//calculate rank for each 
					//calculate rank
					$rsql = "Select AVG(Ranking) from Rank where TNo=".$row['ThreadNo']."";
					$r = mysqli_query($conn,$rsql);
					$ranking = mysqli_fetch_assoc($r);
					$rank = $ranking['AVG(Ranking)'];
					if($rank == null) {
						$rank=0; 					
					}
					
					if ($mod == $username && $row['ThreadStatus'] == "Deleted") {
						echo '<tr class="delthread" >
							<td class="clickforum" onclick="location.href=\'thread.php?threadID='.$row['ThreadNo'].'\'" >'
								.$row['Title'].'</td>
							<td class="clickforum" onclick="location.href=\'thread.php?threadID='.$row['ThreadNo'].'\'" >'
								.$row['TimeCreated'].'</td>										
							<td class="clickforum" onclick="location.href=\'thread.php?threadID='.$row['ThreadNo'].'\'" >'
								.$row['ThreadStartUser'].'</td>
							<td class="clickforum" onclick="location.href=\'thread.php?threadID='.$row['ThreadNo'].'\'" >
							<span class="stars">'.$rank.'</span></td>
						</tr>';	
						
					}
					else if ($row['ThreadStatus'] == "Active") {
						
						echo '<tr>  
							<td class="clickforum" onclick="location.href=\'thread.php?threadID='.$row['ThreadNo'].'\'" >'
								.$row['Title'].'</td>
							<td class="clickforum" onclick="location.href=\'thread.php?threadID='.$row['ThreadNo'].'\'" >'
								.$row['TimeCreated'].'</td>										
							<td class="clickforum" onclick="location.href=\'thread.php?threadID='.$row['ThreadNo'].'\'" >'
								.$row['ThreadStartUser'].'</td>
							<td class="clickforum" onclick="location.href=\'thread.php?threadID='.$row['ThreadNo'].'\'" >
								<span class="stars">'.$rank.'</span></td>
						</tr>';
					}
				 }
				 echo '</tbody>';				 
				 ?> 
				 
			</table>
		</div>
	</div>
	
	
<?php include '../../footer.php'?>
	
</div>
<?php $_SESSION['message'] = "";?>
</body>
</html>