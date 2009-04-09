<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
	include_once("../../classes/Contenu.class.php");
	include_once("../../classes/Contenudesc.class.php");
	include_once("../../classes/Dossier.class.php");
	include_once("../../classes/Dossierdesc.class.php");

	$sep = explode( "_", $_POST['id']);

	$pos = strpos($_POST['id'], "_");
	
	$modif = substr($_POST['id'], 0, $pos);
	
	if($modif == "titrecont"){
		 $obj = new Contenu();
		 $objdesc = new Contenudesc();
	}
	else{
		$obj = new Dossier();
		$objdesc = new Dossierdesc();
	}
	
	$obj->charger(substr($_POST['id'], $pos+1));
	$objdesc->charger($obj->id);
	
	$objdesc->titre = $_POST["value"];
	$objdesc->maj();
	
	echo $objdesc->titre;
	
	
	
?>