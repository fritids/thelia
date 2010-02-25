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

	/* Moteur */
	
	error_reporting(E_ALL ^ E_NOTICE);

	include_once("classes/CacheBase.class.php");
	include_once("classes/Navigation.class.php");
	include_once("classes/Modules.class.php");

	/* Inclusions nécessaires avant ouverture de la session */
	$modules = new Modules();	
	$query = "select * from $modules->table where actif='1'";

	
	$resul = CacheBase::getCache()->mysql_query($query, $modules->link);

	foreach($resul as $row)
		if(file_exists("client/plugins/" . $row->nom . "/inclure_session.php"))
			include_once("client/plugins/" . $row->nom . "/inclure_session.php");
				
	session_start();
	
	include_once("fonctions/boucles.php");
	include_once("fonctions/substitutions.php");
	include_once("fonctions/filtres.php");
	include_once("fonctions/action.php");
	include_once("fonctions/divers.php");
	include_once("classes/Client.class.php");
	include_once("classes/Commande.class.php");
	include_once("classes/Venteprod.class.php");
	include_once("classes/Ventedeclidisp.class.php");
    include_once("classes/Message.class.php");
	include_once("classes/Messagedesc.class.php");
	include_once("classes/Transzone.class.php");
	include_once("classes/Variable.class.php");	
	include_once("classes/Promo.class.php");
	include_once("classes/Perso.class.php");
	include_once("classes/PluginsClassiques.class.php");
	include_once("classes/Mail.class.php");
	include_once("fonctions/parseur.php");
    
