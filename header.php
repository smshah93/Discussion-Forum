<?php
$filepath = $_SERVER['SCRIPT_FILENAME'];
$fileArray= explode('/', $filepath);

$root = $fileArray[4];
?>

<div id="header" class="row">    
	<div class="header-picture">   
    </div> 

	<nav class="navbar navbar-default">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/~<?php echo $root ?>/Forum/">HomePage</a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav navbar-right">
				<?php
				  if(!isset($_SESSION['username']))
					{
					?>                
						<li><a href="/~<?php echo $root ?>/Forum/auth/login.php">Login</a></li>
						<li><a href="/~<?php echo $root ?>/Forum/auth/register.php">Sign Up</a></li>
					<?php  }
					else
					   {  
					?>
									  
						 <li><a href="/~<?php echo $root ?>/Forum/chatroom">Chat</a></li>
						 <li><a href="/~<?php echo $root ?>/Forum/forum">Forums</a></li>
						 <li><a href="/~<?php echo $root ?>/Forum/mailbox">Mailbox</a></li>
						 <li><a href="/~<?php echo $root ?>/Forum/auth/logout.php">Logout</a></li>
						 <form class="form-inline" style="float:left;margin:10px;">
							  <input class="form-control mr-sm-2" type="text" placeholder="Search">
							  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
						</form>
					<?php  }
					?>					
				</ul>
				
        </div>
	</nav>
</div>