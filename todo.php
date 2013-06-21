<!doctype html>
<html>

<head>
<meta charset="UTF-8">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<script type="text/javascript" src="js/ui.js"></script>



<link rel="stylesheet" type="text/css" href="style.css"/>


</head>

<body>

<nav  class="top">
<a href="acount.php">Acount</a>
<a href="logout.php">Logout</a>
</nav>
<div id="wrap">
<div id="middel" class="box">

<?php

		include 'include/todo-class.php';
		include 'include/db_connect.php';
		include 'include/db_functions.php';

		if(!$mysqli) die("mysql error");

		if(!isset($_SESSION)){
 				sec_session_start();
		}


			$list_id = $_GET["list_id"];
			$list_id = $mysqli->real_escape_string($list_id);

			$r = $mysqli->query("SELECT `name`,`owner_id`,`public` FROM `lists` WHERE `id`=".$list_id);
			$r = $r->fetch_assoc();
			
			if((login_check($mysqli) == true and $r["owner_id"] == $_SESSION['user_id']) or $r["public"] == true)
			{
				echo '<h1>'.htmlspecialchars($r["name"], ENT_QUOTES).'</h1>';

				echo '<ul class = "todoList">';


				// Select all the todos, ordered by position:
				$result = $mysqli->query("SELECT * FROM `todos` WHERE `list_id`=".$list_id." ORDER BY `position` ASC");

				$todos = array();

				// Filling the $todos array with new ToDo objects:
				while($row = $result->fetch_assoc()){
		 		   $todos[] = new ToDo($row);
		 		}

		 		foreach($todos as $todo)
		 		{

		 			echo $todo;

		 		}

		 		echo '</ul>

					<input id="addButton" class="but" value="Add Todo" type="button"/>';


	 		}else
	 		{
	 			echo "private list";
	 		}
 		


	?>


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