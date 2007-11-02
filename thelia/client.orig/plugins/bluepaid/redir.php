<?php

	include_once(realpath(dirname(__FILE__)) . "/config.php");

	if($_GET['etat'] == "ok")
		header("Location: $retourok");
	else 
		header("Location: $retourko");

	

?>