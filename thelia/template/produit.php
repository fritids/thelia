<?php

		include_once("fonctions/divers.php");
		include_once("classes/Rubrique.class.php");
		
		$racine = chemin($_GET['id_rubrique']);
		
		switch($racine[count($racine)-1]->id){
				case '1' : $fond="produit.html"; break;
				case '2' : $fond="produit.html"; break;
				case '3' : $fond="produit.html"; break;
				default: $fond="produit.html";
      }
	  
	$pageret=1;
-	include("fonctions/moteur.php");

?>