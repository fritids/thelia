<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                            		 */
/*                                                                                   */
/*      Copyright (c) Octolys Development		                                     */
/*		email : thelia@octolys.fr		        	                             	 */
/*      web : http://www.octolys.fr						   							 */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 2 of the License, or            */
/*      (at your option) any later version.                                          */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program; if not, write to the Free Software                  */
/*      Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    */
/*                                                                                   */
/*************************************************************************************/
?>
<?php
foreach ($_POST as $key => $value) $$key = $value;
foreach ($_GET as $key => $value) $$key = $value;
?>
<?php
	include_once("classes/Navigation.class.php");

	session_start();

	/* Moteur */
	
	/* Le fichier html associŽ au php ( fond ) est parsŽ afin de subsituer les informations au bon endroit */

	include_once("fonctions/boucles.php");
	include_once("fonctions/substitutions.php");
	include_once("fonctions/action.php");
	include_once("fonctions/divers.php");
	include_once("classes/Client.class.php");
	include_once("classes/Commande.class.php");
	include_once("classes/Venteprod.class.php");
    include_once("classes/Message.class.php");
	include_once("classes/Messagedesc.class.php");
	include_once("classes/Transproduit.class.php");
	include_once("classes/Transzone.class.php");
	include_once("classes/Variable.class.php");	
	include_once("classes/Promo.class.php");
	include_once("classes/Perso.class.php");
	include_once("classes/Smtp.class.php");
	include_once("fonctions/parseur.php");
	include_once("fonctions/fonctsajax.php");
	include_once("client/fonctperso/perso.php");

	include_once("lib/Sajax.php");	