function analyse($res){
	global $formulaire, $formconnex;

	// substition simples
	$res = substitutions($res);	
	
	// laisser les infos pour les connectés ou non connectÈs
	$res = filtre_connecte($res);	
	

	// traitement dans le cas d'un formulaire
	if(isset($_GET['errform']) && $_GET['errform'] == "1") $res = traitement_formulaire($res);

	// effectue le nombre de passe nécessaire afin de traiter toutes les boucles et sous boucles

	$res = preg_replace("|<THELIA([^>]*)>\n|Us", "<THELIA\\1>", $res);
	
	while(strstr($res, "<THELIA")) {
		$boucles = pre($res);
		$res = boucle_simple($res, $boucles);
		$res = post($res);
	}

	while(strstr($res, "<BTHELIA")){
	
		// boucles avec sinon
		$res = str_replace("BTHELIA", "THELIA", $res);
		$res = boucle_sinon(explode("\n", $res));

		// boucles
	
		while(strstr($res, "<THELIA")) {
			$boucles = pre($res);
			$res = boucle_simple($res, $boucles);
			$res = post($res);
		}
	
	}
	// on envoie le résultat
	
	return $res;

}

	// initialisation des variables du couple php/html
	if(!isset($res)) $res="";
	if(!isset($lang)) $lang="";
	if(!isset($devise)) $devise="";
	if(!isset($parsephp)) $parsephp="";
	if(!isset($securise)) $securise=0;
	if(!isset($panier)) $panier=0;
	if(!isset($pageret)) $pageret=0;	
	if(!isset($reset)) $reset=0;
	if(!isset($transport)) $transport=0;
	if(!isset($obligetelfixe)) $obligetelfixe=0;
	if(!isset($obligetelport)) $obligetelport=0;
	if(!isset($pagesess)) $pagesess=0;
	if(!$lang)
        if(!isset($_REQUEST['lang'])) $lang=""; 
			else $lang = lireParam($_REQUEST['lang'], "int");
			
	if(!$devise)
        if(!isset($_REQUEST['devise'])) $devise=""; else lireParam("devise", "int");
	if(!isset($_REQUEST['action'])) $action=""; else $action=lireParam("action", "string");
	if(!isset($_REQUEST['append'])) $append=0; else $append=lireParam("append", "int");
	if(!isset($_REQUEST['id'])) $id="";	else $id=lireParam("id", "int");
	if(!isset($_REQUEST['id_parrain'])) $id_parrain=""; else $id_parrain=lireParam("id_parrain", "int");	
	if(!isset($_REQUEST['nouveau'])) $nouveau=""; else $nouveau=lireParam("nouveau", "int");	
	if(!isset($_REQUEST['ref'])) $ref=""; else $ref=lireParam("ref", "string");	
	if(!isset($_REQUEST['quantite'])) $quantite=""; else $quantite=lireParam("quantite", "int");	
	if(!isset($_REQUEST['article'])) $article=""; else $article=lireParam("article", "int");	
	if(!isset($_REQUEST['type_paiement'])) $type_paiement=""; else $type_paiement=lireParam("type_paiement", "int");	
	if(!isset($_REQUEST['code'])) $code=""; else $code=lireParam("code", "string");	

	if(!isset($_REQUEST['entreprise'])) $entreprise=""; else $entreprise=lireParam("entreprise", "string+\-\'\,\s\/\(\)\&\"");	
	if(!isset($_REQUEST['siret'])) $siret=""; else $siret=lireParam("siret", "int+\-");
	if(!isset($_REQUEST['intracom'])) $intracom=""; else $intracom=lireParam("intracom", "string+\s");
	if(!isset($_REQUEST['parrain'])) $parrain=""; else $parrain=lireParam("parrain", "int");
	if(!isset($_REQUEST['motdepasse1'])) $motdepasse1=""; else $motdepasse1=lireParam("motdepasse1", "string+\-\'\,\s\/\(\)\&\@\.\!\"");		
	if(!isset($_REQUEST['motdepasse2'])) $motdepasse2=""; else $motdepasse2=lireParam("motdepasse2", "string+\-\'\,\s\/\(\)\&\@\.\!\"");		
	if(!isset($_REQUEST['raison'])) $raison=""; else $raison=lireParam("raison", "int");
	if(!isset($_REQUEST['prenom'])) $prenom=""; else $prenom=lireParam("prenom", "string+\-\'\,\s\/\(\)\&\"");	
	if(!isset($_REQUEST['libelle'])) $libelle=""; else $libelle=lireParam("libelle", "string+\-\'\,\s\/\(\)\&\"");	
	if(!isset($_REQUEST['nom'])) $nom=""; else $nom=lireParam("nom", "string+\-\'\,\s\/\(\)\&\"");		
	if(!isset($_REQUEST['adresse1'])) $adresse1=""; else $adresse1=lireParam("adresse1", "string+\-\'\,\s\/\(\)\&\";°:");
	if(!isset($_REQUEST['adresse2'])) $adresse2=""; else $adresse2=lireParam("adresse2", "string+\-\'\,\s\/\(\)\&\";°:");
	if(!isset($_REQUEST['adresse3'])) $adresse3=""; else $adresse3=lireParam("adresse3", "string+\-\'\,\s\/\(\)\&\";°:");
	if(!isset($_REQUEST['cpostal'])) $cpostal=""; else $cpostal=lireParam("cpostal", "string");
	if(!isset($_REQUEST['ville'])) $ville=""; else $ville=lireParam("ville", "string+\s\'\/\&\"");	
	if(!isset($_REQUEST['pays'])) $pays=""; else $pays=lireParam("pays", "int");
	if(!isset($_REQUEST['telfixe'])) $telfixe=""; else $telfixe=lireParam("telfixe", "string+\s\.\/");	
	if(!isset($_REQUEST['telport'])) $telport=""; else $telport=lireParam("telport", "string+\s\.\/");	
	if(!isset($_REQUEST['tel'])) $tel=""; else $tel=lireParam("tel", "string+\s\.\/");
	if(!isset($_REQUEST['email1'])) $email1=""; else $email1=lireParam("email1", "string+\@\.");
	if(!isset($_REQUEST['email2'])) $email2=""; else $email2=lireParam("email2", "string+\@\.");
	if(!isset($_REQUEST['email'])) $email=""; else $email=lireParam("email", "string+\@\.");
	if(!isset($_REQUEST['motdepasse'])) $motdepasse=""; else $motdepasse=lireParam("motdepasse", "string+\-\'\,\s\/\(\)\&\@\.\!\"");
	if(!isset($_REQUEST['adresse'])) $adresse=""; else $adresse=lireParam("adresse", "int");
	if(!isset($_REQUEST['id_rubrique'])) $id_rubrique=""; else $id_rubrique=lireParam("id_rubrique", "int");
	if(!isset($_REQUEST['id_dossier'])) $id_dossier=""; else $id_dossier=lireParam("id_dossier", "int");
	if(!isset($_REQUEST['nouveaute'])) $nouveaute=""; else $nouveaute=lireParam("nouveaute", "int");
	if(!isset($_REQUEST['promo'])) $promo=""; else $promo=lireParam("promo", "int");
	if(!isset($_REQUEST['stockmini'])) $stockmini=""; else $stockmini=lireParam("stockmini", "float");	
	if(!isset($_REQUEST['page'])) $page=""; else $page=lireParam("page", "int");
	if(!isset($_REQUEST['totbloc'])) $totbloc=""; else $totbloc=lireParam("totbloc", "int");
	if(!isset($_REQUEST['id_contenu'])) $id_contenu=""; else $id_contenu=lireParam("id_contenu", "int");
	if(!isset($_REQUEST['caracdisp'])) $caracdisp=""; else $caracdisp=lireParam("caracdisp", "int+\-");
	if(!isset($_REQUEST['reforig'])) $reforig=""; else $reforig=lireParam("reforig", "string");
	if(!isset($_REQUEST['motcle'])) $motcle=""; else $motcle=lireParam("motcle", "string+\s\'");
	if(!isset($_REQUEST['id_produit'])) $id_produit=""; else $id_produit=lireParam("id_produit", "int");
	if(!isset($_REQUEST['classement'])) $classement=""; else $classement=lireParam("classement", "string");
	if(!isset($_REQUEST['prixmin'])) $prixmin=""; else $prixmin=lireParam("prixmin", "float");
	if(!isset($_REQUEST['prixmax'])) $prixmax=""; else $prixmax=lireParam("prixmax", "float");	
	if(!isset($_REQUEST['id_image'])) $id_image=""; else $id_image=lireParam("id_image", "int");	
	if(!isset($_REQUEST['declinaison'])) $declinaison=""; else $declinaison=lireParam("declinaison", "string");	
	if(!isset($_REQUEST['declidisp'])) $declidisp=""; else $declidisp=lireParam("declidisp", "int+\-");	
	if(!isset($_REQUEST['declival'])) $declival=""; else $declival=lireParam("declival", "string");
	if(!isset($_REQUEST['declistock'])) $declistock=""; else $declistock=lireParam("declistock", "float");	
	if(!isset($_REQUEST['commande'])) $commande=""; else $commande=lireParam("commande", "string");	
	if(!isset($_REQUEST['caracteristique'])) $caracteristique=""; else $caracteristique=lireParam("caracteristique", "int+\-");		
	if(!isset($_REQUEST['caracval'])) $caracval=""; else $caracval=lireParam("caracval", "string+\s\'\/");	
	
	
	
	
	
	// création de la session si non existante
	
	if(! isset($_SESSION["navig"]))
	 	$_SESSION["navig"] = new Navigation();
	
	// URL précédente
	if(isset($_SERVER['HTTP_REFERER'])) $_SESSION["navig"]->urlprec = $_SERVER['HTTP_REFERER']; 
	
	// Page retour
	if($_SERVER['QUERY_STRING']) $qpt="?"; else $qpt="";
	
	if($pageret && isset($_SERVER['HTTP_REFERER'])) $_SESSION["navig"]->urlpageret =  $_SERVER['PHP_SELF'] . $qpt . $_SERVER['QUERY_STRING'];
	else if($_SESSION["navig"]->urlpageret=="") $_SESSION["navig"]->urlpageret = "index.php";

	// Langue
	if($lang) $_SESSION["navig"]->lang = $lang;
	else if(!$_SESSION["navig"]->lang) $_SESSION["navig"]->lang=1;

	// Devise
	if($devise) $_SESSION["navig"]->devise = $devise;
	else if(!$_SESSION["navig"]->devise) $_SESSION["navig"]->devise="euro";

	// fonctions à éxecuter avant le moteur
	modules_fonction("demarrage");
		
	// Actions

	switch($action){
		case 'ajouter' : ajouter($ref, $quantite, $append, $nouveau); break;
		case 'supprimer' : supprimer($article); break;
		case 'modifier' : modifier($article, $quantite); break;
		case 'connexion' : connexion($email,$motdepasse); break;	
		case 'deconnexion' : deconnexion(); break;	
		case 'paiement' : paiement($type_paiement); break;	
		case 'transport' : transport($id); break;	
		case 'creercompte' : creercompte($raison, $entreprise, $siret, $intracom, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays, $telfixe, $telport, $email1, $email2, $motdepasse1, $motdepasse2, $parrain); break;	
		case 'modifiercompte' : modifiercompte($raison, $entreprise, $siret, $intracom, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays, $telfixe, $telport, $email1, $email2, $motdepasse1, $motdepasse2); break;	
		case 'creerlivraison' : creerlivraison($id, $libelle, $raison, $entreprise, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $tel, $pays); break;
        case 'supprimerlivraison' : supprimerlivraison($id);
		case 'modifierlivraison' : modifierlivraison($id, $libelle, $raison, $entreprise, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $tel, $pays); break;
		case 'modadresse' : modadresse($adresse); break;
		case 'codepromo' : codepromo($code); break;
		case 'chmdp' : chmdp($email); break;
	}


	// Sécurisation
	if($securise && ! $_SESSION["navig"]->connecte) { header("Location: connexion.php"); exit; }

	// Vérif transport 
	if($transport && ! $_SESSION["navig"]->commande->transport) { header("Location: commande.php"); exit; }
	
	// Vérif panier
	if($panier && ! $_SESSION["navig"]->panier->nbart) { header("Location: index.php"); exit; } 
	
	// fonctions à éxecuter avant ouverture du template
	modules_fonction("pre");
	
	// chargement du squelette	
	if($res == ""){
		if(!file_exists($fond)) { echo "Impossible d'ouvrir $fond"; exit; }
		$res = file_get_contents($fond);
	}
	
	// fonctions à éxecuter avant les inclusions
	modules_fonction("inclusion");
		
	// inclusion
	$res = inclusion(explode("\n", $res));
		
		
	// inclusions des plugins
	modules_fonction("action");
	
	// Résultat envoyé au navigateur

	$res =  analyse($res);

    $res = filtres($res);

	// inclusions des plugins filtres
	modules_fonction("post");

	if($parsephp == 1){
    	$res=str_replace('<'.'?php','<'.'?',$res);
    	$res='?'.'>'.trim($res).'<'.'?';
    	$res = eval($res);
	}
		
	echo $res;

	// fonctions à éxecuter apres l'affichage du template
	modules_fonction("apres");
	
	// Reset de la commande
	if($reset){
            $_SESSION["navig"]->commande = new Commande();
            $_SESSION["navig"]->panier = new Panier();	
			$_SESSION['navig']->promo = new Promo();
	}

?>