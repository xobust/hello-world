<?php

define("HOST", "localhost"); // The host you want to connect to.
define("USER", "root"); // The database username.
define("PASSWORD", ""); // The database password. 
define("DATABASE", "todo"); // The database name.
 
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

?>