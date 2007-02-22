<?php
	include_once("../../../classes/Navigation.class.php");	
	session_start();
	
	$total = 0;

	$total = $_SESSION['navig']->panier->total() + $_SESSION['navig']->commande->port;
	$total -= $_SESSION['navig']->commande->remise;
	$total = round($total, 2);
	$total *= 100;
	
	print ("<HTML><HEAD><TITLE>CYBERPLUS - Paiement Securise sur Internet</TITLE></HEAD>");
	print ("<BODY bgcolor=#ffffff>");
	print ("<Font color=#000000>");
	print ("<center><H1>PAIEMENT SECURISE CYBERPLUS </H1></center><br><br>");
	print ("<center><H1>MONSITE SITE . COM</H1></center><br><br>");

	//		Affectation des param�tres obligatoires

	$parm="merchant_id=045065789500014";
	$parm="$parm merchant_country=fr";
	$parm="$parm amount=$total";
	$parm="$parm currency_code=978";


	// Initialisation du chemin du fichier pathfile (� modifier)
    //   ex :
    //    -> Windows : $parm="$parm pathfile=c:\\repertoire\\pathfile";
    //    -> Unix    : $parm="$parm pathfile=/home/repertoire/pathfile";
    //
    // Cette variable est facultative. Si elle n'est pas renseign�e,
    // l'API positionne la valeur � "./pathfile".

		$parm="$parm pathfile=/home/site/pathfile";

	//		Si aucun transaction_id n'est affect�, request en g�n�re
	//		un automatiquement � partir de heure/minutes/secondes
	//		R�f�rez vous au Guide du Programmeur pour
	//		les r�serves �mises sur cette fonctionnalit�
	//
	
	$parm="$parm transaction_id=" . $_SESSION['navig']->commande->transaction;


	$_SESSION['navig']->panier = new Panier();
	$_SESSION['navig']->commande = new Commande();
	
	
	$path_bin = "/home/cyberplus/bin/request";


	//	Appel du binaire request

	$result=exec("$path_bin $parm");

	//	sortie de la fonction : $result=!code!error!buffer!
	//	    - code=0	: la fonction g�n�re une page html contenue dans la variable buffer
	//	    - code=-1 	: La fonction retourne un message d'erreur dans la variable error

	//On separe les differents champs et on les met dans une variable tableau

	$tableau = explode ("!", "$result");

	//	r�cup�ration des param�tres

	$code = $tableau[1];
	$error = $tableau[2];
	$message = $tableau[3];

	//  analyse du code retour

  if (( $code == "" ) && ( $error == "" ) )
 	{
  	print ("<BR><CENTER>erreur appel request</CENTER><BR>");
  	print ("executable request non trouve $path_bin");
 	}

	//	Erreur, affiche le message d'erreur

	else if ($code != 0){
		print ("<center><b><h2>Erreur appel API de paiement.</h2></center></b>");
		print ("<br><br><br>");
		print (" message erreur : $error <br>");
	}

	//	OK, affiche le formulaire HTML
	else {
		print ("<br><br>");
		print ("  $message <br>");
	}

print ("</BODY></HTML>");

?>