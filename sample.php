<?php

	require_once("configuration.php");
	require_once("class.database.php");
	require_once("class.visitors.php");
	
	$visitor = new visitors();
	$visitor->insert();
	// display online users, no parameters
	echo $visitor->display(); 
	
	// display hits
	echo $visitor->display(1); 
	
	// display online users, same as without parameters
	echo $visitor->display(2); 

?>

