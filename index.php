<?php
session_start();
include_once 'connect.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Homepage</title>

 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" href="main.css" />
  
</head>
<body>
<div class="container">

<?php include_once 'header.php';?>

	<div class="row"> 
	<div class="main">
		<?php if ($_SESSION['message'] == "" ) { $block = 'style="display:none; margin:0; padding:0;"';}
		
			echo '<div class="alert alert-info" '.$block .' role="alert">
				'.$_SESSION['message'].'
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
				</button>
			</div>';
		?>
		
		<div id="myCarousel" class="carousel slide" data-ride="carousel">
			  <!-- Indicators -->
			  <ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#myCarousel" data-slide-to="1"></li>
				<li data-target="#myCarousel" data-slide-to="2"></li>
			  </ol>

				<?php
					$sql="SELECT Picture from Thread Limit 3";
					$result=mysqli_query($conn,$sql);
					echo '<div class="carousel-inner">';
					$i = 0; 
					while($row = mysqli_fetch_array($result))
					 {         
							if($i == 0) {echo '<div class="item active">';}
							else {echo '<div class="item">';}
								echo '<img src="forum/threads/'.$row['Picture'].'" alt="Default">
							</div>';
							$i++;
					 }
					echo '</div>';
					
				?>

			  <!-- Left and right controls -->
			  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left"></span>
				<span class="sr-only">Previous</span>
			  </a>
			  <a class="right carousel-control" href="#myCarousel" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right"></span>
				<span class="sr-only">Next</span>
			  </a>
		</div>
		
	</div>
	</div> 

<?php include_once 'footer.php';?>
</div> 
</body>
</html>








    
     
 