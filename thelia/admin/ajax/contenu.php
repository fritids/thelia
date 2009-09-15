<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once(realpath(dirname(__FILE__)) . "/../../fonctions/divers.php");
?>
<?php if(! est_autorise("acces_catalogue")) exit; ?>
<?php
	include_once("../../classes/Contenu.class.php");
	include_once("../../classes/Contenudesc.class.php");
	include_once("../../classes/Dossier.class.php");
	include_once("../../classes/Dossierdesc.class.php");

	$sep = explode( "_", $_POST['id']);

	$pos = strpos($_POST['id'], "_");
	
	$modif = substr($_POST['id'], 0, $pos);


	if($modif == "titredos"){
		$obj = new Dossier();
		$obj->charger(substr($_POST['id'], $pos+1));	
		$objdesc = new Dossierdesc();
		$objdesc->charger($obj->id);
	} else {	
		$obj = new Contenu();
		$obj->charger(substr($_POST['id'], $pos+1));
		$objdesc = new Contenudesc();
		$objdesc->charger($obj->id);		
	}	


		
	switch($modif){
		case 'titrecont' :  $objdesc->titre = utf8_decode($_POST['value']); echo $objdesc->titre; break;
		case 'titredos' :  $objdesc->titre = utf8_decode($_POST['value']); echo $objdesc->titre; break;
		case 'ligne' : 
			if($obj->ligne) $obj->ligne = 0;
			else $obj->ligne= 1;
			break;
	}

	$obj->maj();
	$objdesc->maj();
	
?>