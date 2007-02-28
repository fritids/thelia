<?php
	include_once("classes/Rubrique.class.php");
	$temprub = new Rubrique();
	$temprub->charger($_GET['id_rubrique']);
		switch($_GET['id_rubrique']){
				case '1' : $fond="rubrique.html"; break;
				case '2' : $fond="rubrique.html"; break;
				case '3' : $fond="rubrique.html"; break;
				default: $fond="rubrique.html";
      }
	  
	  include("fonctions/moteur.php");
?>