function analyse($res){
	global $formulaire, $sajax;
	
	// substition simples
	$res = substit(explode("\n", $res));	
	
	// laisser les infos pour les connectŽs ou non connectés
	$res = filtre_connecte(explode("\n", $res));	
	

	// traitement dans le cas d'un formulaire
	if($formulaire) $res = traitement_formulaire($res);
	
	// effectue le nombre de passe nŽcessaire afin de traiter toutes les boucles et sous boucles
	
	while(strstr($res, "<THELIA")) {
		$res = pre($res);
		$res = boucle_simple(explode("\n", $res));
		$res = post($res);
	}

	// si on a un squelette comportant de l'Ajax il faut charger les div
	if($sajax == 1) $res = chargerDiv(explode("\n", $res));

	// boucles avec sinon
	$res = ereg_replace("BTHELIA", "THELIA", $res);
	$res = boucle_sinon(explode("\n", $res));

	// boucles
	
	while(strstr($res, "<THELIA")) {
		$res = pre($res);
		$res = boucle_simple(explode("\n", $res));
		$res = post($res);
	}
	
	// execution du code php
	
	$res = execute_php(explode("\n", $res));

	// on envoie le rŽsultat
	
	return $res;

}
		
	  //$sajax_debug_mode = 1;
	sajax_init(); 
	sajax_export("gosaj");
	sajax_export("ajoutsaj");
	sajax_export("modifpasssaj");
	sajax_export("modifcoordsaj");
	sajax_handle_client_request();

	// initialisation des variables
	if(!isset($lang)) $lang="";
	if(!isset($affilie)) $affilie="";
	if(!isset($action)) $action="";
	if(!isset($securise)) $securise=0;
	if(!isset($transport)) $transport=0;
	if(!isset($panier)) $panier=0;
	if(!isset($vpaiement)) $vpaiement=0;	
	if(!isset($pageret)) $pageret=0;	
	if(!isset($reset)) $reset=0;	
	if(!isset($entreprise)) $entreprise="";	
	if(!isset($parrain)) $parrain="";	
	if(!isset($motdepasse1)) $motdepasse1="";	
	if(!isset($motdepasse2)) $motdepasse2="";	
	if(!isset($raison)) $raison="";	
	if(!isset($prenom)) $prenom="";	
	if(!isset($nom)) $nom="";		
	if(!isset($adresse1)) $adresse1="";	
	if(!isset($adresse2)) $adresse2="";	
	if(!isset($adresse3)) $adresse3="";		
	if(!isset($cpostal)) $cpostal="";	
	if(!isset($ville)) $ville="";	
	if(!isset($pays)) $pays="";		
	if(!isset($telfixe)) $telfixe="";	
	if(!isset($telport)) $telport="";	
	if(!isset($email1)) $email1="";	
	if(!isset($email2)) $email2="";	
	if(!isset($id)) $id="";	
	if(!isset($sajax)) $sajax="";	
	
	// crŽation de la session si non existante
	
	if(! isset($_SESSION["navig"])){
	 	$_SESSION["navig"] = new Navigation();
	 	$_SESSION["navig"]->lang="1";	
	 }	
	
	// URL prŽcŽdente
	if(isset($_SERVER['HTTP_REFERER'])) $_SESSION["navig"]->urlprec = $_SERVER['HTTP_REFERER']; 
	
	// Page retour
	if($_SERVER['QUERY_STRING']) $qpt="?"; else $qpt="";
	
	if($pageret &&  ! $securise && isset($_SERVER['HTTP_REFERER'])) $_SESSION["navig"]->urlpageret = $_SERVER['HTTP_REFERER']; 
	else if($pageret) $_SESSION["navig"]->urlpageret =  $_SERVER['PHP_SELF'] . $qpt . $_SERVER['QUERY_STRING'];
	
	if($_SESSION["navig"]->urlpageret=="") $_SESSION["navig"]->urlpageret = "index.php";

	// Langue
	if($lang) $_SESSION["navig"]->lang = $lang;
	else if(!$_SESSION["navig"]->lang) $_SESSION["navig"]->lang=1;
	
	// Affiliation
	if($affilie != "") $_SESSION["navig"]->affilie = $affilie;
	
	// Actions

	switch($action){
		case 'ajouter' : ajouter($ref); break;
		case 'supprimer' : supprimer($id); break;
		case 'modifier' : modifier($article, $quantite); break;
		case 'connexion' : connexion($email,$motdepasse); break;	
		case 'deconnexion' : deconnexion(); break;	
		case 'paiement' : paiement($type_paiement); break;	
		case 'transport' : transport($id); break;	
		case 'creercompte' : creercompte($raison, $entreprise, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays, $telfixe, $telport, $email1, $email2, $motdepasse1, $motdepasse2, $parrain); break;	
		case 'modifiercompte' : modifiercompte($raison, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays, $telfixe, $telport, $email1, $email2, $motdepasse1, $motdepasse2); break;	
		case 'creerlivraison' : creerlivraison($id, $libelle, $raison, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays); break;
                case 'supprimerlivraison' : supprimerlivraison($id);
		case 'modifierlivraison' : modifierlivraison($id, $libelle, $raison, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays); break;
		case 'modadresse' : modadresse($adresse); break;
		case 'codepromo' : codepromo($code); break;
		case 'chmdp' : chmdp($email); break;
	}


	// SŽcurisation
	if($securise && ! $_SESSION["navig"]->connecte) { header("Location: connexion.php"); exit; }

	// VŽrif transport 
	if($transport && ! $_SESSION["navig"]->commande->transport) { header("Location: transport.php"); exit; }
	
	// VŽrif panier
	if($panier && ! $_SESSION["navig"]->panier->nbart) { header("Location: index.php"); exit; } 
	
    // Paiement
	if($vpaiement && ! strstr( $_SESSION["navig"]->urlprec, "paiement.php")) header("Location: index.php");

	// chargement du squelette	
	$lect = file($fond);
	if(!file_exists($fond)) { echo "Impossible d'ouvrir $fond"; exit; }
	$res = file_get_contents($fond);

	// initialisation de l'ajax
	if($sajax == 1){
		$sajaxjs = sajax_get_javascript();
		if(!file_exists($fond)) { echo "Impossible d'ouvrir fonctions/fonctsajax.js"; exit; }
		$sajaxjs .= file_get_contents("fonctions/fonctsajax.js");
		$jsf = fopen("fonctsajaxgen.js", "w");
		fputs($jsf, $sajaxjs);
		fclose($jsf);
		$res = ereg_replace("#SAJAX", $sajaxjs, $res);
	}
	
	// inclusion
	$res = inclusion(explode("\n", $res));
		
	// RŽsultat envoyŽ au navigateur
	echo perso(analyse($res));

	
	// Reset de la commande
	if($reset){
            $_SESSION["navig"]->commande = new Commande();
            $_SESSION["navig"]->panier = new Panier();	
	}

?>

		
