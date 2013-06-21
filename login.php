<?php

include_once 'include/db_connect.php';
include_once 'include/db_functions.php';

sec_session_start();

if(isset($_POST['email'], $_POST['p']))
{
	$email = $_POST['email'];
	$password = $_POST['p'];

	if(login($email, $password, $mysqli) == true)
	{
		header("Location:acount.php");

	} else
	{

		echo "nope";

	}


}else {
	// post variables not set
	echo "invalid request";
}


?>