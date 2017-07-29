<?php
session_start();

if(isset($_SESSION['username'])) {
	$_SESSION['message'] = "Already logged in!";
	header("Location: ../index.php");
}

include_once '../connect.php';

//set validation error flag as false
$error = false;
$_SESSION['message'] = "";

//check if form is submitted
if (isset($_POST['signup'])) {
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$username = mysqli_real_escape_string($conn, $_POST['usr_id']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
	
	//name can contain only alpha characters and space
	if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
		$error = true;
		$messageerror = "Name must contain only alphabets and space<br />";
	}	
	if(strlen($password) < 6) {
		$error = true;
		$messageerror = "Password must be minimum of 6 characters<br />" . $messageerror;
	}
	if($password != $cpassword) {
		$error = true;
		$messageerror = "Password and Confirm Password doesn't match<br />" . $messageerror;
	}	
	$_SESSION['message'] = $messageerror;
	
	$check = mysqli_query($conn, "SELECT UserName FROM Members where UserName='$username'");
	if($row = mysqli_fetch_array($check)) { $error = true; $_SESSION['message'] = "Username taken"; }
	
	if (!$error) {
		if(mysqli_query($conn, "INSERT INTO Members VALUES('" . $username . "', '" . md5($password) . "', '" . $name . "', 'Active')")) {
			$_SESSION['message'] = "Successfully Registered! <a href='login.php'>Click here to Login</a>";
		} else {
			$_SESSION['message'] = "Error in registering...Please try again later!";
		}
	}
}
mysqli_close($conn); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>User Registration</title>

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
					<form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
						<fieldset>
							<legend>Sign Up</legend>

							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" maxlength="32" name="name" placeholder="Enter Full Name" required value="<?php if($error) echo $name; ?>" class="form-control" />
								<span class="text-danger"><?php if (isset($name_error)) echo $name_error; ?></span>
							</div>
							
							<div class="form-group">
								<label for="name">Username</label>
								<input type="text" maxlength="32" name="usr_id" placeholder="Username" required value="<?php if($error) echo $username; ?>" class="form-control" />
								<span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
							</div>

							<div class="form-group">
								<label for="name">Password</label>
								<input type="password" maxlength="32" name="password" placeholder="Password" required class="form-control" />
								<span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
							</div>

							<div class="form-group">
								<label for="name">Confirm Password</label>
								<input type="password" maxlength="32" name="cpassword" placeholder="Confirm Password" required class="form-control" />
								<span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
							</div>

							<div class="form-group">
								<input type="submit" name="signup" value="Sign Up" class="btn btn-primary" />
							</div>
						</fieldset>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-md-offset-4 text-center">	
				Already Registered? <a href="login.php">Login Here</a>
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



