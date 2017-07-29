<?php
session_start();
session_unset(); 
$_SESSION['message'] = "";

include_once '../connect.php';

//check if form is submitted
if (isset($_POST['login'])) {

	$username = mysqli_real_escape_string($conn, $_POST['usr_id']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$result = mysqli_query($conn, "SELECT * FROM Members WHERE UserName = '" . $username. "' and Password = '" . md5($password) . "'");

	if ($row = mysqli_fetch_array($result)) {		
		$status = $row['Status'];
		if($status == 'Banned') {
			$_SESSION['message'] = "You are banned!";
			header("Location: ../logout.php");
		}
		else {
			$_SESSION['username'] = $row['UserName'];
			$_SESSION['name'] = $row['FullName'];
			
			header("Location: ../index.php");
		}
	} else {
		$_SESSION['message'] = "Incorrect Email or Password!!!";
	}
}

mysqli_close($conn); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Login</title>

 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" href="../main.css" />
  
</head>
<body>
<div class="container">

<?php include_once '../header.php';?>

	<div class="row">
		<div class="main">
			<?php if ($_SESSION['message'] == "" ) { $block = 'style="display:none; margin:0; padding:0;"';}
		
				echo '<div class="alert alert-danger" '.$block .' role="alert">
					'.$_SESSION['message'].'
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
					</button>
				</div>';
			?>
			
			<div class="row">
				<div class="col-md-4 col-md-offset-4 well">
					<form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="loginform">
						<fieldset>
							<legend>Login</legend>
							
							<div class="form-group">
								<label for="name">Username</label>
								<input type="text" maxlength="32" name="usr_id" placeholder="Your Username" required class="form-control" />
							</div>

							<div class="form-group">
								<label for="name">Password</label>
								<input type="password" maxlength="32" name="password" placeholder="Your Password" required class="form-control" />
							</div>

							<div class="form-group">
								<input type="submit" maxlength="32" name="login" value="Login" class="btn btn-primary" />
							</div>
						</fieldset>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-md-offset-4 text-center">	
					New User? <a href="register.php">Sign Up Here</a>
				</div>
			</div>
		</div>
	</div>
	
<?php include_once '../footer.php';?>
	
</div>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
