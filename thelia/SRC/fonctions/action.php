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

	
	function ajouter($ref){
		if(!isset($quantite)) $quantite=1;

		$perso = array();
		
		$i = 0;
	
		foreach ($_POST as $key => $valeur) {
			
			if(strstr($key, "declinaison")){
				$perso[$i] = new Perso();
				$perso[$i]->declinaison = substr($key, 11);
				$perso[$i]->valeur = stripslashes($valeur);
				$i++;
				 
			}
		}

		$_SESSION['navig']->panier->ajouter($ref, $quantite, $perso);	
		
		
		
		
	}
	
	function transport($id){
		$transproduit = new Transproduit();
		$transzone = new Transzone();

		$pays = new Pays();
        
        if($_SESSION['navig']->adresse != ""){
            $adr = new Adresse();
            $adr->charger($_SESSION['navig']->adresse);
            $pays->charger($adr->pays);
        }

        else
             $pays->charger($_SESSION['navig']->client->pays);

		if( ! $transzone->charger($id, $pays->zone)) return;
		
	/*	for($i=0; $i<$_SESSION['navig']->panier->nbart; $i++){
				if(! $transproduit->charger($id, $_SESSION['navig']->panier->tabarticle[$i]->produit->id)
					|| ! $transzone->charger($id, $pays->zone)) return;
			}
	*/
		$_SESSION['navig']->commande->transport = $id;	

	}


	function codepromo($code){
		$promo = new Promo();
		$promo->charger($code);
		$_SESSION['navig']->promo = $promo;	

	}
		
	function supprimer($id){
			$_SESSION['navig']->panier->supprimer($id);
	}
	
	function modifier($article, $quantite){
		$_SESSION['navig']->panier->modifier($article, $quantite);		
	}
	

	
	function connexion($email,$motdepasse){
		
		$client = New Client();
		$rec = $client->charger($email, $motdepasse);

		if($rec) {
			$_SESSION['navig']->client = $client;
			$_SESSION['navig']->connecte = 1; 
			if($_SESSION['navig']->urlpageret) redirige($_SESSION['navig']->urlpageret);
			else redirige("index.php");
		}
		
		else redirige("connexion.php");
		
	}
	

	function deconnexion(){

		$_SESSION['navig']->client= new Client();
		$_SESSION['navig']->connecte = 0;		
		redirige($_SESSION['navig']->urlpageret);		
	}

		
	function modadresse($adresse){
		$_SESSION['navig']->adresse=$adresse;
	}
	
	function paiement($type_paiement){
	
		$total = 0;
		$nbart = 0;
		$poids = 0;
		$unitetr = 0;
		
		$paiement = new Paiement();
		$paiement->charger($type_paiement);

		$commande = new Commande();
		$commande->transport = $_SESSION['navig']->commande->transport;
		$commande->client = $_SESSION['navig']->client->id;
		$commande->adresse = $_SESSION['navig']->adresse;
		$commande->affilie = $_SESSION['navig']->affilie;
		$commande->date = date("Y-m-d H:i:s");
		$commande->ref = "C" . date("ymdHis") . strtoupper(substr($_SESSION['navig']->client->prenom,0, 3));
		$commande->livraison = "L" . date("ymdHis") . strtoupper(substr($_SESSION['navig']->client->prenom,0, 3));
		$commande->transaction = date("His");
		$commande->affilie = $_SESSION['navig']->affilie;
		$commande->remise = 0;

		$adr = new Adresse();
		if($adr->charger($commande->adresse)) $chadr=1; else $chadr=0;
		
		$commande->facture = 0;
		
		$commande->statut="1";
		$commande->paiement = $type_paiement;
		$idcmd = $commande->add();
		$commande->charger($idcmd);
		$venteprod = new Venteprod();

		$sujet=""; 
		$corps="";
		
		$msg = new Message();
		$msg->charger("sujetcommande");
		$msgdesc = new Messagedesc();

		$msgdesc->charger($msg->id);
	
		$sujet = $msgdesc->description . " " . $commande->ref;

		$msg->charger("corpscommande1");
		$msgdesc = new Messagedesc();                
		$msgdesc->charger($msg->id);

		$corps = $msgdesc->description;

                $msg->charger("corpscommande2");
                $msgdesc = new Messagedesc();
                $msgdesc->charger($msg->id);

                $corps2 = "";

		for($i=0; $i<$_SESSION['navig']->panier->nbart; $i++){
		
			$declidisp = new Declidisp();
			$declidispdesc = new Declidispdesc();
			$declinaison = new Declinaison();
			$declinaisondesc = new Declinaisondesc();
		
			$dectexte = "";

			$produit = new Produit();


			$stock = new Stock();
				
			
												
			for($compt = 0; $compt<count($_SESSION['navig']->panier->tabarticle[$i]->perso); $compt++){
				
				// diminution des stocks de dŽclinaison
				$stock->charger($_SESSION['navig']->panier->tabarticle[$i]->perso[$compt]->valeur, $_SESSION['navig']->panier->tabarticle[$i]->produit->id);
                $stock->valeur-=$_SESSION['navig']->panier->tabarticle[$i]->quantite;
                $stock->maj();

				
		   		$tperso = $_SESSION['navig']->panier->tabarticle[$i]->perso[$compt];
				$declinaison->charger($tperso->declinaison);
				$declinaisondesc->charger($declinaison->id);
				// recup valeur declidisp ou string
				if($declinaison->isDeclidisp($tperso->declinaison)){
					$declidisp->charger($tperso->valeur);
					$declidispdesc->charger($declidisp->id);
					$dectexte .= " - " . $declinaisondesc->titre . " " . $declidispdesc->titre . " ";
				}
				
				else $dectexte .= " - " . $declinaisondesc->titre . " " . $tperso->valeur . " ";
				
			}			

			
			// diminution des stocks classiques
			$produit = new Produit();
			$produit->charger($_SESSION['navig']->panier->tabarticle[$i]->produit->ref);
			$produit->stock-=$_SESSION['navig']->panier->tabarticle[$i]->quantite;
			$produit->maj();
			
						
			$prodtradesc = new Produitdesc();
			$prodtradesc->charger($_SESSION['navig']->panier->tabarticle[$i]->produit->id, $_SESSION['navig']->lang);
		
			$venteprod->quantite =  $_SESSION['navig']->panier->tabarticle[$i]->quantite;
			if( ! $_SESSION['navig']->panier->tabarticle[$i]->produit->promo)
				$venteprod->prixu =  $_SESSION['navig']->panier->tabarticle[$i]->produit->prix;	
			else $venteprod->prixu =  $_SESSION['navig']->panier->tabarticle[$i]->produit->prix2;
			$venteprod->ref = $_SESSION['navig']->panier->tabarticle[$i]->produit->ref;
			$venteprod->titre = $prodtradesc->titre . " " . $dectexte;
			$venteprod->chapo = $prodtradesc->chapo;
			$venteprod->description = $prodtradesc->description;
		 	$venteprod->tva =  $_SESSION['navig']->panier->tabarticle[$i]->produit->tva;	
		 	
			$venteprod->commande = $idcmd;
		 	$venteprod->add();
		 	$total += $venteprod->prixu * $venteprod->quantite;
		 	$nbart++;
		 	$poids+= $_SESSION['navig']->panier->tabarticle[$i]->produit->poids;
		 	$unitetr+=$_SESSION['navig']->panier->tabarticle[$i]->produit->unitetr;
		 	
		 	$corps2 .= $venteprod->ref . " " . $venteprod->titre . " " . $venteprod->prixu . " euro * " .  $venteprod->quantite . "\n";
		}	
 
 
 			
			$pays = new Pays();
			$pays->charger($_SESSION['navig']->client->pays);
			
			$zone = new Zone();
			$zone->charger($pays->zone);
			
		if( !$zone->tva)
			$total = round($total/1.196, 2);
		
		 		if($_SESSION['navig']->client->pourcentage>0) $commande->remise = $total * $_SESSION['navig']->client->pourcentage / 100;

		$total -= $commande->remise;
		
		if($_SESSION['navig']->promo->id != ""){
			if($_SESSION['navig']->promo->type == "1" && $_SESSION['navig']->promo->mini <= $total) $commande->remise += $_SESSION['navig']->promo->valeur;
			else if($_SESSION['navig']->promo->type == "2") $commande->remise += $total * $_SESSION['navig']->promo->valeur / 100;
			
		
			$_SESSION['navig']->promo->utilise = 1;
			$commande->maj();
			$temppromo = new Promo();
			$temppromo->charger_id($_SESSION['navig']->promo->id);
			$temppromo->utilise="1";
			$temppromo->maj();

			
			$_SESSION['navig']->promo = new Promo();
		} 


		$commande->port = port();	
		if($commande->port == "" || $commande->port<0) $commande->port = 0; 	
		
 		
		$_SESSION['navig']->commande = $commande;
		
		$commande->maj();		


		
		$emailcontact = new Variable();
		$emailcontact->charger("emailcontact");	

		mail($_SESSION['navig']->client->email , "$sujet", "$corps", "From: $emailcontact->valeur");	
		mail($emailcontact->valeur , "$sujet", "$corps2", "From: $emailcontact->valeur");
/*
		$smtp = new Smtp();
		$smtp->server = "127.0.0.1";

		$smtp->from = $emailcontact->valeur;
		$smtp->rcpt = $_SESSION['navig']->client->email;
		$smtp->subject = "$sujet";
		$smtp->texte = "$corps";
		$smtp->envoyer();

		$smtp->from = $emailcontact->valeur;
		$smtp->rcpt = $emailcontact->valeur;
		$smtp->subject = "$sujet";
		$smtp->texte = "$corps2";
		$smtp->envoyer();
*/
			 		
		redirige($paiement->url . "?total=$total");
	}
	
	function creercompte($raison, $entreprise, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays, $telfixe, $telport, $email1, $email2, $motdepasse1, $motdepasse2, $parrain){

		global $obligetelfixe, $obligetelport;

		$client = New Client();
		$client->raison = $raison;
		$client->nom = $nom;
		$client->entreprise = $entreprise;
		$client->ref = date("ymdHis") . strtoupper(substr($prenom,0, 3));
		$client->prenom = $prenom;
		$client->telfixe = $telfixe;
		$client->telport =$telport; 
		if( preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z.]+$/","$email1") 
			&& $email1==$email2 && ! $client->existe($email1)) $client->email = $email1;
		$client->adresse1 = $adresse1;
		$client->adresse2 = $adresse2;
		$client->adresse3 = $adresse3;
		$client->cpostal = $cpostal;
		$client->ville = $ville;
		$client->pays = $pays;
		$client->type = "0";
		
		$testcli = new Client();
		if($parrain != "") 
			if($testcli->charger_mail($parrain)) $parrain=$testcli->id;
			else $parrain=-1;
		else $parrain=0;

		if($testcli->id != "") $client->parrain=$testcli->id;
		
		if($motdepasse1 == $motdepasse2 && strlen($motdepasse1)>5 ) $client->motdepasse = $motdepasse1;
		
		$_SESSION['navig']->formcli = $client;	
		
		$obligeok = 1;
		
		if($obligetelfixe && $client->telfixe=="") $obligeok=0;
		if($obligetelport && $client->telport=="") $obligeok=0;
			
			
		if($client->raison!="" && $client->prenom!="" && $client->nom!="" && $client->email!="" && $client->motdepasse!="" 
			&& $client->email && $client->adresse1 !="" && $client->cpostal!="" && $client->ville !="" && $client->pays !="" && $obligeok){
			$_SESSION['navig']->client = $client;	

			$client->crypter();
			
			$client->add();
			
			//$_SESSION['navig']->connecte = 1; 
               		 $rec = $client->charger_mail($client->email);

                	if($rec) {
                       		$_SESSION['navig']->client = $client;
                        	$_SESSION['navig']->connecte = 1;
	                }

			redirige("nouveau.php");
		}	
		
		else redirige("formulerr.php");
	}
	
	function modifiercompte($raison, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays, $telfixe, $telport, $email1, $email2, $motdepasse1, $motdepasse2){

		global $obligetelfixe, $obligetelport;

		$client = New Client();

		$client->charger_id($_SESSION['navig']->client->id);
		if( $motdepasse1 == "" ){
			$client->id = $_SESSION['navig']->client->id;
			$client->raison = $raison;
			$client->nom = $nom;
			$client->prenom = $prenom;
			$client->telfixe = $telfixe;
			$client->telport =$telport; 
			if( preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z.]+$/","$email1") 
				&& $email1==$email2 ) $client->email = $email1;
			$client->adresse1 = $adresse1;
			$client->adresse2 = $adresse2;
			$client->adresse3 = $adresse3;
			$client->cpostal = $cpostal;
			$client->ville = $ville;
			$client->pays = $pays;
			$client->motdepasse = $_SESSION['navig']->client->motdepasse;
			
			$_SESSION['navig']->formcli = $client;

		$obligeok = 1;
		
		if($obligetelfixe && $client->telfixe=="") $obligeok=0;
		if($obligetelport && $client->telport=="") $obligeok=0;
					
			if($client->raison!="" && $client->prenom!="" && $client->nom!="" && $client->email!="" 
			&& $client->email && $client->adresse1 !="" && $client->cpostal!="" && $client->ville !="" && $client->pays !="" && $obligeok){
				$client->maj();
		 		$_SESSION['navig']->client = $client;	
		 	redirige($_SESSION['navig']->urlpageret);	

			}
			
				else redirige("compte_modifiererr.php");
	
			}
		

		else{

			if(  $motdepasse1 == $motdepasse2 && strlen($motdepasse1)>5 ) {		
				$client->motdepasse = $motdepasse1;
				$client->crypter();
		    	$client->maj();
				$_SESSION['navig']->client = $client;	
				redirige($_SESSION['navig']->urlpageret);	
			}
			else  {
				$_SESSION['navig']->formcli->motdepasse = "";
				redirige("compte_modifiererr.php");
			}	
			

	   }			
				
		

		

	}
		
	function creerlivraison($id, $libelle, $raison, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays){
		
		if($libelle != "" && $raison != "" && $prenom != "" && $nom != "" && $adresse1 != ""
			 && $cpostal != "" && $ville != "" && $pays != ""){
		
			$adresse = new Adresse();
			$adresse->libelle = $libelle;
			$adresse->raison = $raison;
			$adresse->prenom = $prenom;
			$adresse->nom = $nom;
			$adresse->adresse1 = $adresse1;
			$adresse->adresse2 = $adresse2;
			$adresse->adresse3 = $adresse3;
			$adresse->cpostal = $cpostal;
			$adresse->ville = $ville;
			$adresse->pays = $pays;
			$adresse->client = $_SESSION['navig']->client->id;
			$lastid = $adresse->add();
			
			$_SESSION['navig']->adresse=$lastid;
			
			redirige($_SESSION['navig']->urlpageret);	
		
		}
	}
	

        function supprimerlivraison($id){
                $adresse = new Adresse();
                $adresse->charger($id);
                $adresse->delete();
        }

	function modifierlivraison($id, $libelle, $raison, $prenom, $nom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays){
	
		$adresse = new Adresse();
		$adresse->charger($id);
	
		if($adresse->client != $_SESSION['navig']->client->id) return;
		
		if($libelle != "" && $raison != "" && $prenom != "" && $nom != "" && $adresse1 != ""
			 && $cpostal != "" && $ville != "" && $pays != ""){
			 		
			$adresse->id = $id;
			$adresse->libelle = $libelle;
			$adresse->raison = $raison;
			$adresse->prenom = $prenom;
			$adresse->nom = $nom;
			$adresse->adresse1 = $adresse1;
			$adresse->adresse2 = $adresse2;
			$adresse->adresse3 = $adresse3;
			$adresse->cpostal = $cpostal;
			$adresse->ville = $ville;
			$adresse->pays = $pays;
			$adresse->maj();
		}
	}

	function chmdp($email){
		$msg = new Message();
		$msgdesc = new Messagedesc();
		
		$tclient  = new Client();
		if( $tclient->charger_mail($email)){
			$pass = genpass(8);
			$tclient->motdepasse = $pass;
			$tclient->crypter();
			$tclient->maj();
		
                        $msg->charger("nouveaumdp1");
                        $msgdesc->charger($msg->id);
	
			$sujet = $msgdesc->description;	
                        
			$msg->charger("nouveaumdp2");
			$msgdesc->charger($msg->id);

			$emailcontact = new Variable();
            $emailcontact->charger("emailcontact");
                
                        $corps = $msgdesc->description;     
			mail("$tclient->email", "$sujet", "$corps $pass", "From: $emailcontact->valeur");
                        
 			$msg->charger("mdpmodif");
                        $msgdesc->charger($msg->id);
			echo "<script language=\"javascript\">";                                        echo "alert(\"$msgdesc->description\");";
                        echo "location='index.php'";
                        echo "</script>"; 
		}

		else {

                        $msg->charger("mdpnonvalide");
                        $msgdesc->charger($msg->id);

			echo "<script language=\"javascript\">";
			echo "alert(\"$msgdesc->description\");";
			echo "location='index.php'";	
			echo "</script>";
		}		
	}

?>
