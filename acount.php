<!doctype html>
<html>

<head>
<meta charset="UTF-8">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/lists.js"></script>

<link rel="stylesheet" type="text/css" href="style.css"/>


</head>

<body>

<nav class="top">
<a href="logout.php">Logout</a>
</nav>

<div id="wrap">
<div class="box" id="lists">

	<ol>

		<?php
			include_once 'include/db_connect.php';
			include_once 'include/db_functions.php';

			if(!isset($_SESSION)){
 				sec_session_start();
			}
			
			if(login_check($mysqli) == true) {
	 
	   			$user_id = $_SESSION['user_id'];
	   			$stmt = $mysqli->prepare("SELECT * FROM `lists` WHERE `owner_id`=?");
	   			$stmt->bind_param("i", $user_id);
	   			$stmt->execute();
	   			$result = $stmt->get_result();

	   			$lists = array();

	   			while($row = mysqli_fetch_assoc($result)){
 	   				$lists[] = $row;
 				}

 				foreach($lists as $l)
 				{	

 					echo '<li id="'. $l["id"].'" class="list">
 						<a class="text" href="todo.php?list_id='.$l["id"].'" class="name">'.htmlspecialchars($l['name'], ENT_QUOTES).'</a>
 						<div class="actions">
							<a href="" class="edit">Edit</a>
							<a href="" class="public">Publish</a>
							<a href="" class="delete">Delete</a>
						</div> </li>';

 				}

	 
			} else {
	   			echo 'You are not authorized to access this page, please login. <br/>';
			}

		?>

	</ol>

	<?php
		if(login_check($mysqli) == true) 
			echo '<input class="but" type="button" value="Add list" onclick=""/>';
	?>

</div>

</div>

<div id="publish" class="yellowbox hidden" title="Publish?">

	<p>Do you whant to make this list public?</p>

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