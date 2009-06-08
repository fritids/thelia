<?php
	session_start();
	$_SESSION['serveur']="";
	$_SESSION['utilisateur']="";
	$_SESSION['motdepasse']="";
	$_SESSION['choixbase']="";
	
	include_once("../classes/Variable.class.php");

	$var = new Variable();
	if($var->charger("version"))
			$vcur = $var->valeur;
	else
			$vcur="135";

	header("Location: maj.php?vcur=" . $vcur);
?>	