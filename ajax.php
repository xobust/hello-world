<?php

	include 'include/todo-class.php';
	include 'include/db_functions.php';
	include 'include/db_connect.php';


	if(!isset($_SESSION)){
 		sec_session_start();
	}
			



	if(login_check($mysqli) == true) {
	 
	if(isset($_GET['id']))
		$id = (int)$_GET['id'];
	else if($_GET['action'] != "new" && $_GET['action'] != "add")
	{
		echo "unknown request";
		return;
	}

	try{
		if($_GET['type'] == "todo")
		{
			if($_GET['action'] == "new" or $_SESSION['user_id'] == Todo::owner_id($id, $mysqli) )
			{
			    switch($_GET['action'])
			    {
			        case 'delete':
			            ToDo::delete($id,$mysqli);
			            break;

			        case 'rearrange':
			            ToDo::rearrange($_GET['positions'], $mysqli);
			            break;

			        case 'edit':
			            ToDo::change($id,$_GET['text'],$_GET['desc'], $mysqli);
			            break;

			        case 'new':
			            ToDo::createNew($_GET['text'],$_GET['desc'] ,$_GET['list'],$mysqli);
			            break;
			        case 'get':
			        	ToDo::desc($id,$mysqli);
			            break;
			      
			   }
			}else
			{
				echo "acess denied";
			}
		}elseif ($_GET['type'] == "list") {
			if($_GET['action'] == "add"  or $_SESSION['user_id'] == Lists::owner_id($id, $mysqli))
			{
				switch($_GET['action'])
			    {
			    	case 'add':
			            Lists::add($_GET['text'], $mysqli);
			            break;
			        case 'delete':
			            Lists::delete($id,$mysqli);
			            break;

			        case 'edit':
			            Lists::rename($id,$_GET['text'], $mysqli);
			            break;
			        case 'publish':
			        	Lists::publish($id,$_GET['public'], $mysqli);

			    }
			}else
			{
				echo "acess denied";
			}
		}

	}
		catch(Exception $e){
		//	echo $e->getMessage();
	 	die("0");
	}

	}else
	{

		echo " not logged in";

	}

?>