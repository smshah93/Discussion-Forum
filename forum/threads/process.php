<?php 
session_start();
require_once('../../connect.php');

 // Check if user is logged in using the session variable
if(!isset($_SESSION["username"])){	
	$_SESSION['message'] = "You must log in before viewing your profile page!";
	header("Location: ../../index.php");
	
	exit(); 
}
else {		
	$username = $_SESSION['username'];
}


//SkyID for thread id for rank - pass threadID
//group-1 for ranking 
if(isset($_POST['rank']) ) {
	$thread = $_GET['SkyID'];
	$rank = $_POST['group-1'];
	//find forum 
	$result = mysqli_query($conn, "Select Forum from Thread where ThreadNo='$thread' LIMIT 1"); 
	$row = mysqli_fetch_assoc($result); 
	$forum = $row['Forum'];	 
	
	//check if user rated
	$test = mysqli_query($conn, "Select * from Rank where TNo='$thread' and User='$username';");
	if(mysqli_num_rows($test) <= 0) {
		//User didn't review before 
		$result = mysqli_query($conn, "Insert into Rank Values('$username', '$forum', '$thread', '$rank');"); 
		if(!$result) {
			$_SESSION['message'] = "Database not working right now. rate later";
			header("Location: thread.php?threadID=$thread");
		}
		else {
			$_SESSION['message'] = "Ranking updated!";
			header("Location: thread.php?threadID=$thread");		
		}	
	}
	else {
		if(!mysqli_query($conn, "Update Rank set Ranking='$rank' where TNo='$thread' and User='$username';") ) {
			$_SESSION['message'] = "Database not working right now. rate later";
			header("Location: thread.php?threadID=$thread");
		}
		else {
			$_SESSION['message'] = "You ranked before. Ranking updated!";
			header("Location: thread.php?threadID=$thread");		
		}	
	}	 
 }
 
//DCID for ban from forum - pass username
if($_GET['DCID']) {
	$us = $_GET['DCID'];
	$forum = $_SESSION['forum'];
	
	$result = mysqli_query($conn, "Insert into Ban Values('$forum', '$us');"); 
	
	if(!$result) {
		$_SESSION['message'] = "Database not working right now.";
		header("Location: index.php?forumID=$forum");
	}
	else {
			$_SESSION['message'] = "User ".$us." banned from forum.";
			header("Location: index.php?forumID=$forum");		
		}		
}

//ADIO for ban from website - pass username
if($_GET['ADID']) {
	$us = $_GET['ADID'];
	$forum = $_SESSION['forum'];
	
	$result = mysqli_query($conn, "Update Members set Status='Banned' where UserName='$us';"); 
	
	if(!$result) {
		$_SESSION['message'] = "Database not working right now.";
		header("Location: index.php?forumID=$forum");
	}
	else {
		$_SESSION['message'] = "User ".$us." banned from website";
		header("Location: index.php?forumID=$forum");	
	}		
}

//EPID for deleting thread - pass threadid
if($_GET['EPID']) {
	$threadID = $_GET['EPID'];
	$forum = $_SESSION['forum'];
	
	$result = mysqli_query($conn, "Delete from Thread where ThreadNo='$threadID';"); 
	
	if(!$result) {
		$_SESSION['message'] = "Database not working right now.";
		header("Location: index.php?forumID=$forum");
	}
	else {
		$_SESSION['message'] = "Thread deleted.";
		header("Location: index.php?forumID=$forum");	
	}		
}

//TESID for deleted a post - pass post id
if($_GET['TESID']) {
	$post = $_GET['TESID'];	
	$forum = $_SESSION['forum'];
	
	$result = mysqli_query($conn, "Delete from Post where PostNo=$post;"); 
	
	if(!$result) {
		$_SESSION['message'] = "Database not working right now.";
		header("Location: index.php?forumID=$forum");
	}
	else {
		$_SESSION['message'] = "Post deleted";
		header("Location: index.php?forumID=$forum");		
	}		
}


//MorID for closing thread - pass threadid
if($_GET['MorID']) {
	$threadID = $_GET['MorID'];
	$forum = $_SESSION['forum'];
	
	$result = mysqli_query($conn, "Update Thread set ThreadStatus='Deleted' where ThreadNo='$threadID';"); 
	
	if(!$result) {
		$_SESSION['message'] = "Database not working right now.";
		header("Location: index.php?forumID=$forum");
	}
	else {
		$_SESSION['message'] = "Thread closed.";
		header("Location: index.php?forumID=$forum");		
	}		
}

 ?>
 