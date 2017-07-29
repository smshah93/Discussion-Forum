<?php
/* Displays user information and some useful messages */
session_start();
require_once('connect.php');
$_SESSION['message'] ="";
// Check if user is logged in using the session variable
if(!isset($_SESSION["username"])){	
	$_SESSION['message'] = "You must log in before viewing your profile page!";
	header("Location: index.php");
	
	exit(); 
}
else {		
	$username = $_SESSION['username'];
	$result = mysqli_query($conn, "Select Status from Members where UserName='$username' LIMIT 1"); 
	$row = mysqli_fetch_assoc($result); 
	$status = $row['Status'];
	if($status == 'Banned') {
		$_SESSION['message'] = "You are banned!";
		header("Location: index.php");
	}	
  }
  
  
?>
<?php 
if(isset($_POST['submit']))
{
$conn = mysqli_connect("ecsmysql","cs431s29","aingohye");
if (!$conn)
  {
  die('Could not connect: ' . mysqli_error());
  }

    if(isset($_GET['roomID']) ) {
	   $rid = $_GET['roomID'];
	   $_SESSION['room'] = $rid; 
        
    }


mysqli_select_db($conn, "cs431s29");
		$message=$_POST['message'];
		$sender=$_POST['sender'];
		mysqli_query($conn,"INSERT INTO chat_messages(message, sender,RoomNo)VALUES('$message', '$username','$rid')");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simple Chat</title>
<script language="javascript" src="jquery-1.2.6.min.js"></script>
<script language="javascript" src="jquery.timers-1.0.0.js"></script>
<script type="text/javascript">
var auto_refresh = setInterval (
function() {
	$('#load_msg').load('refresh.php?roomID=<?php echo $rid; ?>').fadeIn("slow");
}, 5000); 
</script>

<style type="text/css">
.refresh {
    border: 1px solid #3366FF;
	border-left: 4px solid #3366FF;
    color: green;
    font-family: tahoma;
    font-size: 12px;
    height: 425px;
    overflow: auto;
    width: 720px;
	padding:10px;
	background-color:#FFFFFF;
    margin: auto;
}
#post_button{
	border: 1px solid #3366FF;
	background-color:#3366FF;
	width: 100px;
	color:#FFFFFF;
	font-weight: bold;
    margin: inherit
	margin-right: -105px; padding-top: 4px; padding-bottom: 4px;
	cursor:pointer;
}
#textb{
	border: 1px solid #3366FF;
	border-left: 4px solid #3366FF;
	width: 320px;
	margin-top: 10px; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; width: 415px;
    margin-left: 380px;
}
#texta{
	border: 1px solid #3366FF;
	border-left: 4px solid #3366FF;
	width: 410px;
	margin-bottom: 10px;
	padding:5px;
    margin-left: 350px;
    margin-top: 0px;
}
p{
border-top: 1px solid #EEEEEE;
margin-top: 0px; margin-bottom: 5px; padding-top: 5px;
}
span{
	font-weight: bold;
	color: #3B5998;
}
</style>
</head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../main.css" />
<body>
<div class="container"> 

	<?php include_once '../header.php';?>
	
    <div class="row">
		<div class="side-menu">
			<ul class="nav nav-pills nav-stacked">
				<li role="presentation"><a href="index.php">Back to Chatrooms</a></li>
				<?php if ($status == 'Admin') {
					echo '<li role="presentation"><a href="deleteChat.php">Delete Chat</a></li>';
				}				
				?>
			</ul>
		</div>
<form method="POST" name="" action="">
<input name="sender" type="text" id="texta" value="<?php echo $sender ?>"/>
<div id="load_msg" class="refresh">
<?php
    if(isset($_GET['roomID']) ) {
	   $cid = $_GET['roomID'];
        
    }
$conn = mysqli_connect("ecsmysql","cs431s29","aingohye");
if (!$conn)
  {
  die('Could not connect: ' . mysqli_error());
  }

mysqli_select_db($conn,"cs431s29");
$result = mysqli_query($conn,"SELECT * FROM chat_messages WHERE RoomNo = $cid ORDER BY id ASC");


while($row = mysqli_fetch_array($result))
  {
  echo '<p>'.'<span>'.$row['sender'].'</span>'. '&nbsp;&nbsp;' . $row['message'].'</p>';
  }

mysqli_close($conn);
?>

</div>
<input name="message" type="text" id="textb"/>
<input name="submit" type="submit" value="Chat" id="post_button" />
</form>
<?php include_once '../footer.php';?>
</div> 
</body>
    
</html>

