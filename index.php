<!doctype html>
<html>

<head>
<meta charset="UTF-8">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="style.css"/>


</head>

<body>
<div id="wrap">

<div id="middel" class="box">

	<?php
	if(isset($_GET['error'])) { 
	   echo 'Error Logging In!';
	}
	?>


	<h1>Login</h1>

	<form action="login.php" method="post" name="login_form">
	   <p>Email</p> 
	   <input class="in" type="text" name="email" />
	   <p>Password</p> 
	   <input class="in" type="password" name="p" id="password"/></br>
	   <input class="but" type="button" value="Login" onclick="form.submit();" />
	   <input class="but" type="button" value="register" onclick='form.action="register.php"; form.submit();' />
	</form>

	


</div>

</div>

<nav id="footer" class="botom">
	<p href="http://alexander.bladh.nu">Made by Alexander Bladh</p>
	<div>
		<a href="about.php">About</a>
		<a href="blog.php">Blog</a>
	</div>
</nav>

</body>
</html>